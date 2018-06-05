<?php
/* @var $this Controller */
/* @var $officer Officer */
$h3 = isset($h3) ? $h3 : false;
$data = Officer::model()->localized(Yii::app()->language)->findByPk($officer->id_officer);
$cssclass = "chmim";
switch($data->desig)
{
    case Officer::DESIG_CHIEFMINISTER:
        $h2title = __('Chief Minister');
        break;
    case Officer::DESIG_DEPUTYCHIEFMINISTER:
        $h2title = __('Deputy Chief Minister');
        break;
    case Officer::DESIG_STATEINFOCOMMISSIONER:
        $h2title = __('State Information Commissioner');
        $cssclass = "sic";
        break;
}
?>
<div class="view <?=$cssclass . ' ' . (empty ( $data->picture ) ? '' : 'pic')?>">
<?php 
echo CHtml::tag($h3 ? 'h3' : 'h2',[],$h2title);
if (! empty ( $data->picture ))
    echo CHtml::image ( '/images/pics/' . $data->picture, $data->name, [ 
            'class' => 'picture amly' 
    ] );

if (! empty ( $data ))
{
    $att = [ 
            array ( // related city displayed as a link
                    //'label' =>$h2title,
                    'name' => 'name' 
            ) 
    ];
    
    if (! empty ( $data->phone ))
        $att [] = [ 
                'type' => 'raw',
                'name' => 'phone',
                'label' => __ ( 'Phone' ),
                'value' => function ($data)
                {
                    $tels = explode ( ',', $data->phone );
                    foreach ( $tels as $tel )
                    {
                        $mats = [ ];
                        $rt = [ ];
                        // echo "<br/>TEL: $tel<br/>";
                        if (preg_match ( '/\(?(?<std>0\d+)?\)?[^\d]*(?<phone>\d+)/', $tel, $mats ))
                        {
                            $rt [] = CHtml::link ( '(0' . intval ( $mats ['std'] ) . ') ' . $mats ['phone'], 
                                    'tel:+91' . intval ( trim ( $mats ['std'] ) ) . trim ( $mats ['phone'] ) );
                        }
                        return implode ( ', ', $rt );
                    }
                } 
        ];
    
    if (! empty ( $data->email ))
        $att [] = [ 
                'type' => 'raw',
                'name' => 'email',
                'label' => __ ( 'Email Address' ),
                'value' => function ($data)
                {
                    $rt = [ ];
                    $ee1 = str_replace ( [ 
                            '[AT]',
                            '[DOT]' 
                    ], [ 
                            '@',
                            '.' 
                    ], $data->email );
                    $ee2 = explode ( ',', $ee1 );
                    foreach ( $ee2 as $email )
                    {
                        $rt [] = CHtml::link ( $email, 'mailto:' . $email );
                    }
                    return implode ( ' ', $rt );
                } 
        ];

        if (! empty ( $data->website ))
        $att [] = [ 
                'type' => 'raw',
                'name' => 'website',
                'label' => __ ( 'Website' ),
                'value' => function ($data)
                {
                    return CHtml::link($data->website,$data->website);
                } 
        ];
    
    $this->widget ( 'zii.widgets.CDetailView',
                        array (
                                'data' => $data,
                                'attributes' => $att
            ));
}
?>

</div>