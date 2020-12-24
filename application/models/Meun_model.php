<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登陆管理模型
 */
class Meun_model extends CI_Model{

	/**
	 * 查询后台用户数据
	 */
	public function check($EmplCode){
		$data = $this->db->select('EmplCode,EmplName,PassWord,DepartmentID,DepaName')->from('org_employee')->join('org_department', 'org_employee.DepartmentID=org_department.ID')->where(array('EmplCode'=>$EmplCode))->get()->result_array();
		return $data;
	}
	
	

}

?>