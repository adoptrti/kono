<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view amly">
<?php
    if(!empty($data->picture))
        echo CHtml::image('/images/pics/' . $data->picture,$data->name,['class' => 'picture amly']);
    ?>
    <h2 class="acname"><?=__('{acname} Assembly Constituency - #{acno}',['{acname}' => strtolower($poly->AC_NAME),'{acno}' => $data->acno])?></h2>

    <?php

    $this->widget ( 'zii.widgets.CDetailView',
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => __('Elected MLA Name'),
                                    'name' => 'name'
                            ),
                            'party',
                            [
                                    'type' => 'raw',
                                    'name' => 'emails',
                                    'value' => function($data)
                                    {
                                        $rt = [];
                                        $ee1 = str_replace(['[AT]','[DOT]'], ['@','.'], $data->emails);
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
                                    'name' => 'phones',
                                    'label' => __('Address'),
                                    'value' => function($data)
                                    {
                                        $rt=[];
                                        $tels = explode(',',$data->phones);
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
                        ],
                        [
                                'type' => 'raw',
                                'label' => __('Committees'),
                                'value' => function($data)
                                {
                                    $html = [];
                                    foreach($data->committees as $comm)
                                    	$html[] = CHtml::tag('li',[],$comm->name);
                                    return join(' ',$html);
                        }
                        ],

                    )
            ) );

    ?>

</div>
