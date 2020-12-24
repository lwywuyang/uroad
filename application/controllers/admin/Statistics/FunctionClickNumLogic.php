<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》微信菜单点击量控制器类
 * 	     主要涉及到的表 - 
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class FunctionClickNumLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Statistics/Functionclicknum_model', 'clicknum');
		checksession();
		//$this->load->helper('network');
	}


	/**
	 * @desc   打开'微信菜单点击量'页面
	 */
	public function index(){
		//$data['road'] = $this->event->selectRoadMsg();
		$this->load->view('admin/Statistics/FunctionClickNumList');
	}

	/**
	 * @desc   查询微信菜单点击量总览数据
	 * @return [type]      [description]
	 */
	public function onLoadAllNumMsg(){

		$data = $this->clicknum->selectAllNumMsg();

		ajax_success($data,null);
	}

	/**
	 * @desc   获取APP用户趋势页面的图表数据
	 */
	public function onLoadHistoryStatisticsMsg(){
		$HistoryNumStartTime = $this->input->post('HistoryNumStartTime');
		$HistoryNumStartEndTime = $this->input->post('HistoryNumStartEndTime');
		/*if (!isEmpty($AppStartTime))
			$AppStartTime .= ' 00:00:00';
		if (!isEmpty($AppEndTime))
			$AppEndTime .= ' 23:59:59';*/

		$data = $this->clicknum->selectHistoryStatisticsMsg($HistoryNumStartTime,$HistoryNumStartEndTime);
		//反转数组,使得按照日期升序排序
		$reverseData = array_reverse($data);
		//select intime,sum(m2) gslw,sum(m3) lkxx,sum(m4) lkdh,sum(m5) cxgb,sum(m6) gsff,sum(m7) cljy,sum(m8) etcff,sum(m9) lxcx,sum(m10) jd,sum(m11) bl,sum(m12) lj,sum(13) wd
		$date = '';$gslw = '';$lkxx = '';$lkdh = '';$cxgb = '';$gsff = '';
		$cljy = '';$etcff = '';$lxcx = '';$jd = '';$bl = '';$lj = '';$wd = '';
		foreach ($data as $v) {
			$date .= $v['date'].',';
			$gslw .= $v['gslw'].',';
			$lkxx .= $v['lkxx'].',';
			$lkdh .= $v['lkdh'].',';
			$cxgb .= $v['cxgb'].',';
			$gsff .= $v['gsff'].',';
			$cljy .= $v['cljy'].',';
			$etcff .= $v['etcff'].',';
			$lxcx .= $v['lxcx'].',';
			$jd .= $v['jd'].',';
			$bl .= $v['bl'].',';
			$lj .= $v['lj'].',';
			$wd .= $v['wd'].',';
		}
		$dataStr['date'] = substr($date,0,(count($date)-2));
		$dataStr['gslw'] = substr($gslw,0,(count($gslw)-2));
		$dataStr['lkxx'] = substr($lkxx,0,(count($lkxx)-2));
		$dataStr['lkdh'] = substr($lkdh,0,(count($lkdh)-2));
		$dataStr['cxgb'] = substr($cxgb,0,(count($cxgb)-2));
		$dataStr['gsff'] = substr($gsff,0,(count($gsff)-2));
		$dataStr['cljy'] = substr($cljy,0,(count($cljy)-2));
		$dataStr['etcff'] = substr($etcff,0,(count($etcff)-2));
		$dataStr['lxcx'] = substr($lxcx,0,(count($lxcx)-2));
		$dataStr['jd'] = substr($jd,0,(count($jd)-2));
		$dataStr['bl'] = substr($bl,0,(count($bl)-2));
		$dataStr['lj'] = substr($lj,0,(count($lj)-2));
		$dataStr['wd'] = substr($wd,0,(count($wd)-2));
		//将反转的数组插进去并一起返回
		$dataStr['reverseData'] = $reverseData;
		//var_dump($dataStr);exit;
		ajax_success($dataStr,null);
	}

	/**
	 * @desc   获取微信用户趋势页面的图表数据
	 */
	public function onLoadWechatStatisticsMsg(){
		$WechatStartTime = $this->input->post('WechatStartTime');
		$WechatEndTime = $this->input->post('WechatEndTime');

		$resultData = $this->clicknum->selectWechatStatisticsMsg($WechatStartTime,$WechatEndTime);
		//var_dump($resultData);
		$date = '[';
		$userIncrease = '[';
		if (isset($resultData['list'])) {
			foreach ($resultData['list'] as $k => $v) {
				$date .= '"'.$v['ref_date'].'",';
				$userIncrease .= $v['cumulate_user'].',';
			}
			$date = substr($date,0,-1);
			$userIncrease = substr($userIncrease,0,-1);
		}
		$date .= ']';
		$userIncrease .= ']';
		$data = array('date' => $date,'userIncrease' => $userIncrease,'tableData' => array_reverse($resultData['list']));

		//$data = array_reverse($data['list']);
		//var_dump($data);exit;
		ajax_success($data,null);
	}
	
}