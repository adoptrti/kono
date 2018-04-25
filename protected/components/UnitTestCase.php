<?php
/**
 */
class UnitTestCase extends CDbTestCase
{
    var $flag_libxml_use_internal_errors = true;
    var $xparser;
    var $testinghost = '';
    protected $backupGlobalsBlacklist = array('wpdb');

    function runs()
    {
        return array (
                array (
                        0
                ), // to check validation fails when des field is missing
                array (
                        1
                )
        )
        // array(2),
        ;
    }

    function runs3()
    {
        return array (
                array (
                        0
                ), // to check validation fails when des field is missing
                array (
                        1
                ),
                array (
                        2
                )
        );
    }

    /**
     * Returns the response output that was create by the controller action in
     * the source
     * 201209062100:vikas:#119
     *
     * @return string Redirect URL
     */
    public function getOutput()
    {
        global $render_output;
        return $render_output;
    }

    public function getError(CModel $model)
    {
        $arr = $model->getErrors ();
        foreach ( $arr as $v )
        {
            return $v [0] . "(" . count ( $arr ) . ")";
        }
    }

    /**
     * 20121214
     * to easily turn off log expections
     */
    public function ignoreLog()
    {
        global $dontthrowlogger;
        $dontthrowlogger = true;
    }

    /**
     * Will return the redirect url which was set by the source
     * 201209062100:vikas:#119
     *
     * @return string Redirect URL
     */
    public function getRedirectURL()
    {
        global $redirect_url;
        return $redirect_url;
    }

    public function assertXpathNotExists($path, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path was found";

        $rt = $this->doxpath ( $path );
        $this->assertTrue(is_object($rt),$msg);
        $this->assertTrue ( $rt->length === 0, $msg );
        return $rt;
    }

    function assertXpathValue($path, $txt, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path value $txt was not found";

        $rt = $this->assertXpathExists ( $path, $msg );
        $this->assertEquals ( $rt->item ( 0 )->nodeValue, $txt, $msg );
    }

    function assertXpathValue2($path, $txt, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path value2 $txt was not found";

        return $this->assertXpathExists ( $path . "[text()='$txt']", $msg );
    }

    function assertXpathValue2NotFound($path, $txt, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path value2 $txt was found";

        return $this->assertXpathNotExists ( $path . "[text()='$txt']", $msg );
    }

    function assertXpathContains($path, $txt, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path value2 $txt was found";

        $and1 = '[';
        if (']' == substr ( $path, - 1 ))
        {
            $and1 = ' and ';
            $path = substr ( $path, 0, - 1 );
        }

        $path2 = $path . $and1 . "contains(.,'$txt')]";

        return $this->assertXpathExists ( $path2, $msg );
    }

    function assertXpathNotContains($path, $txt, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path value2 $txt was found";

        return $this->assertXpathNotExists ( $path . "[contains(.,'$txt')]", $msg );
    }

    public function initXparser()
    {
        // xparser init
        global $render_output;
        $this->xparser = new gymadarasz\xparser\XNode ( $render_output );
    }

    function initXpath()
    {
        global $render_output;
        $this->dom = new DOMDocument ();
        if ($this->flag_libxml_use_internal_errors)
            libxml_use_internal_errors ( true );
        $this->dom->loadHTML ( $render_output );

        $this->xpath = new DOMXPath ( $this->dom );
        // $this->initXparser();
    }

    /**
     * Assert against XPath selection; should contain exact number of nodes
     *
     * @param string $path
     *            XPath path
     * @param string $count
     *            Number of nodes that should match
     * @param string $message
     * @return void
     */
    public function assertXpathExists($path0, $msg = false)
    {
        if (is_array ( $path0 ))
        {
            $path = '';
            $pathconditions = [ ];
            foreach ( $path0 as $k => $p )
            {
                if ($k !== 0)
                {
                    $k = ($k == '.' ? $k : '@' . $k);
                    $pathconditions [] = 'contains(' . $k . ',"' . $p . '")';
                }
                else
                {
                    $path .= $p;
                }
            }
            $path .= '[' . implode ( ' and ', $pathconditions ) . ']';
        }
        else if (is_string ( $path0 ))
            $path = $path0;

        if (! $msg)
            $msg = "Path $path was not found";

        $rt = $this->doxpath ( $path );

        $this->assertTrue ( $rt && $rt->length > 0, $msg );
        return $rt;
    }

