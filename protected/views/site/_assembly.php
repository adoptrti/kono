<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view amly">
<?php 
    if(!empty($data->picture))
        echo CHtml::image('/images/pics/' . $data->picture,$data->name,['class' => 'picture amly']);
    ?>
    <h2 class="acname"><?=strtolower($poly->AC_NAME)?> Assembly Constituency - #<?=$data->acno?></h2>

    <?php
    
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => 'Elected MLA Name',
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
                                    'header' => 'Address',
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
                            ]
                    ) 
            ) );    
    
    ?>

</div>