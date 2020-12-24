<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》控制器类RoadEventLogic对应的模型类
 * @author hwq
 * @date 2015-11-9
 * @version 1.0
 */
class CheckPending_model extends CI_Model{
	/**
	 * @desc   '信息发布'-》页面载入时加载路段下拉框信息
	 * @return array 	所有路段信息
	 */
	public function selectAllRoad(){
		$sql = 'select roadoldid,concat(newcode,shortname,roadname) roadName from gde_roadold';
		$sql .= ' order by newcode';
		
		$data = $this->mysqlhelper->Query($sql);
		//$data['sql'] = $this->db->last_query();
		return $data;
	}

	/**
	 * @desc   '信息发布'->'事件详细信息'页面->大类下拉框信息
	 * @return [type]      所有大类信息
	 */
	public function selectAllEventType(){
		$sql = "select dictcode,name 
				from gde_dict
				where codetype='1006'
				order by seq asc";
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '信息发布'->'事件详细信息'页面->根据大类获取相应小类下拉框信息
	 * @data   2015-10-12 10:05:41
	 * @return [type]      大类下的小类信息
	 */
	public function selectEventCause($eventType){
		$sql = "select dictcode,name 
				from gde_dict
				where codetype=?
				order by seq asc";
		$params = array($eventType);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   '信息发布'->'事件详细信息'页面->获取交通状况单选信息
	 * @data   2015-10-12 15:49:27
	 * @return [type]      [description]
	 */
	public function selectRoadTrafficColor(){
		$sql = "select dictcode,name 
				from gde_dict
				where codetype='1008'
				order by seq asc";
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   '信息发布'->'事件详细信息'页面->获取起始站和终点站的下拉框的内容
	 * @data   2015-10-12 18:26:24
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectAlongStation($eventid){
		$sql = 'select b.poiid,b.name
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				left join gde_eventtraffic c on a.roadlineid=c.roadoldid
				where c.eventid=? and a.direction=?
				order by a.seq asc';
		//两个方向
		$params1 = array($eventid,1);
		$params2 = array($eventid,2);
		$data['direction1'] = $this->mysqlhelper->QueryParams($sql,$params1);
		$data['direction2'] = $this->mysqlhelper->QueryParams($sql,$params2);
		$data['direction1_json'] = json_encode($data['direction1']);
		$data['direction2_json'] = json_encode($data['direction2']);
		return $data;
	}


	/**
	 * @desc   获取站点的桩号
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectStationCode($eventid){
		$sql = 'select b.poiid,b.stationcode
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				left join gde_eventtraffic c on a.roadlineid=c.roadoldid
				where c.eventid=?
				order by a.seq asc';
		$params = array($eventid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	 	//json_encode($data);
	}

	public function selectStationcodeByRoad($roadoldid){
		$sql = 'select b.poiid,b.stationcode
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				where a.roadlineid=? and a.direction=1
				order by a.seq asc';
		$params = array($roadoldid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return json_encode($data);
	}


	/**
	 * @desc   '信息发布'->读取页面表格信息数据
	 * @data   2015-10-10 18:42:56
	 * @param  [type]      $roadId        [description]
	 * @param  [type]      $checkboxValue [description]
	 * @param  [type]      $pageOnload    分页
	 * @return [type]                     [description]
	 */
	public function selectInfoByParams($eventType,$roadId,$status,$keyword,$pageOnload){
		$sql = 'SELECT * FROM etc_member WHERE `status`=2 ';

		$params = array();
		if (!isEmpty($roadId)) {
			$sql .= ' and roadoldid=? ';
			array_push($params,$roadId);
		}
		if (!isEmpty($status)) {
			$sql .= ' and a.eventstatus=? ';
			array_push($params, $status);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (cardno like concat('%',?,'%') or carno like concat('%',?,'%')) ";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		$data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		
		return $data;
	}


	//根据参数改变istop的值
	public function updateTopStatus($eventid,$toptag){
		$selectResult[0]['topNum'] = 0;
		if ($toptag == '1') {
			$sql = 'select count(*) topNum from gde_eventtraffic where istop=1';
			$selectResult = $this->mysqlhelper->Query($sql);
		}
		if ($selectResult[0]['topNum'] >= 3) {//置顶数不能大于3
			return '抱歉,只允许最多3条置顶信息!';
		}

		$updateArr = array('eventid'=>$eventid,'istop'=>$toptag);
		$updateResult = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
		if ($updateResult)
			return true;
		else
			return '更新时数据操作失败!';
	}



	/**
	 * @desc   '信息发布'->点击查看->获取查看的事件信息的详细内容,用于'信息详细内容'页面
	 * @data   2015-10-12 09:29:17
	 * @param  [type]      $eventid 点击查看的事件的id
	 * @return [type]               [description]
	 */
	public function selectDetailMsgById($cardno){
		$sql = 'SELECT * FROM base_ytk WHERE `cardid`=? ';

		$params = array($cardno);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   '信息发布'->查看详细->获取页面顶部站点地图数据
	 *         '详细信息页面'->改变方向选择项->重新获取站点地图数据
	 * @data   2015-10-12 09:16:04
	 * @param  string      $roadid      路段id
	 * @param  string      $directionno 方向id
	 * @return [type]                   [description]
	 */
	public function selectRoadTableMsg($roadoldid,$directionno){
		$sql = 'select a.roadlineid,c.shortname,a.stationid,b.name,b.miles,a.seq
				from gde_roadlinestation a 
				left join gde_roadpoi b on a.stationid=b.poiid
				left join gde_roadold c on a.roadlineid=c.roadoldid
				where a.roadlineid=? and a.direction=?';
		$params = array($roadoldid,$directionno);
		
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//$data['sql'] = $this->db->last_query();
		return $data;
	}


	/**
	 * @desc   '信息发布'->点击查看->'信息发布详情'->获取事件监控信息
	 * @data   datatime
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectControlMsg($eventid){
		$sql = 'select eventcomment,eventid,eventupdatetime updatetime,eventstatus from gde_controllereventrecord where eventid=? order by eventupdatetime desc';
		$params = array($eventid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   '信息发布'->点击查看->'信息发布详情'->获取事件进展信息
	 * @data   datatime
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectProgressMsg($eventid){
		$sql = 'select * from gde_eventtrack where eventid=? and status=1 order by modifytime desc';
		$params = array($eventid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


	/**
	 * @desc   '信息发布'->点击查看->'信息发布详情'->更新进展->插入新进展信息到数据库
	 * @data   datatime
	 * @param  [type]      $eventid          [description]
	 * @param  [type]      $newEventProgress [description]
	 * @return [type]                        [description]
	 */
	public function insertEventProgress($eventid,$newEventProgress){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventid,
			'track' => $newEventProgress,
			'intime' => date('Y-m-d H:i:s'),
			'modifytime' => date('Y-m-d H:i:s')
			);
		return $res = $this->mysqlhelper->Insert('gde_eventtrack',$insertArr);
	}


	/**
	 * @desc   '事件发布'页面->'事件详细'页面->'事件进展'模块->点击修改->获取该进展信息
	 * @data   2015-10-19 14:43:06
	 * @param  string      $trackId 	事件进展表gde_eventtrack主键
	 * @return array       
	 */
	public function selectThisEventProgressMsg($trackId){
		$sql = 'select id,track from gde_eventtrack where id=? ';
		$params = array($trackId);
		return $res = $this->mysqlhelper->QueryParams($sql,$params);
	}


	/**
	 * @desc   '事件发布'页面->'事件详细'页面->'事件进展'模块->点击修改->'修改进展页面'->保存->保存修改信息
	 * @data   2015-10-19 14:58:37
	 */
	public function updateEventProgress($id,$newTrack){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'id' => $id,
			'track' => $newTrack,
			'modifytime' => date('Y-m-d H:i:s')
			);
		return $res = $this->mysqlhelper->Update('gde_eventtrack',$updateArr,'id');
	}

	/**
	 * @desc   '事件发布'页面->'事件详细'页面->'事件进展'模块->点击删除->删除该信息
	 * @data   2015-10-19 15:26:19
	 */
	public function deleteEventProgress($id){
		$sql =  'delete from gde_eventtrack where id=?';
		$params = array($id);

		$this->db->query($sql,$params);
		return ($this->db->affected_rows() > 0)?true:false;
	}

	/**
	 * @desc   '事件发布'页面->'事件详情'页面->获取图集数据
	 * @data   2015-10-20 09:17:32
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectImgMsg($eventid){
		$sql = "select imgurl from gde_eventtraffic where eventid='".$eventid."'";
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '事件发布'->'事件详情'->发布信息->保存
	 * @return [type]                   [description]
	 */
	public function insertToSavePushInfo($eventId,$eventType,$roadoldid,$occtime,$planOverTime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$startStake,$endStake,$title,$pushInfo,$coorAndSplitCode,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventId,
			'eventtype' => $eventType,
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'planovertime' => $planOverTime,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStation,
			'endnodeid' => $endStationId,
			'endnodename' => $endStation,
			'startstake' => $startStake,
			'endstake' => $endStake,
			'title' => $title,
			'reportout' => $pushInfo,
			'coor_x' => $coorAndSplitCode['x'],
			'coor_y' => $coorAndSplitCode['y'],
			'trafficsplitcode' => $coorAndSplitCode['splitCode'],
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'intime' => date('Y-m-d H:i:s'),
			'updatetime' => date('Y-m-d H:i:s'),
			'eventstatus' => 1012004,
			'infosource' => 1014001
			);
		return $res = $this->mysqlhelper->Insert('gde_eventtraffic',$insertArr);
	}

	/**
	 * @desc   '事件发布'->'事件详情'->发布信息->保存
	 * @return [type]                   [description]
	 */
	//$eventId,$eventType,$roadoldid,$occtime,$planOverTime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$startStake,$endStake,$title,$pushInfo,$coorAndSplitCode,$EmplId,$EmplName
	public function updateToSavePushInfo($eventId,$eventType,$roadoldid,$occtime,$planOverTime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$startStake,$endStake,$title,$pushInfo,$coorAndSplitCode,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventId,
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStation,
			'endnodeid' => $endStationId,
			'endnodename' => $endStation,
			'reportout' => $pushInfo,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'updatetime' => date('Y-m-d H:i:s'),
			'eventstatus' => 1012004,
			'startstake' => $startStake,
			'endstake' => $endStake,
			'title' => $title,
			'planovertime' => $planOverTime,
			'coor_x' => $coorAndSplitCode['x'],
			'coor_y' => $coorAndSplitCode['y'],
			'trafficsplitcode' => $coorAndSplitCode['splitCode'],
			);
		//'plancontype' => $bigType,
		//'plancondetailtype' => $smallType
		return $res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}

	/**
	 * @desc   '事件详情'->结束当前事件->更新数据库
	 * @param  [type]      $eventid     [description]
	 * @param  [type]      $operateId   [description]
	 * @param  [type]      $operateName [description]
	 * @return [type]                   [description]
	 */
	public function updateStatusToOff($eventid,$operateId,$operateName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012005,
			'operatorid' => $operateId,
			'operatorname' => $operateName,
			'updatetime' => date('Y-m-d H:i:s'),
			'cancelpersonid' => $operateId,
			'cancelperson' => $operateName,
			'canceltime' => date('Y-m-d H:i:s'),
			'realovertime' => date('Y-m-d H:i:s')
			);
		return $res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}

