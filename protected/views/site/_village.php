<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view ward">
    <h2 class="acname"><?=__('{villagename} Village',['{villagename}' => strtolower($data->name)])?></h2>

    <?php
    if (isset ( $data->village ))
    {
        $this->widget ( 'zii.widgets.CDetailView', 
                array (
                        'data' => $data,
                        'attributes' => array (
                                [  // related city displayed as a link
                                        'label' => __ ( 'Village' ),
                                        'value' => function ($data)
                                        {
                                            return $data->village->name;
                                        } 
                                ],
                                [  // related city displayed as a link
                                        'label' => __ ( 'Panchayat' ),
                                        'value' => function ($data)
                                        {
                                            return $data->village->panchayat->name;
                                        } 
                                ],
                                [  // related city displayed as a link
                                        'label' => __ ( 'Block' ),
                                        'value' => function ($data)
                                        {
                                            return $data->village->panchayat->block->name;
                                        } 
                                ] 
                        
                        ) 
                ) );
    }
    ?>

</div>