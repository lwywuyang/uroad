<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
  * 查询时间差
  * yupeng
  * 11:27 2015/7/11
  * 链接
  * 参数$startdate：2015-05-06 12:11:11 $enddate-05-06 12:11:11
  * 返回
  */

if (!function_exists('timestr')) {
	
	/**
	 * 查询天数
	 */
	
	function timestr($startdate,$enddate)
	{
		
		$day=floor((strtotime($enddate)-strtotime($startdate))/86400);
		$hour=floor((strtotime($enddate)-strtotime($startdate))%86400/3600);
		$minute=floor((strtotime($enddate)-strtotime($startdate))%86400/60);
		$second=floor((strtotime($enddate)-strtotime($startdate))%86400%60);
		// echo $date."天<br>";
		// echo $hour."小时<br>";
		// echo $minute."分钟<br>";
		// echo $second."秒<br>";
		$data['day']=$day;
		$data['hour']=$hour;
		$data['minute']=$minute;
		$data['second']=$second;
		return $data;

	}

}


