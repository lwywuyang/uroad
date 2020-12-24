<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 订单长款控制器
*/
class DDCKLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/DDCK_model', 'DDCK');
		checksession();
	}

	/**
	 * 列表查看
	 */
	public function index(){
		$this->load->view('admin/ETCManage/DDCKList');
	}

	/**
	 * 查找数据
	 */
	public function onLoadDDCK(){
		//查找员工数据
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ''){
			$pageOnload['OrderDesc'] = '';
		}
		// 关键字
		$StartTime = $this->input->post('StartTime');
		$EndTime = $this->input->post('EndTime');
		$keyword = $this->input->post('keyword');

		$data = $this->DDCK->getDDCKData($StartTime,$EndTime,$keyword,$pageOnload);
		
		foreach ($data['data'] as $k => $v) {
			switch ($v['paytype']) {
				case '1':
					$data['data'][$k]['paytype'] = '微信支付';
					break;
				case '2':
					$data['data'][$k]['paytype'] = '支付宝';
					break;
				case '3':
					$data['data'][$k]['paytype'] = '银联';
					break;
				default:
					break;
			}
		}
		ajax_success($data['data'],$data["PagerOrder"]);
	}


}