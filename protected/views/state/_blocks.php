<?php
echo '<div class="view">';
echo '<h2>' . __ ( 'Blocks' ) . '</h2>';
$blocks = Block::model ()->bydistrict ( $model->id_district )->findAll ();
echo '<ol>';
foreach ( $blocks as $block )
{
    echo CHtml::tag ( 'li', [ ], 
            CHtml::link ( $block->name, 
                    [ 
                            'state/block',
                            'id_block' => $block->id_block 
                    ] ) );
}
echo '</ol></div>';