<div class="view items">
<h2><?=__('Districts') ?></h2>
<?php 
if(count($model->divisions)==0)
    _showdist($model->districts);
else
{
    foreach($model->divisions as $division)
    {
        ?>
        <h3><?=__('{{0}} Division',['{{0}}' => $division->name]) ?></h3>
        <?php 
        _showdist($division->districts);
    }
}
function _showdist($districts)
{
    ?>
    <ol>
    <?php 
    foreach($districts as $district)
    {
        $town = Town::model()->findByAttributes(['id_state' => $district->id_state,'dt_name' => $district->name,'sdt_code' => 0]);
        if($town)
            echo CHtml::tag('li',[],CHtml::link($district->name,['state/district','id' => $town->id_place]));
        else
            echo $district->name;
    }
    ?>
    </ol>
    <?php 
}
?>
</div>
