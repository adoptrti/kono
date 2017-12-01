<?php

/**
 * This is the model class for table "officer".
 *
 * The followings are the available columns in table 'officer':
 * @property integer $id_officer
 * @property string $name
 * @property integer $fkey_place
 * @property string $desig
 * @property string $updated
 * @property string $created
 * @property string $phone
 * @property string $fax
 * @property string $email
 */
class Officer extends CActiveRecord
{
    const DESIG_DISTCOLLECTOR = 'DISTCOLLECTOR';
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'officer';
	}

    public function beforeSave()
    {
        if ($this->desig == 'DISTCOLLECTOR')
            if (! Yii::app ()->user->checkAccess ( 'ADD_DEPUTY_COMMISSIONER' ))
                return false;
            
        return parent::beforeSave ();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('fkey_place', 'numerical', 'integerOnly'=>true),
			array('name, phone, fax, email', 'length', 'max'=>255),
			array('desig', 'length', 'max'=>13),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_officer, name, fkey_place, desig, updated, created, phone, fax, email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
	        'district' => array(self::BELONGS_TO, 'District', 'fkey_place'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_officer' => 'Id Officer',
			'name' => __('Name'),
			'fkey_place' => __('Place'),
			'desig' => __('Designation'),
			'updated' => __('Updated'),
			'created' => __('Created'),
			'phone' => __('Phone'),
			'fax' => __('Fax'),
			'email' => __('Email'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_officer',$this->id_officer);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('fkey_place',$this->fkey_place);
		$criteria->compare('desig',$this->desig,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Officer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
