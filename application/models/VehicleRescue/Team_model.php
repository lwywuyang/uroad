<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 中队模型
 */
class Team_model extends CI_Model{
	public function selectRoadPer(){
		$sql = 'select id,name from gde_roadper';
		return $this->mysqlhelper->Query($sql);
	}
	/**
	 * [selectTeamMsg 查询中队列表信息]
	 * @version 2016-05-16 1.0
	 * @param   [type]     $search     [description]
	 * @param   [type]     $pageOnload [description]
	 * @return  [type]                 [description]
	 */
	public function selectTeamMsg($managerSel,$typeSel,$search,$pageOnload){
		$sql = 'select a.id,a.name,a.managerid,a.phone,a.managerzone,a.seq,b.name managerName
				from gde_roadmanager a
				left join gde_roadper b on a.managerid=b.id
				where 1=1 ';
		$params = array();
		if (!isEmpty($managerSel)) {
			$sql .= " and a.type=?";
			array_push($params,$managerSel);
		}
		if (!isEmpty($typeSel)) {
			$sql .= " and a.type=?";
			array_push($params,$typeSel);
		}
		if (!isEmpty($search)) {
			$sql .= " and (a.name like '%".$search."%' or b.name like '%".$search."%' or a.managerzone like '%".$search."%')";
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	/**
	 * @desc   '设备管理'->查看操作->获取某设备详细信息
	 * @data   2015-9-23 14:13:56
	 * @param  [string]      $deviceid [设备id]
	 * @return [array]       设备信息数组
	 */
	public function selectTeamDetail($id){
		$sql = 'select a.id,a.name,a.managerid,a.phone,a.managerzone,a.seq,b.name managerName,a.type
				from gde_roadmanager a
				left join gde_roadper b on a.managerid=b.id
				where a.id=?';
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);

		return isset($data[0])?$data[0]:array();
	}

	/**
	 * [updateTeamMsg 更新中队信息]
	 * @version 2016-05-17 1.0
	 * @param   [type]     $id   [description]
	 * @param   [type]     $data [description]
	 * @return  [type]           [description]
	 */
	public function updateTeamMsg($data){

		return $res = $this->mysqlhelper->Update('gde_roadmanager',$data,'id');

		if ($res === true)
			return true;
		else
			return '保存修改后的数据失败!';
	}


	public function insertTeamMsg($data){
		
		return $res = $this->mysqlhelper->Insert('gde_roadmanager',$data);

		if ($res === true)
			return true;
		else
			return '保存新增的数据失败!';
	}


	/**
	 * @desc   '设备维护'->删除
	 * @data   2015-9-24 09:35:37
	 * @param  [array]      $deleteArr [删除记录的id数组]
	 * @return [boolean]    [标记是否删除成功]
	 */
	public function deleteTeamMsg($deleteArr){
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_roadmanager where id='.$value;
			$this->db->query($sql);
			$num = $this->db->affected_rows();
			if ($num <= 0) {
				$this->db->trans_rollback();
				return '执行删除操作失败!';
			}
			//$affectRows = $this->db->affect_rows();
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}
}