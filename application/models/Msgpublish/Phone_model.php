<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc '救援电话'控制器类对应的模型类
 * @author  hwq
 */
class Phone_model extends CI_Model{
	/**
	 * @desc   '救援电话'->获取页面高速公路下拉框信息
	 * @return array 	路段下拉框信息
	 */
	public function selectRoadOldMsg(){
		$sql = 'select roadoldid,concat(newcode,shortname) shortname from gde_roadold order by newcode';
		return $data = $this->mysqlhelper->Query($sql);
	}

	//查询列表
	public function selectPhoneMsg($road,$search,$pageOnload){
		$sql = 'select id,a.remark,a.phonenum,concat(b.newcode,b.shortname) roadname,b.newcode
				from gde_roadsavephone a
				left join gde_roadold b on b.roadoldid=a.roadoldid
				where 1=1';
		$params = array();
		if (!isEmpty($road)) {
			$sql .= ' and b.roadoldid=?';
			array_push($params,$road);
		}
		if (!isEmpty($search)) {
			$sql .= " and (phonenum like '%".$search."%' )";
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	/**
	 * @desc   '救援电话'->查看操作->获取某设备详细信息
	 */
	public function selectPhoneMsgById($id){
		$sql = 'select id,remark,phonenum,roadoldid
				from gde_roadsavephone 
				where id=?';
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}


	/**
	 * @desc   '救援电话'->查看信息->修改并保存->更改数据库
	 */
	public function updatePhoneMsg($id,$roadSel,$phone,$remark){
		$updateArr = array(
			'id' => $id,
			'roadoldid' => $roadSel,
			'phonenum' => $phone,
			'remark' => $remark
			);
		return $res = $this->mysqlhelper->Update('gde_roadsavephone',$updateArr,'id');
	}


	public function insertPhoneMsg($roadSel,$phone,$remark){
		$insertArr = array(
			'roadoldid' => $roadSel,
			'phonenum' => $phone,
			'remark' => $remark
			);
		return $res = $this->mysqlhelper->Insert('gde_roadsavephone',$insertArr);
	}


	/**
	 * @desc   '救援电话'->删除
	 * @param  [array]      $deleteArr [删除记录的id数组]
	 * @return [boolean]    [标记是否删除成功]
	 */
	public function deletePhoneMsg($deleteArr){
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_roadsavephone where id='.$value;
			$this->db->query($sql);
			$num = $this->db->affected_rows();
			if ($num <= 0) {
				$this->db->trans_rollback();
				return false;
			}
			//$affectRows = $this->db->affect_rows();
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}
}