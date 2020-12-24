<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》微信菜单点击量统计控制器类
 * 	     主要的表 - 
 * @author hwq
 * @date 2015-10-31
 * @version 1.0
 */
class WXClickLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Wxclick_model', 'click');
		checksession();
	}

	/**
	 * @desc   展示'微信菜单点击量统计'页面,默认展示的是路况模块内容
	 */
	public function indexPage(){
		$this->load->view('admin/WXManage/WXClick/WXClickList');
	}
	

	/********************微信菜单点击总数START********************/
	/**
	 * @desc   '微信菜单点击量统计'->'菜单点击总数'->获取点击总数模块的内容并返回
	 *         点击总数模块为默认展示模块
	 */
	public function onLoadMsg_WXMenuStatistics(){
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		//查询数据库
		$data = $this->click->selectMsg_WXMenuStatistics();
		//var_dump($data);exit;
		foreach ($data as $k => $v) {
			$data[$k]['clickNum'] = $this->click->selectClickNumInMenu($v['id'],$startTime,$endTime);
			switch ($v['itype']) {
				case 0:
					$data[$k]['itypeName'] = 'URL跳转';
					break;
				case 1:
					$data[$k]['itypeName'] = '关键词读表';
					break;
				case 2:
					$data[$k]['itypeName'] = '其他';
					break;
				default:
			}
		}

		ajax_success($data,null);
	}


	/********************微信菜单点击总数END********************/


	/********************点击历史统计START********************/
	/**
	 * @desc   '关键字管理'->'关键字回复'->获取关键字回复的模块的内容并返回
	 */
	public function onLoadMsg_HistoryStatistics(){
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		//查询数据库,获得按日期反序排序的数组
		$data = $this->click->selectMsg_HistoryStatistics($startTime,$endTime);
		//print_r($data);exit;
		//反转数组,使得按照日期升序排序
		$reverseData = array_reverse($data);
		//var_dump($data);exit;
		$day = '';$ETCProfessional = '';$TravelServices = '';$PrizeActivity = '';$Tips = '';
		$AboutOurselves = '';$HistoryMessage = '';$SnatchRedPackage = '';
		foreach ($data as $v) {
			$day .= $v['intime'].',';
			$ETCProfessional .= $v['ETCProfessional'].',';
			$TravelServices .= $v['TravelServices'].',';
			$PrizeActivity .= $v['PrizeActivity'].',';
			$Tips .= $v['Tips'].',';
			$AboutOurselves .= $v['AboutOurselves'].',';
			$HistoryMessage .= $v['HistoryMessage'].',';
			$SnatchRedPackage .= $v['SnatchRedPackage'].',';
		}
		$dataStr['intime'] = substr($day,0,(count($day)-2));
		//var_dump($ETCProfessional);
		$dataStr['ETCProfessional'] = substr($ETCProfessional,0,(count($ETCProfessional)-2));
		//var_dump($dataStr['ETCProfessional']);exit;
		$dataStr['TravelServices'] = substr($TravelServices,0,(count($TravelServices)-2));
		$dataStr['PrizeActivity'] = substr($PrizeActivity,0,(count($PrizeActivity)-2));
		$dataStr['Tips'] = substr($Tips,0,(count($Tips)-2));
		$dataStr['AboutOurselves'] = substr($AboutOurselves,0,(count($AboutOurselves)-2));
		$dataStr['HistoryMessage'] = substr($HistoryMessage,0,(count($HistoryMessage)-2));
		$dataStr['SnatchRedPackage'] = substr($SnatchRedPackage,0,(count($SnatchRedPackage)-2));
		//将反转的数组插进去并一起返回
		$dataStr['reverseData'] = $reverseData;

		//var_dump($dataStr);exit;
		ajax_success($dataStr,null);
	}
	/********************点击历史统计END********************/
	
}