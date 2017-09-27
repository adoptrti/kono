<?php
Yii::app()->clientScript->registerCoreScript('bootstrap');
/* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?=Yii::app()->params['google-tracking-id']?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());
  gtag('config', '<?=Yii::app()->params['google-tracking-id']?>');
</script>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php
		if(Yii::app()->user->isGuest)
		{
			$this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>__('Home'), 'url'=>array('/site/index')),
					array('label'=>__('About'), 'url'=>array('/site/page', 'view'=>'about')),
			        array('label'=>__('Data Report'), 'url'=>array('/site/report')),
					array('label'=>__('Contact'), 'url'=>array('/site/contact')),
				        [
				                'encodeLabel' =>false,
				                'label' => "<i class='fa fa-github'></i>",
				                'url' => 'https://github.com/adoptrti/kono'
				        ],
				),
			));
		}
		else
		{
			$this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>__('Home'), 'url'=>array('/site/index')),
					array('label'=>__('Committee'), 'url'=>array('/committee')),
					array('label'=>__('Committee Members'), 'url'=>array('/committeeMember')),
					array('label'=>__('Assembly Results'), 'url'=>array('/assemblyresults')),
					array('label'=>__('Elections'), 'url'=>array('/election')),
					//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>__('Logout ({uname})',['{uname}' => Yii::app()->user->name]), 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				),
			));
		}
		?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		<?=__('Copyright &copy; {year} by Vikas Yadav.',['{year}' => date('Y')])?><br/>
		<?=__('All Rights Reserved.')?><br/>
		<?=CHtml::link(__('Disclaimer'),['site/page','view' => 'disclaimer']) ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
