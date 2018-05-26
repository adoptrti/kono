            <?php
            $lname = Yii::app ()->params ['translatedLanguages'] [Yii::app ()->language];
            ?>
<div style="float: right; width: auto" class="dropdown">
	<button class="btn btn-sm dropdown-toggle ldd" type="button"
		id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
		aria-expanded="false">
    <?=__($lname)?>
  </button>
	<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">		
		<h6 class="dropdown-header"><?=__('Languages')?></h6>
                    <?php
                    foreach ( Yii::app ()->params ['translatedLanguages'] as $code => $lang )
                    {
                        $su = new StateUrlRule ();
                        $url = Yii::app ()->getRequest ()->getUrl ();
                        if ('/' == $url [0])
                            $url = substr ( $url, 1 );
                        $backup = $_GET;
                        $_GET = [ ];
                        $rt = $su->parseUrl ( null, null, $url, null );
                        if ($rt === false)
                        {
                            $params2 = "?lang=$code";
                        } else
                        {
                            $params = $_GET;
                            $_GET = $backup;
                            $params ['lang'] = $code;
                            $params2 = array_merge ( [ 
                                    $rt 
                            ], $params );
                        }
                        $act = $code == Yii::app ()->language ? ' active' : '';
                        
                        ?>
                        <?=CHtml::link(__($lang),$params2,['title' => $lang,'class' => 'dropdown-item' . $act])?>
                        <?php
                    }
                    ?>
            </div>
</div>