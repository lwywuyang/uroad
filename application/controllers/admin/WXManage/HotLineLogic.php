<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》服务热线控制器类
 * 	     主要的表 - gde_phone
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class HotLineLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 * @date 2015-10-9 17:45:38
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Hotline_model', 'hotline');
		checksession();
	}


	/**
	 * @desc   打开'服务热线'页面
	 * @data   2015-10-9 17:47:24
	 */
	public function indexPage(){
		$this->load->view('admin/WXManage/HotLine/HotLineList');
	}
	

	/**
	 * @desc   获取'爆料信息'页面信息
	 * @data   2015-10-9 17:54:38
	 */
	public function onLoadHotLineMsg(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by seq asc';
		}
		//查询数据库
		$data = $this->hotline->selectHotLineMsg($pageOnload);

		foreach ($data['data'] as $k => $v) {
			$data['data'][$k]['content'] = "<a onclick='checkDetail(".$v['id'].")'>".$v['remark']."</a>";
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function showDetailMsg(){
		$tag = $this->input->get('tag');
		if ($tag == 1) {//新增
			$data['id'] = 0;
		}else{//修改
			$data['id'] = $this->input->get('id');
			$data['data'] = $this->hotline->selectHotLineDetail($data['id']);
		}
		
		$this->load->view('admin/WXManage/HotLine/HotLineDetailList',$data);
	}


	public function saveDetailMsg(){
		$id = $this->input->post('id');
		$remark = $this->input->post('remark');
		$phone = $this->input->post('phone');
		$seq = $this->input->post('seq');
		$topSel = $this->input->post('topSel');

		if ($id == 0) {//新增
			$res = $this->hotline->insertNewMsg($remark,$phone,$seq,$topSel);
		}else{//修改
			$res = $this->hotline->UpdateMsg($id,$remark,$phone,$seq,$topSel);
		}

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_error('数据库操作失败!');
		}
	}


	public function delHotLineMsg(){
		$deleteValue = $this->input->post('deleteValue');
		$deleteArr = explode(',', $deleteValue);

		$res = $this->hotline->deleteHotLineMsg($deleteArr);

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_error('数据库操作失败!');
		}
	}


	
}