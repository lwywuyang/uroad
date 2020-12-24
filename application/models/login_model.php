<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登陆管理模型
 */
class Login_model extends CI_Model{

	/**
	 * 查询后台用户数据
	 */
	public function check($EmplCode){
		// $data = $this->db->select('EmplCode,EmplName,PassWord,DepartmentID,DepaName')->from('org_employee')->join('org_department', 'org_employee.DepartmentID=org_department.ID')->where(array('EmplCode'=>$EmplCode))->get()->result_array();
		// $sql="select * from org_employee,org_department where org_employee.DepartmentID = org_department.ID and org_employee.EmplCode = '$EmplCode'";
		// // $sql="select * from org_employee where EmplCode = '$EmplCode'";
		// $data=$this->db->query($sql)->result_array(); 	
		// // p($data);	
		// return $data;
	}
}

?>