<?php

function updateActionKerala()
{
    $id_election = 24; // kerala
    $ST_CODE = 32; // kerala
    $id_state = 19; // kerala
    
    $stateobj = States::model ()->findByPk ( $id_state );
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