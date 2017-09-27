<?php

/**
 * This is the model class for table "lsmp2014".
 *
 * The followings are the available columns in table 'lsmp2014':
 * @property integer $id_mp
 * @property string $phones
 * @property string $emails
 * @property string $pc_name
 * @property string $pc_name_clean
 * @property string $state
 * @property integer $sno
 * @property string $name
 * @property string $p_address1
 * @property string $p_address2
 * @property string $COL9
 * @property string $delhi_address1
 * @property string $COL11
 * @property string $party
 * @property string $COL15
 * @property string $delhi_address2
 * @property string $COL17
 * @property string $COL19
 * @property string $COL20
 * @property string $COL21
 * @property string $COL22
 * @property string $COL23
 * @property string $COL24
 * @property string $COL26
 * @property string $COL29
 * @property string $COL30
 */
class LokSabha2014 extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lsmp2014';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pc_name_clean', 'required'),
			array('sno', 'numerical', 'integerOnly'=>true),
			array('phones, emails, pc_name, pc_name_clean, state, name, p_address1, p_address2, delhi_address1, party, delhi_address2', 'length', 'max'=>255),
			array('COL9', 'length', 'max'=>43),
			array('COL11', 'length', 'max'=>22),
			array('COL15', 'length', 'max'=>20),
			array('COL17', 'length', 'max'=>41),
			array('COL19', 'length', 'max'=>58),
			array('COL20', 'length', 'max'=>35),
			array('COL21', 'length', 'max'=>10),
			array('COL22', 'length', 'max'=>39),
			array('COL23', 'length', 'max'=>29),
			array('COL24, COL30', 'length', 'max'=>3),
			array('COL26', 'length', 'max'=>28),
			array('COL29', 'length', 'max'=>42),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_mp, phones, emails, pc_name, pc_name_clean, state, sno, name, p_address1, p_address2, COL9, delhi_address1, COL11, party, COL15, delhi_address2, COL17, COL19, COL20, COL21, COL22, COL23, COL24, COL26, COL29, COL30', 'safe', 'on'=>'search'),
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
			'id_mp' => __('Id Mp'),
			'phones' => __('Phones'),
			'emails' => __('Emails'),
			'pc_name' => __('Pc Name'),
			'pc_name_clean' => __('Pc Name Clean'),
			'state' => __('State'),
			'sno' => __('Sno'),
			'name' => __('Name'),
			'p_address1' => __('P Address1'),
			'p_address2' => __('P Address2'),
			'COL9' => __('Col9'),
			'delhi_address1' => __('Delhi Address1'),
			'COL11' => __('Col11'),
			'party' => __('Party'),
			'COL15' => __('Col15'),
			'delhi_address2' => __('Delhi Address2'),
			'COL17' => 'Col17',
			'COL19' => 'Col19',
			'COL20' => 'Col20',
			'COL21' => 'Col21',
			'COL22' => 'Col22',
			'COL23' => 'Col23',
			'COL24' => 'Col24',
			'COL26' => 'Col26',
			'COL29' => 'Col29',
			'COL30' => 'Col30',
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

		$criteria->compare('id_mp',$this->id_mp);
		$criteria->compare('phones',$this->phones,true);
		$criteria->compare('emails',$this->emails,true);
		$criteria->compare('pc_name',$this->pc_name,true);
		$criteria->compare('pc_name_clean',$this->pc_name_clean,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('sno',$this->sno);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('p_address1',$this->p_address1,true);
		$criteria->compare('p_address2',$this->p_address2,true);
		$criteria->compare('COL9',$this->COL9,true);
		$criteria->compare('delhi_address1',$this->delhi_address1,true);
		$criteria->compare('COL11',$this->COL11,true);
		$criteria->compare('party',$this->party,true);
		$criteria->compare('COL15',$this->COL15,true);
		$criteria->compare('delhi_address2',$this->delhi_address2,true);
		$criteria->compare('COL17',$this->COL17,true);
		$criteria->compare('COL19',$this->COL19,true);
		$criteria->compare('COL20',$this->COL20,true);
		$criteria->compare('COL21',$this->COL21,true);
		$criteria->compare('COL22',$this->COL22,true);
		$criteria->compare('COL23',$this->COL23,true);
		$criteria->compare('COL24',$this->COL24,true);
		$criteria->compare('COL26',$this->COL26,true);
		$criteria->compare('COL29',$this->COL29,true);
		$criteria->compare('COL30',$this->COL30,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LokSabha2014 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
