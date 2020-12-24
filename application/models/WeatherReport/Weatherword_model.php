<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Weatherword_model extends CI_Model{

	public function selectWeatherWord(){
		$sql = 'select * from gde_weatherreport where id=1';
		$data = $this->mysqlhelper->Query($sql);
		return isset($data[0])?$data[0]:array();
	}


	public function updateWeatherWordDetail($id,$html){
		$sql = 'INSERT INTO gde_weatherreport (id,html,created,operator) VALUES (1,?,now(),?) ON DUPLICATE KEY UPDATE html=?';
		$params = array($html,getsessionempid(),$html);

		$this->db->query($sql,$params);
		$affectedRow = $this->db->affected_rows();

		if ($affectedRow <= 0)
			return '修改图文数据失败!';
		else
			return true;
	}

}