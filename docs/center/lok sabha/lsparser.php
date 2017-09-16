<?php
//@see http://www.loksabha.nic.in/members/membercontactdetails.aspx
/*
    [0] => Sl. No.
    [1] => Name of Member
    [2] => Permanent Address 1
    [3] => Permanent Telephone No.
    [4] => Delhi Address 1
    [5] => contact info
    [6] => Sl. No.
    [7] => Party
    [8] => Permanent Address 2
    [9] => Permanent FAX No.
    [10] => Delhi Address 2
    [11] => contact info
    [12] => Sl. No.
    [13] => Constituency(State)
    [14] =>
    [15] =>
    [16] =>
    [17] => contact info
    [18] => Sl. No.
    [19] =>
    [20] =>
    [21] =>
    [22] =>
    [23] => contact info
 */
$last_sno = 0;
$f = fopen('MemberContactDetails.csv','r');
$data = [];
$n=0;
while(!feof($f))
{
    $row = fgetcsv($f);
    if(!empty($row[0]))
    {
        fixplace($data);
        findstr('fax',$data);
        findstr('[AT]',$data);
        findphone($data);
        $n=0;
        //print_r($data);
        fputcsv(STDOUT, $data);
        $data = [];
        $last_sno = $row[0];
    }
    else
    {
        $n++;
        $row[0] = $last_sno;
    }
    $data = array_merge($data,$row);
}
fclose($f);

function findstr($findstr,&$data)
{
    $var = [];
    foreach($data as $n => $str)
    {
        if(strstr(strtolower($str),strtolower($findstr)) === false)
            continue;
        $data[$n] = '';
        $var[] = $str;
    }
    $data = array_merge([implode(',',$var)],$data);
}

function fixplace(&$data)
{
    if(empty($data[13]))
        return;
    
    $consti = strrev( $data[13] );
    //echo $data[13] . ' => ' . $consti . "\n";
    $mats = [];
    $data2 = explode('(',$consti,2);
    $state = trim(str_replace(')', '', $data2[0]));
    $contituency= trim($data2[1]);
    $data = array_merge([strrev($contituency),strrev($state)],$data);    
}

function findphone(&$data)
{
    $var = [];
    foreach($data as $n => $str)
    {
        if(empty($str))
            continue;
        
        if( preg_match('/\d{5}\s?\(M\)$/',$str) ||
            preg_match('/Tel[\.s\s:]*/',$str) ||
            preg_match('/\(0\d+\)/',$str) ||
            strstr('Tels :',$str) !== false)
        {
            $data[$n] = '';
            $var[] = $str;
        }
    }
    $data = array_merge([implode(',',$var)],$data);
}

?>
