<?php

/**
 * This is the model class for table "comm_member".
 *
 * The followings are the available columns in table 'comm_member':
 * @property integer $id_comm
 * @property integer $id_result
 * @property integer $chairman
 *
 * The followings are the available model relations:
 * @property Tnresults2016 $idResult
 * @property Committee $idComm
 */
class CommitteeMember extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'comm_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_comm, id_result, chairman', 'required'),
			array('id_comm, id_result, chairman', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_comm, id_result, chairman', 'safe', 'on'=>'search'),
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
			'electedmember' => array(self::BELONGS_TO, 'Tnresults2016', 'id_result'),
			'committee' => array(self::BELONGS_TO, 'Committee', 'id_comm'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_comm' => 'Id Comm',
			'id_result' => 'Id Result',
			'chairman' => 'Chairman',
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

		$criteria->compare('id_comm',$this->id_comm);
		$criteria->compare('id_result',$this->id_result);
		$criteria->compare('chairman',$this->chairman);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommitteeMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
