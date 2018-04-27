<?php
//csv format
//sno	name	desig	phone	mobile	email	zone
function updateCoimbatoreMC($csvfile,$dryrun)
{
    $id_state = 32; // Tamil Nadu
    $dt_code = 11111;
    echo "Opening $csvfile\n";
    $F = fopen($csvfile,'r');
    $lctr=0;
    while(!feof($F))
    {
    	$vals = fgetcsv($F);
    	if(!$lctr++ || !is_array($vals))
    		continue;
    	//print_r($vals);
    	$ss = array_combine(['wardno','aeng','mo1','watersupply','mo2','sinspect','mo3'], $vals);
    	$poly = AssemblyPolygon::model()->findByAttributes([
    			'dt_code' => $dt_code,
    			'acno' => $ss['wardno']
    	]);
    	if(!$poly)
    		die("wardno " . $ss['wardno'] . " not found");
    	
    		$poly->newOfficer($ss['aeng'], $ss['mo1'],Officer::DESIG_ASSTENGINEER);
    		$poly->newOfficer($ss['watersupply'], $ss['mo2'],Officer::DESIG_WATERSUPPLYOFF);
    		$poly->newOfficer($ss['sinspect'], $ss['mo3'],Officer::DESIG_SANITORYINSPECTOR);
    	
    }
    fclose($F);
}

function updateCoimbatoreMCZ($csvfile,$dryrun)
{
	$id_state = 18; // Tamil Nadu
	$dt_code = 9822;
	$phone_std_code = "+9180";
	
	echo "Opening $csvfile\n";
	$F = fopen($csvfile,'r');
	$lctr=0;
	$headers = [];
	
	while(!feof($F))
	{
		$vals = fgetcsv($F);
		if(!$lctr++ || !is_array($vals))
		{
			$headers = $vals;
			continue;
		}
			// print_r($vals);
		$ss = array_combine ( $headers, $vals );
		$town = Town::model ()->findByAttributes ( [ 
				'dt_code' => $dt_code,
				'name' => $ss ['zone'] 
		] );
		if (! $town)
			die ( "zone [" . $ss ['zone'] . "] with dt_code=$dt_code not found" );
		
		$poly = AssemblyPolygon::model()->findByAttributes([
				'id_zone' => $town->id_place,
				'polytype' => 'WARD'
		]);
		
		$desigstr = Officer::$designstr;
		
		if(!isset($desigstr[$ss['desig']]))
			die("No desig for " . $ss['desig'] . "\n");
		
			
		$ss['phone'] .= ", " . $ss['mobile'];
		$mats_all = [];
		$rt = [];
		if (preg_match_all ( '/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/', $ss['phone'], $mats_all ))
		{
			print_r($mats_all);
			foreach($mats_all as $mats)
				$rt [] = 
						intval ( trim ( $mats ['std'] ) ) .
						trim ( $mats ['phone'] );
		}
		else if (preg_match_all ( '/(?<phone>\d{8,10})/', $ss['phone'], $mats_all ))
		{
			print_r($mats_all);
			foreach($mats_all['phone'] as $mats)
				$rt [] = trim ( $mats );
		}
		else if (preg_match_all ( '/(?<phone>\d{5}\s?\d{5})/', $ss['phone'], $mats2_all ))
		{
			//print_r($mats2_all);
			foreach($mats2_all['phone'] as $mats2)
				$rt [] = trim ( str_replace ( ' ', '', $mats2 ) ) ;
		}		
		
		$ss['phone'] = implode ( ', ', $rt );
		
		$ss['desig'] = $desigstr[$ss['desig']];
		$ss['dryrun'] = false;
		if($dryrun)
			$ss['dryrun'] = true;
		
		$poly->newZonalOfficer ( $ss );		
				
	}
	fclose($F);
}