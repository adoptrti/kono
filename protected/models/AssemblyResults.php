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
 * @property integer $st_code
 * @property string $picture
 */
class AssemblyResults extends CActiveRecord
{

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'assemblyresults';
    }
    
    public function beforeSave()
    {
    	
    	if(!isset($this->constituency->id_state))
    	{
    		if($this->acno != 999)
    			throw new Exception("Without a valid constituency? id={$this->id_result}");
    	}
        else
        	$this->id_state = $this->constituency->id_state;
        return parent::beforeSave();
    }

    public function behaviors()
    {
        return array (
                'CTimestampBehavior' => array (
                        'class' => 'zii.behaviors.CTimestampBehavior',
                        'createAttribute' => null,
                        'updateAttribute' => 'updated' 
                ),
                // @see
                // http://www.yiiframework.com/extension/multilingual-behavior/
                'ml' => array (
                        'class' => 'application.behaviours.MultilingualBehavior',
                        // 'langTableName' => 'projectLang',
                        'langForeignKey' => 'id_result',
                        'langField' => 'id_lang',
                        'localizedAttributes' => array (
                                'name',
                                'address',
                                'slug' 
                        ),
                        'languages' => Yii::app ()->params ['translatedLanguages'],
                        'defaultLanguage' => Yii::app ()->params ['defaultDBLanguage'] 
                ), 
        );
    }
    
    public function defaultScope()
    {
        return $this->ml->localizedCriteria();
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array (
                array (
                        'id_election,acno,id_consti,name',
                        'required' 
                ),
                array (
                        'id_election, id_state, id_consti, acno, st_code',
                        'numerical',
                        'integerOnly' => true 
                ),
                array (
                        'acname, name, party, address, phones, emails, picture',
                        'length',
                        'max' => 255 
                ),
                //['id_consti','sameStateAsElection'],
                array (
                        'gender',
                        'length',
                        'max' => 6 
                ),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be
                // searched.
                array (
                        'id_result, id_election, id_state, id_consti, acname, acno, name, gender, party, address, phones, emails, st_code',
                        'safe',
                        'on' => 'search' 
                ) 
        );
    }
    
    function sameStateAsElection($attr,$params)
    {
        if(!isset($this->election->id_state) || !isset($this->constituency->id_state) || ($this->election->id_state != $this->constituency->id_state))
            $this->addError($attribute, __('State of Constituency and Election mismatch'));
        return true;
    }

    /**
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array (
                'election' => array (
                        self::BELONGS_TO,
                        'Election',
                        'id_election'
                ),
                'committees' => array (
                        self::MANY_MANY,
                        'Committee',
                        'comm_member(id_result,id_comm)' 
                ),
                'constituency' => array (
                        self::BELONGS_TO,
                        'Constituency',
                        'id_consti' 
                ) 
        );
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array (
                'acname' => __ ( 'Acname' ),
                'acno' => __ ( 'Acno' ),
                'name' => __ ( 'Name' ),
                'party' => __ ( 'Party' ) 
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will
     * filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     *         based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria ();
        
        $criteria->compare ( 'acname', $this->acname, true );
        $criteria->compare ( 'acno', $this->acno );
        $criteria->compare ( 'name', $this->name, true );
        $criteria->compare ( 'party', $this->party, true );
        
        return new CActiveDataProvider ( $this, array (
                'criteria' => $criteria 
        ) );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your
     * CActiveRecord descendants!
     *
     * @param string $className
     *            active record class name.
     * @return AssemblyResults the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model ( $className );
    }
}
