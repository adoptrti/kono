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
        $lctr=0;
        $F = fopen ( $csv, "r" );
        while ( ! feof ( $F ) )
        {
            $lctr++;
            $row = fgetcsv ( $F );            
            if(!$row || count($row)!=3)
                die("line:$lctr, Row is not six");
            if($lctr==1)
                continue;
            
            list ( $eci_ref, $name, $party) = $row;
            
            $constituency = Constituency::model ()->findByAttributes ( [ 
                    'eci_ref' => $eci_ref,
                    'id_state' => $el->id_state,
                    'ctype' => 'AMLY' 
            ] );
            if (! $constituency)
            {
                echo "constituency  $eci_ref not found!" ;
                continue;
            }
            
            $ar = AssemblyResults::model ()->multilang()->findByAttributes ( [ 
                    'id_election' => $id_election,
                    'id_consti' => $constituency->id_consti,
            ] );
            if (! $ar)
            {
                $ar = new AssemblyResults();
            } 
            else
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
}