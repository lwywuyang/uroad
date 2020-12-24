<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class AdPic_model extends CI_Model{
	/**
	 * 分页查找数据$key,$Status,$AdPictype,$txtstarttime,$txtendtime,$pageOnload
	 */
	function getAdPicData($StartTime,$EndTime,$stateSel,$pageOnload){
		$sql = "select * FROM t_etc_ad WHERE 1 = 1";
		$params = array();
		if(!isEmpty($StartTime)){
			$StartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(modifyed) >= UNIX_TIMESTAMP(?)';
			array_push($params, $StartTime);
		}
		if(!isEmpty($EndTime)){
			$EndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(modifyed) <= UNIX_TIMESTAMP(?)';
			array_push($params, $EndTime);
		}
		if(!isEmpty($stateSel)){
			$sql .= " and state = ?";
			array_push($params, $stateSel);
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);

		return $data;

	}


	/**
	 * 按照id查找数据
	 */
	function checkAdPicData($id){
		$sql = "select * from t_etc_ad where id = ?";
		return $this->mysqlhelper->GetRecordBySql($sql,array($id));
	}


	public function saveAdPic($AdPicdata){

	    $imghtml = '<img src="'.$AdPicdata['imageurl'].'" class="ad-image" onclick="showLayerImage(this.src)">';
		if ($AdPicdata['id'] == '0') {
			$sql = 'insert into t_etc_ad (imageurl,redirecturl,state,seq,created,modified)values(?,?,?,?,now(),now())';
			$params = array($AdPicdata['imageurl'],$AdPicdata['redirecturl'],0,$AdPicdata['seq']);
            $content = '新增etc广告 '.$imghtml;
            saveLog($content,2010003);
			$this->db->query($sql,$params);
			if ($this->db->affected_rows() <= 0) {
				return '新增失败!';
			}
		}else{
			$sql = 'update t_etc_ad set imageurl=?,redirecturl=?,seq=?,modified=now() where id=?';
			$params = array($AdPicdata['imageurl'],$AdPicdata['redirecturl'],$AdPicdata['seq'],$AdPicdata['id']);
            $content = '修改etc广告 '.$imghtml;
            saveLog($content,2010003);
			$this->db->query($sql,$params);
			if ($this->db->affected_rows() < 0) {
				return '更新失败!';
			}
		}
		
		return true;
	}


	/**
	 * [updateState 操作广告图状态]
	 * @version 2017-01-06 1.0
	 */
	public function updateState($id,$state){
		$sql = 'update t_etc_ad set state=?,modified=now() where id=?';
        $statusname = '取消发布';
        if($state==1){
            $statusname = '发布';
        }
        $content = '修改etc广告 状态为'.$statusname;
        saveLog($content,2010003);
		$this->db->query($sql,array($state,$id));
		if ($this->db->affected_rows() <= 0)
			return '操作状态失败!';
		else
			return true;
	}
}