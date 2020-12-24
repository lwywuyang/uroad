<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 中心权限管理控制器类对应的模型类
 * @author  hwq
 * @date 2015-11-20
 */

class Centercompetence_model extends CI_Model{
	public function selectCenterCompetenceMsg($centerName,$pageOnload){
		$sql = "select id,name,roadoldids 
				from gde_roadper 
				where roadoldids<>'0' ";
		$params = array();
		if (!isEmpty($centerName)) {
			$sql .= " and name like '%".$centerName."%'";
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		return $data;
	}

	/**
	 * @desc   根据roadId查询roadName
	 * @param  [type]      $v [description]
	 * @return [type]         [description]
	 */
	public function selectRoadNameById($roadOldId){
		$sql = 'select concat(newcode,shortname) roadName from gde_roadold where roadoldid='.$roadOldId;
		//$params = array($roadOldId);
		$data = $this->mysqlhelper->Query($sql);
		return isset($data[0]['roadName'])?$data[0]['roadName']:'';
	}

	/**
	 * @desc   根据id查询分中心信息
	 * @param  [type]      $id [description]
	 * @return [type]          [description]
	 */
	public function selectSubCenterMsgById($id){
		$sql = 'select * from gde_roadper where id='.$id;
		//$params = array($roadOldId);
		$data = $this->mysqlhelper->Query($sql);
		return $data[0];
	}

	/**
	 * @desc   获取所有路段信息
	 * @return [type]      [description]
	 */
	public function selectAllRoad(){
		$sql = 'select roadoldid,concat(newcode,shortname) roadname from gde_roadold order by newcode asc';
		return $data = $this->mysqlhelper->Query($sql);
	}


	public function saveSubCenterMsg($id,$centerName,$roadIds){
		if ($id == '0') {
			$insertArr = array(
				'name' => $centerName,
				'roadoldids' => $roadIds
			);
			$res = $this->mysqlhelper->Insert('gde_roadper',$insertArr);
		}else{
			$updateArr = array(
				'id' => $id,
				'name' => $centerName,
				'roadoldids' => $roadIds
			);
			$res = $this->mysqlhelper->Update('gde_roadper',$updateArr,'id');
		}
		
		return $res;
	}


	public function deleteSubCenter($deleteArr){
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_roadper where id='.$value;
			$res = $this->db->query($sql);
			//var_dump($res);exit;
			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}



}