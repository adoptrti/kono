<?php
function acno()
{
    $f = fopen ( Yii::app ()->basePath . '/../docs/haryana/acno.txt', 'r' );
    while ( ! feof ( $f ) )
    {
        $mats = [ ];
        $ln = trim ( fgets ( $f ) );
        if (! preg_match ( '/(?<acno>\d+)[^\w]*(?<acname>[A-Za-z][\s\w]*)/', $ln, $mats ))
            die ( 'Not matched for [' . $ln . ']' );
        list ( $acno, $acname ) = [ 
                $mats ['acno'],
                $mats ['acname'] 
        ];
        $consti = Constituency::model()->findByAttributes($consti = ['name' => $acname,'id_state' => 14,'ctype' => 'AMLY']);
        if(!$consti)
            die($acno . "\t" . $acname . "\n");
         
        $consti->eci_ref = $acno;
        $consti->update(['eci_ref']);
    }
    fclose ( $f );
}

function updateActionHaryanaCommittee()
{
    $f = fopen(__DIR__ . '/../../../docs/chattisgarh/committee2017.csv','r');
    $lc = 0;
    while(!feof($f))
    {
        $row = fgetcsv($f);
        if(!$lc++ || count($row)<5)
            continue;

        $fc = 0;
        $comm = new Committee();

        list($comm->ctype,$comm->id_state,$comm->id_election,$comm->name) = $row;
        if(!$comm->save())
        {
            print_r($comm->errors);
            die('count not save comm:' . $comm->name);
        }
        $results = CHtml::listData($comm->election->assemblymembers,'acno','id_result');
        echo $comm->name . "\n";
        for($i=4; $i<count($row); $i++)
        {
            if(empty($row[$i]) || strlen(trim($row[$i]))<1)
                continue;

            echo "acno:[" . $row[$i] . "]\n";
            $cm = new CommitteeMember();
            $cm->id_comm = $comm->id_comm;
            $cm->id_result = $results[$row[$i]];
            if(!$cm->save())
            {
                print_r($cm->errors);
                die('count not save comm:' . $comm->name);
            }
        }
    }
    fclose($f);
}

function updateActionHaryana()
{
    $id_election = 17;
    $id_state = 14;
    $ST_CODE = 2;

    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );

    libxml_use_internal_errors ( true );

    $urls = [
            'http://haryanaassembly.gov.in/SearchMLAInformation.aspx'
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );

        //since its the only table
        $table = $doc->getElementById('dgMaster');
        $TRs = $table->getElementsByTagName ( 'tr' );

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
            $phones = null;
            //$picture_path = null;
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 0: //sno
                        break;
                    case 1 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    case 2 : // gender
                        $gender = strtolower(trim ( $td->nodeValue )) == 'male';
                        break;
                    case 3 : // constituency
                        {
                            $mats = [ ];
                            if(!preg_match('/(?<acname>\w[\s\w]+\w)/',trim($td->nodeValue),$mats))
                                die("No match for:" . $td->nodeValue);
                            
                            $acname_fixes = [
                                    'Punahana' => 'Punhana',
                                    'Mahendergarh' => 'Mahendragarh',
                                    'Badkhal' => 'Badhkal',
                                    'Sohana' => 'Sohna',
                            ];
                            
                            $find       = array_keys($acname_fixes);
                            $replace    = array_values($acname_fixes);
                            $acname= str_ireplace($find, $replace, $mats['acname']);

                            $acobj = Constituency::model ()->findByAttributes (
                                    [
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'name_clean' => $acname,
                                    ] );
                            if (! $acobj)
                                die('>> Could not find assembly [' . $acname. "]\n");
                            break;
                        }
                    case 4 : // PARTY
                        $party = trim ( $td->nodeValue );
                        break;
                    /*case 5 : // address
                        $raw1 = explode("\n",$td->nodeValue);
                        $raw2 = [];
                        foreach($raw1 as $i)
                            //if(!empty(trim($i)) && strlen(trim($i))>0)
                            if(preg_match('/\w/',$i))
                            {
                                //echo "[" . trim($i) . "] = " . strlen(trim($i)) . "\n";
                                $raw2[] = trim($i);
                            }
                        $mats = [];
                        //check if last index looks like a phone
                        if(preg_match('/(?<phones>\d{3}[\d-]+)/', $raw2[count($raw2)-1],$mats))
                        {
                            $phones = $mats['phones'];
                            //echo "$acno\t$phones\n";
                            unset($raw2[count($raw2)-1]);
                            $address = implode(",",$raw2);
                        }
                        else
                        {
                            $phones = null;
                            //print_r($raw2);
                            //echo ">>> No phone\n";
                            $address = implode(',', $raw2);
                        }
                        break;
                    case 6 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        if ($imgs->length < 1)
                            die ( 'Not found img in ' . $acno );
                        $img = 'http://cgvidhansabha.gov.in/' . str_replace('../','',trim($imgs->item(0)->getAttribute('src')));
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
                        break;*/
                } // switch
            } // foreach TDs

            $MLA = TamilNaduResults2016::model ()->findByAttributes (
                    [
                            'st_code' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => $acobj->eci_ref,
                    ] );
            if (! $MLA)
                $MLA = new TamilNaduResults2016 ();

            $MLA->id_election = $id_election;
            $MLA->acname = $acobj->name;
            $MLA->acno = $acobj->eci_ref;
            $MLA->name = $name;
            $MLA->party = $party;
            //$MLA->phones = $phones;
            //$MLA->address = $address;
            //$MLA->emails = null;
            //$MLA->picture = $picture_path;
            $MLA->id_consti = $acobj->id_consti;
            $MLA->id_state = $acobj->id_state;
            $MLA->st_code = $ST_CODE;

            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acobj->eci_ref);
            }
            echo $MLA->acname . " saved!\n";
        } // foreach TRs
    } // foreach URLs
}
