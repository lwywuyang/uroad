<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * [Eventstatistics_model 事件信息统计模型类]
 * @author hwq
 * @date 2016-4-20
 */
class Eventstatistics_model extends CI_Model{
	/**
	 * [__construct 构造函数]
	 * @version 2016-04-20 1.0
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * [selectUnitStatisticsMsg 查询根据部门统计的事件发布信息]
	 * @version 2016-04-21 2.0
	 *          log 2.0 添加各个事件状态的统计
	 * @return  [type]                    [description]
	 */
	public function selectUnitStatisticsMsg($UnitStartTime,$UnitEndTime){
		//version1.0
		/*$sql = "select firstreleasepersonid,firstreleaseperson,count(1) num 
				from gde_eventtraffic 
				where firstreleasepersonid<>'T2016011812403044613729863' and firstreleasepersonid<>'' ";*/
		$sql = "select a.firstreleasepersonid,a.firstreleaseperson,count(1) num,IFNULL(b.num,0) num03,IFNULL(c.num,0) num45,IFNULL(d.num,0) num06
				from gde_eventtraffic a 
				left join(
					select firstreleasepersonid,firstreleaseperson,count(1) num
					from gde_eventtraffic where firstreleasepersonid<>'T2016011812403044613729863' and firstreleasepersonid<>'' and eventstatus='1012003'
					group by firstreleasepersonid
				) b on a.firstreleasepersonid=b.firstreleasepersonid
				left join(
					select firstreleasepersonid,firstreleaseperson,count(1) num
					from gde_eventtraffic where firstreleasepersonid<>'T2016011812403044613729863' and firstreleasepersonid<>'' and (eventstatus='1012004' or eventstatus='1012005')
					group by firstreleasepersonid
				) c on a.firstreleasepersonid=c.firstreleasepersonid
				left join(
					select firstreleasepersonid,firstreleaseperson,count(1) num
					from gde_eventtraffic where firstreleasepersonid<>'T2016011812403044613729863' and firstreleasepersonid<>'' and eventstatus='1012006'
					group by firstreleasepersonid
				) d on a.firstreleasepersonid=d.firstreleasepersonid
				where a.firstreleasepersonid<>'T2016011812403044613729863' and a.firstreleasepersonid<>'' ";
		$params = array();
		if (!isEmpty($UnitStartTime)) {
			$UnitStartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(a.firstreleasetime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$UnitStartTime);
		}
		if (!isEmpty($UnitEndTime)) {
			$UnitEndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(a.firstreleasetime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$UnitEndTime);
		}
		$sql .= " group by a.firstreleasepersonid";
		
		return $this->mysqlhelper->QueryParams($sql,$params);
	}


	/**
	 * [selectTypeStatisticsMsg 查询根据事件类型统计的事件发布信息]
	 * @version 2016-04-21 2.0
	 *          log 2.0 添加各个事件状态的统计
	 * @return  [type]                    [description]
	 */
	public function selectTypeStatisticsMsg($TypeStartTime,$TypeEndTime){
		/*$sql = "select eventcauseno,eventcausename,count(1) num 
				from gde_eventtraffic 
				where eventcauseno<>'' ";*/
		$sql = "select a.eventcauseno,eventcausename,count(1) num,IFNULL(b.num,0) num03,IFNULL(c.num,0) num45,IFNULL(d.num,0) num06
				from gde_eventtraffic a
				left join (
					select eventcauseno,count(1) num 
					from gde_eventtraffic where eventstatus='1012003'
					group by eventcauseno
				) b on a.eventcauseno=b.eventcauseno
				left join (
					select eventcauseno,count(1) num 
					from gde_eventtraffic where (eventstatus='1012004' or eventstatus='1012005')
					group by eventcauseno
				) c on a.eventcauseno=c.eventcauseno
				left join (
					select eventcauseno,count(1) num 
					from gde_eventtraffic where eventstatus='1012006'
					group by eventcauseno
				) d on a.eventcauseno=d.eventcauseno
				where a.eventcauseno<>'' ";
		$params = array();
		if (!isEmpty($TypeStartTime)) {
			$TypeStartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(a.firstreleasetime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$TypeStartTime);
		}
		if (!isEmpty($TypeEndTime)) {
			$TypeEndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(a.firstreleasetime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$TypeEndTime);
		}
		$sql .= " group by a.eventcauseno";
		
		return $this->mysqlhelper->QueryParams($sql,$params);
	}


}