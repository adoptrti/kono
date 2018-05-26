<?php
//10.856975354531784,77.1036683713969
//select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ToolsCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $cons = AssemblyPolygon::model()->findAll(['select' => 'distinct pc_name']);
        
        foreach($cons as $consti)
        {
            $mats = [];
            echo $consti->pc_name. "\n";
            if(preg_match('/^(?<clean>[^\(]*)/',$consti->pc_name,$mats))
            {
                AssemblyPolygon::model()->updateAll(['pc_name_clean' => $mats['clean']],'pc_name = :pcname',['pcname' => $consti->pc_name]);
            }
        }
    }
    
    public function actionDBScan($save = false)
    {
        $tables = [
    			'AssemblyPolygon','AssemblyResults',
    			'Block','Committee','CommitteeMember','Constituency',
    			'District','Election','LBVillage','LBWard',
    			'LokSabha2014','Minister','Ministry','ElectionCandidates',
    			'MunicipalResults','Officer','State','Town'];
    	
    	$oldcache = json_decode(file_get_contents(Yii::app()->basePath . '/runtime/dbscan.cache.json'),true);
    	$changedtables = [];
    	$cache = [];
    	//save in cache, and verify in next run
    	//prepare a mysql dump cmd with the diff
    	//attach this to every deploy call
    	foreach($tables as $table)
    	{
    		$obj1 = new $table;
    		$obj2 = $obj1->model();
    		$pk = $obj2->tableSchema->primaryKey;
    		
    		//last record count
    		$ctr = $obj2->count();
    		if($ctr==0) //if no records
    		    continue;
    		
    		$qry = [
    		        //'select' => $pk,
    		        'order' => "t.$pk desc",
    		];
    		$maxpk = $obj2->find($qry);
    		//last PK
    		if(!$maxpk)
    		{
    		    print_r($qry);
    		    throw new Exception("$table qry failed");
    		}
    		
    		
    		$ur = $cr = false;
    		$dates = [];
    		//last created
    		if(isset($maxpk->created))
    		{
    			$cr = true;
    			$dates[] = 'max(created) as created';    			
    		}
    		//last updated
    		if(isset($maxpk->updated))
    		{
    			$ur = true;
    			$dates[] = 'max(updated) as updated';
    		}
    		$datestr = implode(",",$dates);
    		$maxpknum = $maxpk->$pk;
    		global $very_bad_global_variable_I_KNOW_doRelations;
    		$very_bad_global_variable_I_KNOW_doRelations = false;
    		if($ur || $cr)
    		$dater = $obj2->find([
    				'select' => $datestr,
    		]);
    		
    		$maxcreated = $cr ? date("Y-m-d H:i:s",strtotime($dater->created)) : false;
    		$maxupdated = $ur ? date("Y-m-d H:i:s",strtotime($dater->updated)) : false;
    		$tablename = $obj2->tableSchema->name;
    		$tabledata['model'] = $table;
    		$tabledata['table'] = $tablename;    		
    		$tabledata['maxpk'] = $maxpknum;
    		if($tabledata['maxpk'] != $oldcache[$tablename]['maxpk'])
    		    $changedtables[$tablename] = $tablename;
    		
    		$tabledata['maxcreated'] = $maxcreated;
    		if($tabledata['maxcreated'] != $oldcache[$tablename]['maxcreated'])
    		    $changedtables[$tablename] = $tablename;
    		    
    		$tabledata['maxupdated'] = $maxupdated;
    		if($tabledata['maxupdated'] != $oldcache[$tablename]['maxupdated'])
    		    $changedtables[$tablename] = $tablename;
    		    
		    $tabledata['count'] = $ctr;    		
		    if($tabledata['count'] != $oldcache[$tablename]['count'])
		        $changedtables[$tablename] = $tablename;		    
		    
            #201805231626:thevikas:Kovai:Patch to consider xxxLang tables too 
		    $cache[$tablename] = $tabledata;
		    $beh = $obj2->behaviors();
		    if(isset($beh['ml']) && isset($changedtables[$tablename]))
		        $changedtables[$tablename . 'Lang'] = $tablename . 'Lang';
    		#echo "$table\t$maxpknum\t$maxcreated\t$maxupdated\t$ctr\n";
    	}
    	if($save)
    	   file_put_contents(Yii::app()->basePath . '/runtime/dbscan.cache.json', json_encode($cache,JSON_PRETTY_PRINT));
    	echo implode(" ",$changedtables);
    }
    
    function actionWiki($url)
    {
        Yii::import('application.components.Wikipedia');
        $w = new Wikipedia($url);
        echo "Hindi: " . $w->hi . "\n";
    }
}