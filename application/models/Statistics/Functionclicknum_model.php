<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析 -》微信菜单点击量统计控制器类对应的模型类
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class Functionclicknum_model extends CI_Model{
	//构造函数
	public function __construct(){
		parent::__construct();
		//$dbDefault = $this->load->database('default',true);
		
	}


	/**
	 * @desc   通过network_get调用接口查询APP统计数据
	 * @param  [type]      $functionName [访问的接口的具体方法名]
	 * @param  [type]      $paramsArray  [访问接口的参数]
	 * @return [array]                    [查询结果转换成的数组]
	 */
	private function callApi_APP($functionName,$paramsArray){
		//http://api.umeng.com/channels?auth_token=ItWjClZs7Blm7g8Aapce&appkey=5331473756240bb269007a63
		$serverUrl  = 'http://api.umeng.com/'.$functionName;

		$resultData = network_get($serverUrl,$paramsArray);

		return json_decode($resultData,true);//转成数组
	}

	/**
	 * @desc   通过network_post调用接口查询微信统计数据
	 * @param  [type]      $functionName [访问的接口的具体方法名]
	 * @param  [type]      $paramsArray  [访问接口的参数]
	 * @return [array]                    [查询结果转换成的数组]
	 */
	private function callApi_Wechat($functionName,$paramsArray){
		//http://app.zjzhgs.com/MQTTWechatAPIServer/index.php/wechatserver/
		$serverUrl = 'http://127.0.0.1/MQTTWechatAPIServer/index.php/wechatserver/'.$functionName;

		$resultData = network_post($serverUrl,$paramsArray);

		return json_decode($resultData,true);
	}

	/**
	 * @desc   获取微信菜单点击量统计数据
	 * @return [type]      [description]
	 */
	public function selectAllNumMsg(){
		//查询所有微信菜单点击量统计
		$sql = 'select a.functionid,count(1) clicknum,functionname from gde_function a
				left join gde_functionlog b on a.functionid = b.functionid
				group by a.functionid';
		return $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   获取APP用户趋势统计数据
	 * @param  [type]      $AppStartTime [查询条件,起始时间]
	 * @param  [type]      $AppEndTime   [查询条件,结束时间]
	 * @return [type]                    [description]
	 */
	public function selectHistoryStatisticsMsg($HistoryNumStartTime,$HistoryNumStartEndTime){
		$sql = 'select date,sum(m2) gslw,sum(m3) lkxx,sum(m4) lkdh,sum(m5) cxgb,sum(m6) gsff,sum(m7) cljy,sum(m8) etcff,sum(m9) lxcx,sum(m10) jd,sum(m11) bl,sum(m12) lj,sum(13) wd
				from (
					select *,case when functionid=1002 then clicknum else 0 end m2,
					case when functionid=1003 then clicknum else 0 end m3,
					case when functionid=1004 then clicknum else 0 end m4,
					case when functionid=1005 then clicknum else 0 end m5,
					case when functionid=1006 then clicknum else 0 end m6,
					case when functionid=1007 then clicknum else 0 end m7,
					case when functionid=1008 then clicknum else 0 end m8,
					case when functionid=1009 then clicknum else 0 end m9,
					case when functionid=1010 then clicknum else 0 end m10,
					case when functionid=1011 then clicknum else 0 end m11,
					case when functionid=1012 then clicknum else 0 end m12,
					case when functionid=1013 then clicknum else 0 end m13 
					from (
						select count(1) clicknum,date(created) date,functionid from gde_functionlog where 1=1';
		$params = array();
		if (!isEmpty($HistoryNumStartTime)) {
			$HistoryNumStartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(created) >= UNIX_TIMESTAMP(?)';
			array_push($params,$HistoryNumStartTime);
		}
		if (!isEmpty($HistoryNumStartEndTime)) {
			$HistoryNumStartEndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(created) <= UNIX_TIMESTAMP(?)';
			array_push($params,$HistoryNumStartEndTime);
		}
		$sql .= '		group by date(created),functionid
					) a 
				) b group by date 
				order by date asc';
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		
		return $data;
	}


	/**
	 * @desc   获取微信用户趋势统计数据
	 * @param  [type]      $WechatStartTime [查询条件,起始时间]
	 * @param  [type]      $WechatEndTime   [查询条件,结束时间]
	 * @return [array]                       [查询结果数组]
	 */
	public function selectWechatStatisticsMsg($WechatStartTime,$WechatEndTime){
		$params = array(
			'begindate' => $WechatStartTime,
			'enddate' => $WechatEndTime
		);
		return $data['wechat'] = $this->callApi_Wechat('getusercumulate',$params);
	}

}