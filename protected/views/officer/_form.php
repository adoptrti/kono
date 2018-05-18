<?php
/* @var $this OfficerController */
/* @var $model Officer */
/* @var $form CActiveForm */
if(isset($model->id_officer))
    $id_state=$model->district->id_state;
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'officer-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
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
                   
            </fieldset>
    <?php endforeach; ?>    	

	<div class="row">
		<?php echo $form->labelEx($model,'fkey_place'); ?>
		<?php		
		switch($model->desig)
		{
		    case Officer::DESIG_DEPUTYCOMMISSIONER:
		    case Officer::DESIG_DIVCOMMISSIONER:
		        $list= CHtml::listData(District::model()->bystate($id_state)->findAll(), 'id_district', 'name');
		        break;
		   case Officer::DESIG_GOVERNER:
	       case Officer::DESIG_CHIEFMINISTER:
	       case Officer::DESIG_DEPUTYCHIEFMINISTER:
	           $list= CHtml::listData(State::model()->findAll(), 'id_state', 'name');
		        break;
		}		
		echo $form->dropDownList($model, 'fkey_place', $list) ?>
		<?php echo $form->error($model,'fkey_place'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'desig'); ?>
		<?php echo $form->dropDownList($model, 'desig', [
		        Officer::DESIG_DEPUTYCOMMISSIONER => __('Deputy Commissioner of a District (aka Collector)'),
		        Officer::DESIG_DIVCOMMISSIONER => __('Divisional Commissioner of a Division'),
		        Officer::DESIG_CHIEFMINISTER => __('Chief Minister'),
		        Officer::DESIG_DEPUTYCHIEFMINISTER => __('Deputy Chief Minister'),
		        Officer::DESIG_GOVERNER => __('State Governer'),
		]) ?>
		<?php echo $form->error($model,'desig'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fax'); ?>
		<?php echo $form->textField($model,'fax',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'fax'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'website'); ?>
		<?php echo $form->textField($model,'website',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'website'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'picture_url'); ?>
		<?php echo CHtml::textField('picture_url','',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'picture'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->