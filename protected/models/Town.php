<?php

/**
 * This is the model class for table "towns2011".
 *
 * The followings are the available columns in table 'towns2011':
 * @property integer $id_place
 * @property integer $st_code
 * @property integer $id_state
 * @property string $st_name
 * @property integer $dt_code
 * @property string $dt_name
 * @property integer $sdt_code
 * @property string $sdt_name
 * @property integer $tv_code
 * @property string $name
 * @property string $slug
 * @property string $tvtype
 * 
 * @property Town $district
 *
 * The followings are the available model relations:
 * @property States $idState
 */
class Town extends CActiveRecord
{
    private $_district;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'towns2011';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
	    return array(
	            array('st_code, id_state, dt_code, sdt_code, tv_code', 'numerical', 'integerOnly'=>true),
	            array('st_name', 'length', 'max'=>25),
	            array('dt_name, sdt_name', 'length', 'max'=>50),
	            array('name, slug', 'length', 'max'=>70),
	            array('tvtype', 'length', 'max'=>8),
	            // The following rule is used by search().
	            // @todo Please remove those attributes that should not be searched.
	            array('id_place, st_code, id_state, st_name, dt_code, dt_name, sdt_code, sdt_name, tv_code, name, slug, tvtype', 'safe', 'on'=>'search'),
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
			'state' => array(self::BELONGS_TO, 'State', 'id_state'),	        
		);
	}

    public function getdistrict()
    {
        $attrs = [
                'id_state' => $this->id_state,
                'dt_code' => $this->dt_code,
                'sdt_code' => 0,
                'tv_code' => 0
        ];
        
        if(isset ( $this->_district ))
            return $this->_district;
        else
            return $this->_district = Town::model ()->findByAttributes (
                    $attrs );
        
        
        return isset ( $this->_district ) ? $this->_district : $this->_district = Town::model ()->findByAttributes ( 
                [ 
                        'id_state' => $this->id_state,
                        'dt_code' => $this->dt_code,
                        'sdt_code' => 0,
                        'tv_code' => 0 
                ] );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_place' => 'Id Place',
			'st_code' => 'St Code',
			'id_state' => 'Id State',
			'st_name' => 'St Name',
			'dt_code' => 'Dt Code',
			'dt_name' => 'Dt Name',
			'sdt_code' => 'Sdt Code',
			'sdt_name' => 'Sdt Name',
			'tv_code' => 'Tv Code',
			'name' => 'Name',
			'tvtype' => 'Tvtype',
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
	public function search($crit= null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

	    if(empty($criteria))
		  $criteria=new CDbCriteria;
	    else
	        $criteria=new CDbCriteria($crit);
	
        $criteria->compare('id_place',$this->id_place);
		$criteria->compare('st_code',$this->st_code);
		$criteria->compare('id_state',$this->id_state);
		$criteria->compare('st_name',$this->st_name,true);
		$criteria->compare('dt_code',$this->dt_code);
		$criteria->compare('dt_name',$this->dt_name,true);
		$criteria->compare('sdt_code',$this->sdt_code);
		$criteria->compare('sdt_name',$this->sdt_name,true);
		$criteria->compare('tv_code',$this->tv_code);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('tvtype',$this->tvtype,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	        'pagination'=>array(
	                'pageSize'=>100,
	        ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Town the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
