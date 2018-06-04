<?php
class StateControllerTest extends UnitTestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        /*$_SERVER=array(
                'SERVER_NAME'=>'abc.local', // the other fields should follow
                'SCRIPT_FILENAME'=> realpath("../../index.php"), // the other fields should follow
                'SCRIPT_NAME'=>'index.php', // the other fields should follow
                'REQUEST_URI' => '/en/xyz',
                'PHP_SELF'=>'', // the other fields should follow
        );
		*/
        parent::setup();
        $_GET['lang'] = 'en';        
    }

    public function contacturls()
    {
        return [                
        ];
    }

    /**
     * To test new language urls for contact parses and open properly
     *
     * param unknown $pathInfo
     * dataProvider contacturls
     * group ranking
     */
    public function testIndex()
    {    	
        Yii::app ()->runController ( 'state/index' );
    }

}
