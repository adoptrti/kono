<?php

class SiteTest extends UnitTestCase
{
    protected function setUp()
    {
        $_SERVER=array(
                'SERVER_NAME'=>'kono.local', // the other fields should follow
                'SCRIPT_FILENAME'=> realpath("../../index.php"), // the other fields should follow
                'SCRIPT_NAME'=>'index.php', // the other fields should follow
                'REQUEST_URI' => '/',
                'PHP_SELF'=>'/', // the other fields should follow
        );            
    }
    
	public function testIndex()
	{
	    Yii::app ()->runController ( 'site/index' );
	}
	
}
