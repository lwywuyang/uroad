<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class roleper_model extends CI_Model{
	//查找角色拥有的功能
	public function checkroleid($RoleId){
		$sql="select * from sys_funpermission where RoleID = ? ";
		$data=$this->db->query($sql,array($RoleId))->result_array(); 		
		return $data;
	}
	//删除roleid的funid
	public function deleteFunRole($RoleID){
		$sql="delete from sys_funpermission where RoleID='$RoleID' ";
		$this->db->query($sql);
	}
	//添加进去
	public function updateFunRole($data){
		$this->db->insert('sys_funpermission',$data);
	}
	////取出权限下面的所有事件
	public function getpermbudata($budatatypeid){
		$sql="select * from sys_permbudata where BUDataTypeID = '$budatatypeid'";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	//去出选中的数据
	public function RoleCheck($RoleID){
		$sql="select * from sys_datapermission where RoleID = '$RoleID'";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	//删除roleid的funid
	public function deleteRolePer($RoleID,$TypeID){
		// 查出对应的数据分类id下面子数据权限
		// $sql="select Group_concat(ID) ids from sys_permbudata where BUDataTypeID =?";
		// $data=$this->db->query($sql,array($TypeID))->result_array(); 

		// $sql="delete from sys_datapermission where RoleID='$RoleID' and PermBUDataID in ('".$data[0]['ids']."') ";
		// $this->db->query($sql);
		$sql="delete from sys_datapermission where roleid=? and permbudataid in (select id from sys_permbudata where budatatypeid=?)";

		$this->db->query($sql,array($RoleID,$TypeID));
	}
	//添加进去
	public function updateRolePer($data){
		$this->db->insert('sys_datapermission',$data);
	}
	//分页查找角色员工表
	public function checkRoleEmp($key,$pagerOrder,$RoleID){
		//查出角色对应所有的员工
		 $sql="select b.ID EmpID,b.EmplCode,b.EmplName,c.DepaName,d.CompName from sys_userrole a join org_employee b on (a.EmpID = b.ID and a.RoleID = ?) join org_department c on(b.DepartmentID = c.ID) join org_company d on (c.CompanyID = d.ID)";
		 $params=array();
		 array_push($params, $RoleID);
		 if(!isEmpty($key)){
			$sql.=" and EmplName like concat(?,'%')";
			array_push($params, $key);
		}
		
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;

	}
	
	//查找没有被选择的员工
	public function SelectEmp($roleid){
	
		// 查出已经选择的员工id
		$sql="select * from sys_userrole where RoleID = '$roleid'";
		$data=$this->db->query($sql)->result_array(); 
      	return $data;
	}
	//添加
	public function adduserRole($data){
		$this->db->insert('sys_userrole',$data);
	}
	//删除角色员工

		public function delroleemp($empid){ 
		
		// //删除顶级
		 $sql1="delete from sys_userrole where EmpID in (".$empid.")";
		 $this->db->query($sql1); 
		
		
	}
}

?>