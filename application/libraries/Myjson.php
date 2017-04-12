<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myjson {

public function my_json_encode($in) { 
  //$_escape = function ($str) { 
  //  return addcslashes($str, "\v\t\n\r\f\"\\/"); 
  //}; 
  
  $out = ""; 
  if (is_object($in)) { 
    $class_vars = get_object_vars(($in)); 
    $arr = array(); 
    foreach ($class_vars as $key => $val) { 
      //$arr[$key] = "\"{$_escape($key)}\":\"{$val}\""; 
    	$arr[$key] = "\"{$this->scape($key)}\":\"{$val}\""; 
    } 
    $val = implode(',', $arr); 
    $out .= "{{$val}}"; 
  }elseif (is_array($in)) { 
    $obj = false; 
    $arr = array(); 
    foreach($in AS $key => $val) { 
      if(!is_numeric($key)) { 
        $obj = true; 
      } 
      $arr[$key] = $this->my_json_encode($val); 
    } 
    if($obj) { 
      foreach($arr AS $key => $val) { 
        //$arr[$key] = "\"{$_escape($key)}\":{$val}"; 
        $arr[$key] = "\"{$this->scape($key)}\":{$val}"; 
      } 
      $val = implode(',', $arr); 
      $out .= "{{$val}}"; 
    }else { 
      $val = implode(',', $arr); 
      $out .= "[{$val}]"; 
    } 
  }elseif (is_bool($in)) { 
    $out .= $in ? 'true' : 'false'; 
  }elseif (is_null($in)) { 
    $out .= 'null'; 
  }elseif (is_string($in)) { 
    //$out .= "\"{$_escape($in)}\""; 
    $out .= "\"{$this->scape($in)}\""; 
  }else { 
    $out .= $in; 
  } 
  return "{$out}"; 
} 

	function scape($str){
		return addcslashes($str, "\v\t\n\r\f\"\\/"); 
	}	

}

?>