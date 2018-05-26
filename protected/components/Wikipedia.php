<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class Wikipedia
{
    var $dom;
    function __construct($url)
    {
        libxml_use_internal_errors ( true );
        
        $html = file_get_contents($url);
        $this->dom = new DOMDocument();
        $this->dom->loadHTML($html);
        $this->xpath = new DOMXpath ( $this->dom );
    }
    
    function __get($lang)
    {
        $atags = $this->xpath->query ( "//a[@hreflang='$lang']" );
        if($atags->length == 0)
        {
            die("No tags found!");
        }
        $title = $atags->item(0)->getAttribute('title');
        
        if(mb_ereg('/^(?<name>.*)-([^-]+)$/', $title,$mats))
        {
            print_r($mats);
        }
        else
            die("ereg failed");
        
        //$titlerev = strrev($title);
        $pos1 = mb_strpos($title, '-');
        $str2 = mb_substr($title, 0,$pos1);
        echo "pos1=$pos1,str2=$str2\n";
        $mats = [];
        if(preg_match('/(*UTF8)^(?<name>[^-]+)-([^-]+)$/',$title,$mats))
        {
            print_r($mats);
        }
        $ss = explode('-',$title);
        if(count($ss) != 2)
        {
            die("Title= $title, count=" . count($ss) . ",=[" . $ss[0] . "]");            
        }
        return trim($ss[0]);
    }
    
    
}

