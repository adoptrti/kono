<?php

/**
 * This is the model class for table "elections".
 *
 * The followings are the available columns in table 'elections':
 * @property integer $id_election
 * @property integer $id_state
 * @property string $edate
 * @property integer $year
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Boothresult[] $boothresults
 * @property ElectionPersonParty[] $electionPersonParties
 * @property State $state
 * @property Results2009[] $results2009s
 * @property Sabha[] $sabhas
 */
class Election extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elections';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('edate, year, type', 'required'),
			array('id_state, year', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>13),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_election, id_state, edate, year, type', 'safe', 'on'=>'search'),
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
			'boothresults' => array(self::HAS_MANY, 'Boothresult', 'id_election'),
			'electionPersonParties' => array(self::HAS_MANY, 'ElectionPersonParty', 'id_election'),
			'assemblymembers' => array(self::HAS_MANY, 'TamilNaduResults2016', 'id_election'),
			'state' => array(self::BELONGS_TO, 'State', 'id_state'),
			'results2009s' => array(self::HAS_MANY, 'Results2009', 'id_election'),
			'sabhas' => array(self::HAS_MANY, 'Sabha', 'id_election'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_election' => __('Election ID'),
			'id_state' => __('State'),
			'edate' => __('Result Date'),
			'year' => __('Year'),
			'type' => __('Election Type'),
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

		$criteria->compare('id_election',$this->id_election);
		$criteria->compare('id_state',$this->id_state);
		$criteria->compare('edate',$this->edate,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Election the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
