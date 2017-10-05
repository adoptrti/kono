<?php
Yii::app ()->clientScript->registerCoreScript ( 'bootstrap' );
/* @var $this Controller */
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
<!-- xml:lang="<?php echo Yii::app()->language; ?>" lang="<?php echo Yii::app()->language; ?>">  -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="<?php echo Yii::app()->language; ?>">

<!-- blueprint CSS framework -->
<link rel="stylesheet" type="text/css"
    href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
    media="screen, projection">
<link rel="stylesheet" type="text/css"
    href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
    media="print">
<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

<link rel="stylesheet" type="text/css"
    href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
<link rel="stylesheet" type="text/css"
    href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body id="main">
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async
        src="https://www.googletagmanager.com/gtag/js?id=<?=Yii::app()->params['google-tracking-id']?>"></script>
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());
  gtag('config', '<?=Yii::app()->params['google-tracking-id']?>');
</script>
    <script async src="//platform.twitter.com/widgets.js"
        charset="utf-8"></script>

    <div class="container" id="page">

        <div id="header">
            <div style="float:right; margin-top: 20px;">
                <a href="https://twitter.com/adoptrti?ref_src=twsrc%5Etfw"
                    class="twitter-follow-button" data-show-count="false">Follow
                    @adoptrti</a>
            </div>

            <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>

        </div>
        <!-- header -->

        <div id="mainmenu">
            <div style="float: right; width: auto">
                <ul>
                    <?php
                    foreach ( Yii::app ()->params ['translatedLanguages'] as $code => $lang )
                    {
                        ?>
                        <li><a href="?lang=<?=$code?>"><?=$lang?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
		<?php
if (Yii::app ()->user->isGuest)
{
    $this->widget ( 'zii.widgets.CMenu',
            array (
                    'items' => array (
                            array (
                                    'label' => __ ( 'Home' ),
                                    'url' => array (
                                            '/site/index'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'About' ),
                                    'url' => array (
                                            '/site/page',
                                            'view' => 'about'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Data Report' ),
                                    'url' => array (
                                            '/site/report'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Contact' ),
                                    'url' => array (
                                            '/site/contact'
                                    )
                            ),
                            [
                                    'encodeLabel' => false,
                                    'label' => "<i class='fa fa-github'></i>",
                                    'url' => 'https://github.com/adoptrti/kono'
                            ]
                    )
            ) );
}
else
{
    $this->widget ( 'zii.widgets.CMenu',
            array (
                    'items' => array (
                            array (
                                    'label' => __ ( 'Home' ),
                                    'url' => array (
                                            '/site/index'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Committee' ),
                                    'url' => array (
                                            '/committee'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Committee Members' ),
                                    'url' => array (
                                            '/committeeMember'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Assembly Results' ),
                                    'url' => array (
                                            '/assemblyresults'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Elections' ),
                                    'url' => array (
                                            '/election'
                                    )
                            ),
                            // array('label'=>'Login',
                            // 'url'=>array('/site/login'),
                            // 'visible'=>Yii::app()->user->isGuest),
                            array (
                                    'label' => __ ( 'Logout ({uname})',
                                            [
                                                    '{uname}' => Yii::app ()->user->name
                                            ] ),
                                    'url' => array (
                                            '/site/logout'
                                    ),
                                    'visible' => ! Yii::app ()->user->isGuest
                            )
                    )
            ) );
}
?>
	</div>
        <!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php

    $this->widget ( 'zii.widgets.CBreadcrumbs', array (
            'links' => $this->breadcrumbs
    ) );
    ?>
        <!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

        <!-- footer -->

    </div>
    <!-- page -->

    <div id="footer">
        <p><?=__('Made with {heart} in {Coimbatore}',['{heart}' => '<i class="fa fa-heart"></i>','{Coimbatore}' => CHtml::link(__('Coimbatore'),'https://en.wikipedia.org/wiki/Coimbatore',['class' => 'madeinindia','target' => '_new'])])?></p>
        <p class="d">
        <?=`git describe --tags --abbrev=0`?> <i class="fa fa-calendar"></i> <?=`git log --pretty="%ci" -n1 HEAD`?>
        </p>
        <p>
                <?=__('Copyright &copy; {year} by Vikas Yadav.',['{year}' => date('Y')])?> <?=__('All Rights Reserved.')?> <?=CHtml::link(__('Disclaimer'),['site/page','view' => 'disclaimer']) ?><br />
        </p>

    </div>

</body>
</html>
