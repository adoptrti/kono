<?php

/**
 * Deputy Commissioners of each district in kerala
 */
function updateActionKeralaDC()
{
    $ST_CODE = 32; // kerala
    $id_state = 19; // kerala
    
    $stateobj = State::model ()->findByPk ( $id_state );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            //'https://kerala.gov.in/web/guest/districts-collectors-adms-ploce-officers' 
            YiiBase::getPathOfAlias('application') . '/kdc.html',
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );
        
        $TDs = $doc->getElementsByTagName( "td" );
        
        $rctr = 0;
        $dists = [];
        $distitem = [];
        $colctr=0;
        foreach ( $TDs as $td )
        {
            $rctr++;
            $colctr++;
            $mats = [];
            $txt = $td->nodeValue;
            if($td->getAttribute('colspan') == 2)
            {
                if(!empty($distitem))
                {                    
                    $dists[$distitem['name']] = $distitem;
                    $distitem = [];
                    $colctr=0;
                }
                #echo "**** \t";
                $distitem['name'] = trim($txt);
            }
            else if(preg_match('/(?<pname>.*)District Collector/',$txt,$mats))
            {
                $distitem['pname'] = trim($mats['pname']);
            }
            else 
            {
                $mphones = $phones = $emails = [];
                if(preg_match_all('/\D(?<phones>\d[-\d]+\d)/',$txt,$mats))
                {
                    //print_r($mats);
                    $phones = $mats['phones'];
                }
                if(preg_match_all('/\D(?<mphones>\d{10})\D/',$txt,$mats))
                {
                    //print_r($mats);
                    $mphones = $mats['mphones'];
                }
                #Email regex
                if(preg_match_all('/(?<emails>[A-Za-z]+[\w-\s\.]+@[\w-\s\.]+)/',$txt,$mats))
                {
                    //print_r($mats);
                    $emails = $mats['emails'];
                }                
                $distitem['col' . $colctr] = [$txt,'emails' => $emails,'phones' => $phones,'mphones' => $mphones];
            }
        } // foreach TDs

        if(!empty($data))
            $dists[$distitem['name']] = $distitem;

        foreach($dists as $name => $distitem)
        {
            print_r($distitem);
            $dobj = District::model()->findByAttributes([
                'name' => $distitem['name'],
                'id_state' => $id_state
            ]);

            if(!$dobj)
                throw new Exception("dist with name " . $distitem['name'] . " not found");
            
            $off = new Officer();
            $off->fkey_place = $dobj->id_district;
            $off->name = $distitem['pname'];
            $off->desig = 'DISTCOLLECTOR';
            $off->phone = implode(',',$distitem['col3']['phones']);
            $off->email = implode(',',$distitem['col3']['emails']);
            
            if(!$off->save())
            {
                print_r($off->getErrors());
                echo "Could not save $name.\n";
            }
            else
                echo "Saved for $name.\n";
        }

        #print_r($dists);
    } // foreach URLs
}

function updateActionKerala()
{
    $id_election = 24; // kerala
    $ST_CODE = 32; // kerala
    $id_state = 19; // kerala
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://www.niyamasabha.org/codes/members.htm' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );
        
        $xpath = new DOMXpath ( $doc );
        $TABLEs = $xpath->query ( "//table[@width='740']" );
        
        if ($TABLEs->length == 0)
            die ( 'Assembly parsing failed. table not found' );
        
        $TRs = $TABLEs->item ( 0 )->getElementsByTagName ( 'tr' );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            // ignore the first one
            if ($rctr ++ == 0)
                continue;
            $acdone = [];
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 0 : // member nane
                        $name = cleanspace( $td->nodeValue );
                        break;
                    case 1 : // constituency
                        {
                            $mats = [ ];
                            $acname = cleanspace($td->textContent);
                            //special cases
                            if($acname == 'Kozhikode(South)')
                                $acname = 'KOZHIKODE South';
                            if($acname == 'Kozhikode(North)')
                                $acname = 'KOZHIKODE NORTH';
                                    
                            if(!preg_match('/^(?<acname>[^\(]+)/', $acname,$mats))
                                die ( 'Could not preg assembly ' . $acname );
                            $acname = $mats['acname'];
                            echo "Got $acname >>\n";
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',                                            
                                    ] ,'(name = :name1 or name = :name2)',['name1' => $acname,'name2' => str_replace(' ','', $acname)]);
                            if (! $acobj)
                                die ( 'Could not find assembly ' . $acname );
                            $acno = $acobj->eci_ref;                            
                            if(isset($acdone[$acno]))
                                die ( 'Duplicate ' . $acname );                            
                            $acdone[$acno] = $acdone;                            
                            echo "Found $acname as " . $acobj->name . "\t" . $acno . "\n";
                            break;
                        }
                    case 2 : // email
                        $email = cleanspace($td->nodeValue,false);
                        break;
                    case 3 : // phone
                        $phone = cleanspace($td->nodeValue);
                        break;
                    case 5 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        if ($imgs->length < 1)
                            die ( 'Not found img in ' . $acno );
                        $img = 'http://www.niyamasabha.org/codes/' . implode ( '/', 
                                array_reduce ( explode ( '/', $imgs->item ( 0 )->getAttribute ( 'src' ) ), 
                                        function ($rt, $item)
                                        {
                                            $rt [] =  $item;
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
            
            $MLA = AssemblyResults::model ()->findByAttributes ( 
                    [ 
                            'ST_CODE' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => $acno 
                    ] );
            if (! $MLA)
                $MLA = new AssemblyResults ();
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acname;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = null;
            $MLA->phones = $phone;
            $MLA->address = null;
            $MLA->emails = $email;
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