<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》爆料信息控制器RoadEventLogic的模型类
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class Reportmessage_model extends CI_Model{
	/**
	 * @desc   获取下拉框信息
	 * @return [type]      [description]
	 */
	public function selectEventTypeMsg(){
		$sql = "select dictcode,name from gde_dict where codetype='1015' ";
		return $data = $this->mysqlhelper->Query($sql);
	}


	public function selectReportMsg($startTime,$endTime,$keyword,$typeSel,$pageOnload){
		$sql = 'select eventid,a.remark,occplace,eventtype,latitude,longitude,filename,b.nickname,c.name typeName,occtime
				from gde_eventuser a 
				left join gde_user b on a.userid=b.userid
				left join gde_dict c on a.eventtype=c.dictcode
				where 1=1';
		$params = array();
		if (!isEmpty($startTime)) {
			$startTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(a.occtime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$endTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(a.occtime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$endTime);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (a.remark like concat('%',?,'%') or a.occplace like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		if (!isEmpty($typeSel)) {
			$sql .= ' and a.eventtype=?';
			array_push($params,$typeSel);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        //$data['sql'] =  $this->db->last_query();
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}

}