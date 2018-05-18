<?php

class OfficerController extends Controller
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
				'roles'=>array('admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
			        'roles'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
			        'roles'=>array('admin'),
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
	public function actionCreate($id_state,$id_district=0,$desig = null)
	{
		$model=new Officer;
        $this->pageTitle = __('Create Officer');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Officer']))
		{
			$model->attributes=$_POST['Officer'];
			if($model->save())
			{
			    if(!empty($_POST['picture_url']))
			        $model->savePicture($_POST['picture_url']);
			        
				$this->redirect(array('view','id'=>$model->id_officer));
			}
		}
		
		if(!empty($id_district))
		    $model->fkey_place = $id_district;		
	    else if(!empty($id_state) && ($desig == Officer::DESIG_CHIEFMINISTER || $desig == Officer::DESIG_DEPUTYCHIEFMINISTER || $desig == Officer::DESIG_GOVERNER))
            $model->fkey_place = $id_state;
		        
        if(!empty($desig))
	        $model->desig = $desig;

		$this->render('create',array(
			'model'=>$model,
	        'id_state' => $id_state,
	        'id_district' => $id_district,
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
		$this->pageTitle = __('Update Officer');
		    

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Officer']))
		{
			$model->attributes=$_POST['Officer'];
			if($model->save())
			{
			    if(!empty($_POST['picture_url']))
			        $model->savePicture($_POST['picture_url']); 			        
				$this->redirect(array('view','id'=>$model->id_officer));
			}
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
		$dataProvider=new CActiveDataProvider('Officer');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Officer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Officer']))
			$model->attributes=$_GET['Officer'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Officer the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Officer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Officer $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='officer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
