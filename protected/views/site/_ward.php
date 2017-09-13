<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view">
<h2>Municipal Ward #<?=$data->wardno?></h2>

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
                            
            ) ) );
    
?>

</div>