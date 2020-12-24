<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》微信菜单点击量统计控制器RoadEventLogic的模型类
 *       主要的表 - 
 * @author hwq
 * @date 2015-10-31
 * @version 1.0
 */
class Wxclick_model extends CI_Model{
	public function selectMsg_WXMenuStatistics(){
		$sql = 'select title,itype,clickcount,menustatusid,c.name menuStatusName,id
				from wx_menu a
				left join gde_dict c on a.menustatusid=c.dictcode
				order by clickcount desc';
		return $data = $this->mysqlhelper->Query($sql);
	}

	public function selectClickNumInMenu($id,$startTime,$endTime){
		$sql = 'select count(*) num 
				from wx_menuclicklog 
				where menuid=?';
		$params = array($id);
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

		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//$data['sql'] = $this->db->last_query();
		return $data[0]['num'];
	}


	public function selectMsg_HistoryStatistics($startTime,$endTime){
		$sql = "select intime,sum(m19) 'ETCProfessional',sum(m24) 'TravelServices',sum(m30) 'PrizeActivity',sum(m33) 'Tips',sum(m34) 'AboutOurselves',sum(m36) 'HistoryMessage',sum(m38) 'SnatchRedPackage' 
				from (
					select *,case when menuid=19 then clickcount else 0 end m19,
					case when menuid=24 then clickcount else 0 end m24,
					case when menuid=30 then clickcount else 0 end m30,
					case when menuid=33 then clickcount else 0 end m33,
					case when menuid=34 then clickcount else 0 end m34,
					case when menuid=36 then clickcount else 0 end m36,
					case when menuid=38 then clickcount else 0 end m38  
					from (
						SELECT count(*) clickcount,DATE(intime) intime ,menuid 
						from wx_menuclicklog
						where 1=1";
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
		$sql .= "		GROUP BY DATE(intime),menuid
					) a 
				) b group by intime 
				ORDER BY intime asc";
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//$data['sql'] = $this->db->last_query();
		return $data;
	}

}