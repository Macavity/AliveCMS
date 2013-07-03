<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('debug'))
{
    function debug($label, $data = ""){
        $string = "";
        
        if(is_array($label) || is_object($label))
            $string .= " ".print_r($label,true)." ";
        else
            $string .= " $label ";
        
        if($data === ""){
        }
        elseif(is_array($data) || is_object($data))
            $string .= " ".print_r($data,true)." ";
        else
            $string .= " $data ";
        
        echo "\n<!-- ".$string." -->";
    }  
}