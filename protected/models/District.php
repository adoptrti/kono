<?php

/**
 * This is the model class for table "lb_district".
 *
 * The followings are the available columns in table 'lb_district':
 * @property integer $id_district
 * @property string $name
 * @property string $updated
 * @property integer $id_state
 * @property integer $id_district_division_hq
 * @property string $other_names
 * 
 * @property string $slug
 * @property integer $pri_code
 * @property integer $organizationId
 * @property string $domainName
 * @property string $friendlyUrl
 * @property string $nomenclatureName
 * 
 *  @property State $state
 *   @property Block $blocks
 * 
 */
class District extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lb_district';
	}
	
	public function bystate($id_state)
	{
	    $this->getDbCriteria ()->mergeWith (
	            array (
	                    'condition' => 'id_state=:sid',
	                    'params' => array (
	                            'sid' => $id_state
	                    )
	            ) );
	    return $this;
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
	                    'class' => 'application.behaviours.NameLinkBehavior',
	                    'controller' => 'localgov/district',
	                    'template' => '{link}'
	            ],
	            'OtherNamesBehavior' => [
	                    'class' => 'application.behaviours.OtherNamesBehavior',
	            ]	            
	    ];
	}
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, id_state', 'required'),
			array('id_district_division_hq, id_state', 'numerical', 'integerOnly'=>true),
			
	        array('pri_code,organizationId', 'numerical', 'integerOnly'=>true),
	        array('slug, domainName,friendlyUrl,nomenclatureName', 'length', 'max'=>255),
		        
	        array('name', 'length', 'max'=>255),
	        array('other_names', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_district, id_district_division_hq, name, updated, id_state', 'safe', 'on'=>'search'),
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
	        'blocks' => array(self::HAS_MANY, 'Block', 'id_district','order' => 'name'),
	        'polygons' => array(self::HAS_MANY, 'AssemblyPolygon', 'id_district','order' => 'name'),
	        'division' => array(self::BELONGS_TO, 'District', 'id_district_division_hq'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_district' => 'Id District',
			'name' => 'Name',
			'updated' => 'Updated',
			'id_state' => 'Id State',
	        'id_district_division_hq' => __('Divisional HQ')
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

		$criteria->compare('id_district',$this->id_district);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('id_state',$this->id_state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return District the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
