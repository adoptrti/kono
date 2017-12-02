<?php
/* @var $this SiteController */
/* @var $data Officer */
?>

<div class="view district">
    <h2 class="acname"><?=__('{distname} Deputy Commissioner',['{distname}' => strtolower($data->district->name)])?></h2>

    <?php
    $att = [array ( // related city displayed as a link
            'label' => __ ( 'Deputy Commissioner' ),
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
    
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => $att
            ) )?>
    

</div>