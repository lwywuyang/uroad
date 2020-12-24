<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 高德列表模型
 */
class Gaodelist_model extends CI_Model{



	/**
	 * 获取字典表中对应pid的数据
	 * @Author   RaK
	 * @DateTime 2017-05-04T11:25:36+0800
	 * @param    [type]                   $pid    [父类ID]
	 * @param    string                   $noneid [不显示的ID]
	 * @return   [type]                           [description]
	 */
	public function getDictByPid($pid,$noneid=''){
		if(empty($pid)){
			return false;
		}
		$sql = "select * from gde_dictlist where pid=?";
		$params = array();
		array_push($params,$pid);
		if(!empty($noneid)){
			$sql.=" and id not in (?)";
			array_push($params,$noneid);
		}
		$data = $this->db->query($sql,$params)->result_array();
		return $data;
	}

	/**
	 * 加载高德提醒列表数据
	 * @Author   RaK
	 * @DateTime 2017-05-05T08:52:00+0800
	 * @param    [type]                   $pagerOrder [分页数据]
	 * @param    [type]                   $status     [事件结果状态]
	 * @param    [type]                   $starttime  [开始时间]
	 * @param    [type]                   $endtime    [结束时间]
	 * @param    [type]                   $ids        [description]
	 * @return   [type]                               [description]
	 */
	function getTrafficdata($pagerOrder,$status,$starttime,$endtime,$ids,$type,$ppstatus,$keyword){

		//历史记录sql--非当天记录
		$sql = "select a.*,d.miles startmile,e.miles endmile,f.shortname from (select a.*,c.name eventstatusname,b.operatorid,b.operatortime,b.operatorname,b.eventstatus,b.operatorstatus,g.`name` operatorstatuSname from amap_traffic_history a JOIN amap_handle_history b ON a.eventid = b.eventid JOIN amap_dict c ON b.eventstatus = c.code LEFT JOIN amap_dict g ON b.operatorstatus = g. CODE) a LEFT JOIN gde_roadpoi d on a.startstationid=d.poiid
			LEFT JOIN gde_roadpoi e on a.endstationid=e.poiid LEFT JOIN gde_roadold f on a.roadid=f.roadoldid where true and a.direction<2 and TO_DAYS(NOW()) - TO_DAYS(a.inserttime) >0 ";

		if($type==1){//当天记录sql
			$sql = "select a.*,b.operatorname,c.name eventstatusname,b.operatortime,b.eventstatus,d.miles startmile,e.miles endmile,b.operatorstatus,b.operatorid,f.shortname,g.`name` operatorstatuSname,h.operatortime sctime from amap_traffic a LEFT JOIN amap_handle b ON a.eventid = b.eventid LEFT JOIN amap_dict c ON b.eventstatus = c.code LEFT JOIN gde_roadpoi d on a.startstationid=d.poiid LEFT JOIN gde_roadpoi e on a.endstationid=e.poiid LEFT JOIN gde_roadold f on a.roadid=f.roadoldid LEFT JOIN amap_dict g ON b.operatorstatus = g. CODE LEFT JOIN amap_handleprocess h on h.eventid=a.eventid and h.operatorstatus=1002005 WHERE true and a.direction<2 ";
		}
					
		// $sql = "select a.*,c.name eventstatusname,b.operatortime,b.eventstatus from $traffictable a LEFT JOIN $handletable b ON a.eventid = b.eventid left JOIN amap_dict c ON b.eventstatus = c.code WHERE true ";
		$params=array();
			$this->load->helper('budata');
        $budata = budatabycode('M0001');//获取当前登录人员有哪些路段权限

		if(!empty($budata)){
			$sql.="and a.roadid in(".$budata.") ";
		}
		if(!isEmpty($ids)){
			$sql.=" and a.eventid in (".$ids.")";
		}
		if(!isEmpty($starttime)){
			$sql.=" and a.inserttime >= ? ";
			array_push($params, $starttime." 00:00:00");
		}
		if(!isEmpty($endtime)){
			$sql.=" and a.inserttime <= ? ";
			array_push($params, $endtime." 23:59:59");
		}
		if(!isEmpty($status)){
			if($type==1){
				$sql.=" and b.eventstatus = ? ";
			}else{
				$sql.=" and a.eventstatus = ? ";
			}
			array_push($params, $status);
		}
		if(!isEmpty($ppstatus)){
			$sql.=" and a.status = ? ";
			array_push($params, $ppstatus);
		}
		if(!isEmpty($keyword)){
			$sql.=" and (a.roadname like concat('%',?,'%') or f.shortname like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;

	}

	/**
	 * 获取高德提醒表中字典表对应pid的数据
	 * @Author   RaK
	 * @DateTime 2017-05-04T11:25:36+0800
	 * @param    [type]                   $pid    [父类ID]
	 * @param    string                   $noneid [不显示的ID]
	 * @return   [type]                           [description]
	 */
	public function getGaoDeDictByPid($pid,$noneid=''){
		if(empty($pid)){
			return false;
		}
		$sql = "select * from amap_dict where typecode=?";
		$params = array();
		array_push($params,$pid);
		if(!empty($noneid)){
			$sql.=" and code not in (?)";
			array_push($params,$noneid);
		}
		$data = $this->db->query($sql,$params)->result_array();
		return $data;
	}

	/**
	 * 查看对应eventid的高德提醒数据
	 * @Author   RaK
	 * @DateTime 2017-05-05T12:31:35+0800
	 * @param    [type]                   $id [description]
	 * @return   [type]                       [description]
	 */
	function getEventDataByIdNew($id,$selecttype){
		$traffictable = 'amap_traffic_history';//高德提醒历史表
		if($selecttype==1){
			$traffictable = 'amap_traffic';//高德提醒当天表
		}
		$sql="SELECT xys  AS detailData,jamspeed gaodejamSpeed,roadname gaoderoadName,jamdist gaodejamDist,inserttime gaodeinserttime,longtime gaodelongTime,xy gaodexy
			  FROM $traffictable
			  WHERE eventid = ?";
		$params=array($id);
		$data=$this->db->query($sql,$params)->row_array();
		$data['detailData'] = json_encode($data['detailData']);
	 	return $data;
	}

	/**
	 * 查看对应eventid的事件详情
	 * @Author   RaK
	 * @DateTime 2017-05-05T12:58:32+0800
	 * @param    [type]                   $eventid    [description]
	 * @param    [type]                   $selecttype [description]
	 * @return   [type]                               [description]
	 */
	public function getEventDataByEventId($eventid,$selecttype){
		if(empty($eventid)){
			return false;
		}
		$traffictable = 'amap_traffic_history';//高德提醒历史表
		$handletable = 'amap_handle_history';//高德事件处理历史表
		if($selecttype==1){
			$traffictable = 'amap_traffic';//高德提醒当天表
			$handletable = 'amap_handle';//高德事件处理当天表
		}
		$sql = "select a.*,c.name eventstatusname,d.reportout,b.msg,b.picfiles,b.operatortime,b.eventstatus,b.operatorstatus,b.operatorid,b.operatorname,b.linkeventid,d.eventid gsteventid,d.eventid eventno,concat('(',d.startnodename,'~',d.endnodename,')') ksz,d.occtime,e.coor_y startstationx,e.coor_x startstationy,f.coor_y endstationx,f.coor_x endstationy,CONCAT('K',REPLACE(e.miles,'.','+')) startstationmiles,CONCAT('K',REPLACE(f.miles,'.','+')) endstationmiles from $traffictable a LEFT JOIN $handletable b ON a.eventid = b.eventid left JOIN amap_dict c ON b.eventstatus = c.code left join gde_eventtraffic d on b.linkeventid=d.eventid LEFT JOIN gde_roadpoi e ON a.startstationid = e.poiid LEFT JOIN gde_roadpoi f ON a.endstationid = f.poiid WHERE a.eventid=? ";
		$data = $this->db->query($sql,array($eventid))->row_array();
		return $data;
	}

	/**
	 * 获取对应路段正在发生的事件
	 * @Author   RaK
	 * @DateTime 2017-05-05T13:10:41+0800
	 * @param    [type]                   $roadid [description]
	 * @return   [type]                           [description]
	 */
	public function getRelationEvent($roadid){
		if(empty($roadid)){
			return false;
		}
		$sql = "select a.eventid,a.reportout,a.eventid eventno,concat('(',a.startnodename,'~',a.endnodename,')') ksz,a.occtime from  gde_eventtraffic a
                where roadoldid=? and a.eventstatus=1012004 and a.eventtype=1006001 ";
		$data = $this->db->query($sql,array($roadid))->result_array();
		return $data;
	}

	/**
	 * 保存关联高德事件操作
	 * @Author   RaK
	 * @DateTime 2017-05-05T13:39:25+0800
	 * @param    [type]                   $gaodeinserttime  [description]
	 * @param    [type]                   $eventid          [description]
	 * @param    [type]                   $msg    			[description]
	 * @param    [type]                   $imgurl       	[description]
	 * @param    [type]                   $eventstatus      [description]
	 * @param    [type]                   $isnew      		[是否为更新图片]
	 * @param    [type]                   $selecttype       [当天还是历史]
	 */
	public function saveGaoDeHandle($gaodeeventid,$eventid,$msg,$imgurl,$eventstatus,$isnew,$selecttype){
		$operatorid = getsessionempid();
		$operatorname = getsessionempname();
		date_default_timezone_set('PRC');
		$time = date("Y-m-d H:i:s");
		$this->db->trans_begin();
		$handletable = 'amap_handle_history';//高德事件处理历史表
		if($selecttype==1){
			$handletable = 'amap_handle';//高德事件处理当天表
		}
		//记录操作进展
		$sql = "insert into amap_handleprocess (eventid,operatorid,operatorname,operatortime,eventstatus,linkeventid,msg,picfiles,operatorstatus)values(?,?,?,?,?,?,?,?,?)";
		$this->db->query($sql,array($gaodeeventid,$operatorid,$operatorname,$time,$eventstatus,$eventid,$msg,$imgurl,1002003));
		if($isnew==1){//更新图片
			$sql = "update $handletable set operatorid=?,operatorname=?,operatortime=NOW(),picfiles=? where eventid=?";
			$this->db->query($sql,array($operatorid,$operatorname,$imgurl,$gaodeeventid));
			if ($this->db->trans_status() === false) {
	            $this->db->trans_rollback();
	            return false;
	        } else {
	            $this->db->trans_commit();
	            return true;
	        }
			exit;
		}
		$sql = "update $handletable set operatorid=?,operatorname=?,operatortime=?,eventstatus=?,linkeventid=?,msg=?,picfiles=?,operatorstatus=1002003 where eventid=?";
		$this->db->query($sql,array($operatorid,$operatorname,$time,$eventstatus,$eventid,$msg,$imgurl,$gaodeeventid));
		if($eventstatus==1001002){
			$sql = "update gde_eventtraffic set isrelateamap=1,amapid=? where eventid=?";
			$this->db->query($sql,array($gaodeeventid,$eventid));
		}
		if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;

        } else {
            $this->db->trans_commit();
            //删除redis里对应eventid的提醒
            $redis = new redis();
        	$redis->connect('127.0.0.1', 6379);
        	$res = $redis->hdel('gaodetraffic',$gaodeeventid);
        	log_message("INFO","delrediseventid--->".$gaodeeventid.":res->".$res);
            return true;
       }
	}

