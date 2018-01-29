<?php
class MapToPolygonsCommand extends CConsoleCommand
{

    /**
     * 201801291653:Kovai:thevikas
     * Checks municipal wards csv against stored polygons and fills acno/wardno field to the same csv
     * @param string $csvfile File path string to load
     * @param integer $dt_code District code to check against
     */
    public function actionIndex($csvfile,$dtcode)
    {

        $F = fopen($csvfile,'r' );
        $lctr=0;
        $names = [];
        while(!feof($F))
        {
            $cols = fgetcsv($F);
            if(!$lctr++)
            {
                $names = $cols;
                fputcsv(STDOUT,array_merge($names,['ec_ward_number']));
                continue;
            }
            $vals = array_combine($names,$cols);
            
            $rs = $this->findWard($vals['latitude'],$vals['longitude'],$dtcode);
            if($rs)
                $vals['ward_no'] = $rs->acno;
            fputcsv(STDOUT, $vals);
        }
    }
    
    public function findWard($lat,$long,$dtcode)
    {
        $src = [
                'condition' => (new CDbExpression ( "ST_Contains(poly, GeomFromText(:point))")) . ' and dt_code=:dtcode',
                'params' => [
                        ':dtcode' => $dtcode,
                        ':point' => 'POINT(' . $long . ' ' . $lat . ')'
                ]
        ];
        
        $rs = AssemblyPolygon::model ()->findAll ( $src );
        if(count($rs)>1)
            throw new Exception("Too many wards found for that location");
        else if(!$rs)
            return false;
        else
            return $rs[0];
    }
        
}