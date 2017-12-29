<?php
/* @var $this AssemblyresultsController */
/* @var $model TamilNaduResults2016 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

$form = $this->beginWidget ( 'CActiveForm', 
        array (
                'id' => 'tamil-nadu-results2016-form',
                // Please note: When you enable ajax validation, make sure the
                // corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in
                // generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false 
        ) );
?>

	<p class="note">
        Fields with <span class="required">*</span> are required.
    </p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'id_election'); ?>
        <?php
        $districts = CHtml::listData ( Election::model ()->findAll (), 'id_election', 
                function ($data)
                {
                    $txt = $data->year . " " . $data->type;
                    if (isset ( $data->state->name ))
                        $txt .= " " . $data->state->name;
                    return $txt;
                } );
        echo $form->dropDownList ( $model, 'id_election', $districts )?>
        <?php echo $form->error($model,'id_election'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'id_consti'); ?>
        <?php
        $districts = CHtml::listData ( 
                Constituency::model ()->findAllByAttributes ( [ 
                        'ctype' => 'AMLY',
                        'id_state' => 34 
                ],['order' => 'eci_ref'] ), 'id_consti', function($data) {return $data->eci_ref . " " . $data->name;} );
        echo $form->dropDownList ( $model, 'id_consti', $districts )?>
        <?php echo $form->error($model,'id_consti'); ?>
    </div>

    <?php        
    foreach ( Yii::app ()->params ['translatedLanguages'] as $l => $lang ):
            if ($l === Yii::app ()->params ['defaultDBLanguage'])
                $suffix = '';
            else
                $suffix = '_' . $l;
            ?>
            <fieldset>
                <legend>
                    <?php echo $lang; ?> (<?=$suffix?>)
                </legend>
        
                <div class="row">
            		<?php echo $form->labelEx($model,'name'); ?>
            		<?php echo $form->textField($model,'name'.$suffix,array('size'=>60,'maxlength'=>255)); ?>
            		<?php echo $form->error($model,'name'.$suffix); ?>
            	</div>
            
                    <div class="row">
            		<?php echo $form->labelEx($model,'address'); ?>
            		<?php echo $form->textField($model,'address'.$suffix,array('size'=>60,'maxlength'=>255)); ?>
            		<?php echo $form->error($model,'address'.$suffix); ?>
            	</div>
            
                <div class="row">
                    <?php echo $form->labelEx($model,'slug'); ?>
                    <?php echo $form->textField($model,'slug'.$suffix,array('size'=>60,'maxlength'=>255)); ?>
                    <?php echo $form->error($model,'slug'.$suffix); ?>
                </div>
        
            </fieldset>
    <?php endforeach; ?>    

    <div class="row">
		<?php echo $form->labelEx($model,'gender'); ?>
		<?php echo $form->checkBox($model,'gender',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'gender'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'party'); ?>
		<?php echo $form->textField($model,'party',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'party'); ?>
	</div>


    <div class="row">
		<?php echo $form->labelEx($model,'phones'); ?>
		<?php echo $form->textField($model,'phones',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'phones'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'emails'); ?>
		<?php echo $form->textField($model,'emails',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'emails'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'picture'); ?>
		<?php echo $form->textField($model,'picture',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'picture'); ?>
	</div>

    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
<!-- form -->