	/**
	 * 修改对应高德事件的处理状态
	 * @Author   RaK
	 * @DateTime 2017-05-09T15:11:28+0800
	 * @param    [type]                   $gaodeeventid [description]
	 * @return   [type]                                 [description]
	 */
	public function savehandle($operatorname,$gaodeeventid,$status,$selecttype){
		$operatorid = getsessionempid();
		$this->db->trans_begin();
		date_default_timezone_set('PRC');
		$time = date("Y-m-d H:i:s");
		$handletable = 'amap_handle_history';//高德历史处理表
		if($selecttype==1){
			$handletable = 'amap_handle';//高德当天处理表
		}
		$sql = "update ".$handletable." set operatorid=?,operatorname=?,operatorstatus=? where eventid=?";
		$this->db->query($sql,array($operatorid,$operatorname,$status,$gaodeeventid));
		$sql = "insert into amap_handleprocess (eventid,operatorid,operatorname,operatortime,operatorstatus)values(?,?,?,?,?)";
		$this->db->query($sql,array($gaodeeventid,$operatorid,$operatorname,$time,$status));
		if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
       }
	}

	/**
	 * 拼接新的对外信息
	 * @Author   RaK
	 * @DateTime 2017-05-10T16:13:41+0800
	 * @param    [type]                   $eventid [description]
	 * @return   [type]                            [description]
	 */
	public function getReportoutByEventId($eventid){
		$sql = "select reportout oldreportout,CONCAT(date_format(occtime,'%m-%d %H:%i'),' ',c.newcode,c.shortname,directionname) kt,a.startnodename,a.startstake,a.endnodename,a.endstake,a.eventcausename,a.roadtrafficcolor
				from gde_eventtraffic a
				join gde_roadold c on a.roadoldid=c.roadoldid where eventid=?";

		$data = $this->db->query($sql,array($eventid))->row_array();
		return $data;
	}

	/**
	 * 获取部门名称
	 * @Author   RaK
	 * @DateTime 2017-05-10T16:14:09+0800
	 * @return   [type]                   [description]
	 */
	public function getCompName(){
		$depaid = getsessiondepaid();
		$sql = "select CompName from org_company a LEFT JOIN org_department b on a.ID=b.CompanyID where b.id = ?";
		$data = $this->db->query($sql,array($depaid))->row_array();
		$CompName = $data['CompName'];
		return $CompName;
	}

	/**
	 * 高德提醒关联保存事件
	 * @Author   RaK
	 * @DateTime 2017-05-10T16:37:41+0800
	 * @param    [type]                   $isnew        [是否更新对外信息]
	 * @param    [type]                   $eventid      [事件ID]
	 * @param    [type]                   $gaodeeventid [高德提醒事件ID]
	 * @param    [type]                   $reportout    [带上高德信息的对外信息]
	 * @return   [type]                                 [description]
	 */
	public function savereportout($isnew,$eventid,$gaodeeventid,$reportout){
		$this->db->trans_begin();
		$operatorid = getsessionempid();
		$operatorname = getsessionempname();
		date_default_timezone_set('PRC');
		$time = date("Y-m-d H:i:s");
		$eventstatus = 1001002;

		//记录操作进展
		$sql = "insert into amap_handleprocess (eventid,operatorid,operatorname,operatortime,eventstatus,linkeventid,operatorstatus)values(?,?,?,?,?,?,?)";
		$this->db->query($sql,array($gaodeeventid,$operatorid,$operatorname,$time,$eventstatus,$eventid,1002003));

		//修改事件结果状态
		$sql = "update amap_handle set operatorid=?,operatorname=?,operatortime=?,eventstatus=?,linkeventid=?,operatorstatus=1002003 where eventid=?";
		$this->db->query($sql,array($operatorid,$operatorname,$time,$eventstatus,$eventid,$gaodeeventid));

		$sql = "update amap_handle_history set operatorid=?,operatorname=?,operatortime=?,eventstatus=?,linkeventid=?,operatorstatus=1002003 where eventid=?";
		$this->db->query($sql,array($operatorid,$operatorname,$time,$eventstatus,$eventid,$gaodeeventid));

		//修改事件对外信息
		if(!empty($isnew)){
			$params = array();
			$sql = "update gde_eventtraffic set reportout=? where eventid=? ";
			array_push($params, $reportout);
			array_push($params, $eventid);
			$this->db->query($sql,$params);
		}
		if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            //删除redis里对应eventid的提醒
            $redis = new redis();
        	$redis->connect('127.0.0.1', 6379);
        	$res = $redis->hdel('gaodetraffic',$gaodeeventid);
        	log_message("INFO","delrediseventid--->".$gaodeeventid.":res->".$res);
            return true;
        }
	}

	/**
	 * 获取对应高德事件的历史进展
	 * @Author   RaK
	 * @DateTime 2017-05-11T15:10:54+0800
	 * @param    [type]                   $gaodeeventid [description]
	 * @return   [type]                                 [description]
	 */
	public function getTrafficProcess($gaodeeventid){
		$sql = "select roadname,jamspeed,jamdist,inserttime from amap_trafficprocess where eventid=? order by inserttime desc";
		$data = $this->db->query($sql,array($gaodeeventid))->result_array();
		return $data;
	}

	/**
	 * 获取对应高德事件的操作进展
	 * @Author   RaK
	 * @DateTime 2017-05-11T15:35:41+0800
	 * @param    [type]                   $gaodeeventid [description]
	 * @return   [type]                                 [description]
	 */
	public function getHandleProcess($gaodeeventid){
		$sql = "select a.operatorname,a.operatortime,b.`name` eventstatusname,c.`name` operatorstatusname from amap_handleprocess a LEFT JOIN amap_dict b on a.eventstatus=b.`code` JOIN amap_dict c on a.operatorstatus=c.`code` where a.eventid=? order by a.operatortime desc";
		$data = $this->db->query($sql,array($gaodeeventid))->result_array();
		return $data;
	}

	/**
	 * 
	 * @Author   RaK
	 * @DateTime 2017-05-16T09:18:12+0800
	 * @param    [type]                   $status    [description]
	 * @param    [type]                   $starttime [description]
	 * @param    [type]                   $endtime   [description]
	 * @param    [type]                   $ids       [description]
	 * @return   [type]                              [description]
	 */
	public function getTrafficdataExcel($status,$starttime,$endtime,$ids,$type,$ppstatus,$keyword){
		//历史记录sql--非当天记录
		$sql = "select a.*,d.miles startmile,e.miles endmile,f.shortname from (select a.*,c.name eventstatusname,b.operatorid,b.operatortime,b.operatorname,b.eventstatus,b.operatorstatus,g.`name` operatorstatuSname,h.operatortime sctime from amap_traffic_history a JOIN amap_handle_history b ON a.eventid = b.eventid JOIN amap_dict c ON b.eventstatus = c.code LEFT JOIN amap_dict g ON b.operatorstatus = g. CODE JOIN amap_handleprocess h on a.eventid=h.eventid and h.operatorstatus=1002005) a LEFT JOIN gde_roadpoi d on a.startstationid=d.poiid
			LEFT JOIN gde_roadpoi e on a.endstationid=e.poiid LEFT JOIN gde_roadold f on a.roadid=f.roadoldid where true and a.direction<2 and TO_DAYS(NOW()) - TO_DAYS(a.inserttime) >0 ";

		if($type==1){//当天记录sql
			$sql = "select a.*,b.operatorname,c.name eventstatusname,b.operatortime,b.eventstatus,d.miles startmile,e.miles endmile,b.operatorstatus,b.operatorid,f.shortname,g.`name` operatorstatuSname,h.operatortime sctime from amap_traffic a JOIN amap_handle b ON a.eventid = b.eventid JOIN amap_dict c ON b.eventstatus = c.code LEFT JOIN gde_roadpoi d on a.startstationid=d.poiid LEFT JOIN gde_roadpoi e on a.endstationid=e.poiid LEFT JOIN gde_roadold f on a.roadid=f.roadoldid LEFT JOIN amap_dict g ON b.operatorstatus = g. CODE JOIN amap_handleprocess h on a.eventid=h.eventid and h.operatorstatus=1002005 WHERE true and a.direction<2 ";
		}
		// $sql = "select a.*,c.name eventstatusname,b.operatortime,b.eventstatus from $traffictable a LEFT JOIN $handletable b ON a.eventid = b.eventid left JOIN amap_dict c ON b.eventstatus = c.code WHERE true ";
		$params=array();
		$budata = getsessionuserbudata();
		if(!empty($budata)){
			$sql.="and a.roadid in(".$budata.") ";
		}
		if(!isEmpty($ids)){
			$sql.=" and a.eventid in (".$ids.")";
		}
		if(!isEmpty($starttime)){
			$sql.=" and a.inserttime >= ? ";
			array_push($params, $starttime." 00:00:00");
		}
		if(!isEmpty($endtime)){
			$sql.=" and a.inserttime <= ? ";
			array_push($params, $endtime." 23:59:59");
		}
		if(!isEmpty($status)){
			if($type==1){
				$sql.=" and b.eventstatus = ? ";
			}else{
				$sql.=" and a.eventstatus = ? ";
			}
			array_push($params, $status);
		}
		if(!isEmpty($ppstatus)){
			$sql.=" and a.status = ? ";
			array_push($params, $ppstatus);
		}
		if(!isEmpty($keyword)){
			$sql.=" and (a.roadname like concat('%',?,'%') or f.shortname like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}
		$sql.=" order by inserttime desc ";
		$data=$this->db->query($sql,$params)->result_array();
		return $data;
	}

	/**
	 * 获取对应的高德提醒是否正在处理
	 * @Author   RaK
	 * @DateTime 2017-05-26T16:28:48+0800
	 * @param    [type]                   $eventid 高德eventid
	 * @return   [type]                            [description]
	 */
	public function getOperatorStatus($eventid){
		$sql = "select operatorstatus from amap_handleprocess where eventid=? ORDER BY operatortime desc LIMIT 1";
		$data = $this->db->query($sql,array($eventid))->row_array();
		$res = false;
		if(empty($data['operatorstatus']) || $data['operatorstatus']==1002002){
			$res = true;
		}
		return $res;
	}

	/**
	 * 点击列表的处理按钮后--先检查该事件是否处理中，否则插入处理人信息
	 * @Author   RaK
	 * @DateTime 2017-05-26T16:59:16+0800
	 * @param    [type]                   $eventid    [description]
	 * @param    [type]                   $selecttype [description]
	 * @return   [type]                               [description]
	 */
	public function fillInOperator($eventid,$selecttype){
		if(empty($eventid)){
			return false;
		}
		$handletable = 'amap_handle_history';//高德历史处理表
		if($selecttype==1){
			$handletable = 'amap_handle';//高德当天处理表
		}
		$sql = "select operatorstatus,operatorid from $handletable where eventid=?";
		$data = $this->db->query($sql,array($eventid))->row_array();
		$operatorname = getsessionempname();
		$operatorid = getsessionempid();
		if($data['operatorstatus']==1002002 || empty($data['operatorstatus'])){
			$res = $this->savehandle($operatorname,$eventid,1002001,$selecttype);
			return $res;
		}if($data['operatorstatus']==1002001 && $data['operatorid']==$operatorid){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 插入提醒记录
	 * @Author   RaK
	 * @DateTime 2017-06-10T09:36:25+0800
	 * @param    [type]                   $eventid 事件ID
	 * @return   [type]                              [description]
	 */
	public function recordlog($eventid){
		if(empty($eventid)){
			return false;
		}
		$eventids = explode(',', $eventid);
		//先查看操作进展表中是否存在该eventid记录
		foreach ($eventids as $key => $value) {
			$sql = "select operatorstatus from amap_handleprocess where eventid=? order by recordid desc limit 1";
			$data = $this->db->query($sql,array($value))->row_array();
			//不存在就插入状态为首次提醒  存在并且状态为取消的就插入状态为未处理
			if(empty($data)){
				$sql = "insert into amap_handleprocess (eventid,operatorstatus,operatortime) values (?,?,NOW())";
				$this->db->query($sql,array($value,1002005));
			}else if($data['operatorstatus']=='1002002'){
				$sql = "insert into amap_handleprocess (eventid,operatorstatus,operatortime) values (?,?,NOW())";
				$this->db->query($sql,array($value,1002004));
			}
		}
	}


}