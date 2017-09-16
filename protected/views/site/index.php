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
        
        #pac-input {
            width: 250px;
            height: 40px;
            xmargin-top: 10px;
            border: 1px solid;
            xpadding-left: 10px;
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
    
<script src="//maps.googleapis.com/maps/api/js?key=<?= Yii::app()->params['google-api-key']?>&libraries=places"></script>
    
<input id="pac-input" class="controls" type="text" placeholder="Type city, zip or address here..">
<div id="googlemaps"></div>
<script language='Javascript' type="text/javascript" src="/js/gmaprunner.js"></script>
<?php 
$json = json_encode($data0);
Yii::app()->clientScript->registerScript('dd1', <<<DD1
        
    $.post( "/site/placeinfo?t=json",{data:'$json'}, function( data ) {
		$( "#result" ).html( data );
	});

DD1
,CClientScript::POS_READY);
?>
<div id="result"></div>