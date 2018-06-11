<?php
class OpenStreetMap extends CModel
{
    private $_xml;
    var $pos_lat;
    var $pos_long;
    var $dom;
    var $xpath;
    var $xml_array;
    var $last_poi;
    var $poi = [
        'hospitals' => ['amenity','hospital'],
        'bus_stops' => ['highway','bus_stop'],
    ];

    public function __construct($plat,$plong)
    {
        $this->pos_lat = $plat;
        $this->pos_long = $plong;
        libxml_use_internal_errors ( true );
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
        #$qry['data'] .= '<query type="area">' . $bbox . '<has-kv k="'. $k .'" v="' . $v . '"/></query>';
        $qry['data'] .= '</union><union><item/><recurse type="down"/></union><print/></osm-script>';
        $requrl = $apiurl . '?' . http_build_query($qry);
        $xml =  file_get_contents($requrl);
        if(empty($xml))
            return false;

        return $this->xml = $xml;
    }

    /**
     * Parses POIs using xpath
     */
    function parsePOIs()
    {
        $data = [];
        $nodes_by_ids = [];
        foreach($this->xml_array['node'] as $node)
        {
            //cache all nodes, we will use when we scan ways
            if(isset($node['@attributes']['id']))
                $nodes_by_ids[$node['@attributes']['id']] = $node['@attributes'];

            $name = "";
            if(!isset($node['tag']))
                continue;
            //node has tags
            if(!isset($node['tag'][0]))
            {
                $node['tag'][0]['@attributes'] = $node['tag']['@attributes'];
                unset($node['tag']['@attributes']);
            }
            foreach($node['tag'] as $tag)
                if('name' == $tag['@attributes']['k'])
                    $name = $tag['@attributes']['v'];

            $item = $node['@attributes'];
            $item['name'] = $name;
            $item['type'] = 'node';
            $data[$node['@attributes']['id']] = $item;
        }

        foreach($this->xml_array['way'] as $node)
        {
            $name = "";
            if(!isset($node['tag'][0]))
            {
                $node['tag'][0]['@attributes'] = $node['tag']['@attributes'];
                unset($node['tag']['@attributes']);
            }
            foreach($node['tag'] as $tag)
            {
                print_r($tag);
                if('name' == $tag['@attributes']['k'])
                    $name = $tag['@attributes']['v'];
            }
            if(!isset($nodes_by_ids[ $nid = $node['nd'][0]['@attributes']['ref'] ]))
                throw new Exception("Not found ID:" . $node['nd'][0]['@attributes']['ref']);

            $item = $nodes_by_ids[ $nid ];
            $item['name'] = $name;
            $item['type'] = 'way';
            $data[$nid] = $item;
        }
        print_r($data);
        die;

        $poi_ways = $this->xpath->query ( "//way" );
    }

    /**
     * Takes dom and query
     * uses existing pos and calculcates direction and distance of each poi
     */
    function getPOIsDistanceDirection()
    {
        //get pos(lat,long) of each poi
    }

    function setxml($xml)
    {
        $this->_xml = $xml;
        $doc = new DomDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $this->xml_array = json_decode(json_encode((array)simplexml_load_string($xml)),true);
        $doc->loadXml($this->_xml);
        $this->dom = $doc;
        $this->xpath = new DOMXpath ( $this->dom );
   }

 /**
   * Get nodes with positions
   */
   public function getnodes()
   {

   }

    function __get($poi)
    {
        if(isset($this->poi[$poi]))
        {
            $this->last_poi = $poi;
            return $this->getPOIs($this->poi[$poi][0],$this->poi[$poi][1]);
        }
        return parent::__get($poi);
    }

    function __isset($poi)
    {
        return isset($this->poi[$poi]) ? true : parent::__isset($poi);
    }

    function distance($lat2, $lon2)
    {
        $theta = $this->pos_lon - $lon2;
        $dist = sin(deg2rad($this->pos_lat)) * sin(deg2rad($lat2)) +  cos(deg2rad($this->pos_lat)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }

}