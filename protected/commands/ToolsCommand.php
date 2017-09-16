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
}