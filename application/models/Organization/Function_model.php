<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class function_model extends CI_Model{
	 /**
	  * 增加
	  */
	public function insert($form,$data)
		{
			//新增
			$this->db->insert($form,$data);
		}
	public function update($form,$data,$id)
	{
		//编辑
		$this->db->update($form,$data,array('ID'=>$id));

	}
	
	public function save($form,$data)
	{
		if(isset($data["id"]))
		{
			$this->update($form,$data,$data['id']);
		}
		else
		{
			$this->insert($form,$data);
		}
	}
	//检查编码是不是存在
	public function checkCode($form,$FuncCode){
		$sql1="select * from $form where FuncCode = ? ";
		$data['isFuncCode']=$this->db->query($sql1,array($FuncCode))->result_array(); 
		return $data;
		
	}
	//按照系统id取出事件
	public function gettopfun($systemid){
		$sql="select * from sys_functions where SystemID = '$systemid' and (PID ='' or PID  is null)";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	/**
	 * 按照id去出子事件
	 */
	public function gettsubfun($id){
		$sql="select * from sys_functions where PID = '$id'";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	/**
	 * 系统id分页查处顶级事件
	 */
	public function getAllFun($key,$pagerOrder,$SystemID,$PID){

		$sql="select * from sys_functions  where 1=1";
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and FuncName like concat(?,'%')";
			array_push($params, $key);
		}
		if(!isEmpty($SystemID)){
			$sql.=" and SystemID = ?";
		 	array_push($params, $SystemID);
		}
		if(!isEmpty($PID)){
			$sql.=" and PID = ?";
		 	array_push($params, $PID);
		}else{
			$sql.=" and (PID = '' or PID is null) ";
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		
		for($i=0;$i<count($data['data']);$i++){
			if($data['data'][$i]['FuncType']=='1101302'){
				$data['data'][$i]['FuncTypeName']='菜单';
			}
			if($data['data'][$i]['FuncType']=='1101304'){
				$data['data'][$i]['FuncTypeName']='页面';
			}
			if($data['data'][$i]['FuncType']=='1101305'){
				$data['data'][$i]['FuncTypeName']='按钮';
			}
			if($data['data'][$i]['FuncType']=='1101306'){
				$data['data'][$i]['FuncTypeName']='功能';
			}

			if($data['data'][$i]['Status']=='1100102'){
				$data['data'][$i]['StatusName']='确认';
			}
			if($data['data'][$i]['Status']=='1100103'){
				$data['data'][$i]['StatusName']='作废';
			}
		}
		return $data;
	}
	//根据自己的id查找处理
	public function checkId($id){
		$sql="select * from sys_functions where id='$id'";
		$data=$this->db->query($sql)->result_array();
			if($data[0]['FuncType']=='1101302'){
				$data[0]['FuncTypeName']='菜单';
			}
			if($data[0]['FuncType']=='1101304'){
				$data[0]['FuncTypeName']='页面';
			}
			if($data[0]['FuncType']=='1101305'){
				$data[0]['FuncTypeName']='按钮';
			}
			if($data[0]['FuncType']=='1101306'){
				$data[0]['FuncTypeName']='功能';
			}
			if($data[0]['Status']=='1100102'){
				$data[0]['StatusName']='确认';
			}
			if($data[0]['Status']=='1100103'){
				$data[0]['StatusName']='作废';
			}
		return $data;
	}
	//删除顶级事件
	public function delfun($id){
		$sql="delete from sys_functions where ID=$id ";
		$query=$this->db->query($sql); 
	}

	//获取所有用户对应的菜单
	public function GetAllempFunPer($empid){
		/*version1.0
		$sql = "select * from (
					select * from sys_funpermission 
					where (RoleID in (
						select sys_userrole.RoleID 
						from sys_userrole inner join sys_role on sys_userrole.RoleID=sys_role.ID 
						where sys_userrole.EmpID='T2016011812403044613729863'
					) and EmployeeID is null) or (EmployeeID='T2016011812403044613729863' and RoleID is null)
				) a join sys_functions b on a.FunctionID=b.ID ORDER BY FuncSerial";*/
		//version2.0  2016-04-15  hwq
		//只拿部分字段
		//添加筛选条件,只拿有效(1100102)菜单(1101302)
		$sql = "select FunctionID,b.ID,PID,FuncCode,FuncName,FuncType,URI from (
					select * from sys_funpermission 
					where (RoleID in (
						select sys_userrole.RoleID 
						from sys_userrole inner join sys_role on sys_userrole.RoleID=sys_role.ID 
						where sys_userrole.EmpID=?
					) and EmployeeID is null) or (EmployeeID=? and RoleID is null)
				) a join sys_functions b on a.FunctionID=b.ID where FuncType = 1101302 and Status = 1100102 ORDER BY FuncSerial";
		$params = array($empid,$empid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


}