<?php

/**
 * This is the model class for table "election_candidates".
 *
 * The followings are the available columns in table 'election_candidates':
 * @property integer $id_candidate
 * @property string $name
 * @property string $party
 * @property string $gender
 * @property integer $age
 * @property string $symbol
 * @property string $education
 * @property string $category
 * @property integer $button
 * @property integer $eci_ref
 * @property integer $id_election
 * @property string $created
 * @property string $updated
 * @property string $cases
 * @property string $assets,
 * @property string $liabilities
 */
class ElectionCandidates extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'election_candidates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, updated', 'required'),
			array('age, button, eci_ref, id_election', 'numerical', 'integerOnly'=>true),
			array('name, party, symbol, education, cases, assets, liabilities', 'length', 'max'=>255),
			array('gender', 'length', 'max'=>1),
			array('category', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_candidate, name, party, gender, age, symbol, education, category, button, eci_ref, id_election, created, updated', 'safe', 'on'=>'search'),
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
			'election' => array(self::BELONGS_TO, 'Election', 'id_election'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_candidate' => 'Id Candidate',
			'name' => 'Name',
			'party' => 'Party',
			'gender' => 'Gender',
			'age' => 'Age',
			'symbol' => 'Symbol',
			'education' => 'Education',
			'category' => 'Category',
			'button' => 'Button',
			'eci_ref' => 'Eci Ref',
			'id_election' => 'Id Election',
			'created' => 'Created',
			'updated' => 'Updated',
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

		$criteria->compare('id_candidate',$this->id_candidate);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('party',$this->party,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('age',$this->age);
		$criteria->compare('symbol',$this->symbol,true);
		$criteria->compare('education',$this->education,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('button',$this->button);
		$criteria->compare('eci_ref',$this->eci_ref);
		$criteria->compare('id_election',$this->id_election);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination' => [
						'pageSize' => 100
				]				
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ElectionCandidates the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
