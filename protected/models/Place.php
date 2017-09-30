<?php

/**
 * This is the model class for table "place_names".
 *
 * The followings are the available columns in table 'place_names':
 * @property integer $id_place2
 * @property integer $state_code
 * @property integer $id_state
 * @property integer $dt_code
 * @property string $dt_name
 * @property integer $sdt_code
 * @property string $sdt_name
 * @property integer $tv_code
 * @property string $name
 * @property string $slug
 * @property string $updated
 * @property string $st_code_2c
 * @property integer $population
 * @property integer $areasqkm
 * @property string $dthq
 * @property string $dt_code_2c
 * @property integer $eci_ref
 * @property integer $amly_count
 *
 * The followings are the available model relations:
 * @property AllAcData[] $allAcDatas
 * @property AllPcData[] $allPcDatas
 * @property Experiance[] $experiances
 * @property Experiance[] $experiances1
 * @property States $idState
 */
class Place extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'place_names';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state_code, dt_code, dt_name, sdt_code, sdt_name, tv_code, name, updated, eci_ref, amly_count', 'required'),
			array('state_code, id_state, dt_code, sdt_code, tv_code, population, areasqkm, eci_ref, amly_count', 'numerical', 'integerOnly'=>true),
			array('dt_name, sdt_name, name, slug', 'length', 'max'=>255),
			array('st_code_2c, dt_code_2c', 'length', 'max'=>3),
			array('dthq', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_place2, state_code, id_state, dt_code, dt_name, sdt_code, sdt_name, tv_code, name, updated, st_code_2c, population, areasqkm, dthq, dt_code_2c, eci_ref, amly_count', 'safe', 'on'=>'search'),
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
			'allAcDatas' => array(self::HAS_MANY, 'AllAcData', 'id_place2'),
			'allPcDatas' => array(self::HAS_MANY, 'AllPcData', 'id_place2'),
			'experiances' => array(self::HAS_MANY, 'Experiance', 'id_place'),
			'experiances1' => array(self::HAS_MANY, 'Experiance', 'id_place2'),
			'state' => array(self::BELONGS_TO, 'State', 'id_state'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_place2' => 'Id Place2',
			'state_code' => 'State Code',
			'id_state' => 'Id State',
			'dt_code' => 'Dt Code',
			'dt_name' => 'Dt Name',
			'sdt_code' => 'Sdt Code',
			'sdt_name' => 'Sdt Name',
			'tv_code' => 'Tv Code',
			'name' => 'Name',
			'updated' => 'Updated',
			'st_code_2c' => 'St Code 2c',
			'population' => 'Population',
			'areasqkm' => 'Areasqkm',
			'dthq' => 'Dthq',
			'dt_code_2c' => 'Dt Code 2c',
			'eci_ref' => 'Eci Ref',
			'amly_count' => 'Amly Count',
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

		$criteria->compare('id_place2',$this->id_place2);
		$criteria->compare('state_code',$this->state_code);
		$criteria->compare('id_state',$this->id_state);
		$criteria->compare('dt_code',$this->dt_code);
		$criteria->compare('dt_name',$this->dt_name,true);
		$criteria->compare('sdt_code',$this->sdt_code);
		$criteria->compare('sdt_name',$this->sdt_name,true);
		$criteria->compare('tv_code',$this->tv_code);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('st_code_2c',$this->st_code_2c,true);
		$criteria->compare('population',$this->population);
		$criteria->compare('areasqkm',$this->areasqkm);
		$criteria->compare('dthq',$this->dthq,true);
		$criteria->compare('dt_code_2c',$this->dt_code_2c,true);
		$criteria->compare('eci_ref',$this->eci_ref);
		$criteria->compare('amly_count',$this->amly_count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Place the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
