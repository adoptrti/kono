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
    
    public function actionDBScan()
    {
    	$tables = [
    			'AssemblyPolygon','AssemblyResults',
    			'Block','Committee','CommitteeMember','Constituency',
    			'District','Election','LBVillage','LBWard',
    			'LokSabha2014','Minister','Ministry',
    			'MunicipalResults','Officer','State','Town'];
    	print_r($tables);
    	foreach($tables as $table)
    	{
    		$obj1 = new $table;
    		$obj2 = $obj1->model();
    		$pk = $obj2->tableSchema->primaryKey;
    		$maxpk = $obj2->find([
    				//'select' => $pk,
    				'order' => "t.$pk desc",
    		]);
    		if(!$maxpk)
    			continue;
    		$ur = $cr = false;
    		$dates = [];
    		if(isset($maxpk->created))
    		{
    			$cr = true;
    			$dates[] = 'max(created) as created';    			
    		}
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
    		
    		echo "$table\t$maxpknum\t$maxcreated\t$maxupdated\n";
    	}
    	//	scans last PK
    	//	scans max updated
    	//	scans max created
    }
}