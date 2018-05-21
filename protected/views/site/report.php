<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */
$this->pageTitle = Yii::app ()->name . ' - ' . __('Data Report');
$this->breadcrumbs = array (
        __('Data Report')
);
?>

<h1><?=__('Data Report')?></h1>

<div class="view rpt">
<h2><?=__('Municipal Corporations')?></h2>
<?php

#$muni[0]['rti'] = 'https://twitter.com/adoptrti/status/916205653105844224';
#$muni[2]['rti'] = 'https://twitter.com/adoptrti/status/916212513317908480';

$dataProvider = new CArrayDataProvider ( $muni, array (
        'keyField' => 0,
        'sort' => array (
                'attributes' => array (
                        0,
                        1,
                        2,
                        3
                )
        ),
        'pagination' => array (
                'pageSize' => 50
        )
) );

$this->widget ( 'zii.widgets.grid.CGridView',
        [
                'dataProvider' => $dataProvider,
                'template' => '{items}',
                'columns' => [
                        [
                                'header' => __('City'),
                                'value' => function ($data)
                                {
                                    return $data [0];
                                }
                        ],
                        [
                                'header' => __('Wards'),
                                'value' => function ($data)
                                {
                                    return $data [1];
                                }
                        ],
                        [
                        		'header' => __('Ward Staff'),
                        		'value' => function ($data)
                        		{
                        			return $data ['wstaff'];
                        }
                        ],
                        [
                        		'header' => __('Zones'),
                        		'value' => function ($data)
                        		{
                        			return $data [3];
                        }
                        ],
                        [
                        		'header' => __('Zone Staff'),
                        		'value' => function ($data)
                        		{
                        			return $data ['zstaff'];
                        }
                        ],
                        [
                                'header' => __('Councillors'),
                                'type' => 'raw',
                                'value' => function ($data)
                                {
                                    return $data [2] . ' ' . (empty($data['rti']) ? ' ' : CHtml::link(__('RTI'),$data['rti'],['']));
                                }
                        ]
                ]
        ] );

?>
</div>

<style>
tr.score
{
    background: url(/images/blue1.gif);
    background-repeat: no-repeat;
    background-position: bottom left;
}
tr.score td
{

}
<?php
for($i=1; $i<=20; $i++)
{
    ?>
    tr.score<?=$i?>
    {
        b9ackground-size: <?=$i*5?>% 2px;
        background-size: <?=$i*5?>% 10%;
    }
    <?php
}
?>
</style>

<div class="view rpt">
<h2><?=__('States')?></h2>
<?php

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
$css_master = [];
$this->widget ( 'zii.widgets.grid.CGridView',
        [
                'template' => '{items}',
                'dataProvider' => $dataProvider,
                'rowCssClassExpression'=> function($row,$data)
                {
                    global $css_master;
                    $clsname = "score score" . round(intval(100*$data[10])/5);
                    $css_master[$clsname] = intval(100*$data[10]);
                    return $clsname;
                    //echo "row=$row";
                    //if(isset($data[$row]))
                    //    print_r($data[$row]);
                    //die;
                    //if(isset($data[$row]))
                      //  echo " - " . print_r($data[$row],true);

                    //return "score" . intval(100*$data[$row][10]);
                },
                'columns' => [
                        [
                                'header' => __('State'),
                                'type' => 'raw',
                                'value' => function ($data)
                                {
                                    $cm2 = isset($data['cm']) ? '+1' : '+0';
                                    $cm2 .= isset($data['gov']) ? '+1' : '+0';
                                    return CHtml::link($data [0],['state/view','id' => $data[11]]) . $cm2;
                                }
                        ],
                        [
                                'header' => __('A Poly'),
                                'value' => function ($data)
                                {
                                    return $data [1];
                                }
                        ],
                        [
                                'header' => __('E*'),
                                'headerHtmlOptions' => ['title' => __('Election Year')],
                                'value' => function ($data)
                                {
                                    $y = date('Y',strtotime($data [12]));
                                    if($y != 1970) return $y;
                                }
                        ],
                        [
                                'header' => __('MLAs'),
                                'value' => function ($data)
                                {
                                    return $data [2];
                                }
                        ],
                        [
                                'header' => __('P*'),
                                'headerHtmlOptions' => ['title' => __('Phone Numbers')],
                                'value' => function ($data)
                                {
                                    return $data [3];
                        }
                        ],
                        [
                                'header' => __('E*'),
                                'headerHtmlOptions' => ['title' => __('Email Address')],
                                'value' => function ($data)
                                {
                                    return $data [4];
                        }
                        ],
                        [
                                'header' => __('A*'),
                                'headerHtmlOptions' => ['title' => __('Office/Residence Address')],                                
                                'value' => function ($data)
                                {
                                    return $data [5];
                        }
                        ],
                        [
                                'header' => __('P*'),
                                'headerHtmlOptions' => ['title' => __('Picture')],                                
                                'value' => function ($data)
                                {
                                    return $data [6];
                        }
                        ],
                        [
                                'header' => __('M*'),
                                'headerHtmlOptions' => ['title' => __('Municipal Bodies')],
                                'value' => function ($data)
                                {
                                    if(!empty($data[8]))
                                        return $data [7] . '/' . $data[8];
                                }
                        ],
                        [
                                'header' => __('V*'),
                                'headerHtmlOptions' => ['title' => __('Villages')],
                                'value' => function ($data)
                                {
                                    return $data[10];
                                }
                        ],
                        [
                                'header' => __('V Poly'),
                                'value' => function ($data)
                                {
                                    return $data[9];
                                }
                        ],
                        [
                                'header' => __('D*'),
                                'headerHtmlOptions' => ['title' => __('Administrative Divisions and Districts')],
                                'value' => function ($data) use ($ias)
                                {
                                    $id_state = $data[11];
                                    
                                    if(isset($ias[$id_state]['ctr1']))
                                        return $ias[$id_state]['ctr1'] . '/' . $ias[$id_state]['ctr4'];
                                }
                        ],
                        [
                                'header' => __('C*'),
                                'headerHtmlOptions' => ['title' => __('Deputy Commissioners and Divisional Commissioners')],
                                'value' => function ($data) use ($ias)
                                {
                                    $id_state = $data[11];
                                    if(isset($ias[$id_state]['ctr2']))
                                        return $ias[$id_state]['ctr2'] . "/" . $ias[$id_state]['ctr3'];
                                }
                        ],
                        /*[
                                'header' => __('Score'),
                                'type' => 'raw',
                                'value' => function($data)
                                {
                                    return round(100*$data[10],2) . '%';
                                }
                        ] */
                    ]
        ] );

?>
</div>
