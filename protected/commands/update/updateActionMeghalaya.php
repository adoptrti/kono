<?php

function updateActionMeghalaya()
{
    updateActionMeghalaya2();
    return;
    $id_election = 37;//2018
    $ST_CODE = 17;
    $id_state = 23;
    
    $f = fopen ( realpath ( __DIR__ . '/../../../docs/meghalaya/meghalaya2018results.csv' ), 'r' );
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
        if (empty ( $row [0] ))
            continue;
        
        echo ".($rctr)";
        if (count ( $row ) != 6)
            continue;
        print_r ( $row );
        if (! preg_match ( '/(?<acno>\d+)/', $row [0], $mats1 ))
        {
            die ( 'not parsed consti - ' . $row [2] );
            continue;
        }
        $acno = $mats1 ['acno'];
        $name = trim ( $row [1] );
        $party = trim ( $row [2] );
        $gen = trim ( $row [4] );
        $age = trim ( $row [5] );
        
        $mats4 = [ ];
        
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
                
        $MLA = AssemblyResults::model ()->findByAttributes ( 
                [ 
                        'id_state' => $id_state,
                        'id_election' => $id_election,
                        'acno' => $acno 
                ] );
        
        if (! $MLA)
            $MLA = new AssemblyResults ();
        
        $MLA->id_election = $id_election;
        $MLA->acname = $acno == 999 ? 'Nominated' : $consti->name;
        $MLA->acno = $acno;
        $MLA->name = $name;
        $MLA->party = $party;
        $MLA->gender = $gen == 'M' ? 'male' : 'female';
        $MLA->phones = '';
        $MLA->address = '';
        $MLA->emails = '';
        $MLA->id_consti = $acno == 999 ? null : $consti->id_consti;
        $MLA->id_state = $id_state;
        #$MLA->ST_CODE = $ST_CODE;
        
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

function updateActionMeghalaya2()
{
    $id_election = 37;//2018
    $ST_CODE = 17;
    $id_state = 23;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    $urls = [ 
            'http://meghalaya.gov.in/megportal/government/mlas',
            // 'http://meghalaya.gov.in/megportal/government/mlas?page=1' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/meghalaya/mlas1.html' ) );
        $TABLE = $doc->getElementById('tab1');        
        // since its the only table
        $TRs = $TABLE->getElementsByTagName ( 'tr' );
        $aclinklist = [];

        foreach ( $TRs as $tr )
        {
            if (! $rctr ++)
                continue;
            
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            // $picture_path = null;
            foreach ( $tds as $td )
            {        
                switch ($col ++)
                {
                    case 0 : // constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/(?<acno>\d+)/', $td->nodeValue, $mats ))
                                die ( "No match for [" . $td . ']' );
                            
                            $acno = intval ( $mats ['acno'] );
                            
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    $attr = [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            $aclinklist[$acno]['acobj'] = $acobj;
                            break;
                        }
                    case 2 : // member name+link
                        $links = $td->getElementsByTagName('a');
                        if($links->length != 1)
                            throw new Exception("link was not found");
                        $link = $links->item(0);
                        $aclinklist[$acno]['link'] = $link->getAttribute('href');
                        break;                    
                } // switch
            } // foreach TDs                        
        } // foreach TRs
        foreach($aclinklist as $acno => $acdata)
        {
            updateActionMeghalaya3($acno,$acdata['acobj'],'http://meghalaya.gov.in' . $acdata['link']);
        }
    } // foreach URLs
}

function updateActionMeghalaya3($acno,$acobj,$link)
{
    $id_election = 37;//2018
    $ST_CODE = 17;
    $id_state = 23;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    echo "\n\nURL: $link\n";
        
    $doc = new DOMDocument ();
    $doc->loadHTML ( file_get_contents ( $link ) );
    $divs = $doc->getElementsByTagName('div');        
    $data = [];
    $key = "";
    foreach($divs as $div)
    {
        $classname = $div->getAttribute('class');
        if('profile-right-pic' == $classname)
        {
            $imgs = $div->getElementsByTagName('img');
            if($imgs->length>0)
            {
                $img = $imgs->item(0);
                $thumburl = $img->getAttribute("src");
                parse_str($thumburl,$params);
                print_r($params);
                $data['pic'] = $params['src'];

                if (! empty ( $data['pic'] ))
                {
                    $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
                    $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
                    $picture_path = $stateobj->slug . '/' . $outfile;
                    if (! file_exists ( $p1 ))
                        mkdir ( $p1 );
                    $p2 = $p1 . '/' . $outfile;
                    if(!file_exists($outfile) || filesize($outfile)<100)
                    {
                        echo "Getting... " . $data['pic'] . "\n";
                        $img_data = @file_get_contents ( $data['pic'] );
                        if ($img_data)
                            file_put_contents ( $p2, $img_data );
                        else
                            echo "Could not get file\n";
                    }
                }
            }
            continue;
        }
        if($classname != 'profile-left-head-design' && $classname != 'profile-left-content-text')
            continue;
        
        if($classname == 'profile-left-head-design')
            $key = $div->nodeValue;
        else            
            $data[trim($key)] = trim($div->nodeValue);
    }
    print_r($data);
    $params = [ 
        'id_state' => $id_state,
        'id_election' => $id_election,
        'acno' => $acobj->eci_ref 
    ];
    $MLA = AssemblyResults::model ()->multilang()->findByAttributes ( 
        $params );
    if (! $MLA)
    {
        print_r($params);
        throw new Exception("MLA data not found!");
    }

    $MLA->id_election = $id_election;
    $MLA->acname = $acobj->name;
    $MLA->acno = $acobj->eci_ref;
    $MLA->phones = empty($data['Telephone Number (Shillong)']) ? '' : $data['Telephone Number (Shillong)'];
    // $MLA->address = $address;
    $MLA->emails = empty($data['E-Mail']) ? '' : $data['E-Mail'];
    $MLA->picture = $picture_path;
    
    if (! $MLA->save ())
    {
        print_r ( $MLA->errors );
        die ( 'Saving MLA failed for ' . $acobj->eci_ref );
    }
    echo $MLA->acname . " saved!\n";
}