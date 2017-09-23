<?php
/**
 * Does updates to database from fixed web urls
 * 
 * @author vikas
 *
 */
class UpdateCommand extends CConsoleCommand
{

    public function actionKarnataka()
    {
        $id_election = 22;
        $ST_CODE = 29;
        $id_state = 18;
        
        $f = fopen ( realpath ( __DIR__ . '/../../docs/karnataka/mla-parsed-ocr-cleaned.csv' ), 'r' );
        if (! $f)
            die ( 'Could not open file' );
        
        $rctr = 0;
        while ( ! feof ( $f ) )
        {
            $row = fgetcsv ( $f );
            $mats1 = [ ];
            $mats2 = [ ];
            $mats3 = [ ];
            
            if (++ $rctr == 1)
                continue;
            if (empty ( $row [2] ))
                continue;
            
            echo ".($rctr)";
            if (count ( $row ) != 6)
                continue;
            print_r ( $row );
            if (! preg_match ( '/(?<acno>\d+)/', $row [2], $mats1 ))
            {
                die ( 'not parsed consti - ' . $row [2] );
                continue;
            }
            $acno = $mats1 ['acno'];
            $name = trim ( $row [1] );
            $address = trim ( $row [3] );
            $party = trim ( $row [4] );
            
            $mats4 = [ ];
            
            if (! preg_match_all ( '/(?<phone>[-\d]+)|(?<email>[A-Za-z]+[\w-\s\.@]+in)$/', $row [5], $mats4 ))
            {
                die ( 'not parsed phones' );
                continue;
            }
            
            $phones = array_reduce ( $mats4 ['phone'], 'reducer' );
            $phones = count ( $phones ) > 0 ? implode ( ',', $phones ) : '';
            
            $email = array_reduce ( $mats4 ['email'], 'reducer' );
            $email = count ( $email ) > 0 ? implode ( ',', $email ) : '';
            
            $attr = [ 
                    'eci_ref' => $acno,
                    'ctype' => 'AMLY',
                    'id_state' => $id_state 
            ];
            if ($acno != 999)
            {
                $consti = Constituency::model ()->findByAttributes ( $attr );
                if (! $consti)
                {
                    error_log ( __FILE__ . ':' . __LINE__ . ': Error: ' . print_r ( $attr, true ) );
                    die ( 'count not find consti\n' );
                }
            }
            
            $MLA = TamilNaduResults2016::model ()->findByAttributes ( 
                    [ 
                            'ST_CODE' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => $acno 
                    ] );
            
            if (! $MLA)
                $MLA = new TamilNaduResults2016 ();
            
            $MLA->id_election = $id_election;
            $MLA->acname =  $acno==999 ? 'Nominated' : $consti->name;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = $party;
            $MLA->phones = $phones;
            $MLA->address = $address;
            $MLA->emails = $email;
            $MLA->id_consti = $acno==999 ? null : $consti->id_consti;
            $MLA->id_state = $id_state;
            $MLA->ST_CODE = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                print_r ( $MLA );
                die ( 'Saving MLA failed for ' . $acno );
            }
            echo "$rctr done.\n";
        }
        fclose ( $f );
    }

    public function actionIndex()
    {
        $methods = get_class_methods ( $this );
        foreach ( $methods as $method )
        {
            $mats = [ ];
            if (preg_match ( '/^updateKono(?<name>\w+)/', $method, $mats ))
            {
                print "Updating " . $mats ['name'] . "\n";
                $this->$method ();
            }
        }
    }

    /**
     * Delhi
     * URL: http://delhiassembly.nic.in/aspfile/listmembers_VIth_Assembly.htm
     */
    public function updateKonoDelhi()
    {
        $id_election = 21;
        $ST_CODE = 7;
        $id_state = 1;
        
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( 'http://delhiassembly.nic.in/aspfile/listmembers_VIth_Assembly.htm' ) );
        
        $TRs = $doc->getElementsByTagName ( 'tr' );
        if ($TRs->length == 0)
            die ( 'Delhi Assembly parsing failed.' );
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            // ignore the first one
            if ($rctr ++ == 0)
                continue;
            
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 1 :
                        $name = trim ( $td->nodeValue );
                        break;
                    case 2 :
                        $party = trim ( $td->nodeValue );
                        break;
                    case 3 :
                        $address = trim ( str_replace ( '<br />', '', $td->nodeValue ) );
                        break;
                    case 4 :
                        $phones = trim ( $td->nodeValue );
                        break;
                    case 5 :
                        {
                            $mats1 = [ ];
                            if (preg_match ( '/^(?<acname>[^\(]+)\((?<acno>\d+)\)/', $td->nodeValue, $mats1 ))
                            {
                                $acname = $mats1 ['acname'];
                                $acno = $mats1 ['acno'];
                            }
                            else
                            {
                                error_log ( __FILE__ . ':' . __LINE__ . ': Error: ' . $td->nodeValue );
                                die ( 'Failed parsing ACNO/ACNAME' );
                            }
                            break;
                        }
                    case 6 :
                        $emails = trim ( $td->nodeValue );
                        break;
                } // switch($coll)
            } // foreach
            
            $MLA = TamilNaduResults2016::model ()->findByAttributes ( 
                    [ 
                            'ST_CODE' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => $acno 
                    ] );
            if (! $MLA)
                $MLA = new TamilNaduResults2016 ();
            
            $attr = [ 
                    'eci_ref' => $acno,
                    'ctype' => 'AMLY',
                    'id_state' => $id_state 
            ];
            
            $consti = Constituency::model ()->findByAttributes ( $attr );
            if (! $consti)
            {
                error_log ( __FILE__ . ':' . __LINE__ . ': Error: ' . print_r ( $attr, true ) );
                die ( 'count not find consti\n' );
            }
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acname;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = $party;
            $MLA->phones = $phones;
            $MLA->address = $address;
            $MLA->emails = $emails;
            $MLA->id_consti = $consti->id_consti;
            $MLA->id_state = $consti->id_state;
            $MLA->ST_CODE = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acno );
            }
        } // foreach($TR)
    }
}

function reducer($carry, $item)
{
    if (empty ( trim ( $item ) ))
        return $carry;
    
    $carry [] = str_replace ( ' ', '', trim ( $item ) );
    return $carry;
}
    