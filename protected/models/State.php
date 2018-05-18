<?php

/**
 * This is the model class for table "State".
 *
 * The followings are the available columns in table 'State':
 * @property integer $id_state
 * @property integer $ST_CODE
 * @property string $name
 * @property string $ias_short_code
 * @property integer $id_census
 * @property string $eci_ref
 * @property string $session_from
 * @property string $session_to
 * @property integer $lok_parl_seats
 * @property integer $amly_seats
 * @property integer $raj_parl_seats
 * @property string $updated
 * @property string $iso3166
 * @property integer $psloc
 * @property integer $eci_dist_count
 * @property integer $eci_amly_count
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property AllAcData[] $allAcDatas
 * @property AllPcData[] $allPcDatas
 * @property Constituency[] $constituencies
 * @property Constituency[] $constituencies1
 * @property Elections[] $elections
 * @property PlaceNames[] $placeNames
 * @property Results2009[] $results2009s
 * @property Sabha[] $sabhas
 * @property Ward[] $wards
 */
class State extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'states';
	}
	
	public function behaviors()
	{
	    return [
	            'CTimestampBehavior' => array (
	                    'class' => 'zii.behaviors.CTimestampBehavior',
	                    'createAttribute' => null,
	                    'updateAttribute' => 'updated',
	            ),
	            'NameLinkBehavior' => [
	                    'class' => 'kono.behaviours.NameLinkBehavior',
	                    'controller' => 'state',
	                    'template' => '{link}'
	            ]
	    ];
	}

    public function bycode($st_code)
    {
        $this->getDbCriteria ()->mergeWith ( 
                array (
                        'condition' => 't.ST_CODE=?',
                        'params' => [$st_code] 
                ) );
        return $this;
    }	
        
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, ias_short_code, session_from, session_to, lok_parl_seats, amly_seats, raj_parl_seats, updated, iso3166, psloc, eci_dist_count, eci_amly_count', 'required'),
			array('ST_CODE, id_census, lok_parl_seats, amly_seats, raj_parl_seats, psloc, eci_dist_count, eci_amly_count', 'numerical', 'integerOnly'=>true),
			array('name,slug', 'length', 'max'=>50),
			array('ias_short_code', 'length', 'max'=>2),
			array('eci_ref, iso3166', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_state, ST_CODE, name, ias_short_code, id_census, eci_ref, session_from, session_to, lok_parl_seats, amly_seats, raj_parl_seats, updated, iso3166, psloc, eci_dist_count, eci_amly_count', 'safe', 'on'=>'search'),
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
			'allAcDatas' => array(self::HAS_MANY, 'AllAcData', 'id_state'),
			'allPcDatas' => array(self::HAS_MANY, 'AllPcData', 'id_state'),
			'constituencies' => array(self::HAS_MANY, 'Constituency', 'id_state'),
	        'amly_constituencies' => array(self::HAS_MANY, 'Constituency', 'id_state','condition' => "ctype='AMLY'",'order' => 'eci_ref'),
	        'parl_constituencies' => array(self::HAS_MANY, 'Constituency', 'id_state','condition' => "ctype='PARL'",'order' => 'eci_ref'),
			'mc_constituencies' => array(self::HAS_MANY, 'Town', 'id_state','condition' => "tvtype='mcorp'",'order' => 'name'),
			'constituencies1' => array(self::HAS_MANY, 'Constituency', 'id_state2'),
			'elections' => array(self::HAS_MANY, 'Election', 'id_state','condition' => "type='AMLY'"),
			'upcomingelections' => array(self::HAS_MANY, 'Election', 'id_state','condition' => "type='AMLY' and edate>now()"),
	        'districts' => array(self::HAS_MANY, 'District', 'id_state','order' => 'name'),
		    'divisions' => array(self::HAS_MANY, 'District', 'id_state','order' => 'name','condition' => 'id_district_division_hq=id_district'),
			'placeNames' => array(self::HAS_MANY, 'PlaceNames', 'id_state'),
	        'governer' => array(self::HAS_ONE, 'Officer', 'fkey_place','condition' => "desig=:desig",'params' => ['desig' =>  Officer::DESIG_GOVERNER]),
			'results2009s' => array(self::HAS_MANY, 'Results2009', 'id_state'),
			'sabhas' => array(self::HAS_MANY, 'Sabha', 'id_state'),
			'wards' => array(self::HAS_MANY, 'Ward', 'id_state'),
            'chiefminister' => array(self::HAS_ONE, 'Officer', 'fkey_place','condition' => 'desig=:desig1','params' => ['desig1' => Officer::DESIG_CHIEFMINISTER]),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_state' => __('Id State'),
			'ST_CODE' => __('St Code'),
			'name' => __('Name'),
			'ias_short_code' => __('Ias Short Code'),
			'id_census' => __('Id Census'),
			'eci_ref' => __('State number as per ECI'),
			'session_from' => __('Session From'),
			'session_to' => __('Session To'),
			'lok_parl_seats' => __('Lok Parl Seats'),
			'amly_seats' => __('Amly Seats'),
			'raj_parl_seats' => __('Raj Parl Seats'),
			'updated' => __('Updated'),
			'iso3166' => __('Iso3166'),
			'psloc' => __('is polling station info available'),
			'eci_dist_count' => __('no of dist as per ECI'),
			'eci_amly_count' => __('Eci Amly Count'),
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
		$criteria->compare('ST_CODE',$this->ST_CODE);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('ias_short_code',$this->ias_short_code,true);
		#$criteria->compare('id_census',$this->id_census);
		$criteria->compare('eci_ref',$this->eci_ref,true);
		$criteria->compare('session_from',$this->session_from,true);
		$criteria->compare('session_to',$this->session_to,true);
		$criteria->compare('lok_parl_seats',$this->lok_parl_seats);
		$criteria->compare('amly_seats',$this->amly_seats);
		$criteria->compare('raj_parl_seats',$this->raj_parl_seats);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('iso3166',$this->iso3166,true);
		$criteria->compare('psloc',$this->psloc);
		$criteria->compare('eci_dist_count',$this->eci_dist_count);
		$criteria->compare('eci_amly_count',$this->eci_amly_count);

		$criteria->order = 'name';
		return new CActiveDataProvider ( $this, array (
                'criteria' => $criteria,
                'pagination' => [ 
                        'pageSize' => 100 
                ]
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return State the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
