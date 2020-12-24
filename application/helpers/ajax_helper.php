<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('ajax_success')) {
	function ajax_success($data,$pager)
	{
		$obj=array();
		$obj["Success"]=true;
		$obj["data"]=$data;
		if($pager!=null)
		{
			$obj["PagerOrder"]=$pager;
		}
		echo json_encode($obj);
		//return(json_encode($obj));
		//$this->output->set_output($obj);

	}
}


if (!function_exists('ajax_error')) {
	function ajax_error($msg)
	{
		$obj=array();
		$obj["Success"]=false;
		$obj["Message"]=$msg;
		echo json_encode($obj);
		//$this->output->set_output($obj);
	}
}
