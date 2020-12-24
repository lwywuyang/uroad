<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('post_val')) {
	function post_val( )
	{
		//$CI=& get_instance();
		$args = func_get_args();
		for($i=0;$i<count($args);$i++)
		{
			if(!isset($_POST[$args[$i]]))
			{
				 ajax_error("传入参数有误：缺少".$args[$i]);
				exit;
			}
		}
	}
}

if (!function_exists('checkField')) {
	function checkField($key)
	{
		if(isset($key))
		{
			echo $key;
		}
		else
		{
			echo "";
		}
	}
}

if (!function_exists('isEmpty')) {
	function isEmpty($key){
		if(isset($key)&&$key!=""&&trim($key)!="")
		{			
			return false;
		}else{
			return true;
		}
	}

}