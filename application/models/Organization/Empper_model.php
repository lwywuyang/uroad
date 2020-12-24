<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class empper_model extends CI_Model{
	//查找员工拥有的角色,再查找角色拥有的功能
	public function checkfunper($empid){

		$sql="select a.* from sys_funpermission a join sys_userrole b on b.EmpID = ? where a.RoleID =b.RoleID";
		$params=array($empid);
		$data=$this->mysqlhelper->QueryParams($sql,$params);	
	 	return $data;

	}
	//查出只有员工选择的功能
	public function checkempfunper($empid){
		$sql="select * from sys_funpermission where EmployeeID =?";
		$params=array($empid);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
	 	return $data;

	}
	//添加员工特有的功能
	public function addFunEmp($data){
		return $this->mysqlhelper->SaveTrans("sys_funpermission",$data,'ID');
	}
	//删除这个员工选择的功能
	public function deleteEmpfun($EmpID){
		$sql="delete from sys_funpermission where EmployeeID = ?";
		$params=array($EmpID);
		$data['isname']=$this->mysqlhelper->ExecuteSqlParams($sql,$params);
		return $data;	
	}
	//查找员工拥有的角色,在查找角色拥有的业务数据
	public function checkempper($empid){
		$sql="select * from sys_userrole join sys_datapermission on (sys_userrole.RoleID = sys_datapermission.RoleID or sys_datapermission.EmployeeID ='$empid') where EmpID='$empid'";
		$data=$this->db->query($sql,array($empid))->result_array(); 		
		return $data;
	}

	//按照公司id查找角色
	public function emproledata($comID){
		$sql="select * from sys_role where CompanyID = '$comID'";
		$data=$this->db->query($sql)->result_array(); 		
		return $data;
	}
	//按照empid取出roleid
	public function checkroledata($empid){
		$sql="select * from sys_userrole where EmpID = '$empid'";
		$data=$this->db->query($sql)->result_array(); 		
		return $data;
	}

	//删除roleid的funid
	public function deleteEmpPer($empid){
		$sql="delete from sys_datapermission where EmployeeID='$empid' ";
		$this->db->query($sql);
	}
}

?>