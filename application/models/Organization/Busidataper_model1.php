<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class BusiDataPer_model extends CI_Model{

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
	//按照systemid取出数据
	public function getdataper($systemid){
		$sql="select * from sys_budatatype where SystemID = '$systemid'";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	//按照systemid分页查找数据
	public function getAllPer($key,$pagerOrder,$SystemID){
		$sql="select * from sys_budatatype where  1=1";
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and BuName like '%".$key."%'";
		}
		if(!isEmpty($SystemID)){
			$sql.=" and SystemID = ?";
		 	array_push($params, $SystemID);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;

	}
	// 按照id查出数据
	public function checkId($id){
		$sql="select * from sys_budatatype where id='$id'";
		$data=$this->db->query($sql)->result_array();
		return $data;
	}
	//检查编码是不是存在
	public function checkCode($DataTypeCode){
		$sql="select * from sys_budatatype where DataTypeCode = ? ";
		$data['isDataTypeCode']=$this->db->query($sql,array($DataTypeCode))->result_array(); 
		return $data;
		
	}
	//取出权限下面的顶级事件
	public function getToppermbudata($budatatypeid){
		$sql="select * from sys_permbudata where BUDataTypeID = '$budatatypeid' and (PID is null or PID ='') ";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	//取出子集事件
	public function getChildpermbudata($pid,$budatatypeid){
		$sql="select * from sys_permbudata where PID = '$pid' and BUDataTypeID='$budatatypeid'";
		$data=$this->db->query($sql)->result_array(); 
		return $data;
	}
	//删除权限
	public function delper($id){
		$sql="delete from sys_budatatype where ID='$id' ";
		$query=$this->db->query($sql); 
	}
	//查找权限数据
	public function getBusiData($BuTable,$DisFiledID,$DisFiledF,$DisFiledS,$SelfLinkFiled){

		$sql="select ".$DisFiledID." BUDataID,".$DisFiledF." BUDataCode,".$DisFiledS. " BUDataName,".$SelfLinkFiled." PID from ".$BuTable." where 1=1 ";
		if(empty($SelfLinkFiled)){
			$sql="select ".$DisFiledID." BUDataID,".$DisFiledF." BUDataCode,".$DisFiledS. " BUDataName,'' PID from ".$BuTable." where 1=1 ";
		}
		$params=array();
		
	 	$data=$this->mysqlhelper->QueryParams($sql,$params);
		log_message('info',print_r($sql, 1));
	 	return $data;
	}

	public function insertBusiData($data){

		return $this->mysqlhelper->InsertArray('sys_permbudata',$data);
	}

	public function delBusiData($BUDataTypeID){

		$sql="delete from sys_datapermission where permbudataid in (select id from sys_permbudata where budatatypeid=?)";
		$params=array($BUDataTypeID);

		$sql="delete from sys_permbudata where BUDataTypeID = ?";
		$params=array($BUDataTypeID);
		
		return $this->mysqlhelper->ExecuteSqlParams($sql,$params);
	}

}

?>