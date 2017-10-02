<?php

/**
 * This is the model class for table "committee".
 *
 * The followings are the available columns in table 'committee':
 * @property integer $id_state
 * @property integer $id_consti
 * @property string $ctype
 * @property string $name
 * @property string $slug
 * @property integer $id_comm
 * @property integer $id_election
 *
 * The followings are the available model relations:
 * @property CommMember[] $commMembers
 * @property State $state
 * @property Constituency $idConsti
 * @property Elections $idElection
 */
class Committee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'committee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_state, ctype, name, id_election', 'required'),
			array('id_state, id_consti, id_election', 'numerical', 'integerOnly'=>true),
			array('ctype', 'length', 'max'=>4),
			array('name,slug', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_state, id_consti, ctype, name, id_comm, id_election', 'safe', 'on'=>'search'),
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
			'cmembers' => array(self::HAS_MANY, 'CommMember', 'id_comm'),
			'members' => array(self::MANY_MANY, 'AssemblyResults', 'comm_member(id_comm,id_result)'),
			'state' => array(self::BELONGS_TO, 'State', 'id_state'),
			'Constituency' => array(self::BELONGS_TO, 'Constituency', 'id_consti'),
			'election' => array(self::BELONGS_TO, 'Election', 'id_election'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_state' => __('Id State'),
			'id_consti' => __('Id Consti'),
			'ctype' => __('Committee Type'),
			'name' => __('Name'),
			'id_comm' => __('Id Comm'),
			'id_election' => __('Id Election'),
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
		$criteria->compare('id_consti',$this->id_consti);
		$criteria->compare('ctype',$this->ctype,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('id_comm',$this->id_comm);
		$criteria->compare('id_election',$this->id_election);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Committee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
