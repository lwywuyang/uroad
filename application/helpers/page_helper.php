<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('pageorder')) {

	function pageorder($currentPage,$OrderDesc,$PageSize,$num) {
	   $CI =& get_instance();
	   $pageOrder=array(
	   	'CurrentPage'=>intval($currentPage),
	   	'OrderStr'=>$OrderDesc,
	   	'PageSize'=>$PageSize,
	   	'TotalCount'=>$num,
	   	'TotalPage'=>ceil($num/$PageSize)
	   	);
	   return $pageOrder;

	}
}
// 传出CurrentPage当前页，PageSize每一页长度
if (!function_exists('page_onload')) {
	function page_onload() {
	   	$CI =& get_instance();
	   	//当前页
	 	$page = $CI->input->post('page');
	   $OrderDesc= $CI->input->post('OrderDesc');
	   if($page=="0"||$page==0)
	   		$page=1;
	 	// isset是否存在
	 	if(!isset($OrderDesc))
	 	{
	 		$OrderDesc="";
	 	}
		$PageSize=10;
		$pageOnlode=array(
      'CurrentPage' => $page,
      'PageSize'    => $PageSize,
      'OrderDesc'   => $OrderDesc
			);
		// p($pageOnlode);
		return $pageOnlode;
	}
}
