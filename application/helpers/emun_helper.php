<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('GetOrderStatusName')) {
	function GetOrderStatusName($statu)
	{
		if($statu=="0")
		{
			return "取消";
		}
		else if($statu=="1")
		{
			return "待支付";
		}
		else if($statu=="2")
		{
			return "已支付";
		}
	}
}

if (!function_exists('GetOrderDetailStatusName')) {
	function GetOrderDetailStatusName($statu)
	{
		if($statu=="0")
		{
			return "未处理";
		}
		else if($statu=="3")
		{
			return "已受理";
		}
		else if($statu=="4")
		{
			return "已出货";
		}
		else if($statu=="5")
		{
			return "已签收";
		}
		else if($statu=="6")
		{
			return "处理失败 ";
		}
	}
}