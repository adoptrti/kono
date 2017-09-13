<?php

/**
 * This is the model class for table "tnresults2016".
 *
 * The followings are the available columns in table 'tnresults2016':
 * @property string $acname
 * @property integer $acno
 * @property string $name
 * @property string $party
 */
class TamilNaduResults2016 extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tnresults2016';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('acno', 'numerical', 'integerOnly'=>true),
			array('acname', 'length', 'max'=>22),
			array('name', 'length', 'max'=>27),
			array('party', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('acname, acno, name, party', 'safe', 'on'=>'search'),
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
			'acname' => 'Acname',
			'acno' => 'Acno',
			'name' => 'Name',
			'party' => 'Party',
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

		$criteria->compare('acname',$this->acname,true);
		$criteria->compare('acno',$this->acno);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('party',$this->party,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TamilNaduResults2016 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
