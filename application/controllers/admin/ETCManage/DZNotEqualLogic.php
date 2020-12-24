<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 订单账单金额不相等控制器
*/
class DZNotEqualLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/Dznotequal_model', 'DZNotEqual');
		checksession();
	}

	/**
	 * 列表查看
	 */
	public function index(){
		$this->load->view('admin/ETCManage/DZNotEqualList');
	}

	/**
	 * 查找数据
	 */
	public function onLoadDZNotEqual(){
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

		$data = $this->DZNotEqual->getDZNotEqualData($StartTime,$EndTime,$keyword,$pageOnload);
		
		ajax_success($data['data'],$data["PagerOrder"]);
	}


}