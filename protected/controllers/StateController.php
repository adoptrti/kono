<?php
class StateController extends Controller
{
    /**
     *
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *      using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     *
     * @return array action filters
     */
    public function filters()
    {
        return array (
                'accessControl', // perform access control for CRUD operations
                'postOnly + delete'  // we only allow deletion via POST request
        );
    }
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * 
     * @return array access control rules
     */
    public function accessRules()
    {
        return array (
                array (
                        'allow', // allow all users to perform 'index' and 'view' actions
                        'actions' => array (
                                'index',
                                'view',
                                'district',
                                'loksabha',
                                'assembly',
                                'election' ,
                                'town',
                        ),
                        'users' => array (
                                '*' 
                        ) 
                ),
                array (
                        'allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions' => array (
                                'create',
                                'update' 
                        ),
                        'users' => array (
                                '@' 
                        ) 
                ),
                array (
                        'allow', // allow admin user to perform 'admin' and 'delete' actions
                        'actions' => array (
                                'admin',
                                'delete' 
                        ),
                        'users' => array (
                                '@' 
                        ) 
                ),
                array (
                        'deny', // deny all users
                        'users' => array (
                                '*' 
                        ) 
                ) 
        );
    }
    public function actionElection($id_election)
    {
        $state = $election->state;
        if (! $state)
            return false;
        
        $this->pageTitle = ucwords ( __ ( '{state} Assembly Elections {eyear} - Assembly Constituencies with Candidate details, Symbols and EVM Positions', [ 
                '{eyear}' => date ( 'Y', strtotime ( $election->edate ) ),
                '{state}' => $election->state->name 
        ] ) );
        $this->render ( 'election', array (
                'model' => $state,
                'election' => $election 
        ) );
    }
    public function actionResults($id_election)
    {
        $this->layout = '//layouts/main';
        $election = Election::model ()->findByPk ( $id_election );
        $candidates = ElectionCandidates::model ()->findByAttributes ( [ 
                'id_election' => $id_election,
                'party' => 'Aam Aadmi Party' 
        ] );
        foreach ( $candidates as $c )
        {
            $eci = $c->eci_ref;
            $url = "http://eciresults.nic.in/ConstituencywiseS10194.htm?ac=" . $eci;
            $html = file_get_contents ( $url );
            echo $html;
        }
    }
    
    /**
     * Displays a particular model.
     * 
     * @param integer $id
     *            the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->layout = '//layouts/main';
        $state = $this->loadModel ( $id );
        if (! $state)
            return false;
        
        $this->pageTitle = Yii::app()->language == 'en' ? ucwords ( strtolower ( $state->name ) ) : $state->name;
        $this->render ( 'view', array (
                'model' => $state 
        ) );
    }
    
    /**
     * Displays a particular model.
     * 
     * @param integer $id
     *            the ID of the model to be displayed
     */
    public function actionDistrict($id_state, $id)
    {
        $this->layout = '//layouts/main';
        
        $town = Town::model ()->findByPk ( $id );
        if (! $town)
            return false;
        
        $this->pageTitle = __ ( '{dist} District, {state}', [ 
                '{dist}' => ucwords ( $town->name ),
                '{state}' => ucwords ( strtolower ( $town->state->name ) ) 
        ] );
        $this->render ( 'district', array (
                'model' => $town 
        ) );
    }
    
    /**
     * Displays a particular model.
     *
     * @param integer $id
     *            the ID of the model to be displayed
     */
    public function actionTown($id)
    {
        $this->layout = '//layouts/main';
        
        $town = Town::model ()->findByPk ( $id );
        if (! $town)
            return false;
            
            $this->pageTitle = __ ( '{{0}}, {{1}} District, {{2}}', [
                    '{{0}}' => ucwords ( $town->name ),
                    '{{1}}' => ucwords ( $town->district->name ),
                    '{{2}}' => ucwords ( strtolower ( $town->state->name ) )
            ] );
            $this->render ( 'town', array (
                    'model' => $town
            ) );
    }
    
