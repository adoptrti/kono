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

    public function parse_state($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (preg_match ( '/^(?<lang>\w\w)\/(?<stateslug>[\w-]*)/', $pathInfo, $matches ))
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
}
