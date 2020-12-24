<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》意见反馈控制器RoadEventLogic的模型类
 * @author hwq
 * @date 2015-11-9
 * @version 1.0
 */
class Rescueorder_model extends CI_Model{
	public function selectAllRoad(){
		$sql = 'select roadoldid,concat(newcode,shortname) roadname from gde_roadold order by newcode asc,roadoldid asc';
		return $data = $this->mysqlhelper->Query($sql);
	}
	/**
	 * @desc   获取工单状态下拉框内容
	 */
	public function selectOrderStatus(){
		$sql = 'select dictcode,name from jy_dict where codetype=201';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   查询救援工单列表内容
	 * @param  [type]      $road       [description]
	 * @param  [type]      $orderStatus  [description]
	 * @param  [type]      $orderNo    [description]
	 * @param  [type]      $startTime  [description]
	 * @param  [type]      $endTime    [description]
	 * @param  [type]      $pageOnload [description]
	 * @return [array]                  [列表内容]
	 */
	public function selectRescueOrderMsg($road,$orderStatus,$orderNo,$startTime,$endTime,$pageOnload){
		$sql = 'select a.rescueid,
						a.rescueno,
						a.propertyid,
						b.name propertyname,
						a.callhelpphone,
						a.callhelpname,
						a.roadno,
						a.miles,
						concat(c.newcode,c.shortname) roadname,
						c.shortname,
						c.newcode,
						a.directionno,
						a.directionname,
						a.recename,
						a.recetime,
						a.sourceid,
						d.name sourcename,
						a.status,
						e.name statusname,
						a.created,
						a.operatorname,
						a.occtime,
						a.inhighway,
						a.infoman,
						a.infomanid,
						i.name rescuetypeidname,
						a.isduplicate,
						a.callhelpvehicle,
						a.photourl
					FROM
						jy_rescue a   
					left JOIN jy_dict b ON a.propertyid = b.dictcode
					left join jy_dict d  on a.sourceid=d.dictcode
					left join jy_dict e  on a.status=e.dictcode
					left join gde_roadold c on a.roadno= c.roadoldid
					left join jy_dict i  on a.rescuetypeid=i.dictcode
					where a.status=20101 and a.infomanid is null';
		$params = array();
		if (!isEmpty($road)) {
			$sql .= ' and c.roadoldid=?';
			array_push($params,$road);
		}
		if (!isEmpty($orderStatus)) {
			$sql .= ' and a.status=?';
			array_push($params,$orderStatus);
		}
		if (!isEmpty($orderNo)) {
			$sql .= ' and a.rescueno=?';
			array_push($params,$orderNo);
		}
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
		/*if (!isEmpty($keyword)) {
			$sql .= " and (remark like concat('%',?,'%') or wechatname like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}*/
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        //$data['sql'] =  $this->db->last_query();
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}

}