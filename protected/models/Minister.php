<?php

/**
 * This is the model class for table "minister".
 *
 * The followings are the available columns in table 'minister':
 * @property integer $id_state
 * @property integer $id_member
 * @property string $appointed_from
 * @property string $appointed_to
 * @property string $created
 * @property string $updated
 * @property integer $id_minister
 * @property integer $id_ministry
 */
class Minister extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'minister';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_state, id_member, appointed_from, appointed_to, created, updated, id_ministry', 'required'),
			array('id_state, id_member, id_ministry', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_state, id_member, appointed_from, appointed_to, created, updated, id_minister, id_ministry', 'safe', 'on'=>'search'),
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
			'id_state' => 'State',
			'id_member' => 'Memmber',
			'appointed_from' => 'Appointed from',
			'appointed_to' => 'Appointed To',
			'created' => 'Created',
			'updated' => 'Updated',
			'id_minister' => 'Id Minister',
			'id_ministry' => 'Ministry',
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

		$criteria->compare('id_state',$this->id_state);
		$criteria->compare('id_member',$this->id_member);
		$criteria->compare('appointed_from',$this->appointed_from,true);
		$criteria->compare('appointed_to',$this->appointed_to,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('id_minister',$this->id_minister);
		$criteria->compare('id_ministry',$this->id_ministry);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Minister the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
