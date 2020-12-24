<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Adminlog_model extends CI_Model{
	/**
	 * 分页查找数据
	 */
	function getlogdata($key,$starttime,$endtime,$type,$fun,$pagerOrder){
		
		$sql="select a.*,b.name typename,c.name subtypename from sys_adminlog a join sys_adminlogdict b on a.type=b.`code` join sys_adminlogdict c on c.code =a.subtype  where 1=1 ";
				$params=array();
				if(!isEmpty($key)){
					$sql.=" and (a.content like concat('%',?,'%') or a.empname like concat('%',?,'%')  or c.name like concat('%',?,'%')  or b.name like concat('%',?,'%'))";
					array_push($params, $key);
					array_push($params, $key);
					array_push($params, $key);
					array_push($params, $key);
				}		
				if(!isEmpty($starttime)){
					$sql.=" and a.intime >= ?";
					array_push($params, $starttime);
				}
				if(!isEmpty($endtime)){
					$sql.=" and a.intime <= ?";
					array_push($params, $endtime);
				} 
				if(!isEmpty($type)){
					$sql.=" and a.type = ?";
					array_push($params, $type);
				} 
				if(!isEmpty($fun)){
					$sql.=" and a.subtype = ?";
					array_push($params, $fun);
				} 
		
		 $sql.="  order by a.id desc";
		 $PageSize=15;
		 $OrderDesc=$pagerOrder['OrderDesc'];
		 $CurrentPage=$pagerOrder['CurrentPage']-1;
		 $start=$PageSize*$CurrentPage;
		 $pageSql=$sql.$OrderDesc." limit ".$start.",15";    
		 $data=array();
		 $data['data']=$this->db->query($pageSql,$params)->result_array();
		 $CurrentPage=$pagerOrder['CurrentPage'];
		$sql="select a.*,b.name typename,c.name subtypename from sys_adminlog a join sys_adminlogdict b on a.type=b.`code` join sys_adminlogdict c on c.code =a.subtype  where 1=1 ";
						$params=array();
						if(!isEmpty($key)){
							$sql.=" and (a.content like concat('%',?,'%') or a.empname like concat('%',?,'%')  or c.name like concat('%',?,'%')  or b.name like concat('%',?,'%'))";
							array_push($params, $key);
							array_push($params, $key);
							array_push($params, $key);
							array_push($params, $key);
						}		
						if(!isEmpty($starttime)){
							$sql.=" and a.intime >= ?";
							array_push($params, $starttime);
						}
						if(!isEmpty($endtime)){
							$sql.=" and a.intime <= ?";
							array_push($params, $endtime);
						} 
						if(!isEmpty($type)){
							$sql.=" and a.type = ?";
							array_push($params, $type);
						} 
						if(!isEmpty($fun)){
							$sql.=" and a.subtype = ?";
							array_push($params, $fun);
						} 
		 $num=$this->db->query($sql,$params)->num_rows();
		 $data['PagerOrder']=array(
		                 'CurrentPage'=>intval($CurrentPage),
		                 'OrderStr'=>$OrderDesc,
		                 'PageSize'=>$PageSize,
		                 'TotalCount'=>$num, 
		                 'TotalPage'=>ceil($num/$PageSize)
		                 );
		 return $data;

	}

	function save($data)
	{
		return $this->mysqlhelper->SaveTrans("cyy_adminlog",$data,'id');
	}
	/**
	 * 查出所有功能
	 */
	function getallype(){
		$sql='select * from sys_adminlogdict where pid is null order by code';
		$data=$this->mysqlhelper->Query($sql);
		return $data;
	}
	/**
	 * 查出操作
	 */
	function getfun($type){
		$sql='select * from sys_adminlogdict where pid = ? and pid is not null order by code';
		$params=array($type);
		$data=$this->mysqlhelper->QueryParams($sql,$params);
		return $data;
	}
	
}