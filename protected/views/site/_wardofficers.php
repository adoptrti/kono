<?php
/* @var $this SiteController */
/* @var $data Array */

/*$desigs = [
		'ASSTCOMMISSIONER' => __('Assistant Commissioner'),
		'EXECENGINEER' => __('Executive Engineer'),
		'ASSTEXECENGINEER' => __('Assistant Executive Engineer'),
		'ASSTTOWNPLANNER' => __('Assistant Town Planner'),
		'ASSTREVENUEOFF' => __('Assistant Revenue Officer'),
		'ZONALSANITORYOFF' => __('Zonal Sanitory Officer'),
];


*/
$desigs = array_flip(Officer::designstr());


?>

<div class="view ward zone">
    <h2 class="acname"><?=$caption?></h2>
    <?php
    foreach($officers as $data)
    {
    	?><h3 class="acname"><?=isset($desigs[$data['desig']]) ? $desigs[$data['desig']] : $data['desig']?></h3><?php
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => __ ( 'Name' ),
                                    'name' => 'name', 
                            ),
                    		[
    								'label' => __('Phone'),
                    				'type' => 'raw',
    								'value' => function($data)
                    				{
                    					$mats = [];
                    					$rt = [];
                    					if (preg_match_all ( '/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/', $data->phone, $mats_all ))
                    					{
                    						foreach($mats_all as $mats)
                    							$rt [] = CHtml::link (trim ( $mats ['phone'] ),'tel:' .
                    							intval ( trim ( $mats ['std'] ) ) .
                    							trim ( $mats ['phone'] ));
                    					}
                    					else if (preg_match_all ( '/(?<phone>\d{8,10})/', $data->phone, $mats_all ))
                    					{
                    						foreach($mats_all['phone'] as $mats)
                    							$rt [] = CHtml::link($mats,'tel:' . trim ( $mats ));
                    					}
                    					else if (preg_match_all ( '/(?<phone>\d{5}\s?\d{5})/', $data->phone, $mats2_all ))
                    					{
                    						foreach($mats2_all['phone'] as $mats2)
                    							$rt [] = CHtml::link($mats2,'tel:' . trim ( str_replace ( ' ', '', $mats2 ) ) );
                    					}
                    					
                    					/*if (preg_match ( '/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/', $data->phone, $mats ))
                    					{
                    						$rt [] = CHtml::link ( $data->phone,
                    								'tel:+91' . intval ( trim ( $mats ['std'] ) ) .
                    								trim ( $mats ['phone'] ) );
                    					}
                    					else if (preg_match ( '/(?<phone>\d{5}\s?\d{5})/', $data->phone, $mats2 ))
                    					{
                    						$rt [] = CHtml::link ( $data->phone,
                    								'tel:+91' . trim ( str_replace ( ' ', '', $mats2 ['phone'] ) ) );
                    					}*/
                    					return implode ( ', ', $rt );
    								}
    						],
                    
                    ) 
            )
                                        );
    }
    ?>

</div>