<?php
//10.856975354531784,77.1036683713969
//select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ReportCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $this->repMunicipals();
        echo "\n";
        $this->repACs();
    }
    
    public function repMunicipals()
    {
        $rows = AssemblyPolygon::repMunicipals();
        
        echo sprintf("%25s | %s | %s\n",'Municipal','Polygons','Councillors');
        echo sprintf("%25s | %s | %s\n",'---------',str_repeat('-',8),'-----------');
        foreach($rows as $row)
        {
            echo sprintf("%25s | %8d | %d\n",$row[0],$row[1],$row[2]);
        }
    }
    
    public function repACs()
    {
        $rows = AssemblyPolygon::repACs();
        
        echo sprintf("%25s | %s | %s\n",'State Assembly','Polygons','MLAs');
        echo sprintf("%25s | %s | %s\n",'---------',str_repeat('-',8),'---');
        foreach($rows as $row)
        {
            echo sprintf("%25s | %8d | %3d\n",$row[0],$row[1], $row[2]);
        }
    }
}