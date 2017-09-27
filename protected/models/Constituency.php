<?php

/**
 * This is the model class for table "constituency".
 *
 * The followings are the available columns in table 'constituency':
 * @property integer $id_consti
 * @property integer $id_parl_consti
 * @property integer $id_dist_place2
 * @property integer $eci_ref
 * @property string $name
 * @property string $resv
 * @property integer $number
 * @property integer $id_state
 * @property integer $voters
 * @property integer $pollingstns
 * @property double $turnout
 * @property integer $castvotes
 * @property integer $contestents
 * @property string $ctype
 * @property integer $id_parent
 * @property string $candilisthtml
 * @property string $updated
 * @property integer $id_state2
 * @property double $gps_long
 * @property double $gps_lat
 * @property double $gps_ele
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property Boothresult[] $boothresults
 * @property Boothresult[] $boothresults1
 * @property States $idState
 * @property Constituency $idParent
 * @property Constituency[] $constituencies
 * @property States $idState2
 * @property ElectionPersonParty[] $electionPersonParties
 * @property Pollingbooth[] $pollingbooths
 * @property Pollingbooth[] $pollingbooths1
 * @property Results2009[] $results2009s
 * @property Ward[] $wards
 * @property Ward[] $wards1
 */
class Constituency extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'constituency';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, number, castvotes, contestents, ctype, candilisthtml, updated', 'required'),
			array('id_parl_consti, id_dist_place2, eci_ref, number, id_state, voters, pollingstns, castvotes, contestents, id_parent, id_state2', 'numerical', 'integerOnly'=>true),
			array('turnout, gps_long, gps_lat, gps_ele', 'numerical'),
			array('name,slug', 'length', 'max'=>50),
			array('resv', 'length', 'max'=>2),
			array('ctype', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_consti, id_parl_consti, id_dist_place2, eci_ref, name, resv, number, id_state, voters, pollingstns, turnout, castvotes, contestents, ctype, id_parent, candilisthtml, updated, id_state2, gps_long, gps_lat, gps_ele', 'safe', 'on'=>'search'),
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
			'boothresults' => array(self::HAS_MANY, 'Boothresult', 'id_amly_consti'),
			'boothresults1' => array(self::HAS_MANY, 'Boothresult', 'id_parl_consti'),
			'idState' => array(self::BELONGS_TO, 'States', 'id_state'),
			'idParent' => array(self::BELONGS_TO, 'Constituency', 'id_parent'),
			'constituencies' => array(self::HAS_MANY, 'Constituency', 'id_parent'),
			'idState2' => array(self::BELONGS_TO, 'States', 'id_state2'),
			'electionPersonParties' => array(self::HAS_MANY, 'ElectionPersonParty', 'id_consti'),
			'pollingbooths' => array(self::HAS_MANY, 'Pollingbooth', 'id_parl_consti'),
			'pollingbooths1' => array(self::HAS_MANY, 'Pollingbooth', 'id_amly_consti'),
			'results2009s' => array(self::HAS_MANY, 'Results2009', 'id_consti'),
			'wards' => array(self::HAS_MANY, 'Ward', 'id_amly_consti'),
			'wards1' => array(self::HAS_MANY, 'Ward', 'id_parl_consti'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_consti' => __('Id Consti'),
			'id_parl_consti' => __('Id Parl Consti'),
			'id_dist_place2' => __('Id Dist Place2'),
			'eci_ref' => __('Eci Ref'),
			'name' => __('Name'),
			'resv' => __('Resv'),
			'number' => __('Number'),
			'id_state' => __('Id State'),
			'voters' => __('Voters'),
			'pollingstns' => __('Pollingstns'),
			'turnout' => __('Turnout'),
			'castvotes' => __('Castvotes'),
			'contestents' => __('Contestents'),
			'ctype' => __('Contituency Type'),
			'id_parent' => __('Id Parent'),
			'candilisthtml' => __('Candilisthtml'),
			'updated' => __('Updated'),
			'id_state2' => __('Id State2'),
			'gps_long' => __('Gps Long'),
			'gps_lat' => __('Gps Lat'),
			'gps_ele' => __('Gps Ele'),
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

		$criteria->compare('id_consti',$this->id_consti);
		$criteria->compare('id_parl_consti',$this->id_parl_consti);
		$criteria->compare('id_dist_place2',$this->id_dist_place2);
		$criteria->compare('eci_ref',$this->eci_ref);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('resv',$this->resv,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('id_state',$this->id_state);
		$criteria->compare('voters',$this->voters);
		$criteria->compare('pollingstns',$this->pollingstns);
		$criteria->compare('turnout',$this->turnout);
		$criteria->compare('castvotes',$this->castvotes);
		$criteria->compare('contestents',$this->contestents);
		$criteria->compare('ctype',$this->ctype,true);
		$criteria->compare('id_parent',$this->id_parent);
		$criteria->compare('candilisthtml',$this->candilisthtml,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('id_state2',$this->id_state2);
		$criteria->compare('gps_long',$this->gps_long);
		$criteria->compare('gps_lat',$this->gps_lat);
		$criteria->compare('gps_ele',$this->gps_ele);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Constituency the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
