<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('uploadtype')) {
	function uploadname($type)
	{
		//拿到项目名称
		$CI =& get_instance();
		$keycode=$CI->config->item('settingkey');
		
		$sql='select * from ushop_setting where settingkey=?';
		$params=array();
		array_push($params, $keycode);
		$data=$CI->mysqlhelper->QueryParams($sql, $params); 
		$settingval=$data[0]['settingval'];


		if($type==1){
			$name='img/productimg';
		}

		if($type==2){
			$name='img/classimg';
		}

		$imagefile=$_FILES['file'];
		$a=date('YmdHis').substr(microtime(), 2, 3);
		$upFilePath = '../ushopupload/'.$settingval.'/'.$name.'/'.$a.'.jpg';
		$upurl ='ushopupload/'.$settingval.'/'.$name.'/'.$a.'.jpg';
		$url=array(
				'upFilePath'=>$upFilePath,
				'upurl'=>$upurl
			);

		return $url;
	}
}

