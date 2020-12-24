<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》收费标准控制器类
 * 	     涉及到的表 - gde_news
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class TollStandardLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Tollstandard_model', 'standard');
		checksession();
	}


	/**
	 * @desc   打开'信息发布'页面
	 */
	public function indexPage(){
		//事件类型
		$data['id'] = $this->input->get('id');
		$data['newstype'] = $this->input->get('newstype');
		//$data['road'] = $this->roadevent->selectAllRoad();
		$data['data'] = $this->standard->selectTollStandardMsg($data['id'],$data['newstype']);
		//var_dump($data);exit;
		$this->load->view('admin/MsgPublish/TollStandard/TollStandardList',$data);
	}
	

	/**
	 * @desc   保存新收费标准
	 */
	public function onSave(){
		//$id = 'LN20150626003728';//id写死在Logic
		$id = $this->input->post('id');
		$title = $this->input->post('title');
		$html = $this->input->post('html');
		$jpgurl = $this->input->post('jpgurl');

		$res = $this->standard->updateNewsMsg($id,$title,$html,$jpgurl);
		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_error('保存出错');
		}
	}


}