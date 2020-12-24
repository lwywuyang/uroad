<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》微信菜单点击量统计控制器RoadEventLogic的模型类
 *       主要的表 - 
 * @author hwq
 */
class Allstatistics_model extends CI_Model{
	public function selectMsg_WXMenuStatistics(){
		/*$sql = 'select title,itype,clickcount,menustatusid,c.name menuStatusName
				from wx_menu a
				left join gde_dict c on a.menustatusid=c.dictcode
				order by clickcount desc';*/
		$sql = 'select title,itype,clickcount
				from wx_menu 
				order by clickcount desc';
		return $data = $this->mysqlhelper->Query($sql);
	}


	public function selectMsg_HistoryStatistics($startTime,$endTime){
		/*$sql = "select intime,sum(m19) 'ETCProfessional',sum(m24) 'TravelServices',sum(m30) 'PrizeActivity',sum(m33) 'Tips',sum(m34) 'AboutOurselves',sum(m36) 'HistoryMessage',sum(m38) 'SnatchRedPackage' 
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
						where 1=1";*/

		$sql = 'select intime,sum(m2) interactive,sum(m3) APPdownload,sum(m4) roadCheck,sum(m5) congestionIndex,sum(m6) myNeighborhood,sum(m7) report,sum(m14) service,sum(m25) checkIllegal,sum(m27) community,sum(m29) personalCenter,sum(m31) mall
				from (
					select *,case when menuid=2 then clickcount else 0 end m2,
					case when menuid=3 then clickcount else 0 end m3,
					case when menuid=4 then clickcount else 0 end m4,
					case when menuid=5 then clickcount else 0 end m5,
					case when menuid=6 then clickcount else 0 end m6,
					case when menuid=7 then clickcount else 0 end m7,
					case when menuid=14 then clickcount else 0 end m14,
					case when menuid=25 then clickcount else 0 end m25,
					case when menuid=27 then clickcount else 0 end m27,
					case when menuid=29 then clickcount else 0 end m29,
					case when menuid=31 then clickcount else 0 end m31 
					from (
						SELECT count(*) clickcount,date(intime) intime ,menuid 
						from wx_menuclicklog
						where 1=1';
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
		$sql .= '		group by date(intime),menuid
					) a 
				) b group by intime 
				order by intime asc';
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//$data['sql'] = $this->db->last_query();
		return $data;
	}

}