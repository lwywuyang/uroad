<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc '设备维护'控制器类对应的模型类
 * @time 2015-9-23 10:06:32
 * @author  hwq
 */
class Device_model extends CI_Model{
	/**
	 * @desc   '设备维护'->获取页面高速公路下拉框信息
	 * @data   2015-9-23 10:06:57
	 * @return array 	路段下拉框信息
	 */
	public function selectRoadOldMsg(){
		$sql = 'select roadoldid,concat(newcode,shortname) shortname from gde_roadold';
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '设备维护'->获取页面类型下拉框信息
	 * @data   2015-9-23 10:10:37
	 * @return [array] 	类型下拉框信息
	 */
	public function selectTypeMsg(){
		$sql = 'select dictcode,name from gde_dict where codetype=1004 order by seq asc';
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '设备维护'->获取页面表格设备信息
	 * @data   2015-9-23 10:33:42
	 * @param  [string]      $road       [路段下拉框查询条件]
	 * @param  [string]      $type     [类型下拉框查询条件]
	 * @param  [string]      $search     [关键字查询条件]
	 * @param  [string]      $pageOnload [分页]
	 * @return [array] 		 带分页的设备数据
	 */
	public function selectDeviceMsg($road,$type,$search,$pageOnload){
		$sql = 'select deviceid,a.roadoldid,b.shortname,c.name deviceType,a.name,a.miles,concat(a.picturefile,\'?\',unix_timestamp()) picturefile,a.status
				from gde_devicecctv a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_dict c on a.devicetype=c.dictcode
				where 1=1 ';
		$params = array();
		if (!isEmpty($road)) {
			$sql .= 'and a.roadoldid=?';
			array_push($params,$road);
		}
		if (!isEmpty($type)) {
			$sql .= 'and a.devicetype=?';
			array_push($params,$type);
		}
		if (!isEmpty($search)) {
			$sql .= "and (b.shortname like '%".$search."%' or a.name like '%".$search."%')";
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		return $data;
	}


	/**
	 * @desc   '设备管理'->查看操作->获取某设备详细信息
	 * @data   2015-9-23 14:13:56
	 * @param  [string]      $deviceid [设备id]
	 * @return [array]       设备信息数组
	 */
	public function selectDeviceMsgById($deviceid){
		$sql = 'select a.deviceid,a.name,b.name deviceTypeName,c.shortname,a.direction,a.coor_x,a.coor_y,a.miles,a.remark,a.picturefile,a.roadoldid,a.devicetype
				from gde_devicecctv a 
				left join gde_dict b on a.devicetype=b.dictcode
				left join gde_roadold c on a.roadoldid=c.roadoldid
				where a.deviceid=?';
		$params = array($deviceid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//var_dump($this->db->last_query());exit;
		return $data;
	}


	/**
	 * @desc   '设备维护'->查看信息->修改并保存->更改数据库
	 * @data   2015-9-23 18:45:22
	 * @param  [type]      $deviceid  [description]
	 * @param  [type]      $name      [description]
	 * @param  [type]      $type      [description]
	 * @param  [type]      $roadold   [description]
	 * @param  [type]      $direction [description]
	 * @param  [type]      $coor_x    [description]
	 * @param  [type]      $coor_y    [description]
	 * @param  [type]      $miles     [description]
	 * @param  [type]      $remark    [description]
	 * @param  [type]      $picture   [description]
	 * @return [boolean]                 [更新数据库操作结果]
	 */
	public function updateDeviceMsg($deviceid,$name,$type,$roadold,$direction,$coor_x,$coor_y,$miles,$remark,$picture){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'deviceid' => $deviceid,
			'name' => $name,
			'devicetype' => $type,
			'roadoldid' => $roadold,
			'direction' => $direction,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y,
			'miles' => $miles,
			'remark' => $remark,
			'picturefile' => $picture,
			'updatetime' => date('Y-m-d H:i:s')
			);
		return $res = $this->mysqlhelper->Update('gde_devicecctv',$updateArr,'deviceid');
	}


	public function insertDeviceMsg($name,$type,$roadold,$direction,$coor_x,$coor_y,$miles,$remark,$picture){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'name' => $name,
			'devicetype' => $type,
			'roadoldid' => $roadold,
			'direction' => $direction,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y,
			'miles' => $miles,
			'remark' => $remark,
			'picturefile' => $picture,
			'updatetime' => date('Y-m-d H:i:s')
			);
		return $res = $this->mysqlhelper->Insert('gde_devicecctv',$insertArr);
	}


	/**
	 * @desc   '设备维护'->删除
	 * @data   2015-9-24 09:35:37
	 * @param  [array]      $deleteArr [删除记录的id数组]
	 * @return [boolean]    [标记是否删除成功]
	 */
	public function deleteDeviceMsg($deleteArr){
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_devicecctv where deviceid='.$value;
			$this->db->query($sql);
			$num = $this->db->affected_rows();
			if ($num <= 0) {
				$this->db->trans_rollback();
				return false;
			}
			//$affectRows = $this->db->affect_rows();
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}


	public function updateNewStatus($deviceid,$newstatus){
		$updateArr = array(
			'deviceid' => $deviceid,
			'status' => $newstatus
			);
		return $this->mysqlhelper->Update('gde_devicecctv',$updateArr,'deviceid');
	}
}