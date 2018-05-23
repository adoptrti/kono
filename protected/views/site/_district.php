<?php
/* @var $this SiteController */
/* @var $data Officer */

switch($data->desig)
{
    case Officer::DESIG_DEPUTYCOMMISSIONER:
        $label =  __ ( 'Deputy Commissioner' );
        $label2 = __('{distname} Deputy Commissioner',['{distname}' => strtolower($data->district->name)]);
        break;
    case Officer::DESIG_DIVCOMMISSIONER:
        $label =  __ ( 'Divisional Commissioner' );
        $label2 = __('{divname} Divisional Commissioner',['{divname}' => strtolower($data->district->division->name)]);
        break;
    default:
        $label =  __ ( 'N/A' );
        break;
}
?>
<div class="view district <?=empty ( $data->picture ) ? '' : 'pic'?>">
<h2 class="acname"><?=$label2?><?php
if(Yii::app()->user->checkAccess('ADD_CHIEF_MINISTER'))
echo  ' ' . CHtml::link(__('Edit'),['/officer/update','id' => $data->id_officer],['class' => 'editlink']);
 ?></h2>
<?php
if (! empty ( $data->picture ))
    echo CHtml::image ( '/images/pics/' . $data->picture, $data->name, [ 
            'class' => 'picture amly' 
    ] );



$att = [array ( // related city displayed as a link
            'label' => $label,
            'name' => 'name',
    )];
    
    if(!empty($data->phone))
        $att[] = [
                'type' => 'raw',
                'name' => 'phone',
                'label' => __ ( 'Phone' ),
                'value' => function ($data)
                {
                    $tels = explode ( ',', $data->phone );
                    foreach ( $tels as $tel )
                    {
                        $mats = [ ];
                        $rt = [];
                        // echo "<br/>TEL: $tel<br/>";
                        if (preg_match ( '/\(?(?<std>0\d+)?\)?[^\d]*(?<phone>\d+)/', $tel, $mats ))
                        {
                            $rt [] = CHtml::link (
                                    '(0' . intval ( $mats ['std'] ) . ') ' . $mats ['phone'],
                                    'tel:+91' . intval ( trim ( $mats ['std'] ) ) .
                                    trim ( $mats ['phone'] ) );
                        }
                        return implode ( ', ', $rt );
                    }
        }
        ];

    if(!empty($data->email))
        $att[] =  [
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
        if (! empty ( $data->address ))
        $att [] = [ 
                'name' => 'address',
        ];


    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => $att
            ) )?>
    

</div>