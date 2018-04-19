<?php
function updateActionBangaloreMC()
{
    $id_city = 9819;
    
    libxml_use_internal_errors ( true );
    
    $url = 
            //'http://bbmp.gov.in/en/wardwisecouncliesdetails?p_p_id=councillors_WAR_councillorsportlet&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=2&_councillors_WAR_councillorsportlet_keywords=&_councillors_WAR_councillorsportlet_advancedSearch=false&_councillors_WAR_councillorsportlet_andOperator=true&_councillors_WAR_councillorsportlet_resetCur=false&_councillors_WAR_councillorsportlet_delta=750' 
            Yii::app ()->basePath . '/../docs/karnataka/bangalore-wards.html';
    
    echo "\n\nURL: $url\n";
    $doc = new DOMDocument ();
    $html = file_get_contents ( $url );
    $html = str_replace("<br/>","\n",$html);
    $doc->loadHTML ( $html );
    $xpath = new DOMXpath ( $doc );
    $DIVs = $xpath->query ( "//div[contains(@class,'aui-layout-content')]" );                        
    echo "ctr=" . $DIVs->length;
    $wards = [];
    foreach($DIVs as $div)
    {        
        $ward = [];
        $childs = $div->childNodes;
        $dctr = 0;
        foreach($childs as $child)
        {
            $tn = isset($child->tagName) ? $child->tagName : "txt";
            if($tn == 'txt')
                continue;
            if($dctr++ == 0)
            {
                $imgs = $child->getElementsByTagName('img');
                
                $img = $imgs->item(0);
                $ward['img'] =  $img->getAttribute('src');
            }
            else 
            {
                $txt = $child->nodeValue;
                $ss = explode("\n",$txt);
                foreach($ss as $s)
                {
                    $mats = [];
                    if(preg_match("/(?<field>.*):(?<val>.*)/",$s,$mats))
                    {
                    	$mats['field'] = strtolower(trim($mats['field']));
                    	if('ward number' == $mats['field'])
                    		$mats['field'] = 'wardno';
                    	else if('phone number' == $mats['field'])
                    		$mats['field'] = 'phone';
                        $ward[trim($mats['field'])] = trim($mats['val']);
                    }
                }
            }
                
        }
        saveWardData($id_city,$ward);
        $wards[] = $ward;
    }
    return;
}

function saveWardData($id_city,$ward)
{   
	if(!isset($ward['wardno']))
	{
		print_r($ward);
		die;
	}
	print_r($ward);
	$mats = [];
	if(!preg_match("/(?<wardno>\d+)/",$ward['wardno'],$mats))
		die("ward no not found in string =  [" . $ward['wardno'] . ']');
	
	$ward['wardno'] = $mats['wardno'];
	echo "wardno[" . $ward['wardno'] . "]\n";
	$year = 2016;
	$img = $ward['img'];
	$params = [
			'wardno' => trim($ward['wardno']),
			'id_city' => $id_city
	];
	print_r($params);
	$mr = MunicipalResults::model()->find("wardno = ? and id_city = ?",[trim($ward['wardno']),$id_city]);
	if(!$mr)
	{
		#die("Not found for " . $ward['wardno']);
		$mr = new MunicipalResults();
		$mr->wardno = $ward['wardno'];
		$mr->id_city = $id_city; 
	}
	$mr->name = $ward['name'];
	$mr->address= $ward['address'];
	$mr->phone = $ward['phone'];
	
	$town = Town::model()->findbyPk($id_city);
	$outfile = $town->slug . '_MC_' . $mr->wardno . '_' . $year . '.jpg';
	$p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $town->state->slug . '/' . $town->slug;
	$picture_path = $town->state->slug . '/' . $town->slug . '/' . $outfile;
	if (! file_exists ( $p1 ))
		mkdir ( $p1 );
	$p2 = $p1 . '/' . $outfile;
	echo "Getting... " . $img . "\n";
	$img_data = @file_get_contents ( $img );
	if ($img_data)
		file_put_contents ( $p2, $img_data );
	else
		echo "Could not get file\n";
				
	$mr->picture = $picture_path;
	if(!$mr->save())
	{
		print_r($mr->getErrors());
		die;
	}
	return;
}

function updateActionKarnataka()
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
        
        $MLA = AssemblyResults::model ()->findByAttributes ( 
                [ 
                        'ST_CODE' => $ST_CODE,
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