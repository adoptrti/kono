<?php
/* @var $govdata array */

// @see https://dev.mysql.com/doc/refman/5.7/en/populating-spatial-columns.html
// @see
// https://stackoverflow.com/questions/15662910/search-a-table-for-point-in-polygon-using-mysql
#
#
#

$dist = false;

//find district collector
if (isset ( $govdata ['amly_poly'] ))
{
    $dist = District::model ()->findByAttributes ( 
            [ 
                    'name' => $govdata ['amly_poly']->dist_name,
                    'id_state' => $govdata ['amly_poly']->id_state
            ] );
    if (!$dist)
        $dist = District::model ()->findByAttributes (
                [
                        'name' => $rawdata[0]->region,
                        'id_state' => $govdata ['amly_poly']->id_state
                ] );
    if ($dist)
    {
        $govdata['dist_officer'] = Officer::model()->with(['district'])->together()->findByAttributes(['fkey_place' => $dist->id_district,'desig' => Officer::DESIG_DEPUTYCOMMISSIONER]);
    }
    if(isset($dist->division))
        $govdata['div_officer'] = Officer::model()->with(['district'])->together()->findByAttributes(['fkey_place' => $dist->division->id_district,'desig' => Officer::DESIG_DIVCOMMISSIONER]);
}   
else if (isset ( $govdata['village']->village))
{
    $govdata['dist_officer'] = Officer::model()->with(['district'])->together()->findByAttributes(['fkey_place' => $govdata['village']->village->panchayat->block->id_district]);
}

$this->renderPartial ( '_address', [ 
        'address' => $rawdata,
        'mp_poly' => $govdata ['mp_poly'],
        'w3w' => $w3w,
        'amly_poly' => $govdata ['amly_poly'],
        'data' => !empty($govdata ['ward']) ? $govdata ['ward'] : [],
] );

if (! empty ( $govdata['dist_officer']))
    $this->renderPartial ( '_district', [
            'data' => $govdata ['dist_officer'],
    		'rawdata' => $rawdata,
    ] );
    
if (! empty ( $govdata['div_officer']))
    $this->renderPartial ( '_district', [
            'data' => $govdata ['div_officer'],
            'rawdata' => $rawdata,
    ] );
        
if (! empty ( $govdata ['ward'] ))
    $this->renderPartial ( '_ward', [ 
            'data' => $govdata ['ward'],
    		'rawdata' => $rawdata,
    		'govdata' => $govdata,
    ] );

if (! empty ( $govdata ['village'] ))
        $this->renderPartial ( '_village', [
                'data' => $govdata ['village'],
        		'rawdata' => $rawdata,
        ] );
        
if (! empty ( $govdata ['assembly'] ) || ! empty ( $govdata ['amly_poly'] )) 
    $this->renderPartial ( '_assembly', [ 
            'data' => $govdata ['assembly'],
            'poly' => $govdata ['amly_poly'] 
    ] );

if (! empty ( $govdata ['chiefminister'] ) )
    $this->renderPartial ( '//state/_chiefminister', [
            'officer' => $govdata ['chiefminister'],
    ] );
        
if (! empty ( $govdata ['governer'] ) )
        $this->renderPartial ( '_governer', [
                'data' => $govdata ['governer'],
        ] );
        
if (! empty ( $govdata ['mp'] ))
    $this->renderPartial ( '_lowerhouse', [ 
            'data' => $govdata ['mp'],
            'poly' => $govdata ['mp_poly'] 
    ] );
#echo '<pre>' . print_r($govdata0,true) . print_r($att44) . '</pre>';
?>
