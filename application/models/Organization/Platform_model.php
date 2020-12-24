<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class platform_model extends CI_Model{
	/**
	 * 修改
	 */	
	public function save($data,$pkey)
	{		
		return $this->mysqlhelper->SaveTrans('sys_platform',$data,$pkey);
	}
	public function check($from){
		$sql="select * from $from";
		$data=$this->mysqlhelper->Query($sql);
		return $data;
	}
	/**
	 * 根据id查找数据
	 */
	public function GetDataById($id){
		$sql="select * from sys_platform where ID = ? ";
		$params=array($id);
		// log_message('info',$sql);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);

		return $data;
	}
	// 按照名称,编码查找
	public function checkName($name,$code){
		$sql1="select * from sys_platform where Name = ?";
		$params1=array($name);
		$data['isName']=$this->mysqlhelper->checknum($sql1,$params1);
		$sql2="select * from sys_platform where Code = ?";
		$params2=array($code);
		$data['isCode']=$this->mysqlhelper->checknum($sql2,$params2);
		return $data;		
	}
	// 分页显示
	public function loadPageData($key,$pagerOrder){
		$sql="select * from sys_platform where 1=1";
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and Name like concat(?,'%')";
			array_push($params, $key);
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
	}
	/**
	 * 删除
	 */
	public function del($id){
		 $sql="delete from sys_platform where id in (".$id.")";
		 $isSuccess=$this->mysqlhelper->ExecuteSql($sql);
		 return $isSuccess;	
	}
}

?>