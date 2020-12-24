<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MysqlHelper{

	private $_ci;
	public function __construct(){
		
		$this->_ci=& get_instance();
		// log_message('info','aaa');
	}


	/**
	 * 执行sql语句并返回数组
	 * @param [type]
	 */	
	public function Query($sql)
	{
		// echo $sql;
		$data=$this->_ci->db->query($sql)->result_array(); 
		return $data;
	}
/**
 * 查询分页的数据
 */
	public function GetPageOrder($sql,$params,$pagerOrder){
		$CurrentPage=$pagerOrder['CurrentPage'];
		$PageSize=$pagerOrder['PageSize'];
		$OrderDesc=$pagerOrder['OrderDesc'];
		$start=$PageSize*$CurrentPage;
		// $pageSql="select * from (".$sql.") T limit ".$start.",".$PageSize;
		log_message('info','111');
		$num=$this->QueryRow($sql,$params);
		$pageOrder=pageorder($CurrentPage,$OrderDesc,$PageSize,$num);

		return $pageOrder;
	}

	/**
	 * 执行sql语句并返回数组，带分页
	 * @param [type]
	 */	
	public function QueryPage($sql,$params,$pagerOrder)
	{
		$PageSize=$pagerOrder['PageSize'];
		$OrderDesc=$pagerOrder['OrderDesc'];
		$CurrentPage=$pagerOrder['CurrentPage']-1;
		$start=$PageSize*$CurrentPage;
		log_message('info','121121');
		$pageSql="select * from (".$sql.") T ".$OrderDesc." limit ".$start.",".$PageSize;
		log_message('info','121121');
		$data=array();
		$data=$this->QueryParams($pageSql, $params); 
		// $num=$this->QueryRow($sql,$params);
		// $data['pageOrder']=pageorder($CurrentPage,$OrderDesc,$PageSize,$num);
		return $data;
	}
	/**
	 * 执行sql语句并返回数组，带参数
	 * @param [type]
	 * @param [type]
	 */
	public function QueryParams($sql,$params)
	{  
		$data=$this->_ci->db->query($sql,$params)->result_array();
		return $data;
	}

	/**
	 * 查询行数
	 */
	public function QueryRow($sql,$params){
		$num=$this->_ci->db->query($sql,$params)->num_rows();	
		return $num;
	}
	public function QueryRowone($sql){
		$num=$this->_ci->db->query($sql)->num_rows();	
		return $num;
	}

	//根据id查询哪一行数据
	public function GetRecordBySql($sql,$params)
	{
		$data=$this->_ci->db->query($sql,$params)->result_array();
		if(count($data)>0)
		{
			return $data[0];
		}
		else
			return null;
	}
	/**
	 * 执行sql，返回成功与否
	 * @param [type]
	 */
	public function ExecuteSql($sql)
	{
		$data=$this->_ci->db->query($sql);
		log_message('info',print_r($data, 1));
		return $this->_ci->db->affected_rows()>0?true:false;
	}


	/**
	 * 执行sql，返回成功与否，带参数
	 * @param [type]
	 */
	public function ExecuteSqlParams($sql,$params)
	{
		$data=$this->_ci->db->query($sql,$params);
		log_message('info',print_r($data, 1));
		return $this->_ci->db->affected_rows()>0?true:false;
	}


	/**
	  * 查询是否存在
	  */
	 public function  checknum($sql,$params){
	  $num=$this->_ci->db->query($sql,$params)->num_rows();
	  log_message('info',$num);
	  return $num>0?true:false;
	 }
	/**
	 * 插入数组，自动带事务
	 * @param 表名
	 * @param 数据
	 */
	public function InsertArray($t,$data)
	{
		$this->_ci->db->trans_begin();
		foreach ($data as $value) {
			$this->Insert($t,$value);
		}
		if ($this->_ci->db->trans_status() === FALSE){
			$this->_ci->db->trans_rollback();
			return false;
		}else{
			$this->_ci->db->trans_commit();
			return true;
		}
	}
	 /**
	 * [checkId description]p判断id是否存在
	 * @param  [type] $t      [description]
	 * @param  [type] $pkey   [description]
	 * @param  [type] $keyval [description]
	 * @return [type]         [description]
	 */
	public function checkId($t,$pkey,$keyval)
	{
		$sql="select * from ".$t." where ".$pkey." = ?";
		return $this->checknum($sql,array($keyval));
	}


	/**
	 * 插入实体，不带事务
	 * @param 表名
	 * @param 数据
	 */
	public function Insert($t,$data)
	{

		$this->_ci->db->insert($t,$data);
		return $this->_ci->db->affected_rows()>0?true:false;
	}
	/**
	 * 更新实体，不带事务
	 * @param 表名
	 * @param 数据
	 * @param 主键名
	 */
	public function Update($t,$data,$pkey)
	{
		$this->_ci->db->update($t,$data,array($pkey=>$data[$pkey]));
		return $this->_ci->db->affected_rows()>=0?true:false;
	}
	
	/**
	 * 更新实体，带事务，自动识别更新还是插入
	 * @param 表名
	 * @param 数据
	 * @param 主键名
	 */
	public function SaveTrans($t,$data,$pkey)
	{
		$this->_ci->db->trans_begin();
		if(isset($data[$pkey])){
			$keyval=$data[$pkey];
			$checkExist=$this->checkId($t,$pkey,$keyval);
			if($checkExist)
				{
					$this->Update($t,$data,$pkey);
				}
			else
				{
					$this->Insert($t,$data);
				}
			}
		else
		{
			$this->Insert($t,$data);
		}
		if ($this->_ci->db->trans_status() === FALSE){
			$this->_ci->db->trans_rollback();
			return false;
		}else{
			$this->_ci->db->trans_commit();
			return true;
		}
	}

	/**
	 * 更新实体，不带事务，自动识别更新还是插入
	 * @param 表名
	 * @param 数据
	 * @param 主键名
	 */
	public function Save ($t,$data,$pkey)
	{
		 
		if(isset($data[$pkey]))
		{
			$this->Update($t,$data,$pkey);
		}
		else
		{
			$this->Insert($t,$data);
		}
		return $this->_ci->db->affected_rows()>0?true:false;
	}

}