    /**
     * 20171202140:Gurgaan:thevikas
     * 
     * @see StateUrlRule::parse_loksabha Displays a particular model.
     * @param integer $id
     *            the ID of the model to be displayed
     */
    public function actionLoksabha($id_consti)
    {
        $this->layout = '//layouts/main';
        
        $consti = Constituency::model ()->findByPk ( $id_consti );
        if (! $consti)
            return false;
        
        $this->pageTitle = __ ( '{dist} Loksabha Constituency, {state}', [ 
                '{dist}' => ucwords ( $consti->name ),
                '{state}' => ucwords ( strtolower ( $consti->state->name ) ) 
        ] );
        $this->render ( 'loksabha', array (
                'model' => $consti 
        ) );
    }
    
    /**
     * 20171202140:Gurgaan:thevikas
     * 
     * @see StateUrlRule::parse_loksabha Displays a particular model.
     * @param integer $id
     *            the ID of the model to be displayed
     */
    public function actionAssembly($acno, $id_state)
    {
        $this->layout = '//layouts/main';
        
        $consti = Constituency::model ()->findByAttributes ( [ 
                'id_state' => $id_state,
                'eci_ref' => $acno,
                'ctype' => 'AMLY' 
        ] );
        if (! $consti)
            return false;
        
        $this->pageTitle = __ ( '{dist} Assembly Constituency, {state}', [ 
                '{dist}' => ucwords ( $consti->name ),
                '{state}' => ucwords ( strtolower ( $consti->state->name ) ) 
        ] );
        $this->render ( 'assembly', array (
                'model' => $consti 
        ) );
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = State::model()->multilang();
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        if (isset ( $_POST ['State'] ))
        {
            $model->attributes = $_POST ['State'];
            if ($model->save ())
                $this->redirect ( array (
                        'view',
                        'id' => $model->id_state 
                ) );
        }
        
        $this->render ( 'create', array (
                'model' => $model 
        ) );
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     *            the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel ( $id ,true);
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        
        if (isset ( $_POST ['State'] ))
        {
            $model->attributes = $_POST ['State'];
            if ($model->save ())
                $this->redirect ( array (
                        'view',
                        'id' => $model->id_state 
                ) );
        }
        
        $this->render ( 'update', array (
                'model' => $model 
        ) );
    }
    
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * 
     * @param integer $id
     *            the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel ( $id )->delete ();
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (! isset ( $_GET ['ajax'] ))
            $this->redirect ( isset ( $_POST ['returnUrl'] ) ? $_POST ['returnUrl'] : array (
                    'admin' 
            ) );
    }
    
    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->layout = '//layouts/main';
        $dataProvider = State::model ()->search ();
        $this->pageTitle = __('Indian States, Chief Ministers and Office Phone and Email');
        $this->render ( 'index', array (
                'dataProvider' => $dataProvider 
        ) );
    }
    
    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new State ( 'search' );
        $model->unsetAttributes (); // clear any default values
        if (isset ( $_GET ['State'] ))
            $model->attributes = $_GET ['State'];
        
        $this->render ( 'admin', array (
                'model' => $model 
        ) );
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * 
     * @param integer $id
     *            the ID of the model to be loaded
     * @return State the loaded model
     * @throws CHttpException
     */
    public function loadModel($id,$ml=false)
    {
        if($ml)
            $model = State::model ()->multilang()->findByPk ( $id );
        else
        {
            $model = State::model ()->findByPk ( $id );
        }
        
        if ($model === null)
            throw new CHttpException ( 404, 'The requested page does not exist.' );
        return $model;
    }
    
    /**
     * Performs the AJAX validation.
     * 
     * @param State $model
     *            the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset ( $_POST ['ajax'] ) && $_POST ['ajax'] === 'state-form')
        {
            echo CActiveForm::validate ( $model );
            Yii::app ()->end ();
        }
    }
}
