<?php
function updateActionChhattisgarhCommittee()
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

function updateActionChhattisgarh()
{
    $id_election = 26;
    $id_state = 4;
    $ST_CODE = 22;

    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );

    libxml_use_internal_errors ( true );

    $urls = [
            'http://cgvidhansabha.gov.in/english_new/mla_current.htm'
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );

        //since its the only table
        $TRs = $doc->getElementsByTagName ( 'tr' );

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
                    case 0 : // constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/^\s*(?<acno>\d+)[\s.]*$/', $td->nodeValue, $mats ))
                            {
                                echo 'Not parsed constituency:[' . $td->nodeValue . "]\n";
                                $col = 10;
                                break;
                            }
                            $acno = $mats ['acno'];
                            $acobj = Constituency::model ()->findByAttributes (
                                    [
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => intval($acno)
                                    ] );
                            if (! $acobj)
                                die('>> Could not find assembly ' . $acno . "\n");
                            break;
                        }
                    case 1 : // acname
                        $acname = trim ( $td->nodeValue );
                        break;
                    case 2 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    case 3 : // PARTY
                        $party = trim ( $td->nodeValue );
                        break;
                    case 5 : // address
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
                        break;
                } // switch
            } // foreach TDs

            $MLA = TamilNaduResults2016::model ()->findByAttributes (
                    [
                            'ST_CODE' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => intval($acno)
                    ] );
            if (! $MLA)
                $MLA = new TamilNaduResults2016 ();

            $MLA->id_election = $id_election;
            $MLA->acname = $acobj->name;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = $party;
            $MLA->phones = $phones;
            $MLA->address = $address;
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