	/**
	 * @desc   获取大类,用于'事件详情'的新增页面
	 * @param  [type]      $eventType [description]
	 * @return [type]                 [description]
	 */
	public function selectEventType($eventType){
		$sql = 'select dictcode,name from gde_dict where dictcode=?';
		$params = array($eventType);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   新建事件->改变路段下拉框选择,获取该高速的起始站和结束站内容
	 * @param  [type]      $roadoldid [description]
	 * @return [type]                 [description]
	 */
	public function selectStationByRoad($roadoldid){
		$sql = 'select b.poiid,b.name
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				where a.roadlineid=? and a.direction=?
				order by a.seq asc';
		//两个方向
		$params1 = array($roadoldid,1);
		$params2 = array($roadoldid,2);
		$data['direction1'] = $this->mysqlhelper->QueryParams($sql,$params1);
		$data['direction2'] = $this->mysqlhelper->QueryParams($sql,$params2);
		$data['direction1_json'] = json_encode($data['direction1']);
		$data['direction2_json'] = json_encode($data['direction2']);
		return $data;
	}

	/**
	 * @desc   新建事件->改变路段下拉框选择,获取该高速的方向
	 * @param  [type]      $roadoldid [description]
	 * @return [type]                 [description]
	 */
	public function selectDirectionByRoad($roadoldid){
		$sql = 'select direction1,direction2 from gde_roadold where roadoldid=?';
		$params = array($roadoldid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   保存状态为未审核的事件
	 * @param  [type]      $roadoldid     [description]
	 * @param  [type]      $occtime       [description]
	 * @param  [type]      $eventType     [description]
	 * @param  [type]      $eventCause    [description]
	 * @param  [type]      $direction     [description]
	 * @param  [type]      $directionName [description]
	 * @param  [type]      $TrafficColor  [description]
	 * @param  [type]      $startStation  [description]
	 * @param  [type]      $endStation    [description]
	 * @param  [type]      $pushInfo      [description]
	 * @return [type]                     [description]
	 */
	public function insertNewInfoMsg($eventid,$roadoldid,$occtime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$pushInfo,$EmplId,$EmplName,$startStake,$endStake,$infoTitle,$planOverTime,$bigType,$smallType,$eventType){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventid,
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'eventtype' => $eventType,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStation,
			'endnodeid' => $endStationId,
			'endnodename' => $endStation,
			'reportout' => $pushInfo,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'intime' => date('Y-m-d H:i:s'),
			'updatetime' => date('Y-m-d H:i:s'),
			
			'eventstatus' => 1012004,
			'startstake' => $startStake,
			'endstake' => $endStake,
			'title' => $infoTitle,
			'planovertime' => $planOverTime,
			'plancontype' => $bigType,
			'plancondetailtype' => $smallType,
		);
		return $this->mysqlhelper->Insert('gde_eventtraffic',$insertArr);
	}

	public function deleteEventMsg($deleteArr,$operateId,$operateName){
		$this->db->trans_begin();
		date_default_timezone_set('PRC');
		//$sql = 'delete from gde_eventtraffic where eventid=?';
		foreach ($deleteArr as $value) {
			$updateArr = array(
				'eventid' => $value,
				'eventstatus' => 1012005,
				'operatorid' => $operateId,
				'operatorname' => $operateName,
				'updatetime' => date('Y-m-d H:i:s'),
				'cancelpersonid' => $operateId,
				'cancelperson' => $operateName,
				'canceltime' => date('Y-m-d H:i:s'),
				'realovertime' => date('Y-m-d H:i:s')
			);
			$result = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
			/*$params = array($value);
			$result = $this->mysqlhelper->ExecuteSqlParams($sql,$params);
			unset($params);*/

			if (!$result) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

	//直接发布事件
	public function insertToPushInfo($eventid,$roadoldid,$occtime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$pushInfo,$EmplId,$EmplName,$startStake,$endStake,$infoTitle,$planOverTime,$bigType,$smallType,$eventType){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012004,
			'updatetime' => date('Y-m-d H:i:s'),
			'firstreleasepersonid' => $EmplId,
			'firstreleaseperson' => $EmplName,
			'firstreleasetime' => date('Y-m-d H:i:s'),
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStation,
			'endnodeid' => $endStationId,
			'endnodename' => $endStation,
			'reportout' => $pushInfo,
			'startstake' => $startStake,
			'endstake' => $endStake,
			'title' => $infoTitle,
			'planovertime' => $planOverTime,
			'plancontype' => $bigType,
			'plancondetailtype' => $smallType,
			'eventtype' => $eventType,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName
		);
		return $result = $this->mysqlhelper->Insert('gde_eventtraffic',$insertArr);
	}

	//通过审核->发布
	public function updateToPushInfo($eventid,$roadoldid,$occtime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$pushInfo,$EmplId,$EmplName,$startStake,$endStake,$infoTitle,$planOverTime,$bigType,$smallType){
		date_default_timezone_set('PRC');
		
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012004,
			'updatetime' => date('Y-m-d H:i:s'),
			'firstreleasepersonid' => $EmplId,
			'firstreleaseperson' => $EmplName,
			'firstreleasetime' => date('Y-m-d H:i:s'),
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStation,
			'endnodeid' => $endStationId,
			'endnodename' => $endStation,
			'reportout' => $pushInfo,
			'startstake' => $startStake,
			'endstake' => $endStake,
			'title' => $infoTitle,
			'planovertime' => $planOverTime,
			'plancontype' => $bigType,
			'plancondetailtype' => $smallType
		);
		return $result = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}


	//取消发布
	public function updateToCancelPushInfo($eventid,$EmplId,$EmplName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012005,
			'canceltime' => date('Y-m-d H:i:s'),
			'cancelpersonid' => $EmplId,
			'cancelperson' => $EmplName
		);
		return $result = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}



	/**
	 * @desc   根据poiid查询经纬度,用于计算事发地点的经纬度估计值
	 * @param  [type]      $stationId [description]
	 * @return [type]                 [description]
	 */
	public function selectCoor($stationId){
		$sql = 'select coor_x,coor_y from gde_roadpoi where poiid=?';//.$stationId
		$params = array($stationId);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}

	/**
	 * @desc   根据poiid查询stationcode,用于拼接事件的trafficsplitecode
	 * @param  [type]      $stationId [description]
	 * @return [type]                 [description]
	 */
	public function selectSplitCode($stationId){
		$sql = 'select stationcode from gde_roadpoi where poiid='.$stationId;
		$data = $this->mysqlhelper->Query($sql);
		return $data[0];
	}

	/**
	 * 新增保存
	 */
	public function insertRoadPoiMsg($content){
		$cardid = $content['cardid'];
		$this->mysqlhelper->Insert('base_ytk',$content);
		$num = $this->db->affected_rows();
		if ($num >= 0) {
			// $this->db->update('etc_member',array('status'=>'1'),array('cardno' => $cardid));
			return true;
		}else{
			return false;
		}

	}

	/**
	 * 新增修改
	 */
	public function updateRoadPoiMsg($cardid,$content){
		$this->db->update('base_ytk',$content,array('cardid' => $cardid));
		$num = $this->db->affected_rows();
		if ($num >= 0) {
			// $this->db->update('etc_member',array('status'=>'1'),array('cardno' => $cardid));
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 删除审核不通过的信息
	 */
	public function delCheckPending($id){
		$data = $this->db->delete('etc_member',array('id'=>$id));
		return $data;
	}
}