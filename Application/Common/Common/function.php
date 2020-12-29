<?php

function convertUnderline ( $str , $ucfirst = false)
{
    $str = ucwords(str_replace('_', ' ', $str));
    $str = str_replace(' ','',lcfirst($str));
    return $ucfirst ? ucfirst($str) : $str;
}

function uncamelize($camelCaps,$separator='_')
 {
     return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

function convertUnderlineArr($arr){
    $converArr = [];
    foreach($arr as $k => $v){
        $converK = convertUnderline($k);
        $converArr[$converK] = $v;
    }
    return $converArr;
}