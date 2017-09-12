<?php
//@see https://dev.mysql.com/doc/refman/5.7/en/populating-spatial-columns.html
//@see https://stackoverflow.com/questions/15662910/search-a-table-for-point-in-polygon-using-mysql
$ass = AssemblyPolygon::model()->find([
        'condition' => new CDbExpression("ST_Contains(poly, GeomFromText(:point))"),
        'params' => [':point' => 'POINT(' . $data[1]->latitude . ' ' . $data[1]->longitude . ')'],
]);

$this->widget('zii.widgets.CDetailView', array(
        'data'=>$ass,
        /*'attributes'=>array(
                array(               // related city displayed as a link
                        'label'=>__('Husband'),
                        'type'=>'raw',
                        'value'=>$model->husband->namelink,
                ),
                array(               // related city displayed as a link
                        'label'=>__('Wife'),
                        'type'=>'raw',
                        'value'=>$model->wife->namelink,
                ),
                'created',
                'mid',
                'comments',
                'dom',
        ),*/
));

?>