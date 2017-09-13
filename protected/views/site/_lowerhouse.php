<?php
/* @var $this SiteController */
/* @var $data Array */
?>

<div class="view">
    <h2><?=$poly->PC_NAME?> Lok Sabha Constituency</h2>

    <?php
    $this->widget ( 'zii.widgets.CDetailView', 
            array (
                    'data' => $data,
                    'attributes' => array (
                            array ( // related city displayed as a link
                                    'label' => 'Elected Member of Parliament',
                                    'name' => 'name' 
                            ),
                            'party',
                            'category',
                            //more field ignored
                    ) 
            ) );        
    ?>

</div>