<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Userservice_model extends CI_Model{
	//查询所有有效的后台用户
	public function selectAllUser(){
		$sql = 'select ID,EmplName from org_employee where status=1100102';
		return $this->db->query($sql)->result_array();
	}

	//查询所有服务区
	public function selectAllService(){
		$sql = 'select poiid,name from gde_roadpoi where pointtype in (1003001,1003002,1003003,1003004)';
		return $this->db->query($sql)->result_array();
	}


	//查询用户下的服务区的id
	public function selectServiceByUser($id){
		$sql = 'select serviceids from gde_serviceemployee where userid=?';
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return isset($data[0]['serviceids'])?$data[0]['serviceids']:'';
	}

	//更新权限
	public function updateUserService($userid,$serviceids){

		/*$sql = 'insert into gde_serviceemployee (userid,serviceids) values (?,?) on duplicate key update userid=?';
		$params = array($userid,$checkedId,$userid);

		$this->db->query($sql,$params);

		if($this->db->affected_rows()>=0)
			return true;
		else
			return '更新用户服务区权限失败!';*/

		$this->db->trans_begin();

		$sql = 'select * from gde_serviceemployee where userid=?';
		$params = array($userid);
		$resultArray = $this->db->query($sql,$params)->result_array();

		if (count($resultArray) <= 0) {
			$insert = array('userid' => $userid,'serviceids' => $serviceids);
			$this->db->insert('gde_serviceemployee',$insert);

			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				return '保存新用户服务区关系失败!';
			}
		}else{
			$update = array('userid' => $userid,'serviceids' => $serviceids);
			$this->db->update('gde_serviceemployee',$update,array('userid' => $userid));

			if ($this->db->affected_rows() < 0) {
				$this->db->trans_rollback();
				return '更新用户服务区关系失败!';
			}
		}

		
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

}