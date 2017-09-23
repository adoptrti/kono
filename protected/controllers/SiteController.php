<?php
class SiteController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array (
                // captcha action renders the CAPTCHA image displayed on the
                // contact page
                'captcha' => array (
                        'class' => 'CCaptchaAction',
                        'backColor' => 0xFFFFFF 
                ),
                // page action renders "static" pages stored under
                // 'protected/views/site/pages'
                // They can be accessed via: index.php?r=site/page&view=FileName
                'page' => array (
                        'class' => 'CViewAction' 
                ) 
        );
    }
    
    function actionPlaceinfo($t)
    {
        $this->disableWebLog();
        if($t=='json')
        {
            $data = json_decode($_REQUEST['data']);
            $lat = $data[1]->latitude;
            $long = $data[1]->longitude;
        }
        
        $this->layout = false;
        $this->render ( 'placeinfo' ,['lat' => $lat,'long' => $long,'data0' => $data]);
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (empty ( $_POST ['address'] ))
        {
            $this->layout = false;
            $this->render ( 'fetchlocation' );
        }
        else
        {
            /*
             * [0] => stdClass Object
                (
                    [commonName] => 
                    [streetNumber] => B 15
                    [street] => Parsan Road
                    [route] => Parsan Road
                    [neighborhood] => 
                    [town] => Coimbatore
                    [city] => Coimbatore
                    [region] => Coimbatore
                    [postalCode] => 641016
                    [state] => Tamil Nadu
                    [stateCode] => TN
                    [country] => India
                    [countryCode] => IN
                )
        
            [1] => stdClass Object
                (
                    [latitude] => 11.0014015
                    [longitude] => 77.0453917
                    [altitude] => 
                    [accuracy] => 40
                    [altitudeAccuracy] => 
                    [heading] => 
                    [speed] => 
                )
             */
            $data = [json_decode($_POST['address']),json_decode($_POST['coords'])];
            $this->render ( 'index' ,['data0' => $data]);
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app ()->errorHandler->error)
        {
            if (Yii::app ()->request->isAjaxRequest)
                echo $error ['message'];
            else
                $this->render ( 'error', $error );
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm ();
        if (isset ( $_POST ['ContactForm'] ))
        {
            $model->attributes = $_POST ['ContactForm'];
            if ($model->validate ())
            {
                $name = '=?UTF-8?B?' . base64_encode ( $model->name ) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode ( $model->subject ) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" . "Reply-To: {$model->email}\r\n" .
                         "MIME-Version: 1.0\r\n" . "Content-Type: text/plain; charset=UTF-8";
                
                mail ( Yii::app ()->params ['adminEmail'], $subject, $model->body, $headers );
                Yii::app ()->user->setFlash ( 'contact', 
                        'Thank you for contacting us. We will respond to you as soon as possible.' );
                $this->refresh ();
            }
        }
        $this->render ( 'contact', array (
                'model' => $model 
        ) );
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm ();
        
        // if it is ajax validation request
        if (isset ( $_POST ['ajax'] ) && $_POST ['ajax'] === 'login-form')
        {
            echo CActiveForm::validate ( $model );
            Yii::app ()->end ();
        }
        
        // collect user input data
        if (isset ( $_POST ['LoginForm'] ))
        {
            $model->attributes = $_POST ['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate () && $model->login ())
                $this->redirect ( Yii::app ()->user->returnUrl );
        }
        // display the login form
        $this->render ( 'login', array (
                'model' => $model 
        ) );
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app ()->user->logout ();
        $this->redirect ( Yii::app ()->homeUrl );
    }
    
    public function actionUpdate()
    {
        $cmd = new UpdateCommand(1,2);
        $cmd->actionIndex();
    }
}