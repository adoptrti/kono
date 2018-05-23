<?php

/**
 * This is the model class for table "officer".
 *
 * The followings are the available columns in table 'officer':
 * @property integer $id_officer
 * @property string $name
 * @property integer $fkey_place
 * @property string $desig
 * @property string $updated
 * @property string $created
 * @property string $phone
 * @property string $address
 * @property string $govoffice ('MCORP')
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $picture
 */
class Officer extends CActiveRecord
{   
    //state
    const DESIG_GOVERNER= 'GOVERNER';
    const DESIG_LGOVERNER= 'LGOVERNER';
    const DESIG_CHIEFMINISTER= 'CHIEFMINISTER';
	const DESIG_DEPUTYCHIEFMINISTER= 'DEPUTYCHIEFMINISTER';
	const DESIG_STATEINFOCOMMISSIONER = 'STATEINFOCOMMISSIONER';
    //district
    const DESIG_DEPUTYCOMMISSIONER = 'DEPUTYCOMMISSIONER';
    const DESIG_DIVCOMMISSIONER = 'DIVCOMMISSIONER';
    //municipal
    const DESIG_JOINTCOMMISSIONER = 'JOINTCOMMISSIONER';
    const DESIG_ASSTCOMMISSIONER = 'ASSTCOMMISSIONER';
    const DESIG_EXECENGINEER = 'EXECENGINEER';
    const DESIG_ASSTEXECENGINEER= 'ASSTEXECENGINEER';
    const DESIG_ASSTTOWNPLANNER = 'ASSTTOWNPLANNER';
    const DESIG_ASSTREVENUEOFF = 'ASSTREVENUEOFF';
    const DESIG_ZONALSANITORYOFF = 'ZONALSANITORYOFF';
    //ward
    const DESIG_ASSTENGINEER = 'ASSTENGINEER';
    const DESIG_WATERSUPPLYOFF = 'WATERSUPPLYOFF';
    const DESIG_SANITORYINSPECTOR = 'SANITORYINSPECTOR';
    //zone
    const DESIG_HEALTHOFFICER = 'HEALTHOFFICER';
    const DESIG_DEPUTYHEALTHOFFICER = 'DEPUTYHEALTHOFFICER';
    const DESIG_CHIEFENGINEER = 'CHIEFENGINEER';
    
    
   	public static function designstr()
   	{
   		$designstr = [
   				//duplicates
   				__('Chief Engineer (I/C)') => Officer::DESIG_CHIEFENGINEER,
   				__('Health Officer (I/C)') => Officer::DESIG_HEALTHOFFICER,
   				__('Medical Officer of Health') => Officer::DESIG_HEALTHOFFICER,
   				//originals
   				__('Deputy Commissioner') => Officer::DESIG_DEPUTYCOMMISSIONER,
   				__('Joint Commissioner') => Officer::DESIG_JOINTCOMMISSIONER,
   				__('Assisstant Commissioner') => Officer::DESIG_ASSTCOMMISSIONER,
   				__('Executive Engineer') => Officer::DESIG_EXECENGINEER,
   				__('Chief Engineer') => Officer::DESIG_CHIEFENGINEER,
   				__('Health Officer') => Officer::DESIG_HEALTHOFFICER,
   				__('Deputy Health Officer') => Officer::DESIG_DEPUTYHEALTHOFFICER,
   				__('Assistant Executive Engineer') => Officer::DESIG_ASSTEXECENGINEER,
   				__('Assistant Town Planning Officer') => Officer::DESIG_ASSTTOWNPLANNER,
   				__('Assistant Revenue Officer') => Officer::DESIG_ASSTREVENUEOFF,
   				__('Zonal Sanitary Officer') => Officer::DESIG_ZONALSANITORYOFF,
   				//
   				__('Assistant Engineer') => Officer::DESIG_ASSTENGINEER,
   				__('Water Supply Engineer') => Officer::DESIG_WATERSUPPLYOFF,
   				__('Sanitory Inspector') => Officer::DESIG_SANITORYINSPECTOR,
   		];
   		return $designstr;
   	}
    
    public function behaviors()
    {
    	return array (
    			'CTimestampBehavior' => array (
					'class' => 'zii.behaviors.CTimestampBehavior',
					'createAttribute' => 'created',
					'updateAttribute' => 'updated'
    			),
    	        'ml' => array (
	                'class' => 'application.behaviours.MultilingualBehavior',
	                // 'langTableName' => 'projectLang',
	                'langForeignKey' => 'id_officer',
	                'langField' => 'id_lang',
	                'localizedAttributes' => array (
                        'name',
	                ),
	                'languages' => Yii::app ()->params ['translatedLanguages'],
	                'defaultLanguage' => Yii::app ()->params ['defaultDBLanguage']
    	        ),
    	);
    }
    
