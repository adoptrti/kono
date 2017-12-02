<?php
/* @var $this SiteController */
/* @var $data AssemblyResult */
?>

<div class="view amly <?=empty ( $data->picture ) ? '' : 'pic'?>">
<?php
if (! empty ( $data->picture ))
    echo CHtml::image ( '/images/pics/' . $data->picture, $data->name, [ 
            'class' => 'picture amly' 
    ] );

?>
    <h2 class="acname"><?= CHtml::link(__('{acname} Assembly Constituency - #{acno}',['{acname}' => strtolower($data->constituency->name),'{acno}' => $data->constituency->eci_ref]),['state/assembly','acno' => $data->constituency->eci_ref,'id_state' => $data->constituency->id_state])?></h2>

    <?php
    if(!empty($data))        
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => __ ( 'Elected MLA Name' ),
                                    'name' => 'name' 
                            ),
                            'party',
                            [ 
                                    'type' => 'raw',
                                    'visible' => !empty($data->emails),
                                    'name' => 'emails',
                                    'value' => function ($data)
                                    {
                                        $rt = [ ];
                                        $ee1 = str_replace ( [ 
                                                '[AT]',
                                                '[DOT]' 
                                        ], [ 
                                                '@',
                                                '.' 
                                        ], $data->emails );
                                        $ee2 = explode ( ',', $ee1 );
                                        foreach ( $ee2 as $email )
                                        {
                                            $rt [] = CHtml::link ( $email, 'mailto:' . $email );
                                        }
                                        return implode ( ' ', $rt );
                                    } 
                            ],
                            [
                                    'name' => 'address',
                                    'visible' => !empty($data->address),
                            ],
                            [ 
                                    'type' => 'raw',
                                    'name' => 'phones',
                                    'visible' => !empty($data->phones),
                                    'label' => __ ( 'Phones' ),
                                    'value' => function ($data)
                                    {
                                        $rt = [ ];
                                        $tels = explode ( ',', $data->phones );
                                        foreach ( $tels as $tel )
                                        {
                                            $mats = [ ];
                                            $mats2 = [ ];
                                            // landlines
                                            if (preg_match ( '/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/', $tel, $mats ))
                                            {
                                                $rt [] = CHtml::link ( $tel, 
                                                        'tel:+91' . intval ( trim ( $mats ['std'] ) ) .
                                                                 trim ( $mats ['phone'] ) );
                                            }
                                            // mobile phones
                                            else if (preg_match ( '/(?<phone>\d{5}[-\s]?\d{5})/', $tel, $mats2 ))
                                            {
                                                $rt [] = CHtml::link ( $tel, 
                                                        'tel:+91' . trim ( str_replace ( [' ','-'], '', $mats2 ['phone'] ) ) );
                                            }
                                            return implode ( ', ', $rt );
                                        }
                                    } 
                            ],
                            [ 
                                    'type' => 'raw',
                                    'visible' => !empty($data->committees),
                                    'label' => __ ( 'Committees' ),
                                    'value' => function ($data)
                                    {
                                        $html = [ ];
                                        foreach ( $data->committees as $comm )
                                            $html [] = CHtml::tag ( 'li', [ ], $comm->name );
                                        return join ( ' ', $html );
                                    } 
                            ] 
                    
                    ) 
            ) );
    
    ?>

</div>
