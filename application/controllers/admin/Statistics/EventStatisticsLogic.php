<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * [EventStatisticsLogic 事件信息统计控制器类]
 * @author hwq
 * @date 2016-4-20
 */
class EventStatisticsLogic extends CI_Controller {
	/**
	 * [__construct 构造函数]
	 * @version 2016-04-20 1.0
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Statistics/Eventstatistics_model', 'Eventstatistics');
		checksession();
	}


	/**
	 * [indexPage 展示默认页面]
	 * @version 2016-04-20 1.0
	 * @return  [type]     [description]
	 */
	public function index(){
		$this->load->view('admin/Statistics/EventStatisticsList');
	}

	/**
	 * [onLoadUnitStatisticsMsg 获取根据部门统计的事件发布统计信息]
	 * @version 2016-04-20 1.0
	 * @return  [type]     [description]
	 */
	public function onLoadUnitStatisticsMsg(){
		$UnitStartTime = $this->input->post('UnitStartTime');
		$UnitEndTime = $this->input->post('UnitEndTime');

		$data['table'] = $this->Eventstatistics->selectUnitStatisticsMsg($UnitStartTime,$UnitEndTime);

		//组装遍历数组用的数组
		$unitNameArr = array();
		$dataArr = array();

		foreach ($data['table'] as $k => $v) {
			$unitNameArr[$k] = $v['firstreleaseperson'];
			$dataArr[$k]['value'] = $v['num'];
			$dataArr[$k]['name'] = $v['firstreleaseperson'];

			//$title .= $v['firstreleaseperson'].$v['num'].',';
		}
		$data['unit'] = json_encode($unitNameArr);
		$data['piedata'] = json_encode($dataArr);

		ajax_success($data,null);
	}

	/**
	 * [onLoadTypeStatisticsMsg 获取根据事件类型统计的事件发布统计信息]
	 * @version 2016-04-20 1.0
	 * @return  [type]     [description]
	 */
	public function onLoadTypeStatisticsMsg(){
		$TypeStartTime = $this->input->post('TypeStartTime');
		$TypeEndTime = $this->input->post('TypeEndTime');

		$data['table'] = $this->Eventstatistics->selectTypeStatisticsMsg($TypeStartTime,$TypeEndTime);

		//组装遍历数组用的数组
		$typeNameArr = array();
		$dataArr = array();

		foreach ($data['table'] as $k => $v) {
			$typeNameArr[$k] = $v['eventcausename'];
			$dataArr[$k]['value'] = $v['num'];
			$dataArr[$k]['name'] = $v['eventcausename'];

		}
		$data['type'] = json_encode($typeNameArr);
		$data['piedata'] = json_encode($dataArr);

		ajax_success($data,null);
	}

	
}