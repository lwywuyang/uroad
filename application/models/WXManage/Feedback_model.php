<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》意见反馈控制器RoadEventLogic的模型类
 * @author hwq
 * @date 2015-11-9
 * @version 1.0
 */
class Feedback_model extends CI_Model{
	public function selectFeedbackMsg($startTime,$endTime,$keyword,$pageOnload){
		$sql = 'select remark,intime,wechatname,phone from gde_advice where 1=1';
		$params = array();
		if (!isEmpty($startTime)) {
			$startTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(intime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$endTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(intime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$endTime);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (remark like concat('%',?,'%') or wechatname like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        //$data['sql'] =  $this->db->last_query();
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}

}