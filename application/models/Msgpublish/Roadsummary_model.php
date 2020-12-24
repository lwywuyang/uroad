<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》路况综述的控制器类RoadEventLogic对应的模型类
 * @author hwq
 * @version 1.0
 */
class Roadsummary_model extends CI_Model{
	/**
	 * [getNowTime 获取现在数据库时间]
	 * @version 2016-05-13 1.0
	 * @return  [type]     [description]
	 */
	private function getNowTime(){
		$sql = 'select now() now';
		$time = $this->mysqlhelper->Query($sql);
		return $time[0]['now'];
	}

	/**
	 * @desc   '路况综述'->获取路况综述页面信息
	 * @return [type]                  [description]
	 */
	public function selectRoadSummary($status,$keyword,$pageOnload){
		$sql = 'select *
				from gde_eventtraffic_summarize 
				where 1=1';
		$params = array();
		if (!isEmpty($status)) {
			$sql .= ' and a.eventstatus=?';
			array_push($params,$status);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (a.title like concat('%',?,'%') or a.reportinfo like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

	/**
	 * @desc   '路况综述'->查看综述详细内容->获取该数据的详细信息
	 * @return [type]               [description]
	 */
	public function selectRoadSummaryById($eventid){
		$sql = 'select a.*,b.name statusName
				from gde_eventtraffic_summarize a 
				left join gde_dict b on a.eventstatus = b.dictcode
				where eventid=?';
		$params = array($eventid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return isset($data[0])?$data[0]:array();
	}

	/**
	 * @desc   '路况综述'->新增综述->保存新增数据
	 * @return [type]                  [description]
	 */
	public function insertNewRoadSummary($title,$intime,$reportinfo,$jpgurl,$status,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'title' => $title,
			'reportinfo' => $reportinfo,
			'eventtype' => 1006010,
			'eventstatus' => $status,
			'intime' => $intime,
			'updatetime' => $this->getNowTime(),
			'imgurl' => $jpgurl,
			'operatorid' => $EmplId,
			'operator' => $EmplName
		);

		return $res = $this->mysqlhelper->Insert('gde_eventtraffic_summarize',$insertArr);
	}

	/**
	 * @desc   '路况综述'->查看综述->保存修改后数据
	 * @return [type]                  [description]
	 */
	public function updateRoadSummary($eventid,$title,$intime,$reportinfo,$jpgurl,$status,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'title' => $title,
			'intime' => $intime,
			'reportinfo' => $reportinfo,
			'imgurl' => $jpgurl,
			'updatetime' => $this->getNowTime(),
			'operatorid' => $EmplId,
			'operator' => $EmplName
		);

		if ($status != '0') {
			$updateArr['eventstatus'] = $status;
		}

		return $res = $this->mysqlhelper->Update('gde_eventtraffic_summarize',$updateArr,'eventid');
	}


	public function deleteRoadSummaryMsg($deleteArr){
		$this->db->trans_begin();
		// $sql = 'delete from gde_eventtraffic where eventid=?';
		$sql = "delete from gde_eventtraffic_summarize where eventid=?";
		foreach ($deleteArr as $value) {
			$params = array($value);
			$result = $this->mysqlhelper->ExecuteSqlParams($sql,$params);
			unset($params);

			if (!$result) {
				$this->db->trans_rollback();
				return '删除数据失败!';
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

}