<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */
$this->pageTitle = Yii::app ()->name . ' - Contact Us';
$this->breadcrumbs = array (
        'Contact' 
);
?>

<h1>Data Report</h1>

<?php

$dataProvider = new CArrayDataProvider ( $muni, array (
        'keyField' => 0,
        'sort' => array (
                'attributes' => array (
                        0,
                        1,
                        2 
                ) 
        ),
        'pagination' => array (
                'pageSize' => 50 
        ) 
) );

$this->widget ( 'zii.widgets.grid.CGridView', 
        [ 
                'dataProvider' => $dataProvider,
                'columns' => [ 
                        [ 
                                'header' => 'City',
                                'value' => function ($data)
                                {
                                    return $data [0];
                                } 
                        ],
                        [ 
                                'header' => 'Polygons',
                                'value' => function ($data)
                                {
                                    return $data [1];
                                } 
                        ],
                        [ 
                                'header' => 'Councillors',
                                'value' => function ($data)
                                {
                                    return $data [2];
                                } 
                        ] 
                ] 
        ] );

$dataProvider = new CArrayDataProvider ( $amly, array (
        'keyField' => 0,
        'sort' => array (
                'attributes' => array (
                        0,
                        1,
                        2,3,4 
                ) 
        ),
        'pagination' => array (
                'pageSize' => 50 
        ) 
) );

$this->widget ( 'zii.widgets.grid.CGridView', 
        [ 
                'dataProvider' => $dataProvider,
                'columns' => [ 
                        [ 
                                'header' => 'States',
                                'value' => function ($data)
                                {
                                    return $data [0];
                                } 
                        ],
                        [ 
                                'header' => 'Polygons',
                                'value' => function ($data)
                                {
                                    return $data [1];
                                } 
                        ],
                        [ 
                                'header' => 'MLAs',
                                'value' => function ($data)
                                {
                                    return $data [2];
                                } 
                        ],
                        [
                                'header' => 'Phones',
                                'value' => function ($data)
                                {
                                    return $data [3];
                        }
                        ],
                        [
                                'header' => 'Emails',
                                'value' => function ($data)
                                {
                                    return $data [4];
                        }
                        ],
                        [
                                'header' => 'Address',
                                'value' => function ($data)
                                {
                                    return $data [5];
                        }
                        ],
                        [
                                'header' => 'Picture',
                                'value' => function ($data)
                                {
                                    return $data [6];
                        }
                        ],
                        ] 
        ] );
?>