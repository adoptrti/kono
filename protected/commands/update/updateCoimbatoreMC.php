<?php

function updateCoimbatoreMC($csvfile)
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

function updateCoimbatoreMCZ($csvfile)
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
			// print_r($vals);
		$ss = array_combine ( [ 
				'z',
				'name',
				'desig',
				'mo1',
				'zone' 
		], $vals );
		$town = Town::model ()->findByAttributes ( [ 
				'dt_code' => $dt_code,
				'name' => $ss ['zone'] 
		] );
		if (! $town)
			die ( "zone " . $ss ['zone'] . " not found" );
		
		$poly = AssemblyPolygon::model()->findByAttributes([
				'id_zone' => $town->id_place,
				'polytype' => 'WARD'
		]);
		
		$desigstr = [
				'Assisstant Commissioner' => Officer::DESIG_ASSTCOMMISSIONER,
				'Executive Engineer' => Officer::DESIG_EXECENGINEER,
				'Assistant Executive Engineer' => Officer::DESIG_ASSTEXECENGINEER,
				'Assistant Town Planning Officer' => Officer::DESIG_ASSTTOWNPLANNER,
				'Assistant Revenue Officer' => Officer::DESIG_ASSTREVENUEOFF,
				'Zonal Sanitary Officer' => Officer::DESIG_ZONALSANITORYOFF,
		];
		
		if(!isset($desigstr[$ss['desig']]))
			die("No desig for " . $ss['desig'] . "\n");
		
		$poly->newZonalOfficer ( $ss ['name'], $ss ['mo1'], $desigstr[$ss['desig']]);		
				
	}
	fclose($F);
}