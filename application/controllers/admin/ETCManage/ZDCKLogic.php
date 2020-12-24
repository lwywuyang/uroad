<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 账单长款控制器
*/
class ZDCKLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/Zdck_model', 'ZDCK');
		checksession();
	}

	/**
	 * 列表查看
	 */
	public function index(){
		$this->load->view('admin/ETCManage/ZDCKList');
	}

	/**
	 * 查找数据
	 */
	public function onLoadZDCK(){
		//查找员工数据
		$pageOnload = page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ''){
			$pageOnload['OrderDesc'] = '';
		}
		// 关键字
		$StartTime = $this->input->post('StartTime');
		$EndTime = $this->input->post('EndTime');
		$keyword = $this->input->post('keyword');

		$data = $this->ZDCK->getZDCKData($StartTime,$EndTime,$keyword,$pageOnload);
		
		ajax_success($data['data'],$data["PagerOrder"]);
	}


}