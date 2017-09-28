<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view addr">
    <h2><?=$address[0]->city?>, <?=$address[0]->state?></h2>

    <?php
    
    $address [0]->latitude = $address [1]->latitude;
    $address [0]->longitude = $address [1]->longitude;
    $address [0]->district = $amly_poly->DIST_NAME;
    // 'amly_poly' => $data ['amly_poly'],
    
    // $address[0]->altitude= $address[1]->altitude;
    
    $this->widget ( 'zii.widgets.CDetailView', 
            [ 
                    'data' => $address [0],
                    'attributes' => [ 
                            [ 
                                    'name' => 'town',
                                    'label' => __ ( 'Town' ) 
                            ],
                            
                            [ 
                                    'label' => __ ( 'City' ),
                                    'name' => 'city' 
                            ],
                            [ 
                                    'label' => __ ( 'PIN Code' ),
                                    'name' => 'postalCode' 
                            ],
                            [ 
                                    'label' => __ ( 'District' ),
                                    'name' => 'district' 
                            ],
                            [ 
                                    'label' => __ ( 'State' ),
                                    'name' => 'state',
                                    'type' => 'raw',
                                    'value' => function($data)
                                    {
                                        $st = State::model()->findByAttributes(['name' => $data->state]);
                                        if($st)
                                            return CHtml::link($st->name,['state/view','id' => $st->id_state]);
                                        else 
                                            return $data->state;
                                    }
                            ],
                            [ 
                                    'label' => __ ( 'Country' ),
                                    'name' => 'country' 
                            ],
                            [ 
                                    'label' => __ ( 'Latitude' ),
                                    'name' => 'latitude' 
                            ],
                            [ 
                                    'label' => __ ( 'Longitude' ),
                                    'name' => 'longitude' 
                            ],
                            [ 
                                    'label' => __ ( 'Altitude' ),
                                    'name' => 'altitude' 
                            ] 
                    ] 
            ] );
    
    ?>

</div>