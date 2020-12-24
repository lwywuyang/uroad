<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析 -》用户数统计控制器类对应的模型类
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class Usernum_model extends CI_Model{
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
	 * @desc   获取总览APP用户数统计数据
	 * @return [type]      [description]
	 */
	public function selectUserNumMsg(){
		//APP用户数
		$paramsAndroid = array('auth_token'=>'ItWjClZs7Blm7g8Aapce','appkey'=>'5322742d56240b0325084ed8');
		$resAndroid = $this->callApi_APP('channels',$paramsAndroid);
		$data['Android'] = $resAndroid[0]['total_install'];

		$paramsIOS = array('auth_token'=>'ItWjClZs7Blm7g8Aapce','appkey'=>'5322745156240b031e08c983');
		$resIOS = $this->callApi_APP('channels',$paramsIOS);
		$data['IOS'] = $resIOS[0]['total_install'];

		return $data;
	}

	/**
	 * [selectDateStatisticsMsg 按照日期查询数据]
	 * @version 2016-06-17 1.0
	 */
	public function selectDateStatisticsMsg($DateStartTime,$DateEndTime){
		//获取折线图数据
		$paramsAndroid = array(
			'auth_token'=>'ItWjClZs7Blm7g8Aapce',
			'appkey'=>'5322742d56240b0325084ed8',
			'start_date'=>$DateStartTime,
			'end_date'=>$DateEndTime,
		);
		$paramsIOS = array(
			'auth_token'=>'ItWjClZs7Blm7g8Aapce',
			'appkey'=>'5322745156240b031e08c983',
			'start_date'=>$DateStartTime,
			'end_date'=>$DateEndTime,
		);
		$resAndroid = $this->callApi_APP('new_users',$paramsAndroid);
		$data['AndroidIncrease'] = $resAndroid['data']['all'];
		$data['AndroidDate'] = $resAndroid['dates'];

		$resIOS = $this->callApi_APP('new_users',$paramsIOS);
		$data['IOSIncrease'] = $resIOS['data']['all'];
		$data['IOSDate'] = $resIOS['dates'];
		
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