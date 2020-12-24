<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *新闻页面
 */
class WeatherWordLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WeatherReport/Weatherword_model', 'WeatherWord');
		checksession();
	}

	/**
	 * 列表查看
	 */
	public function index(){
		$data = $this->WeatherWord->selectWeatherWord();
		$this->load->view('admin/WeatherReport/WeatherWordDetail',$data);
	}


	public function saveWeatherWord(){
		$id = $this->input->post('id');
		$html = $this->input->post('html');

		$res = $this->WeatherWord->updateWeatherWordDetail($id,$html);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

}