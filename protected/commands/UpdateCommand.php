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
            $MLA->acname = $acno == 999 ? 'Nominated' : $consti->name;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = $party;
            $MLA->phones = $phones;
            $MLA->address = $address;
            $MLA->emails = $email;
            $MLA->id_consti = $acno == 999 ? null : $consti->id_consti;
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

    public function actionSlug()
    {
        $rs = Constituency::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $mats = [ ];
            if (preg_match ( '/(?<bad>[^\s-\.\w,\(\)&]+)/', $r->name, $mats ))
            {
                print_r ( $mats );
                die ( 'found invalid char for ' . $r->id_consti . '-' . $r->name );
            }
            $slug1 = strtolower ( 
                    str_replace ( 
                            [ 
                                    ',',
                                    '.',
                                    ' ',
                                    '(',
                                    ')',
                                    '&' 
                            ], '-', trim ( $r->name ) ) );
            
            $slug1 = preg_replace ( '/-+/', '-', $slug1 );
            $slug1 = preg_replace ( '/-$/', '', $slug1 );
            $slug1 = preg_replace ( '/^-/', '', $slug1 );
            
            $r->slug = $slug1;
            
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [ 
                    'slug' 
            ] );
        }
        
        $rs = States::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $mats = [ ];
            if (preg_match ( '/(?<bad>[^\s-\.\w,\(\)&]+)/', $r->name, $mats ))
            {
                print_r ( $mats );
                die ( 'found invalid char for ' . $r->id_consti . '-' . $r->name );
            }
            $slug1 = strtolower ( 
                    str_replace ( 
                            [ 
                                    ',',
                                    '.',
                                    ' ',
                                    '(',
                                    ')',
                                    '&' 
                            ], '-', trim ( $r->name ) ) );
            
            $slug1 = preg_replace ( '/-+/', '-', $slug1 );
            $slug1 = preg_replace ( '/-$/', '', $slug1 );
            $slug1 = preg_replace ( '/^-/', '', $slug1 );
            
            $r->slug = $slug1;
            
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [ 
                    'slug' 
            ] );
        }
    }

    public function actionIndex()
    {
        $this->actionSlug ();
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
     * Sikkim
     * URL: http://www.sikkimassembly.org.in/mla.html
     */
    public function actionKonoSikkim()
    {
        $id_election = 23;
        $ST_CODE = 11;
        $id_state = 31;
        
        $stateobj = States::model ()->findByPk ( $id_state );
        $eleobj = Election::model ()->findByPk ( $id_election );
        
        libxml_use_internal_errors ( true );
        
        $urls = [ 
                'http://www.sikkimassembly.org.in/mla.html',
                'http://www.sikkimassembly.org.in/mla1.html',
                'http://www.sikkimassembly.org.in/mla2.html',
                'http://www.sikkimassembly.org.in/mla3.html' 
        ];
        foreach ( $urls as $url )
        {
            echo "\n\nURL: $url\n";
            $doc = new DOMDocument ();
            $doc->loadHTML ( file_get_contents ( $url ) );
            $div = $doc->getElementById ( 'middle_col' );
            if (empty ( $div ))
                die ( 'Assembly parsing failed. middle_col not found' );
            
            $TRs = $div->getElementsByTagName ( 'tr' );
            
            if ($TRs->length == 0)
                die ( 'Assembly parsing failed. TRs not found' );
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
                        case 0 : // constituency
                            {
                                if (preg_match ( '/Next/', $td->nodeValue ))
                                    continue;
                                
                                $mats = [ ];
                                if (! preg_match ( '/^(?<acno>\d+)\.(?<acname>.*)$/', $td->nodeValue, $mats ))
                                {
                                    echo 'Not parsed constituency:[' . $td->nodeValue . "]\n";
                                    $col = 10;
                                    break;
                                }
                                $acno = $mats ['acno'];
                                echo "Got $acno >>\n";
                                $acname = $mats ['acname'];
                                $acobj = Constituency::model ()->findByAttributes ( 
                                        [ 
                                                'id_state' => $id_state,
                                                'ctype' => 'AMLY',
                                                'eci_ref' => $acno 
                                        ] );
                                if (! $acobj)
                                    die ( 'Could not find assembly ' . $acno );
                            }
                        case 1 : // member nane
                            $name = trim ( $td->nodeValue );
                            break;
                        case 2 : // picture
                            $imgs = $td->getElementsByTagName ( 'img' );
                            if ($imgs->length < 1)
                                die ( 'Not found img in ' . $acno );
                            $img = 'http://www.sikkimassembly.org.in/' . implode ( '/', 
                                    array_reduce ( explode ( '/', $imgs->item ( 0 )->getAttribute ( 'src' ) ), 
                                            function ($rt, $item)
                                            {
                                                $rt [] = urlencode ( $item );
                                                return $rt;
                                            } ) );
                            $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
                            $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
                            $picture_path = $stateobj->slug . '/' . $outfile;
                            if (! file_exists ( $p1 ))
                                mkdir ( $p1 );
                            $p2 = $p1 . '/' . $outfile;
                            echo "Getting... " . $img . "\n";
                            $img_data = @file_get_contents ( $img );
                            if ($img_data)
                                file_put_contents ( $p2, $img_data );
                            else
                                echo "Could not get file\n";
                            break;
                    } // switch
                } // foreach TDs
                
                $MLA = TamilNaduResults2016::model ()->findByAttributes ( 
                        [ 
                                'ST_CODE' => $ST_CODE,
                                'id_election' => $id_election,
                                'acno' => $acno 
                        ] );
                if (! $MLA)
                    $MLA = new TamilNaduResults2016 ();
                
                $MLA->id_election = $id_election;
                $MLA->acname = $acname;
                $MLA->acno = $acno;
                $MLA->name = $name;
                $MLA->party = null;
                $MLA->phones = null;
                $MLA->address = null;
                $MLA->emails = null;
                $MLA->picture = $picture_path;
                $MLA->id_consti = $acobj->id_consti;
                $MLA->id_state = $acobj->id_state;
                $MLA->ST_CODE = $ST_CODE;
                
                if (! $MLA->save ())
                {
                    print_r ( $MLA->errors );
                    die ( 'Saving MLA failed for ' . $acno );
                }
            } // foreach TRs
        } // foreach URLs
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
    