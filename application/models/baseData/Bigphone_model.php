<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc '大手机维护'控制器类对应的模型类
 * @author  hwq
 * @date 2015年9月25日
 */

class Bigphone_model extends CI_Model{
	/**
	 * @desc   '大手机维护'->获取大手机数据
	 * @data   2015-9-25 14:30:23
	 * @param  [type]      $keyword    [description]
	 * @param  [type]      $pageOnload [description]
	 * @return [array]     [大手机设备信息]
	 */
	public function selectBigPhoneMsg($keyword,$pageOnload){
		$sql = 'select id,devicename,latitude,longitude,city,status
				from gde_bigphone where 1=1';
		$params = array();
		if (!isEmpty($keyword)) {
			$sql .= " and devicename like concat('%',?,'%') ";
			array_push($params, $keyword);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		$data['sql'] = $this->db->last_query();
		return $data;
	}


	/**
	 * @desc   '大手机维护'->点击查看大手机信息->获取点击大手机的信息并展示
	 * @data   2015-9-25 15:01:32
	 * @param  [type]      $id [description]
	 * @return [array]         [数据库原本的记录数据]
	 */
	public function selectBigPhoneMsgById($id){
		$sql = 'select * from gde_bigphone where id=?';
		$params = array($id);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


	/**
	 * @desc   '大手机维护'->新增/修改大手机->更改deviceid框->异步检查id是否已存在
	 * @data   2015-9-28 11:04:43
	 * @param  [type]      $deviceid [description]
	 * @return [type]                [description]
	 */
	public function checkIdExist($deviceid,$phoneid){
		$sql = 'select * from gde_bigphone where deviceid=? ';
		$params = array($deviceid);
		if (!isEmpty($phoneid)) {
			$sql .= ' and id<>?';
			array_push($params,$phoneid);
		}
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		if (count($data) > 0) {//id已存在,返回true,提示重复,不允许提交
			return true;
		}else{
			return false;
		}
	}


	/**
	 * @desc   '大手机维护'->查看->修改并确定->更新数据库保存新城市信息
	 * @data   2015-9-25 15:30:14
	 * @param  [type]      $id   [description]
	 * @param  [type]      $city [description]
	 * @return [type]            [description]
	 */
	public function updateBigPhoneMsg($phoneid,$deviceid,$devicename,$longitude,$latitude,$remark,$city){
		$updateArr = array(
			'id' => $phoneid,
			'deviceid' => $deviceid,
			'devicename' => $devicename,
			'longitude' => $longitude,
			'latitude' => $latitude,
			'remark' => $remark,
			'city' => $city
			);
		//return $res = $this->mysqlhelper->Update('gde_bigphone',$updateArr,'id');

		$this->db->update('gde_bigphone',$updateArr,array('id' => $phoneid));
		$num = $this->db->affected_rows();
		if ($num >= 0)
			return true;
		else
			return false;
	}


	/**
	 * @desc   '大手机维护'->新增->保存新大手机信息
	 * @data   2015-9-25 16:15:58
	 * @param  [type]      $name      [description]
	 * @param  [type]      $type      [description]
	 * @param  [type]      $longitude [description]
	 * @param  [type]      $latitude  [description]
	 * @param  [type]      $city      [description]
	 * @return [type]                 [description]
	 */
	public function insertBigPhoneMsg($deviceid,$devicename,$longitude,$latitude,$remark,$city){
		//date_default_timezone_set('PRC');
		$insertArr = array(
			'deviceid' => $deviceid,
			'devicename' => $devicename,
			'longitude' => $longitude,
			'latitude' => $latitude,
			'remark' => $remark,
			'city' => $city
			//'status' => 1,
			//'updatetime' => date(),
			);
		return $res = $this->mysqlhelper->Insert('gde_bigphone',$insertArr);
	}


	/**
	 * @desc   删除大手机
	 * @data   2015-9-28 08:43:11
	 * @param  [type]      $deleteArr [description]
	 * @return [type]                 [description]
	 */
	public function deleteBigPhone($deleteArr){
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_bigphone where id='.$value;
			$res = $this->db->query($sql);
			//var_dump($res);exit;
			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}


}