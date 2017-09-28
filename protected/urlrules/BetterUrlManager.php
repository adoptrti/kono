<?php
class BetterUrlManager extends CUrlManager
{
	/**
	 * IssueID: #386:thevikas:Gurgaon:20160111:Overriding this class to remove objects from params which crashes the flow
	 */
	public function createPathInfo($params,$equal,$ampersand, $key=null)
	{
	    foreach($params as $k => $v)
	    {
	        if(is_object($v))
	            unset($params[$k]);
	    }
	    return parent::createPathInfo($params, $equal, $ampersand);
	}
}
