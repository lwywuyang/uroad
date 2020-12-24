<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc 简图发布段控制器类对应的模型类
 * @author  hwq
 * @date 2015-10-19
 */
class Publishmap_model extends CI_Model{
	/**
	 * @desc   '简图发布段'->获取简图信息
	 * @data   2015-10-19 16:30:51
	 * @param  [type]      $pageOnload [description]
	 * @return [type]                  [description]
	 */
	public function selectMapMsg($keyword,$pageOnload){
		$sql='select * from gde_simplemappub where 1=1';
		$params = array();
		if (!isEmpty($keyword)) {
			$sql .= " and pubcode like concat('%',?,'%')";
			$params = array($keyword);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	public function selectThisMapMsg($id){
		$sql = 'select * from gde_simplemappub where id=?';
		$params = array($id);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


	public function insertMapMsg($mapid,$pubcode,$x,$y){
		$insertArr = array(
			'mapid' => $mapid,
			'pubcode' => $pubcode,
			'x' => $x,
			'y' => $y

			);
		return $res = $this->mysqlhelper->Insert('gde_simplemappub',$insertArr);
	}

	public function updateMapMsg($id,$mapid,$pubcode,$x,$y){
		$updateArr = array(
			'id' => $id,
			'mapid' => $mapid,
			'pubcode' => $pubcode,
			'x' => $x,
			'y' => $y

			);
		return $res = $this->mysqlhelper->Update('gde_simplemappub',$updateArr,'id');
	}

	
	public function deleteMapMsg($deleteArr){
		$this->db->trans_begin();
		
		foreach ($deleteArr as $k => $v) {
			$sql = 'delete from gde_simplemappub where id='.$v;
			//$params = array($v);
			$res = $this->mysqlhelper->ExecuteSql($sql);
			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}

		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

	public function selectMapMsgToExcel($keyword){
		$sql='select mapid,pubcode,x,y from gde_simplemappub where 1=1';
		$params = array();
		if (!isEmpty($keyword)) {
			$sql .= " and pubcode like concat('%',?,'%')";
			$params = array($keyword);
		}
		return $this->mysqlhelper->QueryParams($sql,$params);
	}
}