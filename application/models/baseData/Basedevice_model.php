<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc '设备维护'控制器类对应的模型类
 * @author  hwq
 */
class Basedevice_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		//$testuroad = $this->load->database('testuroad',true);
	}

	/**
	 * @desc   '设备维护'->获取页面高速公路下拉框信息
	 * @return array 	路段下拉框信息
	 */
	public function selectRoadOldMsg(){
		$testuroad = $this->load->database('testuroad',true);
		$sql = 'select roadoldid,concat(newcode,shortname) shortname from gde_roadold order by newcode';
		return $data = $testuroad->query($sql)->result_array();
		//return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '设备维护'->获取页面表格设备信息
	 * @param  [string]      $road       [路段下拉框查询条件]
	 * @param  [string]      $type     [类型下拉框查询条件]
	 * @param  [string]      $search     [关键字查询条件]
	 * @param  [string]      $pageOnload [分页]
	 * @return [array] 		 带分页的设备数据
	 */
	public function selectBaseDeviceMsg($road,$status,$search,$pageOnload){
		$testuroad = $this->load->database('testuroad',true);
		$sql = 'select deviceid,sn,devicename,a.roadoldid,a.status,concat(b.newcode,b.shortname) roadname,a.coor_x,a.coor_y
				from base_device a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				where 1=1 ';
		$params = array();
		if (!isEmpty($road)) {
			$sql .= 'and a.roadoldid=?';
			array_push($params,$road);
		}
		if (!isEmpty($status)) {
			$sql .= 'and a.status=?';
			array_push($params,$status);
		}
		if (!isEmpty($search)) {
			$sql .= "and (b.shortname like '%".$search."%' or devicename like '%".$search."%' or sn like '%".$search."%')";
		}
		//旧
		//$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);

		//列表数据
		$PageSize = $pageOnload['PageSize'];
		$OrderDesc = $pageOnload['OrderDesc'];
		$CurrentPage = $pageOnload['CurrentPage']-1;
		$start = $PageSize*$CurrentPage;
		$pageSql = "select * from (".$sql.") T ".$OrderDesc." limit ".$start.",".$PageSize;
		//$data=$this->QueryParams($pageSql, $params);
		$data['data'] = $testuroad->query($pageSql,$params)->result_array();
		//分页
		$CurrentPage2 = $pageOnload['CurrentPage'];
		//$PageSize=$pageOnload['PageSize'];
		//$OrderDesc=$pageOnload['OrderDesc'];
		//$start2=$PageSize*$CurrentPage2;
		// $pageSql="select * from (".$sql.") T limit ".$start.",".$PageSize;
		//log_message('info','111');
		//$num = $this->QueryRow($sql,$params);
		$num = $testuroad->query($sql,$params)->num_rows();
		$data['pageOnload'] = pageorder($CurrentPage2,$OrderDesc,$PageSize,$num);

		return $data;
	}

	/**
	 * [updateBaseDevice 将设备设为有效/无效]
	 * @version 2016-04-21 1.0
	 * @return  [type]               [description]
	 */
	public function updateBaseDevice($deviceid,$status){
		$testuroad = $this->load->database('testuroad',true);
		$updateArr = array(
			'deviceid' => $deviceid,
			'status' => $status
			);
		//$res = $this->mysqlhelper->Update('base_device',$updateArr,'deviceid');
		$testuroad->update('base_device',$updateArr,array('deviceid'=>$updateArr['deviceid']));

		$res = ($testuroad->affected_rows() >= 0)?true:false;
		return ($res === true)?true:'修改设备状态失败！';

	}

	/**
	 * [selectBaseDeviceMsgById 根据deviceid查询设备信息]
	 * @version 2016-04-27 1.0
	 * @param   [type]     $deviceid [description]
	 * @return  [type]               [description]
	 */
	public function selectBaseDeviceMsgById($deviceid){
		$testuroad = $this->load->database('testuroad',true);
		$sql = 'select * from base_device where deviceid=?';
		$params = array($deviceid);
		//$data = $this->mysqlhelper->QueryParams($sql,$params);
		$data = $testuroad->query($sql,$params)->result_array();
		return isset($data[0])?$data[0]:array();
	}


	/**
	 * @desc   '设备维护'->查看信息->修改并保存->更改数据库
	 * @return [boolean]                 [更新数据库操作结果]
	 */
	public function updateBaseDeviceDetail($deviceid,$coor_x,$coor_y){
		$testuroad = $this->load->database('testuroad',true);
		$updateArr = array(
			'deviceid' => $deviceid,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y
			);
		//$res = $this->mysqlhelper->Update('base_device',$updateArr,'deviceid');

		$testuroad->update('base_device',$updateArr,array('deviceid'=>$updateArr['deviceid']));

		$res = ($testuroad->affected_rows() >= 0)?true:false;

		if ($res == true)
			return true;
		else
			return '更新经纬度失败!';
	}

}