<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc '欢迎页面'控制器类对应的模型类
 * @author  hwq
 */

class DevicePoi_model extends CI_Model{
	public function selectDevicePoiMsg($pageOnload){
		$sql = "select deviceid,a.name,a.coor_x,a.coor_y,b.name devicetype,concat(c.newcode,c.shortname) roadname,a.picturefile
				from gde_roaddevicepoi a 
				left join gde_dict b on a.devicetype=b.dictcode 
				left join gde_roadold c on a.roadoldid=c.roadoldid
				where a.seq is not null and a.seq<>'' ";
		$params = array();
/*		if (!isEmpty($startTime)) {
			$sql .= ' and startdate >= ?';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$sql .= ' and enddate <= ?';
			array_push($params,$endTime);
		}*/
		
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	public function selectDevicePicture($deviceid){
		$sql = 'select name,picturefile from gde_roaddevicepoi where deviceid=?';
		$params = array($deviceid);

		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}



	public function updatePicInDevicePoi($deviceid,$picture){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'deviceid' => $deviceid,
			'picturefile' => $picture,
			'picturetime' => date('Y-m-d H:i:s')
		);
		return $res = $this->mysqlhelper->Update('gde_roaddevicepoi',$updateArr,'deviceid');
	}

}