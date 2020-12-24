<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class WXMenu_model extends CI_Model{
	/**
	 * 分页查找数据$key,$Status,$Wechatmeun type,$txtstarttime,$txtendtime,$pageOnload
	 */
	function getwechatData($key,$starttime,$endtime,$pagerOrder){
		$sql="select * from etc_user where 1=1";
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and (nickname like concat('%',?,'%') or phone like concat('%',?,'%') or openid like concat('%',?,'%'))";
			array_push($params, $key);
			array_push($params, $key);
			array_push($params, $key);
		}	
		if(!isEmpty($starttime)){
			$sql.=" and registertime >= ?";
			array_push($params, $starttime);
		}
		if(!isEmpty($endtime)){
			$sql.=" and registertime <= ?";
			array_push($params, $endtime);
		}
		

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);

		return $data;

	}
	/**
	 * 拿到顶级菜单条数
	 */
	function gettopnum(){
		$sql="SELECT COUNT(*) topnum from wx_menu where pid=0 and menustatusid=1010001";
		$params=array();
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);
		return $data;
	}
	/**
	 * 按照id查找数据
	 */
	function checkwechatmeun($id){
		$sql="select * from wx_menu where id = ?";
		$params=array($id);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);	
	 	return $data;
	}
	
	/**
	 * 修改
	 */	
	public function save($data)
	{
		return $this->mysqlhelper->SaveTrans("wx_menu ",$data,'id');
	}
	/**
	 * 删除
	 */
	public function deletewechatmeun($id){
		//删除顶级
		$this->db->trans_begin();
		//$sql = 'delete from wx_menu where id=?';
		$sql = 'update wx_menu set menustatusid=1010002 where id=?';
		//$sql="update wx_menu set ishow=0 where id=?";
		$this->db->query($sql,array($id));
		if ($this->db->trans_status() === FALSE) {
		  	$this->db->trans_rollback();
			return false;
		}else{
		    $this->db->trans_commit();
		        return true;
		}
	}

	
	/**
	 * 拿到所有的地区
	 */
	public function getAllzone(){
		$sql='select id,zone from wx_menu zone order by seq asc';
		$data=$this->mysqlhelper->Query($sql);	
	 	return $data;

	}
	/**
	 * 拿到图文信息列表
	 */
	public function getnewlist($id){
		$sql='select
				a.id,
				a.developercode,
				b.id newid,
				b.title,
				b.imgurl,
				b.intime,
				b.viewcount,
				b.url,
				b.status,
				b.pubtime,
				b.sort
			FROM
				wx_menu a
			right JOIN wx_news b ON a.cateid = b.cateid
			WHERE
				a.id = ?';
		$params=array();
		array_push($params, $id);
		$data=$this->mysqlhelper->QueryParams($sql,$params);	
		return $data;

	}
	/**
	 * 查出图文信息
	 */
	function checknew($newid){
		$sql="select * from wx_news where id = ?";
		$params=array($newid);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);	
		return $data;

	}
	/**
	 * 查找条数
	 */
	public function checknewnum($newstypeid){
		$sql="select count(*) num from wx_news where newsstatusid = '1002001' and newstypeid=?";
		$params=array($newstypeid);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);
	 	return $data;
	}
	/**
	 * 保存信息
	 */
	public function  savenew($data){
		$this->db->trans_begin();
		//查看是更改还是添加
		if(isset($data['id'])){
			// 更改
			$this->db->update('wx_news', $data,array('id' => $data['id']));
		}else{
			$this->db->insert('wx_news', $data); 
		}
		if ($this->db->trans_status() === FALSE) {
		  	$this->db->trans_rollback();
			return false;
		}else{
		    $this->db->trans_commit();
	        return true;
		}
	}
	/**
	 * 删除图文
	 */
	public function delnew($id){
		$this->db->trans_begin();
		//$sql="delete from wx_news where id in (".$id.")";
		$sql = 'update wx_news set status=0 where id in ('.$id.')';
		$this->db->query($sql);
		if ($this->db->trans_status() === FALSE) {
			  $this->db->trans_rollback();
				return false;
			}else{
			    $this->db->trans_commit();
			        return true;
			}
		

	}
	public function statuschange($id,$status,$time){
		$this->db->trans_begin();
		if ($time == '') {//取消发布时,time传0,不修改数据库的pubtime
			$sql="update wx_news set status = ? where id = ?";
			$this->db->query($sql,array($status,$id));
		}else{
			$sql="update wx_news set status = ? ,pubtime = ? where id = ?";
			$this->db->query($sql,array($status,$time,$id));
		}

		if ($this->db->trans_status() === FALSE) {
			  $this->db->trans_rollback();
				return false;
			}else{
			    $this->db->trans_commit();
			        return true;
			}
	}
	/**
	 * 保存url
	 */
	public function saveurl($id,$url){
		$this->db->trans_begin();
			
		$sql="update wx_menu set url = ? where id = ?";
		$this->db->query($sql,array($url,$id));

		if ($this->db->trans_status() === FALSE) {
			  $this->db->trans_rollback();
				return false;
			}else{
			    $this->db->trans_commit();
			        return true;
			}
	}


	public function selectItypeIds(){
		$sql = "select dictcode,name from gde_dict where codetype='2001' ";
		return $data = $this->mysqlhelper->Query($sql);
	}

}