    public function defaultScope()
    {
        return $this->ml->localizedCriteria ();
    }    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'officer';
	}

    public function beforeSave()
    {		
        if (Yii::app()->id == 'app-frontend' && $this->desig == self::DESIG_DEPUTYCOMMISSIONER)
            if (! Yii::app ()->user->checkAccess ( 'ADD_DEPUTY_COMMISSIONER' ))
                return false;
        if($this->isNewRecord)    
            if($this->desig != self::DESIG_DEPUTYCHIEFMINISTER)                
                if(self::countByAttributes(['fkey_place' => $this->fkey_place,'desig' => $this->desig])>0)
                    throw new Exception($this->fkey_place . '-' . $this->desig . ' - already exists');
        return parent::beforeSave ();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('fkey_place', 'numerical', 'integerOnly'=>true),
			array('name, phone, fax, email, picture, address, website', 'length', 'max'=>255),
			array('desig', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_officer, name, fkey_place, desig, updated, created, phone, fax, email', 'safe', 'on'=>'search'),
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
	        'district' => array(self::BELONGS_TO, 'District', 'fkey_place'),
	        'state' => array(self::BELONGS_TO, 'State', 'fkey_place'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_officer' => 'Id Officer',
			'name' => __('Name'),
			'fkey_place' => __('Place'),
			'desig' => __('Designation'),
			'updated' => __('Updated'),
			'created' => __('Created'),
			'phone' => __('Phone'),
			'fax' => __('Fax'),
			'email' => __('Email'),
		    'picture' => __('Picture'),
	        'picture_url' => __('Picture URL'),
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

		$criteria->compare('id_officer',$this->id_officer);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('fkey_place',$this->fkey_place);
		$criteria->compare('desig',$this->desig,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Officer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	static function repOfficers()
	{
	    $rows = Yii::app ()->db->createCommand ( "
            SELECT s.id_state
                    ,s.name
                    ,count(d.id_district) as ctr1
                    ,count(o.id_officer) as ctr2
                    ,count(o2.id_officer) as ctr3
                    ,count(distinct d. 	id_district_division_hq) as ctr4
             FROM states s 
            left join lb_district d on d.id_state=s.id_state
            left join officer o on d.id_district=o.fkey_place and o.desig=:desig1
            left join officer o2 on d.id_district=o2.fkey_place and o2.desig=:desig2
            group by s.id_state" )->queryAll ( true, [ 
                'desig1' => self::DESIG_DEPUTYCOMMISSIONER,
                'desig2' => self::DESIG_DIVCOMMISSIONER 
        ] );
	    
	    $init = [];
	    $rows2=array_reduce ( $rows, 
	            function ($carry, $item)
                {
	        
                    $carry[$item['id_state']] = $item;
                    return $carry;
                },[] );
	    return $rows2;
	}
	
	function savePicture($url)
    {
        $desig = $this->desig; 
        
        $stateobj = $this->state;
        $outfile = '';
        switch($this->desig)
        {
            case self::DESIG_DEPUTYCOMMISSIONER:
				$outfile = strtolower($this->district->state->slug . '_' . $this->district->slug . '_' . $desig . '.jpg');
				$stateobj = $this->district->state;
                break;
            case self::DESIG_DEPUTYCHIEFMINISTER:
                $outfile = strtolower($stateobj->slug . '_' . $desig . '_' . time() . '.jpg');
                break;
            case self::DESIG_GOVERNER:
			case self::DESIG_CHIEFMINISTER:
			case self::DESIG_STATEINFOCOMMISSIONER:
                $outfile = strtolower($stateobj->slug . '_' . $desig . '.jpg');
                break;
        }
        if(empty($outfile))
            throw new Exception("Have not learnt to save picture for " . $this->desig);
                
        $rp = realpath ( Yii::app ()->basePath . '/../images/pics' );
        if(!is_writable($rp))
            throw new Exception("$rp is not writable");
        
        $p1 = $rp. '/' . $stateobj->slug;
        $picture_path = $stateobj->slug . '/' . $outfile;
        if (! file_exists ( $p1 ))
            mkdir ( $p1 );
        $p2 = $p1 . '/' . $outfile;
        $p2_renamed = $p1 . '/' . time() . '_' . $outfile;
        // get url
        echo "Getting... " . $url. "\n";
        $img_data = @file_get_contents ( $url);
        // save picture
        if ($img_data)
        {
            if(file_exists($p2))
            {
                rename($p2,$p2_renamed);
            }
            if(!file_put_contents ( $p2, $img_data ))
                throw new Exception("Could not write $p2");
        }
        else
            throw new Exception("Could not get $url");
        $this->picture = $picture_path;
        // update model
        $this->update(['picture']);
                
    }
}
