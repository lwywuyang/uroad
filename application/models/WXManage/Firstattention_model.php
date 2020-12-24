<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》服务热线控制器RoadEventLogic的模型类
 *       主要的表 - gde_phone
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class Firstattention_model extends CI_Model{
	public function selectFirstAttentionMsg(){
		$sql = "select id, title, imgurl, intime, viewcount, url, status, pubtime, sort
			FROM
				alipay_news 
			WHERE cateid = '10600' ";
		return $data = $this->mysqlhelper->Query($sql);
	}


	public function selectDetailMsg($id){
		$sql="select * from alipay_news where id = ?";
		$params=array($id);
		$data=$this->mysqlhelper->QueryParams($sql,$params);	
		return $data[0];
	}


	/**
	 * [updateStatus 操作信息的发布状态]
	 * @version 2016-12-09 1.0
	 */
	public function updateStatus($id,$type){
		
		if ($type == '1'){
			$sql = 'select count(1) num from alipay_news where status = 1';
			$data = $this->db->query($sql)->result_array();
			if ($data[0]['num'] > 5) {
				return '最多不能发布超过五条资讯！';
			}
			$sql = 'update alipay_news set status = ?,pubtime = now() where id = ?';
		}else
			$sql = 'update alipay_news set status = ?,overtime = now() where id = ?';
		
		$this->db->query($sql,array($type,$id));
		if ($this->db->affected_rows() > 0)
			return true;
		else
			return '操作信息的发布状态失败！';
	}



	/**
	 * 保存信息
	 */
	public function  savenew($data){
		$this->db->trans_begin();
		//查看是更改还是添加
		if(isset($data['id'])){
			// 更改
			$this->db->update('alipay_news', $data,array('id' => $data['id']));
		}else{
			$this->db->insert('alipay_news', $data); 
		}

		if ($this->db->trans_status() === FALSE) {
		  	$this->db->trans_rollback();
			return false;
		}else{
		    $this->db->trans_commit();
		    $this->db->trans_complete();
	        return true;
		}
	}


	public function delnew($id){
		$this->db->trans_begin();
		$sql = "delete from alipay_news where id in (".$id.")";
		//$sql = 'update wx_news set status=0 where id in ('.$id.')';
		$this->db->query($sql);
		if ($this->db->trans_status() === FALSE) {
		  	$this->db->trans_rollback();
			return false;
		}else{
		    $this->db->trans_commit();
	        return true;
		}
	}
}