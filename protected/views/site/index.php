<?php
Yii::app ()->clientScript->registerCoreScript ( 'jquery-ui' );

$js = <<<J
$( document ).ready(function() {
    $('#maptab').click(function(){
    	$('#googlemaps').toggle();
    })
});
J;

Yii::app()->clientScript->registerScript( 'slider' ,$js,CClientScript::POS_READY);
?>
<link rel="stylesheet" href="/css/listing.css" type="text/css"/>
<link rel='stylesheet' id='bizfinder_elated_google_fonts-css'  href='http://fonts.googleapis.com/css?family=Quicksand%3A300%2C400%2C500%2C600%2C700%2C800%2C900%7COpen+Sans%3A300%2C400%2C500%2C600%2C700%2C800%2C900%7COpen+Sans%3A300%2C400%2C500%2C600%2C700%2C800%2C900&#038;subset=latin-ext&#038;ver=1.0.0' type='text/css' media='all' />
<script src="//maps.googleapis.com/maps/api/js?key=<?= Yii::app()->params['google-api-key']?>&libraries=places&language=<?=Yii::app()->language?>"></script>
<style>
        .tools a {
            color: white;
        }

        .googlecontainer {
            height: 100vh;
            max-height: 600px;
            xwidth: 95%;
            margin: auto;
        }

        #googlemaps {
            width: 100%;
            height: 500px;
        }

        @media all and (max-width: 767px) {
            br {
                display: block;
            }
        }
</style>
<?php #$this->renderPartial('_github-corner');?>
<div class="view" id="maptab">
    <h2 class="acname"><?=__ ( 'Map' )?></h2>

<div id="askaddress">
    <div class="box2">
        <div class="fieldbox">
            <input type="text" class="controls" id="addrbox" placeholder="<?= __('Go to an address') ?>">
        </div>
    </div>
</div>

<div id="googlemaps"></div>

</div>

<script language='Javascript' type="text/javascript" src="/js/gmaprunner.js"></script>
<?php
/*
$json = str_replace("'","",json_encode($data0));
Yii::app()->clientScript->registerScript('dd1', <<<DD1
    $.get( "/site/placeinfo",{t: 'json2',data:'$json'}, function( data ) {
		$( "#result" ).html( data );
	});
DD1
,CClientScript::POS_READY);
*/
?>
<div id="result"></div>
