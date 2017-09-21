<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view">
    <h2><?=$poly->pc_name_clean?> Lok Sabha Constituency</h2>

    <?php
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => __('Lok Sabha MP'),
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
                                    'name' => 'p_address1',
                                    'header' => 'Address',
                                    'value' => function($data)
                                    {                                        
                                        return CHtml::tag('address',[],$data->p_address1 . ' ' . $data->p_address2);
                                    }
                            ],
                            [
                                    'type' => 'raw',
                                    'name' => 'delhi_address1',
                                    'header' => 'Address',
                                    'value' => function($data)
                                    {
                                        return CHtml::tag('address',[],$data->delhi_address1. ' ' . $data->delhi_address2);
                            }
                            ],
                            [
                                    'type' => 'raw',
                                    'name' => 'phones',
                                    'header' => 'Address',
                                    'value' => function($data)
                                    {
                                        $tels = explode(',',$data->phones);
                                        foreach($tels as $tel)
                                        {
                                            $mats = [];
                                            //echo "<br/>TEL: $tel<br/>";
                                            if(preg_match('/\((?<std>0\d+)?\)[^\d]*(?<phone>\d+)/',$tel,$mats))
                                            {
                                                $rt[] = CHtml::link($tel,'tel:+91' . intval(trim($mats['std'])) . trim($mats['phone']) );
                                            }
                                            return implode(', ',$rt);
                                        }
                                    }
                            ]
                            
                            //more field ignored
                    ) 
            ) );        
    ?>

</div>