<?php

function updateActionGujarat()
{
    $id_election = 33; // gujarat
    $id_state = 13; // gujarat
    $ST_CODE = 24; // gujarat
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://www.gujaratassembly.gov.in/emembers13.htm' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/gujarat/mlas.html' ) );
        
        $xpath = new DOMXpath ( $doc );
        $TABLEs = $xpath->query ( "//table[@width='580']" );
        if ($TABLEs->length !== 1)
            die ( 'Too many tables found' );
        
        // since its the only table
        $TRs = $TABLEs->item ( 0 )->getElementsByTagName ( 'tr' );
        
        if ($TRs->length < 10)
            die ( 'Assembly parsing failed. TRs not found' );
        $rctr = 0;
        
        /* @var $TRs DOMNodeList */
        /* @var $td DOMNode|DOMElement */
        foreach ( $TRs as $tr )
        {
            // echo "\n$rctr:" . $tr->nodeValue;
            // ignore the first one
            if ($rctr ++ < 3)
                continue;
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            // $picture_path = null;
            foreach ( $tds as $td )
            {
                // echo "$rctr:" . $td->nodeValue;
                switch ($col ++)
                {
                    case 1 : // acno, constituency
                        {
                            $mats = [ ];
                            $nv = str_replace ( 
                                    [ 
                                            '(South)',
                                            '(North)' 
                                    ], 
                                    [ 
                                            'South',
                                            'North' 
                                    ], trim ( $td->nodeValue ) );
                            if (! preg_match ( '/(?<acname>\w[^\(]+\w)/', $nv, $mats ))
                                die ( "No match for:" . $td->nodeValue );
                            
                            $acname_fixes = [ 
                                    'Sidhpur' => 'Siddhpur',
                                    'Dahegam' => 'Dehgam',
                                    'Anjar' => 'Anjar',
                                    'Gandhidham (SC)' => 'Gandhidham',
                                    'Danta (ST)' => 'Danta',
                                    'Vadgam (SC)' => 'Vadgam',
                                    'Kadi (SC)' => 'Kadi',
                                    'Idar (SC)' => 'Idar',
                                    // "\n" => '',
                                    'Jamalpur- Khadia' => 'Jamalpur - Khadia',
                                    'Bhiloda (ST)' => 'Bhiloda',
                                    'Kalavad (SC)' => 'Kalavad',
                                    'Gariyadhar' => 'Gariadhar',
                                    'Mahemdabad' => 'Mehmedabad',
                                    'Kapadwanj' => 'Kapadvanj',
                                    'Shehra' => 'Shahera',
                                    'Morvahadaf' => 'Morva Hadaf',
                                    'Devgadhbaria' => 'Devgadbaria',
                                    'Chhotaudaipur' => 'Chhota Udaipur',
                                    'Dediapada' => 'Dediyapada',
                                    'Dang' => 'Dangs' 
                            
                            ];
                            
                            $find = array_keys ( $acname_fixes );
                            $replace = array_values ( $acname_fixes );
                            $acname = trim ( str_ireplace ( $find, $replace, $mats ['acname'] ) );
                            
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'name_clean' => $acname 
                                    ] );
                            if (! $acobj)
                                die ( '>> Could not find assembly [' . $acname . "]\n" );
                            break;
                        }
                    case 2 : // mobile+emails
                        $mats = [ ];
                        if (preg_match ( '/Vacant/', $td->nodeValue ))
                            continue;
                        if (! preg_match ( '/(?<name>\w[^\(]+)(\((?<party>.*)\))?/', $td->nodeValue, $mats ))
                            die ( "No match for name+party for:" . $td->nodeValue );
                        
                        $name = $mats ['name'];
                        $party = isset ( $mats ['party'] ) ? $mats ['party'] : null;
                        break;
                    case 3 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        if ($imgs->length < 1)
                            die ( 'Not found img in ' . $acno );
                        $img = 'www.gujaratassembly.gov.in/' . $imgs->item ( 0 )->getAttribute ( 'src' );
                        $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
                        $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
                        $picture_path = $stateobj->slug . '/' . $outfile;
                        if (! file_exists ( $p1 ))
                            mkdir ( $p1 );
                        $p2 = $p1 . '/' . $outfile;
                        echo "Getting... [" . $img . "]\n";
                        $img_data = @file_get_contents ( $img );
                        if ($img_data)
                            file_put_contents ( $p2, $img_data );
                        else
                            echo "Could not get file\n";
                        break;
                    case 4 : // address+phone+email
                        $mats = [ ];
                        $nv = $td->nodeValue;
                                                
                        #echo "\n-----\n" . $nv . "\n-----\n";
                        
                        $phones = [];
                        $mobiles = [];
                        $emails = [];
                        // extract mobile phones
                        #(?:M\D*)(?:(?<mob>\d{10})|(?:\D*))
                        #'/(?<block>M[\.\-\s]((?<mob>\d+)|([,\s]*)))/'
                        if (preg_match_all ( '/(?:M[\.\-\s]*)(?<mob>\d{10})/', $nv, $mats ))
                        {
                            //print_r ( $mats );
                            //print_r($mats);
                            $mobiles = $mats ['mob'];
                            foreach ( $mats [0] as $block )
                                $nv = trim ( str_replace ( $block, '', $nv ) );
                        }
                        else
                            echo "No match for mobiles\n";
                        
                        //echo "PHONE -- NV=$nv\n";
                        
                        /*(?<block>Phone[^\(\d]*)((?<std>\(\d+\))|(?<ph>\d+)|(?<bogus>[,\s]*))**/
                        // extract landlines
                        $mats = [ ];
                        if (preg_match_all ( 
                                //'/(?<block>Phone[^\D]*(?<ph>\d[\d,\s]*\d))/', $nv, $mats ))
                                //'/Phone\D*(?<ph>.*)$/', $nv, $mats ))
                                '/(?<block>Phone[^\(\d]*(?<ph>[\)\(\d,\s]*\d))/' #superb!
                                , $nv, $mats ))
                        {
                            //print_r($mats);
                            $phones = $mats['ph'];
                            foreach ( $mats ['block'] as $block )
                                $nv = trim ( str_replace ( $block, '', $nv ) );                                
                        }
                        else
                            echo "No match for landlines\n";
                        
                        //echo "NV=$nv\n";
                        
                        // extract email address
                        $mats = [ ];
                        if (preg_match_all ( 
                                '/(?<email>\w[\w\.]+@[\w\.]+\w)/', $nv, $mats ))
                        {
                            $emails = $mats['email'];
                            foreach ( $mats ['email'] as $block )
                                $nv = trim ( str_replace ( $block, '', $nv ) );                                
                        }
                        else
                            echo "No match for emaiks\n";
                        /*echo "Address:" . trim($nv) . "\n";
                        echo "Emails :" . print_r($emails,true) . "\n";
                        echo "Phones :" . print_r($phones,true) . "\n";
                        echo "Mobiles :" . print_r($mobiles,true) . "\n";                        
                        sleep(5);
                        system('clear');*/
                        $address = trim($nv);
                        $emails = implode(',',$emails);
                        $phones = implode(',', array_merge($phones,$mobiles) );
                        break;
                } // switch
            } // foreach TDs
            print $acobj->eci_ref . "\t$name\t$party\t$picture_path\n";
            
            $MLA = AssemblyResults::model ()->findByAttributes ( 
                    [ 
                            'st_code' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => intval ( $acobj->eci_ref ) 
                    ] );
            if (! $MLA)
                $MLA = new AssemblyResults ();
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acobj->name;
            $MLA->acno = $acobj->eci_ref;
            $MLA->name = $name;
            $MLA->emails = $emails;
            $MLA->address = $address;
            $MLA->phones = $phones;
            $MLA->party = null;
            $MLA->picture = $picture_path;
            $MLA->id_consti = $acobj->id_consti;
            $MLA->id_state = $acobj->id_state;
            $MLA->st_code = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acobj->eci_ref . ' - ' . $acname );
            }
        } // foreach TRs
    } // foreach URLs
}
