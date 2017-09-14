<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view">
    <h2><?=$address[0]->city?>, <?=$address[0]->state?></h2>

    <?php
    
    $address[0]->latitude = $address[1]->latitude;
    $address[0]->longitude= $address[1]->longitude;
    //$address[0]->altitude= $address[1]->altitude;
    
    $this->widget ( 'zii.widgets.CDetailView', 
            [
                    'data' => $address[0],
                    'attributes' => [ 
                            'town',
                            'city',
                            'postalCode',
                            'state',
                            'country',
                            'latitude',
                            'longitude',
                            'altitude'
                    ]
            ]
        ) ;
    
    ?>

</div>