    /**
     * Assert against XPath selection; should contain exact number of nodes
     *
     * @param string $path
     *            XPath path
     * @param string $count
     *            Number of nodes that should match
     * @param string $message
     * @return void
     */
    public function doxpath($path)
    {
        // libxml_use_internal_errors(true);
        $this->initXpath ();
        return $this->xpath->query ( $path );
    }

    /**
     * Assert against XPath selection; should contain exact number of nodes
     *
     * @param string $path
     *            XPath path
     * @param string $count
     *            Number of nodes that should match
     * @param string $message
     * @return void
     */
    public function assertXpathCount($path, $count = 1, $msg = false)
    {
        if (! $msg)
            $msg = "Path $path count did not match";

        $rt = $this->doxpath ( $path );
        $this->assertEquals ( $rt->length, $count, $msg );
        return $rt;
    }

    function assertOutputContains($txt, $msg = false)
    {
        if (! $msg)
            $msg = "Output did not contain $txt";

        global $render_output;
        $this->assertTrue ( strstr ( $render_output, $txt ) !== FALSE, $msg );
    }

    function assertOutputNotContains($txt, $msg = false)
    {
        if (! $msg)
            $msg = "Output did contain $txt";

        global $render_output;
        $this->assertFalse ( strstr ( $render_output, $txt ) !== FALSE, $msg );
    }

    /**
     * 201602081601:vikas:#394:Gurgaon
     *
     * @param unknown $txt
     * @param string $msg
     */
    function assertOutputRegExp($txt, $msg = false)
    {
        if (! $msg)
            $msg = "Output did contain $txt";

        global $render_output;
        $this->assertRegExp ( $txt, $render_output, $msg );
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // $mailmock = $this->getModelMock('SendMail',array('send','toUser'));
        // $mailmock->expects($this->any())->method('toUser');

        // Controller::$dic->set($mailmock,'SendMail');
        $_SERVER = array (
                'SERVER_NAME' => 'testing.local', // the other fields should
                                                // follow
                'SCRIPT_FILENAME' => realpath ( dirname ( __FILE__ ) . "/../../index.php" ), // the
                                                                                     // other
                                                                                     // fields
                                                                                     // should
                                                                                     // follow
                'SCRIPT_NAME' => "index.php", // the other fields should follow
                'PHP_SELF' => '', // the other fields should follow
                'REMOTE_ADDR' => '127.0.0.1',
                'SERVER_PORT' => 80,
                'HTTP_HOST' => 'testing.local',
                'REQUEST_URI' => 'participant/something'
        ) // the other fields should
                                                // follow
;
        Controller::$dic = new Bucket ();
        global $dontthrowlogger;
        $dontthrowlogger = false;
        global $render_output;
        $render_output = 'no-output';
        global $redirect_url, $redirect_status;
        $redirect_url = $redirect_status = NULL;
        parent::setup ();
    }

    public function dontThrowLog()
    {
        global $dontthrowlogger;
        $dontthrowlogger = true;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        global $render_output;
        $hh = Yii::app ()->basePath . '/tests/build/htmloutput';
        file_put_contents ( $hh . '/last_test_reponse.html', $render_output );
    }

    public function runControllerWithExceptionMessage($param, $expected_msg)
    {
        if (strlen ( $expected_msg ) < 5)
        {
            $this->fail ( "Too small exception message: $expected_msg" );
        }
        $actual_msg = $this->runControllerWithException ( $param );
        if (strstr ( $actual_msg ['message'], $expected_msg ) === FALSE)
            $this->fail ( "Expected msg {$actual_msg['message']} found instead of $expected_msg" );
        $this->addToAssertionCount ( 1 );
    }

