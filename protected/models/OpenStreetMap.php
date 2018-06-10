<?php
class OpenStreetMap extends CModel
{
    var $pos_lat;
    var $pos_long;
    var $poi = [
        'hospitals' => ['amenity','hospital'],
        'bus_stops' => ['highway','bus_stop'],
    ];

    public function __construct($plat,$plong)
    {
        $this->pos_lat = $plat;
        $this->pos_long = $plong;
    }

    public function attributeNames()
    {
        return array_merge(['pos_lat','pos_long'],array_keys($this->poi));
    }

    function getBoundingBox($distance)
    {
        $coef = $distance * 0.0000089;
        $new_lat_north = $this->pos_lat + $coef;
        $new_lat_south = $this->pos_lat - $coef;
        $new_long_west = $this->pos_long - $coef / cos($this->pos_lat * 0.018);
        $new_long_east = $this->pos_long + $coef / cos($this->pos_lat * 0.018);
        $data = ['south' => $new_lat_south,'west' => $new_long_west,'north' => $new_lat_north,'east' => $new_long_east];
        $data['northwest'] = ['lat' => $data['north'],'long' => $data['west']];
        $data['northeast'] = ['lat' => $data['north'],'long' => $data['east']];
        $data['southeast'] = ['lat' => $data['south'],'long' => $data['east']];
        $data['southwest'] = ['lat' => $data['south'],'long' => $data['west']];
        return $data;
    }

    function getBoundingBoxXML($distance)
    {
        $data = self::getBoundingBox($distance,$this->pos_lat,$this->pos_long);
        return '<bbox-query s="' . $data['south'] . '" w="' . $data['west'] . '" ' .
                'n="' . $data['north'] . '" e="' . $data['east'] . '"/>';
    }

    function getPOIs($k,$v,$distance = 2000)
    {
        $apiurl = Yii::app()->params['api_overpass'];
        $bbox = $this->getBoundingBoxXML($distance);
        $qry['data'] = '<osm-script><union>';
        $qry['data'] .= '<query type="node">' . $bbox . '<has-kv k="'. $k .'" v="' . $v . '"/></query>';
        $qry['data'] .= '<query type="way">' . $bbox . '<has-kv k="'. $k .'" v="' . $v . '"/></query>';
        $qry['data'] .= '<query type="relation">' . $bbox . '<has-kv k="'. $k .'" v="' . $v . '"/></query>';
        $qry['data'] .= '<union><item/><recurse type="down"/></union><print/></osm-script>';
        print_r($qry);

        $requrl = $apiurl . '?' . http_build_query($qry);
        return file_get_contents($requrl);
    }

    function __get($poi)
    {
        if(isset($this->poi[$poi]))
            return $this->getPOIs($this->poi[$poi][0],$this->poi[$poi][1]);
        return parent::__get($poi);
    }

}