<?php

/**
 * 201712011947:vikas:Gurgaon
 * A common system to store other names when there is 1 name field
 * A place name lot of names, besides spelling differences.
 * 
 *  It will expose a setter which will just store other name as many times it is called.
 *  Internally it will append to array
 *  
 *   It will then hook to onsave to save the array as serialized php
 *   It will also init by loading this field and init the array on first setter call
 *    
 * @author vikas
 */
class OtherNamesBehavior extends CActiveRecordBehavior
{
    var $other_names_array;
    var $owner;
    
    function setOtherName($val)
    {
        if(!isset($this->other_names_array))
            $this->loadOtherNames();
        
        $this->other_names_array[$val] = empty($this->other_names_array[$val]) ? 1 : $this->other_names_array[$val]+1;
    }
    
    public function attach($owner)
    {
        parent::attach ( $owner );
        $this->owner = $owner;
    }
    
    function loadOtherNames()
    {
        if(empty($this->owner->other_names))
            $this->owner->other_names = [];
        else
            $this->other_names_array= unserialize($this->owner->other_names);
    }
    
    function beforeSave($event)
    {
        $this->owner->other_names = serialize($this->other_names_array);
        $event->isValid = true;
    }
        
}
