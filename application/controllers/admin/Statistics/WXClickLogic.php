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
		$this->load->model('Statistics/Wxclick_model', 'click');
		checksession();
	}

	/**
	 * @desc   展示'微信菜单点击量统计'页面,默认展示的是路况模块内容
	 */
	public function indexPage(){
		$this->load->view('admin/Statistics/WXClickList');
	}
	

	/********************微信菜单点击总数START********************/
	/**
	 * @desc   '微信菜单点击量统计'->'菜单点击总数'->获取点击总数模块的内容并返回
	 *         点击总数模块为默认展示模块
	 */
	public function onLoadMsg_WXMenuStatistics(){
		//查询数据库
		$data = $this->click->selectMsg_WXMenuStatistics();
		//var_dump($data);exit;
		//
		//菜单类型
		/*foreach ($data as $k => $v) {
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
		}*/

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
		//var_dump($data);exit;
		//反转数组,使得按照日期升序排序
		$reverseData = array_reverse($data);
		//sum(m2) interactive,sum(m3) APPdownload,sum(m4) roadCheck,sum(m5) congestionIndex,sum(m6) myNeighborhood,sum(m7) report,sum(m14) service,sum(m25) checkIllegal,sum(m27) community,sum(m29) personalCenter,sum(m31) mall
		$day = '';$interactive = '';$APPdownload = '';$roadCheck = '';$congestionIndex = '';$myNeighborhood = '';
		$report = '';$service = '';$checkIllegal = '';$community = '';$personalCenter = '';$mall = '';
		foreach ($data as $v) {
			$day .= $v['intime'].',';
			$interactive .= $v['interactive'].',';
			$APPdownload .= $v['APPdownload'].',';
			$roadCheck .= $v['roadCheck'].',';
			$congestionIndex .= $v['congestionIndex'].',';
			$myNeighborhood .= $v['myNeighborhood'].',';
			$report .= $v['report'].',';
			$service .= $v['service'].',';
			$checkIllegal .= $v['checkIllegal'].',';
			$community .= $v['community'].',';
			$personalCenter .= $v['personalCenter'].',';
			$mall .= $v['mall'].',';
		}
		$dataStr['intime'] = substr($day,0,(count($day)-2));
		$dataStr['interactive'] = substr($interactive,0,(count($interactive)-2));
		$dataStr['APPdownload'] = substr($APPdownload,0,(count($APPdownload)-2));
		$dataStr['roadCheck'] = substr($roadCheck,0,(count($roadCheck)-2));
		$dataStr['congestionIndex'] = substr($congestionIndex,0,(count($congestionIndex)-2));
		$dataStr['myNeighborhood'] = substr($myNeighborhood,0,(count($myNeighborhood)-2));
		$dataStr['report'] = substr($report,0,(count($report)-2));
		$dataStr['service'] = substr($service,0,(count($service)-2));
		$dataStr['checkIllegal'] = substr($checkIllegal,0,(count($checkIllegal)-2));
		$dataStr['community'] = substr($community,0,(count($community)-2));
		$dataStr['personalCenter'] = substr($personalCenter,0,(count($personalCenter)-2));
		$dataStr['mall'] = substr($mall,0,(count($mall)-2));
		//将反转的数组插进去并一起返回
		$dataStr['reverseData'] = $reverseData;

		//var_dump($dataStr);exit;
		ajax_success($dataStr,null);
	}
	/********************点击历史统计END********************/
	
}