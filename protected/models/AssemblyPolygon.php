<?php

/**
 * This is the model class for table "acpoly".
 *
 * The followings are the available columns in table 'acpoly':
 * @property integer $id_poly
 * @property string $polytype
 * @property integer $acno
 * @property integer $id_parl_consti
 * @property string $zone
 * @property integer $id_zone
 * @property integer $wardno
 * @property integer $id_district
 * @property string $name
 * @property string $poly
 * @property integer $st_code
 * @property integer $id_state
 * @property string $st_name
 * @property integer $dt_code
 * @property string $dist_name
 * @property integer $pcno
 * @property string $pc_name
 * @property string $pc_name_clean
 * @property integer $pc_id
 * @property double $Shape_Leng
 * @property double $Shape_Area
 * @property double $MaxSimpTol
 * @property double $MinSimpTol
 * @property integer $id_village Local Body Village Key
 * 
 * Report on zones:
 * --
 * SELECT count(name),count(acno),count(distinct id_zone),count(distinct zone),dt_code FROM `acpoly` where polytype='WARD' group by dt_code order by dt_code
 * 
 * Adding Municipal zones: mzone
 * --
 * INSERT INTO `towns2011` 
 * 	(name,`st_code`, `id_state`, `st_name`, `dt_code`, `id_district`, `dt_name`, `tvtype`) 
 * SELECT distinct zone,id_state,st_code,st_name, dt_code, id_district, dist_name, 'mzone' FROM `acpoly` WHERE dt_code=10229 and polytype = 'ward'
 * 
 * Updating IDs to zones in acpoly
 * update `acpoly` p join towns2011 t on t.name=p.zone and t.dt_code=p.dt_code and t.tvtype='mzone' set id_zone = t.id_place
 * 
 * Not needed anymore
 * --
 * UPDATE `acpoly` set dt_code=10229 WHERE dt_code=603 and polytype='WARD'
 */
class AssemblyPolygon extends CActiveRecord
{
    var $ctr1;
    var $ctr2;
    var $ctr3;
    var $ctr4;
    var $ctr5;
    var $ctr6;
    var $ctr7;
    var $ctr8;
    var $ctr9;
    var $ctr10;
    
    public function behaviors()
    {
    	return array (
    			'CTimestampBehavior' => array (
    					'class' => 'zii.behaviors.CTimestampBehavior',
    					'createAttribute' => null,
    					'updateAttribute' => 'updated'
    			),
    		);
    }
    
    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'acpoly';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acno, id_parl_consti, wardno, id_village, id_district, st_code, id_state, dt_code, pcno, pc_id', 'numerical', 'integerOnly'=>true),
            array('Shape_Leng, Shape_Area, MaxSimpTol, MinSimpTol', 'numerical'),
            array('polytype', 'length', 'max'=>7),
            array('zone, name, st_name, dist_name, pc_name, pc_name_clean', 'length', 'max'=>255),
            array('poly', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id_poly, polytype, acno, id_parl_consti, zone, wardno, id_village, id_district, name, poly, st_code, id_state, st_name, dt_code, dist_name, pcno, pc_name, pc_name_clean, pc_id, Shape_Leng, Shape_Area, MaxSimpTol, MinSimpTol', 'safe', 'on'=>'search'),
        );
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
        		'town' => array (
        				self::BELONGS_TO,
        				'Town',
        				'id_city'
        		),
        		'zone' => array (
        				self::BELONGS_TO,
        				'Town',
        				'id_zone'
        		),
        		'state' => array (
                        self::BELONGS_TO,
                        'State',
                        'id_state'
                ),
                'village' => array (
                        self::BELONGS_TO,
                        'LBVillage',
                        'id_village'
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
                'acno' => __('Assembly Number'),
                'poly' => __('Polygon'),
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

        $criteria->compare ( 'acno', $this->acno );
        $criteria->compare ( 'poly', $this->poly, true );

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
     * @return AssemblyPolygon the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model ( $className );
    }

    static function repMunicipals()
    {
        $rs = self::model ()->findAll (
                [
                        'group' => 'dist_name,dt_code',
                        'select' => "dist_name,
								count(*) as ctr1,
								(select count(mr.name) from municipalresults mr
									join towns2011 tw on tw.id_place=mr.id_city where tw.tvtype='mcorp' and tw.id_place=t.dt_code) as ctr2,
                				count(distinct id_zone) as ctr3",
                        'condition' => 'polytype=?',
                        'order' => 'dist_name'
,                        'params' => [
                                'WARD'
                        ]
                ] );

        foreach ( $rs as $r )
        {
            $row [] = [
                    $r->dist_name,
                    $r->ctr1,
                    $r->ctr2,
            		$r->ctr3,
            ];
        }
        return $row;
    }

