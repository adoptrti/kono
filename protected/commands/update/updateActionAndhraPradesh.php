<?php
function updateActionAndhraPradeshDC() {
	$id_state = 7;
	$ST_CODE = 28;
	
	$stateobj = State::model ()->findByPk ( $id_state );
	
	libxml_use_internal_errors ( true );
	
	$urls = [ 
			// 'http://www.ap.gov.in/contacts/district-officals/'
			YiiBase::getPathOfAlias ( 'application' ) . '/apdc.html' 
	];
	foreach ( $urls as $url ) 
	{
		echo "\n\nURL: $url\n";
		$doc = new DOMDocument ();
		$doc->loadHTML ( file_get_contents ( $url ) );
		
		// since its the only table
		$xpath = new DOMXpath ( $doc );
		$TRs = $xpath->query ( "//div[@class='panel panel-default']" );
		
		if ($TRs->length == 0)
			die ( 'Assembly parsing failed. TRs not found' );
		$rctr = 0;
		$dists = [];
		foreach ( $TRs as $tr ) 
		{
			$distitem = handleDistrict($tr);
			saveOfficers($distitem);
			//$dists[$distitem['name']] = $distitem;
		} // foreach TRs
		print_r($dists);
	} // foreach URLs
}

function saveOfficer($id_district,$distitem,$desig)
{
	$off = new Officer();
	$off->fkey_place = $id_district;
	$off->name = $distitem['pname'];
	$off->desig = $desig;
	$off->phone = implode(',',$distitem['phones']);
	$off->email = implode(',',$distitem['emails']);
	
	print_r($distitem);
	
	if(!$off->save())
	{
		print_r($off->getErrors());
		echo "Could not save $name.\n";
	}
	else
		echo "Saved for $name.\n";
		
}

function saveOfficers($distitem)
{
	saveOfficer($distitem['id_district'],$distitem['Collector'], 'DISTCOLLECTOR');
	if(isset($distitem['Joint Collector'])) 
		saveOfficer($distitem['id_district'],$distitem['Joint Collector'], 'JOINTCOLLECTOR');
	if(isset($distitem['Superintendent of Police']))
		saveOfficer($distitem['id_district'],$distitem['Superintendent of Police'], 'SPOLICE');
	if(isset($distitem['D.I.G./I.G./Additional D.G. of Police']))
		saveOfficer($distitem['id_district'],$distitem['D.I.G./I.G./Additional D.G. of Police'], 'IGPOLICE');			
}

function handleDistrict($tr)
{
	$id_state = 7;
	$ST_CODE = 28;
	
	$desigs = [];
	$divs = $tr->getElementsByTagName('div');
	foreach($divs as $div)
	{
		$cn = $div->getAttribute('class');
		if('panel-heading' == $cn)
		{
			$desigs['name'] = trim($div->nodeValue);
			switch($desigs['name'])
			{
				case 'Ananthapur':
					$desigs['name'] = 'Anantapur';
					break;
				case 'Pottisriramulu Nellore':
					$desigs['name'] = 'NELLORE(09)';
					break;
			}			
			
			$dobj = District::model()->findByAttributes([
					'name' => $desigs['name'],
					'id_state' => $id_state
			]);
			
			if(!$dobj)
				throw new Exception("dist with name " . $desigs['name'] . " not found");
				
			$desigs['id_district'] = $dobj->id_district;
			continue;
		}
		
		if(!preg_match('/profile-list/',$cn))
			continue;
		
		$tds = $div->getElementsByTagName('div');
		$pname = trim($tds->item(0)->nodeValue);
		$pname = preg_replace('/,[^,]+$/', '', $pname);
		$desig = trim($tds->item(1)->nodeValue);
		$txt = trim($tds->item(2)->nodeValue);
		
		#this is just a header div with no data
		if('Designation' == $desig)
			continue;
		
		$mats = [];
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
		
		$desigs[$desig] = ['pname' => $pname,'desig' => $desig,'phones' => $phones,'mphones' => $mphones,'emails' => $emails];
	}
	return $desigs;
}

