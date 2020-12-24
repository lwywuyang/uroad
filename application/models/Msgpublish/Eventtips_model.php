<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》易堵预报的控制器类RoadEventLogic对应的模型类
 * @author hwq
 * @version 1.0
 */
class Eventtips_model extends CI_Model{
	/**
	 * @desc   '易堵预报'->获取易堵预报页面信息
	 * @return [type]                  [description]
	 */
	public function selectTipsByParams($eventType,$roadId,$status,$keyword,$pageOnload){
		$sql = 'select eventid,reportout,occtime,updatetime,b.name eventStatus,operatorname
				from gde_eventtraffic a 
				left join gde_dict b on a.eventstatus=b.dictcode
				where eventtype=?';
		$params = array($eventType);
		if (!isEmpty($roadId)) {
			$sql .= ' and a.roadoldid=?';
			array_push($params,$roadId);
		}
		if (!isEmpty($status)) {
			$sql .= ' and a.eventStatus=?';
			array_push($params,$status);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (a.reportout like concat('%',?,'%') or a.operatorname like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

	/**
	 * @desc   '易堵预报'->查看预报详细内容->获取该数据的详细信息
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectTipMsgById($eventid){
		$sql = 'select eventid,occtime,reportout,roadoldid from gde_eventtraffic where eventid=?';
		$params = array($eventid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   '易堵预报'->新增预报->保存新增数据
	 * @param  [type]      $occtime    [description]
	 * @param  [type]      $tipContent [description]
	 * @return [type]                  [description]
	 */
	public function insertNewTips($eventid,$roadSel,$occtime,$tipContent,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventid,
			'roadoldid' => $roadSel,
			'occtime' => $occtime,
			'reportout' => $tipContent,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'intime' => date('Y-m-d H:i:s'),
			'updatetime' => date('Y-m-d H:i:s'),
			'firstreleasepersonid' => $EmplId,
			'firstreleaseperson' => $EmplName,
			'firstreleasetime' => date('Y-m-d H:i:s'),
			'eventtype' => 1006003,
			'eventstatus' => 1012004
		);
		
		$res = $this->mysqlhelper->Insert('gde_eventtraffic',$insertArr);

		if ($res)
			return true;
		else
			return '保存新增的易堵预报信息失败!';
	}

	/**
	 * @desc   '易堵预报'->查看预报->保存修改后数据
	 * @param  [type]      $occtime    [description]
	 * @param  [type]      $tipContent [description]
	 * @return [type]                  [description]
	 */
	public function updateTips($eventid,$roadSel,$occtime,$tipContent,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'roadoldid' => $roadSel,
			'occtime' => $occtime,
			'reportout' => $tipContent,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'updatetime' => date('Y-m-d H:i:s')
		);
		
		$res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');

		if ($res)
			return true;
		else
			return '修改易堵预报信息失败!';
	}


	public function deleteTipsMsg($deleteArr){
		$this->db->trans_begin();
		// $sql = 'delete from gde_eventtraffic where eventid=?';
		$sql="update gde_eventtraffic set eventstatus='1012005' where eventid=?";
		foreach ($deleteArr as $value) {
			$params = array($value);
			$result = $this->mysqlhelper->ExecuteSqlParams($sql,$params);
			unset($params);

			if (!$result) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

}