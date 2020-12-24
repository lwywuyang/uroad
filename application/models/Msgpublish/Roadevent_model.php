<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》突发事件,计划事件,管制事件,出行提示,实时路况共同的控制器类RoadEventLogic对应的模型类
 * @author hwq
 * @date 2015-10-21
 * @version 1.0
 */
class Roadevent_model extends CI_Model{
	//利用mysql语句获取now()时间
	private function getNowTime(){
		$sql = 'select now() now';
		$time = $this->mysqlhelper->Query($sql);
		return $time[0]['now'];
	}

	/**
	 * @desc   '信息发布'-》页面载入时加载路段下拉框信息
	 * @return array 	所有路段信息
	 */
	public function selectAllRoad(){
		$this->load->helper('budata');
		$budata = budatabycode('M0001');

		$sql = 'select roadoldid,concat(newcode,shortname) roadName from gde_roadold';
		if ($budata !== '0') {//省中心
			$sql .= ' where roadoldid in ('.$budata.')';
		}
		$sql .= ' order by newcode';
		
		$data = $this->mysqlhelper->Query($sql);
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
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectAlongStation($eventid){
		$sql = "select b.poiid,CONCAT(b.name,'(K',REPLACE(b.miles,'.','+'),')') name
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				left join gde_eventtraffic c on a.roadlineid=c.roadoldid
				where c.eventid=? and a.direction=?
				order by a.seq asc";
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
	 * @desc   '信息发布'->读取页面表格信息数据
	 * @param  [type]      $roadId        [description]
	 * @param  [type]      $checkboxValue [description]
	 * @param  [type]      $pageOnload    分页
	 * @return [type]                     [description]
	 */
	public function selectInfoByParams($roadId,$eventType,$status,$keyword,$startTime,$endTime,$pageOnload){
		$this->load->helper('budata');
		$budata = budatabycode('M0001');
		$sql = "select eventid,b.picurl,b.roadoldid,b.shortname,d.name eventCauseName,reportout,f.name eventstatusName,a.intime,operatorname,concat(occtime,'(',firstreleaseperson,')') occtime,concat(updatetime,'(',operatorname,')') updatetime,g.name eventTypeName,concat(checktime,'(',checkperson,')') checktime,concat(readtime,'(',readperson,')') readtime,concat(canceltime,'(',cancelperson,')') canceltime
				from gde_eventtraffic a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_dict d on a.eventcauseno=d.dictcode
				left join gde_dict f on a.eventstatus=f.dictcode
				left join gde_dict g on a.eventtype=g.dictcode
				where 1=1 and eventtype=?";
				// and a.roadoldid in ('.$budata.')
		if ($budata !== '0') {//省中心
			$sql .= ' and a.roadoldid in ('.$budata.')';
		}
        $params = array($eventType);
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


		if (!isEmpty($roadId)) {
			$sql .= ' and a.roadoldid=? ';
			array_push($params,$roadId);
		}
		if (!isEmpty($status)) {
			if ($status == '1012004') {//发布中的事件包括1012004发布中,1012005结束待审核
				$sql .= ' and (a.eventstatus=1012004 or a.eventstatus=1012005) ';
			}else{
				$sql .= ' and a.eventstatus=? ';
				array_push($params, $status);
			}
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (a.reportout like concat('%',?,'%') or a.title like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		$data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		
		return $data;
	}


	/**
	 * @desc   '信息发布'->操作信息的是否置顶,保存数据库
	 * @param  [type]      $eventid [description]
	 * @param  [type]      $top     [description]
	 * @return [type]               [description]
	 */
	public function updateEventTraffic($eventid,$top){
		$updateArr = array(
			'eventid' => $eventid,
			'istop' => $top
			);
		return $res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}


	/**
	 * @desc   '信息发布'->点击简图,获取简图展示
	 * @return [type]      [description]
	 */
	public function selectSmallPic(){
		$sql = 'select filename from gde_simplemap';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   '信息发布'->点击查看->获取查看的事件信息的详细内容,用于'信息详细内容'页面
	 * @param  [type]      $eventid 点击查看的事件的id
	 * @return [type]               [description]
	 */
	public function selectDetailMsgById($eventid){
		$this->load->helper('budata');
		$budata = budatabycode('M0001');
		$sql = 'select eventid,a.roadoldid,b.shortname,occtime,eventtype,c.name eventTypeName,eventcauseno,d.name eventCauseName,directionno,e.name roadTrafficName,occplace,a.startnodename,a.endnodename,b.direction1,b.direction2,startnodeid,endnodeid,roadtrafficcolor,reportout,a.eventstatus,planovertime,realovertime,TIMESTAMPDIFF(SECOND,occtime,realovertime) duration
				from gde_eventtraffic a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_dict c on a.eventtype=c.dictcode
				left join gde_dict d on a.eventcauseno=d.dictcode
				left join gde_dict e on a.roadtrafficcolor=e.dictcode
				where eventid=? ';
				//and a.roadoldid in ('.$budata.')
		if ($budata !== '0') {//省中心
			$sql .= ' and a.roadoldid in ('.$budata.')';
		}

		$params = array($eventid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   '信息发布'->查看详细->获取页面顶部站点地图数据
	 *         '详细信息页面'->改变方向选择项->重新获取站点地图数据
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
	 * @param  [type]      $eventid          [description]
	 * @param  [type]      $newEventProgress [description]
	 * @return [type]                        [description]
	 */
	public function insertEventProgress($eventid,$newEventProgress){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventid,
			'track' => $newEventProgress,
			'intime' => $this->getNowTime(),
			'modifytime' => $this->getNowTime()
			);
		return $res = $this->mysqlhelper->Insert('gde_eventtrack',$insertArr);
	}


	/**
	 * @desc   '事件发布'页面->'事件详细'页面->'事件进展'模块->点击修改->获取该进展信息
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
	 */
	public function updateEventProgress($id,$newTrack){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'id' => $id,
			'track' => $newTrack,
			'modifytime' => $this->getNowTime()
			);
		return $res = $this->mysqlhelper->Update('gde_eventtrack',$updateArr,'id');
	}

	/**
	 * @desc   '事件发布'页面->'事件详细'页面->'事件进展'模块->点击删除->删除该信息
	 */
	public function deleteEventProgress($id){
		$sql =  'delete from gde_eventtrack where id=?';
		$params = array($id);

		$this->db->query($sql,$params);
		return ($this->db->affected_rows() > 0)?true:false;
	}

	/**
	 * @desc   '事件发布'页面->'事件详情'页面->获取图集数据
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectImgMsg($eventid){
		$sql = "select imgurl from gde_eventtraffic where eventid='".$eventid."'";
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * [updateToSavePushInfo 事件详细页面,发布或保存事件]
	 * @version 1.0
	 * @return  [type]                   [description]
	 */
	public function updateToSavePushInfo($eventid,$roadoldid,$occtime,$planovertime,$eventType,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStationArr,$endStationId,$endStationArr,$pushInfo,$EmplId,$EmplName,$coor,$trafficSplitcode,$tag){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'planovertime' => $planovertime,
			'eventtype' => $eventType,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStationArr[0],
			'endnodeid' => $endStationId,
			'endnodename' => $endStationArr[0],
			'reportout' => $pushInfo,
			'startstake' => $startStationArr[1],
			'endstake' => $endStationArr[1],
			'coor_x' => $coor['x'],
			'coor_y' => $coor['y'],
			'trafficsplitcode' => $trafficSplitcode
			);

		if ($tag == '1012004') {//如果是发布,修改这些字段
			$updateArr['eventstatus'] = 1012004;
			$updateArr['checktime'] = $this->getNowTime();
			$updateArr['checkperson'] = $EmplName;
			$updateArr['checkpersonid'] = $EmplId;

			//如果是第一次点击发布
			/*$sql_first = 'select firstreleasepersonid from gde_eventtraffic where eventid=?';
			$params = array($eventid);
			$data_first = $this->mysqlhelper->QueryParams($sql_first,$params);
			if ($data_first[0]['firstreleasepersonid'] == null || $data_first[0]['firstreleasepersonid'] == '') {
				$updateArr['firstreleasepersonid'] = $EmplId;
				$updateArr['firstreleaseperson'] = $EmplName;
				$updateArr['firstreleasetime'] = $this->getNowTime();
			}*/
		}else{
			$updateArr['updatetime'] = $this->getNowTime();
			$updateArr['operatorid'] = $EmplId;
			$updateArr['operatorname'] = $EmplName;
		}

		return $res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}

	/**
	 * [updateToSendback 更新数据库以打回事件信息]
	 * @version 1.0
	 * @param   [type] $eventid [description]
	 * @return  [type]          [description]
	 */
	public function updateToSendback($eventid){
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012003,
			'checktime' => $this->getNowTime(),
			'checkperson' => getsessionempname(),
			'checkpersonid' => getsessionempid()
			);

		$res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');

		if ($res)
			return true;
		else
			return '打回事件失败!';
	}

	/**
	 * [updateStatusToOff '事件详情'->结束当前事件->更新数据库]
	 * @version 1.0
	 * @return  [type]               [description]
	 */
	public function updateStatusToOff($eventid,$realovertime,$operateId,$operateName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012005,
			'cancelpersonid' => $operateId,
			'cancelperson' => $operateName,
			'canceltime' => $this->getNowTime(),
			'realovertime' => $realovertime
			);
		return $res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}

	/**
	 * @desc   获取大类,用于'事件详情'的新增页面
	 * @param  [type]      $eventType [description]
	 * @return [type]                 [description]
	 */
	public function selectEventType($eventType){
		$sql = 'select dictcode eventTypeCode,name eventTypeName from gde_dict where dictcode=?';
		$params = array($eventType);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   新建事件->改变路段下拉框选择,获取该高速的起始站和结束站内容
	 * @param  [type]      $roadoldid [description]
	 * @return [type]                 [description]
	 */
	public function selectStationByRoad($roadoldid){
		//REPLACE(CONCAT(b.name,'(K',b.miles,')'),'.','+')
		$sql = "select b.poiid,CONCAT(b.name,'(K',REPLACE(b.miles,'.','+'),')') name
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				where a.roadlineid=? and a.direction=?
				order by a.seq asc";
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
	 * [insertNewInfoMsg 新增事件,提交审核]
	 * @version 1.0
	 * @return  [type]                   [description]
	 */
	public function insertNewInfoMsg($eventid,$roadoldid,$occtime,$planovertime,$eventType,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStationArr,$endStationId,$endStationArr,$pushInfo,$EmplId,$EmplName,$coor,$trafficSplitcode){
		date_default_timezone_set('PRC');
		//保存状态为"提交审核"的事件信息
		$insertArr = array(
			'eventid' => $eventid,
			'roadoldid' => $roadoldid,
			'occtime' => $occtime,
			'planovertime' => $planovertime,
			'eventtype' => $eventType,
			'eventcauseno' => $eventCause,
			'eventcausename' => $eventCauseName,
			'directionno' => $direction,
			'directionname' => $directionName,
			'roadtrafficcolor' => $TrafficColor,
			'startnodeid' => $startStationId,
			'startnodename' => $startStationArr[0],
			'endnodeid' => $endStationId,
			'endnodename' => $endStationArr[0],
			'reportout' => $pushInfo,
			'firstreleasepersonid' => $EmplId,
			'firstreleaseperson' => $EmplName,
			'firstreleasetime' => $this->getNowTime(),
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'updatetime' => $this->getNowTime(),
			'intime' => $this->getNowTime(),
			'eventstatus' => 1012002,
			'infosource' => 1014001,
			'startstake' => $startStationArr[1],
			'endstake' => $endStationArr[1],
			'coor_x' => $coor['x'],
			'coor_y' => $coor['y'],
			'trafficsplitcode' => $trafficSplitcode
		);
		return $this->mysqlhelper->Insert('gde_eventtraffic',$insertArr);
		//var_dump($this->db->last_query());exit;
		//
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
				'updatetime' => $this->getNowTime(),
				/*'cancelpersonid' => $operateId,
				'cancelperson' => $operateName,
				'canceltime' => $this->getNowTime(),*/
				'realovertime' => $this->getNowTime()
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


	/**
	 * @desc   查询站点的经纬度
	 * @return [type]               [description]
	 */
	public function selectCoor($poiid){
		$sql = 'select coor_x,coor_y from gde_roadpoi where poiid='.$poiid;
		$coor = $this->mysqlhelper->Query($sql);
		return $coor[0];
	}


	/**
	 * @desc   查询开始站点和结束站点的stationcode并拼接
	 * @param  [type]      $startid
	 * @param  [type]      $endid
	 * @return [type]               [description]
	 */
	public function selectTrafficSplitcode($startid,$endid){
		$sql = 'select stationcode from gde_roadpoi where poiid=?';
		$params_s = array($startid);
		$params_e = array($endid);
		$startStationcode = $this->mysqlhelper->QueryParams($sql,$params_s);
		$endStationcode = $this->mysqlhelper->QueryParams($sql,$params_e);
		$trafficStationCode = $startStationcode[0]['stationcode'].$endStationcode[0]['stationcode'];
		return $trafficStationCode;
	}


	/********************管制事件START********************/
	//展示新增/修改管制事件页面时,拉取管制原因下拉内容
	public function selectControlReason(){
		$sql = 'select name from gde_dict where codetype=1019 ORDER BY seq';
		return $data = $this->mysqlhelper->Query($sql);
	}

	//新增/修改管制事件时,获取相应的站点信息
	public function selectStationByRoad_c($roadoldid){
		$sql = 'select poiid,name
				from gde_roadpoi
				where roadoldid=?
				order by poiid desc';
		$params = array($roadoldid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	//修改管制事件时,获取事件信息,以设置页面默认值
	public function selectControlEventMsg($eventId){
		$sql = 'select eventid,a.roadoldid,occtime,reportout,g1,g2,g4,g5,b.direction1,b.direction2,reportinfo,eventstatus,planovertime,realovertime
				from gde_eventtraffic a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				where eventid=?';
		$params = array($eventId);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	//修改管制事件时,获取相关路段的选中值,以设置页面默认值
	public function selectStationById($eventId){
		$sql = 'select * from gde_eventtrafficstation where eventid=?';
		$params = array($eventId);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * [saveNewControlEventMsg 保存新增管制事件的信息,返回操作结果]
	 * @version 2016-04-20 2.0
	 *          log 2.0 修改了记录操作人的字段
	 * @return  [type]                  [description]
	 */
	public function saveNewControlEventMsg($eventtype,$eventId,$roadSel,$occTime,$planovertime,$realovertime,$station,$dir1_come,$dir1_out,$dir2_come,$dir2_out,$reasonSel,$pushContent,$EmplId,$EmplName){//,$eventstatus
		$this->db->trans_begin();

		$insertArr = array(
			'eventid' => $eventId,
			'roadoldid' => $roadSel,
			'occtime' => $occTime,
			'planovertime' => $planovertime,
			'realovertime' => $realovertime,
			'g1' => $dir1_come,
			'g2' => $dir1_out,
			'g4' => $dir2_come,
			'g5' => $dir2_out,
			'reportinfo' => $reasonSel,
			'reportout' => $pushContent,
			'intime' => $this->getNowTime(),
			'firstreleasepersonid' => $EmplId,
			'firstreleaseperson' => $EmplName,
			'firstreleasetime' => $this->getNowTime(),
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'updatetime' => $this->getNowTime(),
			'eventtype' => $eventtype,
			'eventstatus' => 1012002,
			'infosource' => 1014001
		);
		$res_eventtraffic = $this->mysqlhelper->Insert('gde_eventtraffic',$insertArr);
		if (!$res_eventtraffic) {
			$this->db->trans_rollback();
			return false;
		}

		//插入gde_eventtrafficstation
		$stationArr = explode(',',$station);
		foreach ($stationArr as $k => $v) {
			//$sql = 'insert into gde_eventtrafficstation (eventid,stationid)values("'.$eventId.'","'.$v.'"") on duplicate stationid key update eventid='.$eventId;
			$sql = 'insert into gde_eventtrafficstation (`eventid`,`stationid`)VALUES("'.$eventId.'","'.$v.'") on DUPLICATE key UPDATE eventid="'.$eventId.'"';
			$res_station = $this->mysqlhelper->ExecuteSql($sql);
			/*$insertArrStaion = array(
				'eventid' => $eventId,
				'stationid' => $v
			);
			$res_station = $this->mysqlhelper->Insert('gde_eventtrafficstation',$insertArrStaion);*/
			if (!$res_station) {
				$this->db->trans_rollback();
				return false;
			}
		}

		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}


	/**
	 * [updateControlEventMsg 更新监控事件详细,事件状态操作可能为不改变状态,变成发布状态]
	 * [eventstatus==1012004时,更新operatorid,以及checkpersonid]
	 * [eventstatus==其他时,只更新operatorid]
	 * @version 2016-4-20 2.0
	 *          log 2.0 修改了记录操作人的字段
	 * @return  [type]              [description]
	 */
	public function updateControlEventMsg($eventtype,$eventId,$roadSel,$occTime,$planovertime,$realovertime,$station,$dir1_come,$dir1_out,$dir2_come,$dir2_out,$reasonSel,$pushContent,$eventstatus,$EmplId,$EmplName){
		$this->db->trans_begin();
		$updateArr = array(
			'eventid' => $eventId,
			'roadoldid' => $roadSel,
			'occtime' => $occTime,
			'planovertime' => $planovertime,
			'realovertime' => $realovertime,
			'g1' => $dir1_come,
			'g2' => $dir1_out,
			'g4' => $dir2_come,
			'g5' => $dir2_out,
			'reportinfo' => $reasonSel,
			'reportout' => $pushContent,
			'operatorid' => $EmplId,//修改者
			'operatorname' => $EmplName,
			'updatetime' => $this->getNowTime(),
			'eventstatus' => $eventstatus
		);
		if ($eventstatus == '1012004') {
			$updateArr['checkpersonid'] = $EmplId;
			$updateArr['checkperson'] = $EmplName;
			$updateArr['checktime'] = $this->getNowTime();
		}
		$res_eventtraffic = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');

		if (!$res_eventtraffic) {
			$this->db->trans_rollback();
			return false;
		}

		//删除gde_eventtrafficstation表中eventid符合的数据
		$sql_del = 'delete from gde_eventtrafficstation where eventid=?';
		$params_del = array($eventId);

		$this->db->query($sql_del,$params_del);
		$affectRows = $this->db->affected_rows();

		///$res_delete = $this->mysqlhelper->ExecuteSqlParams($sql_del,$params_del);
		if ($affectRows < 0) {
			$this->db->trans_rollback();
			return false;
		}

		//插入gde_eventtrafficstation
		$stationArr = explode(',',$station);
		foreach ($stationArr as $k => $v) {
			$sql = 'insert into gde_eventtrafficstation (`eventid`,`stationid`)VALUES("'.$eventId.'","'.$v.'") on DUPLICATE key UPDATE eventid="'.$eventId.'"';
			$res_station = $this->mysqlhelper->ExecuteSql($sql);
			if (!$res_station) {
				$this->db->trans_rollback();
				return false;
			}
		}

		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

	/**
	 * [updateToFinishControlEvent 更新数据库以结束监控事件]
	 * @version 2016-04-20 2.0
	 *          log 2.0 修改了记录操作人的字段
	 * @return  [type]                   [description]
	 */
	public function updateToFinishControlEvent($eventId,$realovertime){
		$updateArr = array(
			'eventid' => $eventId,
			'eventstatus' => 1012005,
			'realovertime' => $realovertime,
			'canceltime' => $this->getNowTime(),
			'cancelperson' => getsessionempname(),
			'cancelpersonid' => getsessionempid()
			);

		$res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');

		if ($res)
			return true;
		else
			return '结束事件失败!';
	}

	/**
	 * [updateToSendbackControlEvent 修改事件状态至1012003，打回收费站出入口事件]
	 * @version 2016-04-22 1.0
	 * @return  [type]              [description]
	 */
	public function updateToSendbackControlEvent($eventId){
		$updateArr = array(
			'eventid' => $eventId,
			'eventstatus' => 1012003,//打回
			'checkpersonid' => getsessionempid(),
			'checkperson' => getsessionempname(),
			'checktime' => $this->getNowTime()
			);

		$res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');

		if ($res)
			return true;
		else
			return '打回事件失败!';
	}
	/********************管制事件END********************/

	/**
	 * 根据高德eventid获取对应的高德信息
	 * @Author   RaK
	 * @DateTime 2017-07-14T08:47:52+0800
	 * @param    [type]                   $eventid [description]
	 * @return   [type]                            [description]
	 */
	public function getGDEventTrafficByEvent($eventid){
		$sql = "select eventid,createtime,jamspeed,jamdist,pubrunstatus,longtime,startstationid,endstationid,roadid,direction from amap_traffic where eventid=?";
		$data = $this->db->query($sql,array($eventid))->row_array();

		//当前没数据则查看历史表
		if(empty($data)){
			$sql = "select eventid,createtime,jamspeed,jamdist,pubrunstatus,longtime,startstationid,endstationid,roadid,direction from amap_traffic_history where eventid=?";
			$data = $this->db->query($sql,array($eventid))->row_array();
		}
		return $data;
	}
}