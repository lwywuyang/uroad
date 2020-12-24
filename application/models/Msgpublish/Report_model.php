<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》控制器类RoadEventLogic对应的模型类
 * @author hwq
 * @version 1.0
 */
class Report_model extends CI_Model{
	//报料类型
	public function selectEventType(){
		$sql = 'select dictcode,name from gde_dict where codetype=1015';
		return $this->mysqlhelper->Query($sql);
	}

	//查询车友报料列表信息
	public function selectReportMessage($startTime,$endTime,$keyword,$eventTypeSel,$eventStatusSel,$pageOnload){
		$sql = 'select a.eventid,a.userid,a.remark,a.occplace,b.nickname,c.name eventTypeName,GROUP_CONCAT(d.filepath) filepath,a.status,a.occtime,a.casename `case`
				from gde_eventuser a
				left join gde_user b on a.userid=b.userid
				left join gde_dict c on a.eventtype=c.dictcode
				left join gde_eventuserfile d on a.eventid=d.eventuserid
				where 1=1 ';

		$params = array();
		
		if (!isEmpty($startTime)) {
			$startTime .= ' 00:00:00';
			$sql .= ' and a.occtime >= ? ';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$endTime .= ' 23:59:59';
			$sql .= ' and a.occtime <= ? ';
			array_push($params, $endTime);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (b.nickname like concat('%',?,'%') or a.remark like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}
		if (!isEmpty($eventTypeSel)) {
			$sql .= " and a.eventtype = ? ";
			array_push($params, $eventTypeSel);
		}
		if (!isEmpty($eventStatusSel)) {
			$sql .= " and a.status = ? ";
			array_push($params, $eventStatusSel);
		}

		$sql .= 'group by eventid';
		
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		
		return $data;
	}


	//根据参数改变istop的值
	public function updateEventStatus($eventid,$status,$case){
		$updateArr = array(
			'eventid' => $eventid,
			'status' => $status,
			'casename' => $case
			);
		$updateResult = $this->mysqlhelper->Update('gde_eventuser',$updateArr,'eventid');
		if ($updateResult)
			return true;
		else
			return '更新时数据操作失败!';
	}

}