    public function runControllerWithException($param)
    {
        try
        {
            Yii::app ()->runController ( $param );
        }
        catch ( CException $ex )
        {
            return array (
                    'class' => get_class ( $ex ),
                    'message' => $ex->getMessage ()
            );
        }
        $this->fail ( 'expected exception did not happen' );
    }

    public function getModelMock($class, $methods)
    {
        $setmethods = is_array ( $methods ) ? $methods : array (
                $methods
        );
        return $this->getMockBuilder ( $class )->disableAutoload ()->disableOriginalConstructor ()->setMethods (
                $setmethods )->getMock ();
    }

    public function model2array($arr)
    {
        if (! isset ( $arr [0] ))
        {
            echo "given param is non array()";
            return false;
        }

        $primarykey = $arr [0]->tableSchema->primaryKey;
        $arr2 = array ();
        foreach ( $arr as $i )
        {
            $arr2 [$i->$primarykey] = $i;
        }
        return $arr2;
    }

    public function assertLogContains($logtxt)
    {
        global $last_log;
        $this->assertTrue ( strpos ( $last_log [0], $logtxt ) !== FALSE, "($logtxt) was not found in ($last_log[0])" );
    }

    /**
     * converts arrray into xml
     * checks if the xpath exists in the xml
     * use render_output
     */
    public function assertArrrayXpathExists($needleXpath, $haystack)
    {
        $xml_data = new SimpleXMLElement ( '<?xml version="1.0"?><data></data>' );
        array_to_xml ( $haystack, $xml_data );
        global $render_output;
        $ro_backup = $render_output;
        $render_output = $xml_data->asXML ();
        $this->assertXpathExists ( $needleXpath );
        if (! empty ( $ro_backup ))
        {
            $render_output = $ro_backup;
            $this->initXpath ();
        }
    }

    /**
     * For doing some preparatory work for ACL
     * 201603291330:vikas:#422:Gurgaon:to fix some issues
     */
    public static function setUpACL()
    {
        if (! Yii::app ()->authManager->isAssigned ( AuthConstants::ROLE_ADMIN, 2 ))
            Yii::app ()->authManager->assign ( AuthConstants::ROLE_ADMIN, 2 );

        if (! Yii::app ()->authManager->isAssigned ( AuthConstants::ROLE_EDITOR, 5 ))
            Yii::app ()->authManager->assign ( AuthConstants::ROLE_EDITOR, 5 );

        if (! Yii::app ()->authManager->isAssigned ( AuthConstants::ROLE_DEMO, 7 ))
            Yii::app ()->authManager->assign ( AuthConstants::ROLE_DEMO, 7 );

        Yii::app ()->authManager->save ();
    }

    public function switchEditor()
    {
        Yii::app ()->user->setId ( 5 );
    }

    public function switchAdmin()
    {
        Yii::app ()->user->setId ( 2 );
    }

    public function switchDemo()
    {
        Yii::app ()->user->setId ( 7 );
    }

    public static function tearDownAfterClass()
    {
        Yii::app()->onEndRequest(new CEvent(null));
    }

    /*
     * public function assertXParseContains($query,$val,$msg = '')
     * {
     * $this->initXparser();
     * $this->assertContains($val,$this->xparser->find($query)->getInner(),$msg);
     * }
     */
}

function array_to_xml($data, &$xml_data)
{
    foreach ( $data as $key => $value )
    {
        if (is_array ( $value ))
        {
            if (is_numeric ( $key ))
            {
                $key = 'item' . $key; // dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild ( $key );
            array_to_xml ( $value, $subnode );
        }
        else
        {
            $xml_data->addChild ( "$key", htmlspecialchars ( "$value" ) );
        }
    }
}

