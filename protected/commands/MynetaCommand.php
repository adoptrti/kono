<?php
//10.856975354531784,77.1036683713969
//select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class MynetaCommand extends CConsoleCommand
{	
	var $candidates;
	var $parties;	
	var $constituency;
	var $id_state;
	var $id_election;
	
    public function actionkarnataka()
    {
    	libxml_use_internal_errors ( true );
    	$this->id_state = 18;
    	$this->id_election = 39;
    	$csv = "karnataka-eci-myneta.csv";
    	$F = fopen(YiiBase::getPathOfAlias("application.data") . '/' . $csv,"r");
    	if($F)
    	{
    		while(!feof($F))
    		{
    			list($eci,$name,$neta) = fgetcsv($F);
    			$myneta_map[$eci] = $neta;
    		}
    		fclose($F);
    	}
    	    	
    	$constis = Constituency::model()->findAllByAttributes([
    			'id_state' => $this->id_state,
    			'ctype' => 'AMLY',
    	]);
    	
    	foreach($constis as $consti)
    	{
    		
    		$candidates = ElectionCandidates::model()->findAllByAttributes([
    				'id_election' => $this->id_election,
    				'eci_ref' => $consti->eci_ref,		
			]);
			
    		$this->candidates = [];
			foreach($candidates as $can)
			{
				if(empty($can->adr_id))
					$this->candidates[strtoupper(trim($can->name))] = $can->id_candidate . " - " . $can->party;
			}

			$adr2party = [
				'INDIAN NATIONAL CONGRESS' => 'INC',
				'BHARATIYA JANATA PARTY' => 'BJP',
				'JANATA DAL (SECULAR)' => 'JD(S)',
			];

    		$this->parties = [];
			foreach($candidates as $can)
			{
				if(empty($can->adr_id))
				{
					if(!isset($adr2party[strtoupper(trim($can->party))]))
					{	
						echo "No match for [{$can->party}]\n";
					}
					else
					{
						$pc = $adr2party[strtoupper(trim($can->party))];
						$this->parties[$pc] = $can->id_candidate;
					}
				}
			}

			print_r($this->parties);
    		
    		$this->constituency = $consti;
    		
			$adr_id = $myneta_map[$consti->eci_ref];
			if(empty($adr_id))
			{
				echo "Could not find adr conti id for eci_ref={$consti->eci_ref} name:{$consti->name}\n";
				continue;
			}
    		$adrurl = "http://www.myneta.info/karnataka2018/index.php?action=show_candidates&constituency_id=" . $adr_id;
    		echo "Getting $adrurl...\n";
    		$dom = new DOMDocument();
    		$adrfile = YiiBase::getPathOfAlias("application.data") . "/$adr_id.html";
    		if(file_exists($adrfile))
    			$html = file_get_contents($adrfile);
    		else
    		{
    			$html = file_get_contents($adrurl);
    			file_put_contents($adrfile, $html);
    		}
    		$dom->loadHTML($html);
    		
    		$table = $dom->getElementById('table1');
    		$this->parseTable($table,$consti,$adr_id);
    	}
    	    	
    }
    
    function parseTable($table,Constituency $consti,$adr_id)
    {
    	$trs = $table->getElementsByTagName('tr');
    	foreach($trs as $tr)
    	{
    		$this->parseRow($tr);
    	}
    }
    
    function parseRow( $tr)
    {
    	$col=0;    	
    	$tds = $tr->getElementsByTagName('td');
    	if($tds->length == 0)
    		return;
		
		$candidate_adr_id = 0;
    	#echo "TDs = " . $tds->length . "\n";
    	foreach($tds as $td)
    	{
    		$mats = [];
    		#echo "col-$col = " . $td->nodeValue . "\n";
    		switch($col++)
    		{
    			case 0: //name
    				$tags = $td->getElementsByTagName('a');
    				$href = $tags->item(0);
    				//$href = $td->childNodes->item(0);
    				$url = $href->getAttribute('href');
    				if(!preg_match('/candidate_id=(?<can_adr_id>\d+)$/',$url,$mats))
    					die("Could not parse $url");
    				$name = $href->nodeValue;
    				$candidate_adr_id = $mats['can_adr_id'];
    				break;
    			case 1://party
    				$party = $td->nodeValue;
    				break;
    			case 2: //cases
    				$caseshtml =$td->nodeValue;
    				break;
    			case 3://education
    				$eduhtml =$td->nodeValue;
    				break;
    			case 5://assets
    				$assetshtml =$td->nodeValue;
    				break;
    			case 6://liabilities
    				$liabhtml =$td->nodeValue;
    				break;
    		}
    	}
		
		$crs = ElectionCandidates::model()->findByAttributes([
			'id_election' => $this->id_election,
			'adr_id' => $candidate_adr_id,
		]);

		if(!$crs)
		{    	
			if(!isset($this->candidates[strtoupper(trim($name))]))
			{
				if(!empty($this->parties[$party]))
				{
					$id_candidate = $this->parties[$party];
				}	
				else	
				{								
					#echo "Not found {$name}\n";
					#return false;
					//we ignore all that did not match
					print_r($this->parties);
					echo "isset=[$party]?" . empty($this->parties[$party]) . "\n";			
					print_r($this->candidates);
					print "Please fill matching candidate to [$name, - ,$party] - eci:{$this->constituency->eci_ref}:adr:{$candidate_adr_id}";
					$id_candidate = fgets(STDIN);
				}
			}
			else
			{
					$id_candidate = $this->candidates[strtoupper(trim($name))];
					unset($this->candidates[strtoupper(trim($name))]);
				
			}
			$crs = ElectionCandidates::model()->findByPk($id_candidate);
		}		 					
    	
		if(isset($crs->adr_id) && $crs->adr_id>0)
		{
			echo "Found {$crs->name}\n";
			return false;
		}
    	
    	$crs->adr_id = $candidate_adr_id;
    	$crs->cases  = $caseshtml;
    	$crs->assets  = $assetshtml;
    	$crs->liabilities  = $liabhtml;
    	if(!$crs->save())
    	{
    		print_r($crs->getErrors());
    		die("could not save");
    	}
    	echo "Saved {$this->constituency->eci_ref} - {$crs->button} - {$crs->cases} - $name\n";
    }
}