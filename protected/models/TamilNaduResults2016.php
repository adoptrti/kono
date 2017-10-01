<?php

/**
 * This is the model class for table "tnresults2016".
 *
 * The followings are the available columns in table 'tnresults2016':
 * @property integer $id_election
 * @property integer $id_state
 * @property integer $id_consti
 * @property string $acname
 * @property integer $acno
 * @property string $name
 * @property string $slug
 * @property string $gender
 * @property string $party
 * @property string $address
 * @property string $phones
 * @property string $emails
 * @property integer $ST_CODE
 * @property string $picture
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
	            array('id_election,acno,id_state,name', 'required'),
	            array('id_election, id_state, id_consti, acno, ST_CODE', 'numerical', 'integerOnly'=>true),
	            array('acname, name, party, address, phones, emails, picture', 'length', 'max'=>255),
	            array('gender', 'length', 'max'=>6),
	            // The following rule is used by search().
	            // @todo Please remove those attributes that should not be searched.
	            array('id_result, id_election, id_state, id_consti, acname, acno, name, gender, party, address, phones, emails, ST_CODE', 'safe', 'on'=>'search'),
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
			'committees' => array(self::MANY_MANY, 'Committee', 'comm_member(id_result,id_comm)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'acname' => __('Acname'),
			'acno' => __('Acno'),
			'name' => __('Name'),
			'party' => __('Party'),
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