    static function repACs()
    {
        $AR_table = AssemblyResults::model()->tableName();
        $rs = AssemblyPolygon::model ()->findAll (
                [
                        'group' => 't.id_state,t.st_name,t.st_code',
                        'select' => "st_name,count(*) as ctr1,
                            (select count(name) from $AR_table r2 where r2.st_code=t.st_code or r2.id_state=t.id_state) as ctr2,
                            (select count(phones) from $AR_table r3 where phones<>'' and r3.id_state=t.id_state) as ctr3,
                            (select count(emails) from $AR_table r4 where emails<>'' and r4.id_state=t.id_state) as ctr4,
                            (select count(address) from $AR_table r5 where address<>'' and r5.id_state=t.id_state) as ctr5,
                            (select count(picture) from $AR_table r6 where picture<>'' and r6.id_state=t.id_state) as ctr6,
                            (select count(distinct id_city) from municipalresults r7 join towns2011 r7t on r7t.id_place=r7.id_city and r7t.tvtype in ('mcorp','mcorp+og') where r7t.id_state=t.id_state) as ctr7,
                            (select count(*) from towns2011 r8 where r8.tvtype in ('mcorp','mcorp+og') and r8.id_state=t.id_state) as ctr8,
                            vpr.polygons as ctr9,
                            vpr.villages as ctr10,
                            t.id_state",
                        'join' => 'left join `village-polygon-report` vpr on vpr.id_state=t.id_state',
                        'order' => 'st_name',
                        'condition' => 'polytype=?',
                        'params' => [
                                'AC'
                        ]
                ] );
        foreach ( $rs as $r )
        {
            $data = [
                    $r->st_name,
                    $r->ctr1,
                    $r->ctr2,
                    $r->ctr3,
                    $r->ctr4,
                    $r->ctr5,
                    $r->ctr6,
                    $r->ctr7,
                    $r->ctr8,
                    $r->ctr9,
                    $r->ctr10,
                    $r->id_state
            ];

            $mx = $data [1];
            $score = 0;
            for($i = 2; $i < 7; $i ++)
            {
                $score += min ( $data [$i], $mx ) / $mx;
            }
            $data[] = $score/5;
            $row [] = $data;
        }
        return $row;
    }
    
    function extractDataFromGIS($state_name,$gis_lat,$gis_long)
    {
    	$src = [
    			'condition' => (new CDbExpression ( "ST_Contains(poly, GeomFromText(:point))")) . ' and state.name=:statename',
    			'with' => ['state'],
    			'params' => [
    					':statename' => $state_name,
    					':point' => 'POINT(' . $gis_long. ' ' . $gis_lat. ')'
    			]
    	];
    	
    	$ass2 = AssemblyPolygon::model ()->findAll ( $src );
    	
    	$govdata = [ ];
    	/* @var $ass AssemblyPolygon */
    	foreach ( $ass2 as $ass )
    	{
    		error_log('GIS-found poly:' . $ass->id_poly . ' name:' . $ass->name . ' type:' . $ass->polytype . ' id_village:' . $ass->id_village);
    		if ($ass->polytype == 'WARD')
    			$this->extractWardData($govdata, $ass);    			
    		else if($ass->polytype == 'AC')
    			$this->extractACData($govdata, $ass);
    		else if($ass->polytype == 'VILLAGE')
    			$govdata['village'] = $ass;
    	}
    	return $govdata;
    }
    
    function extractWardData(&$govdata,AssemblyPolygon $ass)
    {
    	$govdata['wardzone'] = isset($ass->zone) ? $ass->zone : null;
    	
    	$con2 = MunicipalResults::model ()->findByAttributes ( [
    			'wardno' => $ass->acno,
    			'id_city' => $ass->dt_code,
    	] );
    	
    	if ($con2)
    	{
    		$govdata['ward'] = $con2;
    	}
    	
    	//can we find ward staff?
    	$ward_offs = Officer::model()->findAllByAttributes($params = [
    			'fkey_place' => $ass->id_poly,
    			'govoffice' => 'MCORP',
    	]);
    	
    	if(count($ward_offs))
    		$govdata['ward_officers'] = $ward_offs;
    	
    	//can we find zone staff?
    	$zone_offs = Officer::model()->findAllByAttributes([
    			'fkey_place' => $ass->id_zone,
    			'govoffice' => 'MCORP',
    	]);
    	
    	if(count($zone_offs))
    		$govdata['zone_officers'] = $zone_offs;
    		
    	//can we find municipal commissioner?
    	//can we find municipal mayor?
    }
    
    function extractACData(&$govdata,AssemblyPolygon $ass)
    {
    	$govdata['assembly'] = null;
    	$govdata['amly_poly'] = $ass;
    	$con2 = LokSabha2014::model ()->findByAttributes ( [
    			'pc_name_clean' => $ass->pc_name_clean
    	] );
    	
    	if ($con2)
    	{
    		$govdata['mp'] = $con2;
    		$govdata['mp_poly'] = $ass;
    	}
    	$att44 = [
    			'acno' => $ass->acno ,
    			'id_state' => $ass->id_state,
    	];
    	
    	$con3 = AssemblyResults::model ()->findByAttributes ( $att44 );
    	if ($con3)
    	{
    		$govdata['assembly'] = $con3;
    	}
    }
    
    function newOfficer($name = '',$phone = '',$desig = '',$fkey_place = '')
    {
    	$off = new Officer();
    	$off->fkey_place = empty($fkey_place) ? $this->id_poly : $fkey_place;
    	$off->govoffice = $this->polytype == 'WARD' ? 'MCORP' : null;
    	if(Officer::model()->countByAttributes([
    			'desig' => $desig,
    			'fkey_place' => $off->fkey_place
    	]))
    	{
    		echo "$desig already exists for ward " . $this->acno . "\n";
    		return;
    	}
    	
    	if(empty($name) || empty($desig) || empty($phone))
    		return $off;
    	
    	$off->name = $name;
    	$off->phone = $phone;
    	$off->desig = $desig;
    	//print_r($off);
    	if(!$off->save())
    	{
    		print_r($off->getErrors());
    		die("Count not save.");
    	}
    	return $off;
    }
    
    function newZonalOfficer($name = '',$phone = '',$desig = '')
    {
    	return $this->newOfficer($name,$phone,$desig,$this->id_zone);
    }
}
