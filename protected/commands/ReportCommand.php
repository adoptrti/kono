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
        
        $rs = AssemblyPolygon::model()->findAll([
                'group' => 'DIST_NAME',
                'select' => 'DIST_NAME,count(*) as ctr1,(select count(name) from municipalresults where city=DIST_NAME) as ctr2',
                'condition' => 'polytype=?',
                'params' => ['WARD'],
        ]);
        
        echo sprintf("%25s | %s | %s\n",'Municipal','Polygons','Councillors');
        echo sprintf("%25s | %s | %s\n",'---------',str_repeat('-',8),'-----------');
        foreach($rs as $r)
        {
            echo sprintf("%25s | %8d | %d\n",$r->DIST_NAME,$r->ctr1,$r->ctr2);
        }
    }
    
    public function repACs()
    {
        
        $rs = AssemblyPolygon::model()->findAll([
                'group' => 'ST_NAME,ST_CODE',
                'select' => 'ST_NAME,count(*) as ctr1,(select count(name) from tnresults2016 r2 where r2.ST_CODE=t.ST_CODE) as ctr2',
                'condition' => 'polytype=?',
                'params' => ['AC'],
        ]);
        
        echo sprintf("%25s | %s | %s\n",'State Assembly','Polygons','MLAs');
        echo sprintf("%25s | %s | %s\n",'---------',str_repeat('-',8),'---');
        foreach($rs as $r)
        {
            echo sprintf("%25s | %8d | %3d\n",$r->ST_NAME,$r->ctr1, $r->ctr2);
        }
    }
}