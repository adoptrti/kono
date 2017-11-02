<?php
class What3Words
{
    static function fetchWords($lat,$long)
    {
        $qry = [ 
                'format' => 'json',
                'coords' => $lat . ',' . $long,
                'key' => Yii::app ()->params ['w3w-api-key'],
                'lang' => Yii::app ()->language,
                'display' => 'full',// 'minimal' 
        ];
        $data = @file_get_contents("https://api.what3words.com/v2/reverse?" . http_build_query($qry));
        if($data)
            return json_decode($data);
        return false;
    }
}