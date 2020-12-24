<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息审核-》信息审核控制器类EventCheckLogic对应的模型类
 * @author hwq
 * @version 1.0
 */
class Eventcheck_model extends CI_Model{
	//利用mysql语句获取now()时间
	private function getNowTime(){
		$sql = 'select now() now';
		$time = $this->mysqlhelper->Query($sql);
		return $time[0]['now'];
	}



	/**
	 * @desc   '信息审核'->读取页面表格信息数据
	 * @param  [type]      $roadId        [description]
	 * @param  [type]      $checkboxValue [description]
	 * @param  [type]      $pageOnload    分页
	 * @return [type]                     [description]
	 */
	public function selectInfoByParams($roadId,$keyword,$pageOnload){
		//version1.0
		/*$sql = 'select eventid,b.picurl,b.roadoldid,b.shortname,d.name eventCauseName,reportout,f.name eventstatusName,a.intime,operatorname,occtime,updatetime,g.name eventTypeName,eventtype
				from gde_eventtraffic a
				left join gde_roadold b on a.roadoldid = b.roadoldid
				left join gde_dict d on a.eventcauseno = d.dictcode
				left join gde_dict f on a.eventstatus = f.dictcode
				left join gde_dict g on a.eventtype = g.dictcode
				where eventtype in (1006001,1006002) and eventstatus in (1012002,1012005)';*/
		//version2.1
		//添加筛选是否重复数据,重复则tag为1否则0
		//根据roadoldid,reportout,directionno,startstake,endstake,roadtrafficcolor判断是否重复数据
		//添加持续时间duration的计算TIMESTAMPDIFF()
		$sql = 'select eventid,b.picurl,b.roadoldid,b.shortname,d.name eventCauseName,reportout,f.name eventstatusName,a.intime,operatorname,occtime,updatetime,g.name eventTypeName,eventtype,TIMESTAMPDIFF(SECOND,occtime,realovertime) duration,
				IF(eventid in (
					select eventid from gde_eventtraffic 
					where (roadoldid,reportout,directionno,startstake,endstake,roadtrafficcolor) in (
						select roadoldid,reportout,directionno,startstake,endstake,roadtrafficcolor
						from gde_eventtraffic 
						where eventtype in (1006001,1006002,1006005,1006007) and eventstatus in (101202,1012005)
						GROUP BY roadoldid,reportout,directionno,startstake,endstake,roadtrafficcolor
						HAVING count(1) > 1
					)
				), 1, 0) tag
				from gde_eventtraffic a
				left join gde_roadold b on a.roadoldid = b.roadoldid
				left join gde_dict d on a.eventcauseno = d.dictcode
				left join gde_dict f on a.eventstatus = f.dictcode
				left join gde_dict g on a.eventtype = g.dictcode
				where eventtype in (1006001,1006002,1006005,1006007) and eventstatus in (1012002,1012005)';
		$params = array();
		if (!isEmpty($roadId)) {
			$sql .= ' and a.roadoldid=? ';
			array_push($params,$roadId);
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
	 * @desc   '信息发布'->'事件详细信息'页面->获取起始站和终点站的下拉框的内容
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectAlongStation($eventid,$directionno){
		$sql = "select b.poiid,CONCAT(b.name,'(K',REPLACE(b.miles,'.','+'),')') name
				from gde_roadlinestation a
				left join gde_roadpoi b on a.stationid=b.poiid
				left join gde_eventtraffic c on a.roadlineid=c.roadoldid
				where c.eventid=? and a.direction=?
				order by a.seq asc";

		$params = array($eventid,$directionno);
		$direction = $this->mysqlhelper->QueryParams($sql,$params);
		//$data['direction_json'] = json_encode($direction);
		return json_encode($direction);
	}

	/**
	 * [selectStation 查询事件对应的站点]
	 * @version 2016-04-22 1.0
	 * @return  [type]              [description]
	 */
	public function selectStation($eventid){
		$sql = 'select a.eventid,group_concat(b.name) stationname
				from gde_eventtrafficstation a
				left join gde_roadpoi b on a.stationid=b.poiid
				where eventid=?';
		$params = array($eventid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return isset($data[0]['stationname'])?$data[0]['stationname']:'';
	}

	/**
	 * @desc   '信息审核'->点击简图,获取简图展示
	 * @return [type]      [description]
	 */
	public function selectSmallPic(){
		$sql = 'select filename from gde_simplemap';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   '信息审核'->点击查看->获取查看的事件信息的详细内容
	 * @param  [type]      $eventid 点击查看的事件的id
	 * @return [type]               [description]
	 */
	public function selectDetailMsgById($eventid){
		$sql = 'select a.*,concat(b.newcode,b.shortname) roadname,c.name eventTypeName,d.name eventCauseName,e.name roadTrafficName,b.direction1,b.direction2,reportinfo
				from gde_eventtraffic a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_dict c on a.eventtype=c.dictcode
				left join gde_dict d on a.eventcauseno=d.dictcode
				left join gde_dict e on a.roadtrafficcolor=e.dictcode
				where eventid=? ';

		$params = array($eventid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return isset($data[0])?$data[0]:array();
	}

	/**
	 * @desc   '信息审核'->查看详细->获取页面顶部站点地图数据
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
	 * @desc   '信息审核'->点击查看->'信息审核详情'->获取事件监控信息
	 * @param  [type]      $eventid [description]
	 * @return [type]               [description]
	 */
	public function selectControlMsg($eventid){
		$sql = 'select eventcomment,eventid,eventupdatetime updatetime,eventstatus from gde_controllereventrecord where eventid=? order by eventupdatetime desc';
		$params = array($eventid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


	/**
	 * @desc   '事件发布'->'事件详情'->发布信息->保存
	 * @return [type]                   [description]
	 */
	public function updateStatus($eventid,$status,$eventstatus,$EmplId,$EmplName){
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => $status
			//'checkperson' => $EmplName,
			//'checkpersonid' => $EmplId,
			//'checktime' => $this->getNowTime()
			);

		switch ($status) {
			case '1012003'://打回不发布
				$updateArr['checkperson'] = $EmplName;
				$updateArr['checkpersonid'] = $EmplId;
				$updateArr['checktime'] = $this->getNowTime();
				break;
			case '1012004':
				if ($eventstatus == '1012002') {//审核通过->发布
					$updateArr['checkperson'] = $EmplName;
					$updateArr['checkpersonid'] = $EmplId;
					$updateArr['checktime'] = $this->getNowTime();
				}
				if ($eventstatus == '1012005') {//审核不通过->不结束->回到发布
					$updateArr['readperson'] = $EmplName;
					$updateArr['readpersonid'] = $EmplId;
					$updateArr['readtime'] = $this->getNowTime();
				}
				break;
			case '1012006'://审核通过->结束(已审核)
				$updateArr['readperson'] = $EmplName;
				$updateArr['readpersonid'] = $EmplId;
				$updateArr['readtime'] = $this->getNowTime();
				break;
			default:
				break;
		}

		return $res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');
	}

	/**
	 * @desc   '事件详情'->结束当前事件->更新数据库
	 * @return [type]                   [description]
	 */
	public function updateStatusToOff($eventid,$realovertime,$operateId,$operateName){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => 1012005,
			//'operatorid' => $operateId,
			//'operatorname' => $operateName,
			//'updatetime' => $this->getNowTime(),
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
	 * @desc   description
	 * @return [type]                     [description]
	 */
	public function insertNewInfoMsg($eventid,$roadoldid,$occtime,$eventType,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStationArr,$endStationId,$endStationArr,$pushInfo,$EmplId,$EmplName,$coor,$trafficSplitcode){
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
			'startnodename' => $startStationArr[0],
			'endnodeid' => $endStationId,
			'endnodename' => $endStationArr[0],
			'reportout' => $pushInfo,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'intime' => $this->getNowTime(),
			'updatetime' => $this->getNowTime(),
			'eventstatus' => 1012002,
			'infosource' => 1014001,
			'startstake' => $startStationArr[1],
			'endstake' => $endStationArr[1],
			'coor_x' => $coor['x'],
			'coor_y' => $coor['y'],
			'trafficsplitcode' => $trafficSplitcode
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
		$sql = 'select eventid,a.roadoldid,occtime,reportout,g1,g2,g4,g5,b.direction1,b.direction2,reportinfo,eventstatus
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

	//保存新增管制事件的信息,返回操作结果
	public function saveNewControlEventMsg($eventId,$roadSel,$occTime,$station,$dir1_come,$dir1_out,$dir2_come,$dir2_out,$reasonSel,$pushContent,$EmplId,$EmplName){//,$eventstatus
		$this->db->trans_begin();
		date_default_timezone_set('PRC');
		$insertArr = array(
			'eventid' => $eventId,
			'roadoldid' => $roadSel,
			'occtime' => $occTime,
			'g1' => $dir1_come,
			'g2' => $dir1_out,
			'g4' => $dir2_come,
			'g5' => $dir2_out,
			'reportinfo' => $reasonSel,
			'reportout' => $pushContent,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'intime' => $this->getNowTime(),
			'updatetime' => $this->getNowTime(),
			//'firstreleasepersonid' => $EmplId,
			//'firstreleaseperson' => $EmplName,
			//'firstreleasetime' => $this->getNowTime(),
			'eventtype' => 1006005,
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


	//更新数据库
	public function updateControlEventMsg($eventId,$roadSel,$occTime,$station,$dir1_come,$dir1_out,$dir2_come,$dir2_out,$reasonSel,$pushContent,$eventstatus,$EmplId,$EmplName){
		$this->db->trans_begin();
		date_default_timezone_set('PRC');
		$updateArr = array(
			'eventid' => $eventId,
			'roadoldid' => $roadSel,
			'occtime' => $occTime,
			'g1' => $dir1_come,
			'g2' => $dir1_out,
			'g4' => $dir2_come,
			'g5' => $dir2_out,
			'reportinfo' => $reasonSel,
			'reportout' => $pushContent,
			'operatorid' => $EmplId,
			'operatorname' => $EmplName,
			'updatetime' => $this->getNowTime(),
			'eventstatus' => $eventstatus
		);
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

	public function updateToFinish($eventId,$realovertime){
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


	public function updateControlStatus($eventid,$status,$eventstatus){
		//var_dump($eventid,$status,$eventstatus);
		$EmplId = getsessionempid();
		$EmplName = getsessionempname();

		$updateArr = array(
			'eventid' => $eventid,
			'eventstatus' => $status
			);

		switch ($status) {//004
			case '1012003'://打回不发布
				$updateArr['checkperson'] = $EmplName;
				$updateArr['checkpersonid'] = $EmplId;
				$updateArr['checktime'] = $this->getNowTime();
				break;
			case '1012004':
				if ($eventstatus == '1012002') {//审核通过->发布
					$updateArr['checkperson'] = $EmplName;
					$updateArr['checkpersonid'] = $EmplId;
					$updateArr['checktime'] = $this->getNowTime();
				}
				if ($eventstatus == '1012005') {//审核不通过->不结束->回到发布
					$updateArr['readperson'] = $EmplName;
					$updateArr['readpersonid'] = $EmplId;
					$updateArr['readtime'] = $this->getNowTime();
				}
				break;
			case '1012006'://审核通过->结束(已审核)
				$updateArr['readperson'] = $EmplName;
				$updateArr['readpersonid'] = $EmplId;
				$updateArr['readtime'] = $this->getNowTime();
				break;
			default:
				break;
		}

		$res = $this->mysqlhelper->Update('gde_eventtraffic',$updateArr,'eventid');

		if ($res)
			return true;
		else
			return '保存审核结果失败!';
	}
	/********************管制事件END********************/

}