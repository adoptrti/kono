<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view ward">
<h2 class="acname"><?=strtolower($data0[0]->city)?> Municipal Ward - #<?=$data->wardno?></h2>

    <?php 
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes'=>array(
                            array(               // related city displayed as a link
                                    'label'=> 'Zone',
                                    'value' => function($data) {
                                        $ward = AssemblyPolygon::model()->findByAttributes(['acno' => $data->wardno,'DT_CODE' => $data->DT_CODE,'ST_CODE' => $data->ST_CODE]);
                                        if($ward)
                                            return $ward->zone;
                                    }
                            ),
                            array(               // related city displayed as a link
                                    'label'=> 'Elected Councillor Name',
                                    'name' => 'name',
                            ),
                            'party',
                            'phone',
                            'address',
                            [
                                    'type' => 'raw',
                                    'name' => 'phone',
                                    'header' => 'Phone',
                                    'value' => function($data)
                                    {
                                        $rt=[];
                                        $tels = explode(',',$data->phone);
                                        foreach($tels as $tel)
                                        {
                                            $mats = [];
                                            $mats2 = [];
                                            
                                            if(preg_match('/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/',$tel,$mats))
                                            {
                                                $rt[] = CHtml::link($tel,'tel:+91' . intval(trim($mats['std'])) . trim($mats['phone']) );
                                            }
                                            else if(preg_match('/(?<phone>\d{5}\s?\d{5})/',$tel,$mats2))
                                            {
                                                $rt[] = CHtml::link($tel,'tel:+91' . trim(str_replace(' ','',$mats2['phone'])) );
                                            }
                                            return implode(', ',$rt);
                                        }
                            }
                            ]
                            
            ) ) );
    
?>

</div>