<?php
/* @var $this SiteController */
/* @var $data Officer */
if (! isset ( $data ))
    return;
?>

<div class="view amly <?=empty ( $data->picture ) ? '' : 'pic'?>">
<?php
if (! empty ( $data->picture ))
    echo CHtml::image ( '/images/pics/' . $data->picture, $data->name, [ 
            'class' => 'picture amly' 
    ] );

?>
    <h2 class="acname governer officer"><?=__ ( '{{0}} State Governer', [ '{{0}}' => strtolower ( $data->state->name ) ] )?></h2>

    <?php
    if (! empty ( $data ))
        $this->widget ( 'zii.widgets.CDetailView', array (
                'data' => $data,
                'attributes' => array (
                        array ( // related city displayed as a link
                                'label' => __ ( 'Governer Name' ),
                                'name' => 'name' 
                        ),
                        [ 
                                'name' => 'address',
                                'visible' => ! empty ( $data->address ) 
                        ],
                        [
                                'type' => 'raw',
                                'name' => 'email',
                                'label' => __('Email Address'),
                                'value' => function($data)
                                {
                                    $rt = [];
                                    $ee1 = str_replace(['[AT]','[DOT]'], ['@','.'], $data->email);
                                    $ee2 = explode(',',$ee1);
                                    foreach($ee2 as $email)
                                    {
                                        $rt[] = CHtml::link($email,'mailto:' . $email);
                                    }
                                    return implode(' ',$rt);
                        }
                        ],
                        [
                                'type' => 'raw',
                                'name' => 'website',
                                'label' => __('Website'),
                                'value' => function($data)
                                {
                                    $rt = [];
                                    $ee2 = explode(',',$data->website);
                                    foreach($ee2 as $www)
                                    {
                                        $www = str_replace('https://', '', $www);
                                        $www = str_replace('http://', '', $www);
                                        $www = preg_replace('/\/$/', '', $www);
                                        $rt[] = CHtml::link($www,'http://' . $www);
                                    }
                                    return implode(' ',$rt);
                        }
                        ],
                        [ 
                                'type' => 'raw',
                                'name' => 'phone',
                                'visible' => ! empty ( $data->phone ),
                                'label' => __ ( 'Phone' ),
                                'value' => function ($data)
                                {
                                    $rt = [ ];
                                    //return $data->phone;
                                    $tels = explode ( ',', $data->phone );
                                    foreach ( $tels as $tel )
                                    {
                                        $mats = [ ];
                                        $mats2 = [ ];
                                        // landlines
                                        if (preg_match ( '/\(?(?<std>0\d+)?\)?[^\d]*(?<phone>\d+)/', $tel, $mats ))
                                        {
                                            $rt [] = CHtml::link ( $tel, 'tel:+91' . intval ( trim ( $mats ['std'] ) ) . trim ( $mats ['phone'] ) );
                                        } // mobile phones
else if (preg_match ( '/(?<phone>\d{5}[-\s]?\d{5})/', $tel, $mats2 ))
                                        {
                                            $rt [] = CHtml::link ( $tel, 'tel:+91' . trim ( str_replace ( [ 
                                                    ' ',
                                                    '-' 
                                            ], '', $mats2 ['phone'] ) ) );
                                        }
                                        return implode ( ', ', $rt );
                                    }
                                } 
                        ] 
                ) 
        ) );
    
    ?>

</div>