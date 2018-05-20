<?php

/**
 * CRAP on 20150217 is 1212->3549->1308
 * @author vikasyadav
 */
class StateUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';
    public $summaryname = 'contractors';
    public $lang;
    
    /**
     * Prefix output when creating URLs
     *
     * @var string
     */
    public $prefix;
    
    /**
     * Prefix output when parsing URLs
     *
     * @var string
     */
    public $prefix2;

    public function handle_state_election($manager, $route, $params, $ampersand)
    {
        $qs = '';
        
        if (! isset ( $params ['lang'] ))
        {
            $lang = $this->lang;
        }
        else
        {
            $lang = $params ['lang'];
            unset ( $params ['lang'] );
        }
        
        if (! isset ( $params ['id_election'] ))
        	throw new Exception("URL needs election");
        
        $id_election = $params ['id_election'];
        unset ( $params ['id_election'] );
        
        $election = Election::model ()->findByPk($id_election);
        
        $state = $election->state;
        
        if (count ( $params ))
        {
            $qs = "?" . http_build_query ( $params );
        }
        
        if ($election && $state)
        {
        	$state_slug = strtolower ( $state->slug );
        	$etype = $election->type == 'AMLY' ? __('Assembly') : __('');
        	$e_slug = strtolower($etype  . '-election-' . $election->year);
        	
        }
        else
            return false;
        
        return $this->prefix . $lang . '/' . $state_slug . '/' . $e_slug . '/' . $qs;
    }
    
    public function handle_election_candidates($manager, $route, $params, $ampersand)
    {
    	$qs = '';
    	
    	if (! isset ( $params ['lang'] ))
    	{
    		$lang = $this->lang;
    	}
    	else
    	{
    		$lang = $params ['lang'];
    		unset ( $params ['lang'] );
    	}
    	
    	if (! isset ( $params ['id_election'] ))
    		throw new Exception("URL needs election");
    	
    		if (! isset ( $params ['eci_ref'] ))
    			throw new Exception("URL needs eciref");
    			
    				
    		$id_election = $params ['id_election'];
    		unset ( $params ['id_election'] );
    		
    		$eci_ref = $params ['eci_ref'];
    		unset ( $params ['eci_ref']);
    		
    		
    		$election = Election::model ()->findByPk($id_election);
    		$consti = Constituency::model ()->findByAttributes([
    				'id_state' => $election->id_state,
    				'eci_ref' => $eci_ref,
    				'ctype' => 'AMLY',
    		]);
    		
    		$state = $election->state;
    		
    		if (count ( $params ))
    		{
    			$qs = "?" . http_build_query ( $params );
    		}
    		
    		if ($election && $state && $consti)
    		{
    			$state_slug = strtolower ( $state->slug );
    			$etype = $election->type == 'AMLY' ? __('Assembly') : __('');
    			$e_slug = strtolower($etype  . '-election-' . $election->year);
    			
    			$consti_slug = strtolower ( $consti->slug );
    		}
    		else
    			return false;
    			
    		return $this->prefix . $lang . '/' . $state_slug . '/' . $e_slug . '/' . $consti_slug . '/' . $qs;
    }
    
    public function handle_state_view($manager, $route, $params, $ampersand)
    {
    	$qs = '';
    	
    	if (! isset ( $params ['lang'] ))
    	{
    		$lang = $this->lang;
    	}
    	else
    	{
    		$lang = $params ['lang'];
    		unset ( $params ['lang'] );
    	}
    	
    	if (! isset ( $params ['id'] ))
    	{
    		return $this->prefix . $lang . '/state/all';
    	}
    	else
    	{
    		$id_state = $params ['id'];
    		unset ( $params ['id'] );
    	}
    	
    	$stateobjs = State::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findAll ( 'id_state=:cc',
    			array (
    					':cc' => $id_state
    			) );
    	
    	if (count ( $params ))
    	{
    		$qs = "?" . http_build_query ( $params );
    	}
    	
    	if (count ( $stateobjs ) == 1)
    		$state_slug = strtolower ( $stateobjs [0]->slug );
    		else
    			return false;
    			
    			return $this->prefix . $lang . '/' . $state_slug . '/' . $qs;
    }
    
    public function handle_state_district($manager, $route, $params, $ampersand)
    {
        $qs = '';
        
        if (! isset ( $params ['lang'] ))
        {
            $lang = $this->lang;
        }
        else
        {
            $lang = $params ['lang'];
            unset ( $params ['lang'] );
        }
        
        if (! isset ( $params ['id'] ))
        {
            return $this->prefix . $lang . '/district/all';
        }
        else
        {
            $id_district= $params ['id'];
            unset ( $params ['id'] );
        }
        
        $obj = Town::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findByPk ($id_district);
        
        if (count ( $params ))
        {
            $qs = "?" . http_build_query ( $params );
        }
        
        if (isset($obj))
            $url_path = strtolower ( $obj->state->slug ) . '/' . $obj->slug;
        else
            return false;
                
        return $this->prefix . $lang . '/' . $url_path. '/' . $qs;
    }      
    
    public function handle_state_assembly($manager, $route, $params, $ampersand)
    {
        $qs = '';
        
        if (! isset ( $params ['lang'] ))
        {
            $lang = $this->lang;
        }
        else
        {
            $lang = $params ['lang'];
            unset ( $params ['lang'] );
        }
        
        if (! isset ( $params ['acno'] ) || ! isset ( $params ['id_state'] ))
            return false;
        
        $acno = $params ['acno'];
        unset ( $params ['acno'] );
        $id_state = $params ['id_state'];
        unset ( $params ['id_state'] );
        
        $obj = Constituency::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findByAttributes ( 
                [ 
                        'id_state' => $id_state,
                        'eci_ref' => $acno,
                        'ctype' => 'AMLY' 
                ] );
        if(empty($obj->name))
            return false;
        
        if (count ( $params ))
        {
            $qs = "?" . http_build_query ( $params );
        }
        
        if(empty($obj->slug))            
        {
            Yii::log("URL making ac:$acno state:$id_state error:" . print_r($params,true),"error");
            throw new Exception("URL making error");
        }
        
        if (isset ( $obj ))
            $url_path = strtolower ( $obj->state->slug ) . '/assembly/' . $obj->slug;
        else
            return false;
        
        return $this->prefix . $lang . '/' . $url_path . '/' . $qs;
    }
    
    public function handle_state_loksabha($manager, $route, $params, $ampersand)
    {
        $qs = '';
        
        if (! isset ( $params ['lang'] ))
        {
            $lang = $this->lang;
        }
        else
        {
            $lang = $params ['lang'];
            unset ( $params ['lang'] );
        }
        
        if (! isset ( $params ['id'] ))
            return false;
        
        $id= $params ['id'];
        unset ( $params ['id'] );
        
        $obj = Constituency::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findByPk($id);
        
        if (count ( $params ))
        {
            $qs = "?" . http_build_query ( $params );
        }
        
        if (isset ( $obj ))
            $url_path =  'loksabha/' . $obj->slug;
        else
            return false;
        
        return $this->prefix . $lang . '/' . $url_path . '/' . $qs;
    }
    
    public function handle_state_town($manager, $route, $params, $ampersand)
    {
        $url_path = $qs = '';
        
        if (! isset ( $params ['lang'] ))
        {
            $lang = $this->lang;
        }
        else
        {
            $lang = $params ['lang'];
            unset ( $params ['lang'] );
        }
        
        if (! isset ( $params ['id_place'] ))
            return false;
        
        $id_place = $params ['id_place'];
        unset ( $params ['id_place'] );
        
        if (count ( $params ))
        {
            $qs = "?" . http_build_query ( $params );
        }
        
        $obj = Town::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findByPk($id_place);        
        
        if (isset ( $obj ))
        {
            if($obj->district)
            {
                $url_path = strtolower ( $obj->state->slug ) . '/' . $obj->district->slug . '/' . $obj->slug;
            }
        }
        else
            return false;

        return $this->prefix . $lang . '/' . $url_path . '/' . $qs;
    }

    function __construct()
    {
    }

    static function initLanguage($parsing, CBaseUrlRule $rule, $params)
    {
        if ($parsing)
        {
            if (! empty ( $_GET ['lang'] ))
                $lang = $_GET ['lang'];
            else if (! empty ( $_SESSION ['lang'] ))
                $lang = $_GET ['lang'] = $_SESSION ['lang'];
            else
                $lang = Yii::app ()->params ['defaultLanguage'];
        }
        else // creating - params needs to be given
        {
            if (! empty ( $params ['lang'] ))
                $lang = $params ['lang'];
            else if (! empty ( $_GET ['lang'] ))
                $lang = $_GET ['lang'];
            else if (! empty ( $_SESSION ['lang'] ))
                $lang = $_SESSION ['lang'];
            else
                $lang = Yii::app ()->language;
        }
        
        if (isset ( $_SESSION ))
            $_SESSION ['lang'] = $lang;
        
        $rule->lang = $lang;
        $rule->prefix = '/';
        $rule->prefix2 = '';
        $rule->hasHostInfo = true;
    }

    public function createUrl($manager, $route, $params, $ampersand)
    {
        self::initLanguage ( false, $this, $params );
        
        $r2 = 'handle_' . str_replace ( '/', '_', $route );
        if (method_exists ( $this, $r2 ))
        {
            return $this->$r2 ( $manager, $route, $params, $ampersand );
        }
        
        if ($route === 'site/index')
        {
            $qs = '';
            if (! isset ( $params ['lang'] ))
                $lang = Yii::app ()->params ['defaultLanguage'];
            else
            {
                $lang = $params ['lang'];
            }
            if (count ( $params ))
                $qs = "?" . http_build_query ( $params );
            return $qs;
        }
        return false; // this rule does not apply
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        self::initLanguage ( true, $this, [ ] );
        
        foreach ( get_class_methods ( $this ) as $method )
        {
            if (preg_match ( '/^parse_\w+/', $method ))
                if (($rt = $this->$method ( $manager, $request, $pathInfo, $rawPathInfo )) !== false)
                    return $rt;
        }
        
        return false; // this rule does not apply
    }

    public function parse_state_election($manager, $request, $pathInfo, $rawPathInfo)
    {
    	if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)\/(?<eslug>assembly-election-(?<eyear>\d{4}))\/?$/', $pathInfo, $matches ))
    	{
    		
    		if (isset ( $matches ['stateslug'] ) && isset ( $matches ['eyear'] ) && isset ( $matches ['eslug'] ))
    		{
    			$stateslug = $matches ['stateslug'];
    			$stateobjs = State::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findAll (
    					'slug=:cc',
    					array (
    							':cc' => $stateslug
    					) );
    			
    			if (count ( $stateobjs ) > 0)
    			{
    				if (count ( $stateobjs ) == 1)
    					$_GET ['id'] = $stateobjs[0]->id_state;
    				else
    					return false;
    				
    				$state = $stateobjs[0];
    				
    				$ele = Election::model()->findByAttributes([
    						'id_state' => $state->id_state,
    						'type' => 'AMLY',
    						'year' => $matches ['eyear']
    				]);
    				
    				if(!$ele)
    					return false;
    				
    				$_GET ['id_election'] = $ele->id_election;
    					
    				$_GET ['lang'] = $matches ['lang'];
    					
    				return $this->prefix2 . "state/election";
    			}
    			else
    				return false;
    		}
    	}
    	return false;
    }
        
    public function parse_election_candidates($manager, $request, $pathInfo, $rawPathInfo) {
		if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)\/(?<eslug>assembly-election-(?<eyear>\d{4}))\/(?<amlyslug>[\w-]*)\/?$/', $pathInfo, $matches )) {
			
			if (isset ( $matches ['stateslug'] ) && isset ( $matches ['eyear'] ) && isset ( $matches ['eslug'] ) && isset ( $matches ['amlyslug'] )) {
				$stateslug = $matches ['stateslug'];
				$stateobjs = State::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findAll ( 'slug=:cc', array (
						':cc' => $stateslug 
				) );
				
				if (count ( $stateobjs ) > 0) {
					if (count ( $stateobjs ) == 1)
						$_GET ['id'] = $stateobjs [0]->id_state;
					else
						return false;
					
					$state = $stateobjs [0];
					
					$ele = Election::model ()->findByAttributes ( [ 
							'id_state' => $state->id_state,
							'type' => 'AMLY',
							'year' => $matches ['eyear'] 
					] );
					
					if (! $ele)
						return false;
					
					$consti = Constituency::model ()->findByAttributes ( [
							'id_state' => $state->id_state,
							'ctype' => 'AMLY',
							'slug' => $matches ['amlyslug']
					] );
					if (! $consti)
						return false;
						
					$_GET ['id_election'] = $ele->id_election;					
					$_GET ['eci_ref'] = $consti->eci_ref;					
					$_GET ['lang'] = $matches ['lang'];
										
					return $this->prefix2 . "election/candidates";
				} else
					return false;
			}
		}
		return false;
	}
    
    public function parse_state($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)\/?$/', $pathInfo, $matches ))
        {
            if (isset ( $matches ['stateslug'] ))
            {
                $stateslug = $matches ['stateslug'];
                $stateobjs = State::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->findAll ( 
                        'slug=:cc', 
                        array (
                                ':cc' => $stateslug 
                        ) );
                if (count ( $stateobjs ) > 0)
                {
                    if (count ( $stateobjs ) == 1)
                        $_GET ['id'] = $stateobjs[0]->id_state;
                    
                    $_GET ['lang'] = $matches ['lang'];
                    
                    return $this->prefix2 . "state/view";
                }
                else
                    return false;
            }
        }
        return false;
    }
    
    public function parse_district($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)\/(?<dtslug>[\(\)\w-]*)\/?$/', $pathInfo, $matches ))
        {
            if (isset ( $matches ['stateslug'] ) && isset ( $matches ['dtslug'] ))
            {
                $dtslug = $matches['dtslug'];
                $stateslug = $matches ['stateslug'];
                if($stateslug == 'loksabha')
                    return false;
                
                $stateobj = State::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->find (
                        'slug=:cc',
                        array (
                                ':cc' => $stateslug
                        ) );
                $dtobj = Town::model()->findByAttributes(['id_state' => $stateobj->id_state,'slug' => $dtslug,'sdt_code' => 0, 'tv_code' => 0]);
                
                if (isset ( $stateobj ) && isset ( $dtobj ))
                {
                    $_GET ['id_state'] = $stateobj->id_state;
                    $_GET ['id'] = $dtobj->id_place;
                    $_GET ['lang'] = $matches ['lang'];                        
                    return $this->prefix2 . "state/district";
                }
                else
                    return false;
            }
        }
        return false;
    }
    
    public function parse_district_town($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)\/(?<dtslug>[\(\)\w-]*)\/(?<townslug>[\(\)\w-]*)\/?$/', $pathInfo, $matches ))
        {
            if (isset ( $matches ['stateslug'] ) && isset ( $matches ['dtslug'] ) && isset ( $matches ['townslug'] ))
            {
                $dtslug = $matches ['dtslug'];
                $stateslug = $matches ['stateslug'];
                $townslug = $matches ['townslug'];
                if ($stateslug == 'loksabha')
                    return false;
                
                $stateobj = State::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->find ( 'slug=:cc', array (
                        ':cc' => $stateslug 
                ) );
                $dtobj = Town::model ()->findByAttributes ( [ 
                        'id_state' => $stateobj->id_state,
                        'slug' => $dtslug,
                        'sdt_code' => 0,
                        'tv_code' => 0 
                ] );
                
                if (isset ( $stateobj ) && isset ( $dtobj ))
                    $townobj = Town::model ()->findByAttributes ( [
                        'id_state' => $stateobj->id_state,
                        'id_district' => $dtobj->id_district,
                        'slug' => $townslug,
                        'sdt_code' => 0,
                        'tv_code' => 0
                ] );
                
                
                
                if (isset ( $stateobj ) && isset ( $dtobj ) && isset($townobj))
                {
                    $_GET ['id'] = $townobj->id_place;
                    $_GET ['lang'] = $matches ['lang'];
                    return $this->prefix2 . "state/town";
                } else
                    return false;
            }
        }
        return false;
    }
    
    public function parse_assembly($manager, $request, $pathInfo, $rawPathInfo)
    {
        $matches = [];
        if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)\/assembly\/(?<amlyslug>[\(\)\w-]*)\/?$/', $pathInfo, $matches ))
        {
            if (isset ( $matches ['stateslug'] ) && isset ( $matches ['amlyslug'] ))
            {                
                $amlyslug = $matches['amlyslug'];
                $stateslug = $matches ['stateslug'];
                $obj = Constituency::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->with ( [ 
                        'state' 
                ] )->together()->find ( 
                        [ 
                                'condition' => 't.slug=:amlyslug and state.slug=:stateslug and ctype=:ctype',
                                'params' => [ 
                                        ':ctype' => 'AMLY',
                                        ':amlyslug' => $amlyslug,
                                        ':stateslug' => $stateslug 
                                ] 
                        ] );
                
                if (isset ( $obj))
                {
                    $_GET ['id_state'] = $obj->id_state;
                    //$_GET ['id_consti'] = $obj->id_consti;
                    $_GET ['acno'] = $obj->eci_ref;
                    if(empty($_GET['lang']))
                        $_GET ['lang'] = $matches ['lang'];
                    return $this->prefix2 . "state/assembly";
                }
                else
                    return false;
            }
        }
        return false;
    }
        
    /**
     * @see StateController::actionLoksabha
     * @param unknown $manager
     * @param unknown $request
     * @param unknown $pathInfo
     * @param unknown $rawPathInfo
     * @return string|boolean
     */
    public function parse_loksabha($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (preg_match ( '/^(?<lang>\w\w)\/loksabha\/(?<amlyslug>[\(\)\w-]*)\/?$/', $pathInfo, 
                $matches ))
        {
            if ( isset ( $matches ['amlyslug'] ))
            {
                Yii::log("$pathInfo was parsed by " . __METHOD__,'info','urlrules');
                $amlyslug = $matches ['amlyslug'];
                $obj = Constituency::model ()->cache ( Yii::app ()->params ['data_cache_duration'] )->find ( 
                        [ 
                                'condition' => 't.slug=:cc and ctype=:ctype',
                                'params' => [ 
                                        ':ctype' => 'PARL',
                                        ':cc' => $amlyslug 
                                ] 
                        ] );
                
                if (isset ( $obj ))
                {
                    $_GET ['id_consti'] = $obj->id_consti;
                    $_GET ['lang'] = $matches ['lang'];
                    return $this->prefix2 . "state/loksabha";
                }
                else
                    return false;
            }
        }
        return false;
    }
}