function updateActionAndhraPradesh() {
	$id_election = 30;
	$id_state = 7;
	$ST_CODE = 28;
	
	$stateobj = State::model ()->findByPk ( $id_state );
	$eleobj = Election::model ()->findByPk ( $id_election );
	
	libxml_use_internal_errors ( true );
	
	$urls = [ 
			//'http://aplegislature.org/web/legislative-assembly/member-s-information'
			'http://www.telanganalegislature.org.in/web/legislative-assembly/members-information'
	];
	foreach ( $urls as $url ) {
		echo "\n\nURL: $url\n";
		$doc = new DOMDocument ();
		$doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/andhrapradesh/mlas.html' ) );
		
		// since its the only table
		$xpath = new DOMXpath ( $doc );
		$TRs = $xpath->query ( "//div[@class='data']" );
		
		if ($TRs->length == 0)
			die ( 'Assembly parsing failed. TRs not found' );
		$rctr = 0;
		foreach ( $TRs as $tr ) {
			$row = [ ];
			$imgs = $tr->getElementsByTagName ( 'img' );
			if ($imgs->length !== 1)
				die ( "Not found the img" );
			
			$img = $imgs->item ( 0 )->getAttribute ( 'src' );
			
			$tds = $tr->childNodes;
			$col = 0;
			$phones = null;
			$acobj = null;
			
			foreach ( $tds as $td ) {
				echo "$col\t" . $td->nodeValue . "\n";
				switch ($col ++) {
					case 5 : // member nane
						$name = trim ( $td->nodeValue );
						break;
					case 7 : // constituency
						{
							$mats = [ ];
							if (! preg_match ( '/(?<acno>\d+)/', $td->nodeValue, $mats ))
								die ( "No match for [" . $td . ']' );
							
							$acno = intval ( $mats ['acno'] );
							
							$acobj = Constituency::model ()->findByAttributes ( $attr = [ 
									'id_state' => $id_state,
									'ctype' => 'AMLY',
									'eci_ref' => $acno 
							] );
							if (! $acobj)
								die ( '>> Could not find assembly ' . "#$acno\n" . print_r ( $attr, true ) );
							break;
						}
					case 9 : // PARTY
						$party = trim ( str_replace ( ':', '', $td->nodeValue ) );
						break;
				} // switch
			} // foreach TDs
			/*
			 * $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
			 * $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
			 * $picture_path = $stateobj->slug . '/' . $outfile;
			 * if (! file_exists ( $p1 ))
			 * mkdir ( $p1 );
			 * $p2 = $p1 . '/' . $outfile;
			 * echo "Getting... [" . $img . "]\n";
			 * $img_data = @file_get_contents ( $img );
			 * if ($img_data)
			 * file_put_contents ( $p2, $img_data );
			 * else
			 * echo "Could not get file\n";
			 */
			$picture_path = null;
			
			$MLA = AssemblyResults::model ()->findByAttributes ( [ 
					'st_code' => $ST_CODE,
					'id_election' => $id_election,
					'acno' => $acobj->eci_ref 
			] );
			if (! $MLA)
				$MLA = new AssemblyResults ();
			
			$MLA->id_election = $id_election;
			$MLA->acname = $acobj->name;
			$MLA->acno = $acobj->eci_ref;
			$MLA->name = $name;
			$MLA->party = $party;
			// $MLA->phones = $phones;
			// $MLA->address = $address;
			// $MLA->emails = null;
			$MLA->picture = $picture_path;
			$MLA->id_consti = $acobj->id_consti;
			$MLA->id_state = $acobj->id_state;
			$MLA->st_code = $ST_CODE;
			
			if (! $MLA->save ()) {
				print_r ( $MLA->errors );
				die ( 'Saving MLA failed for ' . $acobj->eci_ref );
			}
			echo $MLA->acname . " saved!\n";
		} // foreach TRs
	} // foreach URLs
}
