<?php

/**
 * This is the model class for table "results2014".
 *
 * The followings are the available columns in table 'results2014':
 * @property integer $SLNo
 * @property string $STATENAME
 * @property string $CONSTITUENCY
 * @property string $WINNERNAME
 * @property string $CATEGORY
 * @property string $SOCIALCATEGORY
 * @property string $PARTY
 * @property string $PARTYSYMBOL
 * @property integer $MARGIN
 */
class Results2014 extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'results2014';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SLNo, MARGIN', 'numerical', 'integerOnly'=>true),
			array('STATENAME', 'length', 'max'=>25),
			array('CONSTITUENCY', 'length', 'max'=>30),
			array('WINNERNAME', 'length', 'max'=>49),
			array('CATEGORY, SOCIALCATEGORY', 'length', 'max'=>3),
			array('PARTY, PARTYSYMBOL', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('SLNo, STATENAME, CONSTITUENCY, WINNERNAME, CATEGORY, SOCIALCATEGORY, PARTY, PARTYSYMBOL, MARGIN', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'SLNo' => __('Slno'),
			'STATENAME' => __('Statename'),
			'CONSTITUENCY' => __('Constituency'),
			'WINNERNAME' => __('Winnername'),
			'CATEGORY' => __('Category'),
			'SOCIALCATEGORY' => __('Socialcategory'),
			'PARTY' => __('Party'),
			'PARTYSYMBOL' => __('Partysymbol'),
			'MARGIN' => __('Margin'),
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

		$criteria->compare('SLNo',$this->SLNo);
		$criteria->compare('STATENAME',$this->STATENAME,true);
		$criteria->compare('CONSTITUENCY',$this->CONSTITUENCY,true);
		$criteria->compare('WINNERNAME',$this->WINNERNAME,true);
		$criteria->compare('CATEGORY',$this->CATEGORY,true);
		$criteria->compare('SOCIALCATEGORY',$this->SOCIALCATEGORY,true);
		$criteria->compare('PARTY',$this->PARTY,true);
		$criteria->compare('PARTYSYMBOL',$this->PARTYSYMBOL,true);
		$criteria->compare('MARGIN',$this->MARGIN);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Results2014 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
