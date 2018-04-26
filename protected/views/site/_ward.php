<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view ward <?=empty ( $data->picture ) ? '' : 'pic'?>">

<?php
if (! empty ( $data->picture ))
    echo CHtml::image ( '/images/pics/' . $data->picture, $data->name, [ 
            'class' => 'picture pc' 
    ] );
?>
    <h2 class="acname"><?=__('{wardname} Municipal Ward - #{wardno}',['{wardname}' => strtolower($rawdata[0]->city),'{wardno}' => $data->wardno])?></h2>
    <?php
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => __ ( 'Zone' ),
                                    'value' => function ($data)
                                    {
                                        $ward = AssemblyPolygon::model ()->findByAttributes ( 
                                                [ 
                                                        'acno' => $data->wardno,
                                                        'dt_code' => $data->id_city,
                                                        'st_code' => $data->st_code 
                                                ] );
                                        
                                        if (isset($ward->zone->name))
                                            return $ward->zone->name;
                                    } 
                            ),
                            array ( // related city displayed as a link
                                    'label' => __ ( 'Elected Councillor Name' ),
                                    'name' => 'name' 
                            ),
                            'party',
                            'phone',
                            'address',
                            [ 
                                    'type' => 'raw',
                                    'name' => 'phone',
                                    'label' => __ ( 'Phone' ),
                                    'value' => function ($data)
                                    {
                                        $rt = [ ];
                                        $tels = explode ( ',', $data->phone );
                                        foreach ( $tels as $tel )
                                        {
                                            $mats = [ ];
                                            $mats2 = [ ];
                                            
                                            if (preg_match ( '/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/', $tel, $mats ))
                                            {
                                                $rt [] = CHtml::link ( $tel, 
                                                        'tel:+91' . intval ( trim ( $mats ['std'] ) ) .
                                                                 trim ( $mats ['phone'] ) );
                                            }
                                            else if (preg_match ( '/(?<phone>\d{5}\s?\d{5})/', $tel, $mats2 ))
                                            {
                                                $rt [] = CHtml::link ( $tel, 
                                                        'tel:+91' . trim ( str_replace ( ' ', '', $mats2 ['phone'] ) ) );
                                            }
                                            return implode ( ', ', $rt );
                                        }
                                    } 
                            ] 
                    
                    ) 
            ) );
    
    ?>

</div>

<?php
if (! empty ( $govdata ['ward_officers'] ))
	$this->renderPartial ( '_wardofficers', [
			'officers' => $govdata ['ward_officers'],
			'rawdata' => $rawdata,
			'govdata' => $govdata,
			'caption' => __('Municipal Ward Staff'),
	] );
if (! empty ( $govdata ['zone_officers'] ))
	$this->renderPartial ( '_wardofficers', [
			'officers' => $govdata ['zone_officers'],
			'rawdata' => $rawdata,
			'govdata' => $govdata,
			'caption' => __('Municipal Zone Staff'),
	] );