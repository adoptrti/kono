<?php
// 10.856975354531784,77.1036683713969
// select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ElectionResulsCommand extends CConsoleCommand
{
    var $votes;
    public function actionIndex($id_election)
    {
        $election = Election::model ()->findByPk ( $id_election );
        libxml_use_internal_errors ( true );
        $election = Election::model ()->findByPk ( $id_election );
        $eci_ref_state = $election->state->eci_ref;
        $results = [ ];
        $ctr = 0;
        $F = fopen ( Yii::app ()->basePath . "/runtime/{$election->state->slug}-results.csv", 'w' );
        foreach ( $election->state->amly_constituencies as $c )
        {
            $eci = $c->eci_ref;
            if (! $eci)
                continue;
            $url = sprintf ( "http://eciresults.nic.in/Constituencywise%s%d.htm?ac=%d", $eci_ref_state, $eci, $eci );
            $html = file_get_contents ( $url );
            echo $url . "...\n";
            $dom = new DOMDocument ();
            $dom->loadHTML ( $html );
            $div = $dom->getElementById ( 'div1' );
            $html = $dom->saveHTML ( $div );
            // $data = $this->getTR($html,$div);
            $data = $this->getWinnerTR ( $div );
            $data [0] ['id_consti'] = $c->id_consti;
            $data [0] ['eci_ref'] = $c->eci_ref;
            $data [0] ['url'] = $url;
            $results [] = $data [0];
            // if($ctr++ > 3)
            // break;
            print_r ( $data [0] );
            fputcsv ( $F, $data [0] );
        }
        fclose ( $F );
        return;
    }
    public function getWinnerTR($div)
    {
        $data = [ ];
        if (! $div)
            return [ ];
        echo "HH\n";
        $trs = $div->getElementsByTagName ( 'tr' );
        foreach ( $trs as $tr )
        {
            $row = [ ];
            $ctr = 0;
            $tds = $tr->getElementsByTagName ( 'td' );
            if ($tds->length != 3)
                continue;
            
            foreach ( $tds as $td )
            {
                $row [] = $td->nodeValue;
            }
            $data [] = $row;
        }
        return $data;
    }
    public function getTR($div)
    {
        $data = [ ];
        if (! $div)
            return [ ];
        $trs = $div->getElementsByTagName ( 'tr' );
        foreach ( $trs as $tr )
        {
            $row = [ ];
            $ctr = 0;
            $tds = $tr->getElementsByTagName ( 'td' );
            foreach ( $tds as $td )
            {
                $row [] = $td->nodeValue;
            }
            $ctr ++;
            if (! empty ( $row [1] ) && $row [1] == 'Aam Aadmi Party')
            {
                $data [] = $row;
                $this->votes += $row [2];
            }
            $ctr ++;
        }
        print_r ( $data );
        return $data;
    }
    public function actionImport($id_election, $csv)
    {
        $el = Election::model ()->findByPk ( $id_election );
        if (! $el)
            die ( "Election id $id_election not found!" );
        $lctr = 0;
        $F = fopen ( $csv, "r" );
        while ( ! feof ( $F ) )
        {
            $lctr ++;
            $row = fgetcsv ( $F );
            if (! $row || count ( $row ) != 3)
                die ( "line:$lctr, Row is not six" );
            if ($lctr == 1)
                continue;
            
            list ( $eci_ref, $name, $party ) = $row;
            
            $constituency = Constituency::model ()->findByAttributes ( [ 
                    'eci_ref' => $eci_ref,
                    'id_state' => $el->id_state,
                    'ctype' => 'AMLY' 
            ] );
            if (! $constituency)
            {
                echo "constituency  $eci_ref not found!";
                continue;
            }
            
            $ar = AssemblyResults::model ()->multilang ()->findByAttributes ( [ 
                    'id_election' => $id_election,
                    'id_consti' => $constituency->id_consti 
            ] );
            if (! $ar)
            {
                $ar = new AssemblyResults ();
            } else
            {
                echo "Found for {$eci_ref}!\n";
            }
            $ar->id_election = $id_election;
            $ar->id_consti = $constituency->id_consti;
            $ar->acno = $eci_ref;
            $ar->name = $name;
            $ar->party = $party;
            if (! $ar->save ())
            {
                print_r ( $ar->getErrors () );
                die ( "Could not save rsult for $eci_ref." );
            }
        }
        fclose ( $F );
    }
    /*
     * Template:
     * Sno Division/District Name STD Phone Residence Fax
     * 1 Indore Shri P.K. Parashar 731 "24351112335222 " "27008882535113 " 2539552
     * Indore Shri Akash Tripathi 731 "2449111 2449112 " 2700111 2449114
     * Dhar Shri C.B. Singh  7292 234702 234701 234711
     *
     */
    public function actionDistrictCommissioners($id_state, $csv)
    {
        global $very_bad_global_variable_I_KNOW_doRelations;
        $very_bad_global_variable_I_KNOW_doRelations = false;
        global $very_bad_global_variable_I_KNOW_dont_add_validators;
        $very_bad_global_variable_I_KNOW_dont_add_validators = true;
        
        $state = State::model ()->findByPk ( $id_state );
        if (! $state)
            die ( "State id $id_state not found!" );
        
        $districts = [ ];
        $districts_names = [ ];
        $div_ids = [ ];
        $div_names = [ ];
        $districts_obj = [ ];
        $dist_divs = [ ];
        $districts1 = $state->districts;
        foreach ( $districts1 as $d )
        {
            $districts [$d->id_district] = trim ( strtolower ( $d->name ) );
            $districts_obj [$d->id_district] = $d;
            $districts_names [trim ( strtolower ( $d->name ) )] = $d->id_district;
            if (! empty ( $d->id_district_division_hq ))
            {
                $div_ids [$d->id_district_division_hq] [] = $d->id_district;
                $dist_divs [$d->id_district] = $d->id_district_division_hq;
            }
        }
        
        foreach ( $div_ids as $id => $distids )
        {
            $div_names [$districts [$id]] = $distids;
        }
        
        print_r ( $districts_names );
        
        $lctr = 0;
        $F = fopen ( $csv, "r" );
        $current_div_id = 0;
        while ( ! feof ( $F ) )
        {
            $lctr ++;
            $row = fgetcsv ( $F );
            if (! $row || count ( $row ) != 7)
                die ( "line:$lctr, Row is not seven" );
            if ($lctr == 1) // headers
                continue;
            
            list ( $sno, $dt_name, $name, $std, $phone, $phone2, $fax ) = $row;
            
            $dt_name = trim ( strtolower ( $dt_name ) );
            
            $dist_id = $districts_names [$dt_name];
            
            if (! empty ( $sno ))
            {
                $current_div_id = $districts_names [$dt_name];
                
                $off = Officer::model ()->findByAttributes ( [ 
                        'fkey_place' => $dist_id,
                        'desig' => Officer::DESIG_DIVCOMMISSIONER 
                ] );
                if (! $off)
                {
                    $off = new Officer ();
                    $off->desig = Officer::DESIG_DIVCOMMISSIONER;
                    $off->fkey_place = $dist_id;
                }
            } else
            {
                $off = Officer::model ()->findByAttributes ( [ 
                        'fkey_place' => $dist_id,
                        'desig' => Officer::DESIG_DEPUTYCOMMISSIONER 
                ] );
                if (! $off)
                {
                    $off = new Officer ();
                    $off->desig = Officer::DESIG_DEPUTYCOMMISSIONER;
                    $off->fkey_place = $dist_id;
                }
            }
            // if current dist is not saved in a div
            if (empty ( $dist_divs [$dist_id] ))
            {
                $dist = District::model ()->updateByPk ( $dist_id, [ 
                        'id_district_division_hq' => $current_div_id 
                ] );
            } else if ($dist_id != 714 && $dist_id != 715 && $dist_divs [$dist_id] != $current_div_id)
                throw new Exception ( "dist $dist_id does not have div id as $current_div_id" );
            
            $off->name = $name;
            $off->phone = $phone . "," . $phone2;
            $off->fax = $fax;
            if (! $off->save ())
            {
                print_r ( $off->getErrors () );
                die ( "Could not save officer data" );
            }
        }
        fclose ( $F );
    }
        
}