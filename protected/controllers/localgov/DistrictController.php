<?php

use Prophecy\Doubler\ClassPatch\DisableConstructorPatch;

class DistrictController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','creatediv'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new District;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['District']))
		{
			$model->attributes=$_POST['District'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_district));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreatediv($id_state = 0)
	{
	    $model=new District;
	    
	    // Uncomment the following line if AJAX validation is needed
	    // $this->performAjaxValidation($model);
	    
	    if(isset($_POST['District']))
	    {
	        $hq_id = 0;
	        $objs = [];
	        foreach($_POST['id_district'] as $id)
	        {
	            $dist = District::model()->findByPk($id);
	            $objs[$id] = $dist;
	            #echo "distname=" . $dist->name . " != " . $_POST['District']['name'] . "<br/>";
	            if(strtolower(trim($dist->name)) == strtolower(trim($_POST['District']['name'])))
	                $hq_id = $id;
	        }
	        
	        
	        if(!$hq_id)
	        {
	            $dist = new District();
	            $dist->name = $_POST['District']['name'];
	            $dist->id_state = $id_state;
	            $dist->id_district_division_hq = $id_state;
	            
	            if(!$dist->save())
	            {	                
	                print_r($dist->getErrors());
	                die("Could not save new HQ {$dist->name}");
	            }
	            $hq_id = $dist->id_district_division_hq  = $dist->id_district;
	        }
	        
	        foreach($objs as $id => $dist)
	        {
	            $dist->id_district_division_hq = $hq_id;
	            $dist->update(['id_district_division_hq']);
	        }
	        $this->redirect(array('view','id'=>$hq_id));
	        return;
	    }
	    
	    $state = 0;
	    if($id_state)
	        $state = State::model()->findByPk($id_state);
	    
	    $this->render('creatediv',array(
	            'model'=>$model,
	            'id_state' => $id_state,
	            'state' => $state
	    ));
	}
	
    /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['District']))
		{
			$model->attributes=$_POST['District'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_district));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('District');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new District('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['District']))
			$model->attributes=$_GET['District'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return District the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=District::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param District $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='district-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
