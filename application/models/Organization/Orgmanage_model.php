<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 栏目管理模型
 */
class orgmanage_model extends CI_Model{
	/**
	 * 修改
	 */	
	public function save($t,$data,$pkey)
	{		
		return $this->mysqlhelper->SaveTrans($t,$data,$pkey);
	}
	
	/**
	 *  按照名称查找
	 */	
	public function checkName($shortName,$code,$name){
		$sql1="select * from sys_platform where Name = ?";
		$params1=array($name);
		$data['isName']=$this->mysqlhelper->ExecuteSqlParams($sql1,$params1);
		$sql2="select * from sys_platform where Code = ?";
		$params2=array($code);
		$data['isCode']=$this->mysqlhelper->ExecuteSqlParams($sql2,$params2);
		return $data;

		$sql1="select * from org_company where CompShortName = ? ";
		$params1=array($shortName);
		$data['isShortName']=$this->mysqlhelper->ExecuteSqlParams($sql1,$params1);

		$sql2="select * from org_company where CompCode = ?";
		$params2=array($code);
		$data['isCode']=$this->mysqlhelper->ExecuteSqlParams($sql2,$params2);

		$sql3="select * from org_company where CompName = ?";
		$params3=array($name);
		$data['isName']=$this->mysqlhelper->ExecuteSqlParams($sql3,$params3);
		return $data;		
	}
	/**
	 * 按照名称查找部门信息
	 */
	public function checkDepName($DepaCode,$DepaName,$DepaSerial){

		$sql1="select * from org_department where DepaCode = ? ";
		$params1=array($DepaCode);
		$data['isDepaCode']=$this->mysqlhelper->ExecuteSqlParams($sql1,$params1);		
		$sql2="select * from org_department where DepaName = ?";
		$params2=array($DepaName);
		$data['isDepaName']=$this->mysqlhelper->ExecuteSqlParams($sql2,$params2);
		$sql3="select * from org_department where DepaSerial = ?";
		$params3=array($DepaSerial);
		$data['isDepaSerial']=$this->mysqlhelper->ExecuteSqlParams($sql3,$params3);
		return $data;
		
	}
	/**
	 * 按名称查找员工信息
	 */
	public function checkEmpName($EmplCode){
		$sql="select * from org_employee where EmplCode = ? ";
		$params=array($EmplCode);
	 	$data['isEmplCode']=$this->mysqlhelper->QueryParams($sql,$params);
		return $data;
		
	}
	/**
	 * 根据id查找数据
	 */
	public function GetComDataById($id){
		$sql="select * from org_company where ID = ? ";
		$params=array($id);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);
		return $data;
	}
	/**
	 * 根据id查找公司数据
	 */
	public function checkId($id){
		$sql="select * from org_company where ID = ? ";
		$params=array($id);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
		return $data;	
	}
	/**
	 * 根据id查找数据
	 */
	public function GetDepDataById($id){
		$sql="select * from org_department where ID = ? ";
		$params=array($id);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);
		return $data;
	}
	public function GetEmpDataById($id){
		$sql="select * from org_employee where ID = ? ";
		$params=array($id);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);
		return $data;
	}
	/**
	 * 根据id查找部门信息
	 */
	public function checkDepId($id){
		$sql="select * from  org_department where ID = ? ";
		$params=array($id);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
		return $data;
	}
	/**
	 * 根据id查员工信息
	 */
	public function checkEmpId($id){
		$sql="select * from org_employee where ID = ? ";
		$params=array($id);
		$data=$this->mysqlhelper->QueryParams($sql,$params);	
		return $data;
	}
	/**
	 * 取出顶级公司
	 */
	public function loadTopComData($key,$pagerOrder){
		$sql="select * from org_company where (PID is null or PID='')";
		$params=array();

		if(!isEmpty($key)){
			$sql.=" and CompName like concat(?,'%')";
			array_push($params, $key);
		}	
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
	}	
	/**
	 * 需要级联删除
	 */
	public function del($id){ 
		// //删除顶级
		 $sql="delete from org_company where ID in (".$id.")";
		 $isSuccess=$this->mysqlhelper->ExecuteSql($sql);
		 return $isSuccess;			
	}
	//查找子id
	public function CheckChildID($pid){
		$sql="select * from org_company where PID = ?";
		$params=array($pid);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
 		return $data;
	}
	/**
	 * 删除部门
	 */
	public function delDep($id){
		//删除顶级
		 $sql="delete from org_department where ID in (".$id.") ";

		 $isSuccess=$this->mysqlhelper->ExecuteSql($sql);
		 return $isSuccess; 
		
	}


	/**
	 * 删除员工
	 */
	public function delEmp($id){
		 $sql="delete from org_employee where ID in (".$id.") ";
		 $isSuccess=$this->mysqlhelper->ExecuteSql($sql);
		 return $isSuccess;
	}

	/**
	 * @desc   禁用离职用户
	 */
	public function disableEmployee($id){
		$sql="update org_employee set status=1100103 where ID in (".$id.") ";
		$isSuccess=$this->mysqlhelper->ExecuteSql($sql);
		return $isSuccess;
	}

	//无限极分类查找
	/**
	 * 查找顶级公司
	 */
     function GetAllCom()
    {
      $sql="select * from org_company where (PID is null or PID='') ";
      $data=$this->mysqlhelper->Query($sql);
      return $data;
    }
    //查找下级公司
    //查出子id==pid的数据
     function GetSubCom($pid)
    {
    	$sql="select * from org_company where PID = ?";
     	$params=array($pid);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
      	return $data;
    }

    /**
     * 分页查找子公司
     */
    function getChildCom($key,$pagerOrder,$PID){
    	$sql="select * from org_company where 1 = 1";		
		$params=array();

		if(!isEmpty($key)){
			$sql.=" and CompName like concat(?,'%')";
			array_push($params, $key);
		}
		if(!isEmpty($PID)){
			$sql.=" and PID = ?";
		 	array_push($params, $PID);
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
    }
     //分页查找部门
    function getDep($key,$pagerOrder,$CompanyID){
		$sql="select * from org_department where 1=1";
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and DepaName like concat(?,'%')";
			array_push($params, $key);
		}
		if(!isEmpty($CompanyID)){
			$sql.=" and CompanyID = ?";
		 	array_push($params, $CompanyID);
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
    }
    //分页查找子部门
    function getChildDep($key,$pagerOrder,$PID){
		$sql="select * from org_department where 1=1";
		$params=array();

		if(!isEmpty($key)){
			$sql.=" and DepaName like concat(?,'%')";
			array_push($params, $key);
		}
		if(!isEmpty($PID)){
			$sql.=" and PID = ?";
		 	array_push($params, $PID);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
    }

    //分页查找员工
    function getEmp($key,$pagerOrder,$DepartmentID){
    	$sql = "select a.*,b.name statusName from org_employee a
    			left join sys_orgdict b on a.status=b.code
    			where 1=1";
		$params=array();

		if(!isEmpty($key)){
			$sql.=" and EmplName like concat(?,'%')";
			array_push($params, $key);
		}
		if(!isEmpty($DepartmentID)){
			$sql.=" and DepartmentID = ?";
		 	array_push($params, $DepartmentID);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
    }

    //查找与公司相同的id的部门，pid为null
    function GetAllDep($id){
    	$sql="select * from org_department where CompanyID = ? and PID is null";
     	$params=array($id);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
      return $data;
    }
    //查找子部门
    function GetSubDep($id){ 
    	$sql="select * from org_department where PID = ?";
    	$params=array($id);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
      	return $data;
    }
    //查找部门下面的员工
    function GetAllEmp($id){
      $sql="select * from org_employee where DepartmentID = ?";
      $params=array($id);
	  $data=$this->mysqlhelper->QueryParams($sql,$params);
      return $data;
    }
    
	//根据公司id查出公司的上级id,也就是查处PID
	function checkTopCompid($id){
		$sql="select * from org_company where ID = ? ";
 		$params=array($id);
	  	$data=$this->mysqlhelper->QueryParams($sql,$params);
 		return $data;
	}

	//根据最顶级公司id查处所拥有的角色
	function checkComRole($id){
		$sql="select * from sys_role where CompanyID = ?";
		$params=array($id);
	  	$data=$this->mysqlhelper->QueryParams($sql,$params);
 		return $data;
	}
	//添加角色员工表
	function addRoleEmp($id,$roleid,$userid){
		$sql="insert into sys_userrole (ID, RoleID, EmpID) VALUES (?, ?, ?)";
		$params=array($id,$roleid,$userid);
	  	return $this->mysqlhelper->ExecuteSqlParams($sql,$params);

	}
	//修改
	function editRoleEmp($id,$roleid,$userid){
		
		$sql="insert into sys_userrole (ID, RoleID, EmpID) VALUES (?, ?, ?)";
		$params=array($id,$roleid,$userid);
		$this->mysqlhelper->QueryParams($sql,$params);
	}
	
	//按照员工id找出对应的角色
	function checkRoleId($id){
		$sql="select * from sys_userrole where EmpID = ?";
		$params=array($id);
 		$data=$this->mysqlhelper->QueryParams($sql,$params);
 		return $data;
	}
	//删除所有与员工id相同的角色

	function deleteRole($userid){
		$sql="delete from sys_userrole where EmpID = ?";
		$params=array($userid);
		return $this->mysqlhelper->ExecuteSqlParams($sql,$params);
	}
	/**
	 * 登陆
	 *按照员工编码查找员工数据
	 */
	 function checkEmplCode($EmplCode){
	 	$sql = "select * from org_department,org_employee 
	 			where org_employee.DepartmentID = org_department.ID and org_employee.EmplCode = ? and org_employee.status<>1100103";
	 	$params=array($EmplCode);
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
			return $data;
	}

	public function saveIp($username, $ip, $address, $isp) {
		$sql="insert into sys_iplog (username, ip, address, isp, intime) VALUES (?, ?, ?, ?, NOW())";
		$params=array($username,$ip,$address,$isp);
	  	return $this->mysqlhelper->ExecuteSqlParams($sql,$params);
	}
}

?>