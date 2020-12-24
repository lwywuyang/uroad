<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布=>>救援电话
 * @author hwq
 */
class PhoneLogic extends CI_Controller{
	/**
	 * @desc 构造方法
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Phone_model','phone');
		checksession();
	}


	/**
	 * @desc   展示'设备维护'页面
	 */
	public function indexPage(){
		$select['roadold'] = $this->phone->selectRoadOldMsg();
		$this->load->view('admin/MsgPublish/Phone/PhoneList',$select);
	}


	/**
	 * @desc   '设备维护'->load设备信息
	 */
	public function onLoadPhone(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by newcode asc,id desc';
		}
		$road = $this->input->post('road');
		$search = $this->input->post('search');

		$data = $this->phone->selectPhoneMsg($road,$search,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="operatePhone('.$v['id'].')">查看</lable>';
		}
		
		ajax_success($data['data'],$data['pageOnload']);
	}


	/**
	 * @desc   '设备维护'页面->新增/查看->管理设备信息
	 */
	public function operatePhone(){
		$id = $this->input->get('id');

		$data['roadold'] = $this->phone->selectRoadOldMsg();
		if ($id != '0') {//新增
			$data['msg'] = $this->phone->selectPhoneMsgById($id);
		}
		$this->load->view('admin/MsgPublish/Phone/OperatePhoneDetail',$data);
	}


	/**
	 * @desc   '设备维护'->新增/查看设备信息->保存操作后数据
	 */
	public function savePhoneMsg(){
		$id = $this->input->post('id');
		$roadSel = $this->input->post('roadSel');
		$phone = $this->input->post('phone');
		$remark = $this->input->post('remark');
		//var_dump($id,$roadSel,$phone,$remark);exit;
		if ($id == '0') {//新增
			$res = $this->phone->insertPhoneMsg($roadSel,$phone,$remark);
		}else{//修改
			$res = $this->phone->updatePhoneMsg($id,$roadSel,$phone,$remark);
		}

		if ($res)
			ajax_success($res,null);
		else
			ajax_error('error!');
		
	}


	/**
	 * @desc   '设备维护'->删除
	 */
	public function delPhone(){
		$deleteValue = $this->input->post('deleteValue');
		//var_dump($deleteValue);exit;
		$deleteArr = explode(',', $deleteValue);
		//$deleteArr = array(1,2);
		$res = $this->phone->deletePhoneMsg($deleteArr);
		//var_dump($res);exit;
		ajax_success($res,null);
	}

}