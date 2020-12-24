<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc '欢迎页面'控制器类对应的模型类
 * @author  hwq
 * @date 2015-11-5
 */

class Welcome_model extends CI_Model{
	public function selectWelcomeMsg($startTime,$endTime,$pageOnload){
		$sql = 'select * from gde_welcomejpg where 1=1';
		$params = array();
		if (!isEmpty($startTime)) {
			$sql .= ' and startdate >= ?';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$sql .= ' and enddate <= ?';
			array_push($params,$endTime);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	public function selectWelcomeMsgById($id){
		$sql = 'select * from gde_welcomejpg where id='.$id;
		return $data = $this->mysqlhelper->Query($sql);
	}


	public function insertWelcomeMsg($startTime,$endTime,$imgurl){
		$insertArr = array(
			'startdate' => $startTime,
			'enddate' => $endTime,
			'url' => $imgurl
		);
		return $res = $this->mysqlhelper->Insert('gde_welcomejpg',$insertArr);
	}

	public function updateWelcomeMsg($id,$startTime,$endTime,$imgurl){
		/*$insertArr = array(
			'id' => $id,
			'startdate' => $startTime,
			'enddate' => $endTime,
			'url' => $imgurl
		);
		return $res = $this->mysqlhelper->Update('gde_welcomejpg',$insertArr,'id');*/
		$updateSql = "UPDATE `gde_welcomejpg` SET `startdate` = ?, `enddate` = ?, `url` = ? WHERE `id` = ?";
		$params = array($startTime,$endTime,$imgurl,$id);
		$this->db->query($updateSql,$params);
		$res = ($this->db->affected_rows()>=0)?true:false;
		return $res;
	}


	public function deleteWelcomeMsg($delValue){
		$delArr = explode(',', $delValue);
		$sql = 'delete from gde_welcomejpg where id=';

		$this->db->trans_begin();
		foreach ($delArr as $k => $v) {
			//$params = array($v);
			$sql .= $v;
			$res = $this->db->query($sql);
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