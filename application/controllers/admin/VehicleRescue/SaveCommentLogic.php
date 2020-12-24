<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 救援评价
 */
class SaveCommentLogic extends CI_Controller{
	/**
	 * [__construct 构造方法]
	 * @version 2016-05-17 1.0
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('VehicleRescue/SaveComment_model','savecomment');
		$this->load->model('Dict_model','dict');
		checksession();
	}


	/**
	 * @desc   展示'救援评价'页面
	 * @data   2015-9-23 08:54:34
	 */
	public function index(){
		//$data['teamType'] = $this->dict->SelectDict(1020);
		$data['manager'] = $this->savecomment->selectRoadPer();
		$this->load->view('admin/VehicleRescue/SaveCommentList',$data);
	}



	/**
	 * @desc   '救援评价'->load救援评价
	 * @data   2015-9-23 10:01:17
	 */
	public function onLoadSaveComment(){
		$pageOnload = page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ""){
			$pageOnload['OrderDesc'] = '';
		}
		//$managerSel = $this->input->post('managerSel');
		//$typeSel = $this->input->post('typeSel');
		$search = $this->input->post('search');

		$data = $this->savecomment->selectSaveCommentMsg($search,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$star = '<span class="redstar">';
			for($i = $v['level']; $i > 0; $i--){
				$star .= '★';
			}
			$star .= '</span>';
			$data['data'][$k]['star'] = $star;
			//$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="operateSaveComment(\''.$v['id'].'\')">查看</lable>';
		}

		ajax_success($data['data'],$data['pageOnload']);
	}


	/**
	 * @desc   '救援评价'页面->新增/查看->管理救援评价
	 *         tag=0位新增,tag=1为查看
	 * @data   2015-9-23 11:24:11
	 * @return [type]      [description]
	 */
	public function operateSaveCommentMsg(){
		$id = $this->input->get('id');

		
		if ($id != '0') {
			$data = $this->savecomment->selectSaveCommentDetail($id);
		}
		$data['id'] = $id;
		$data['roadper'] = $this->savecomment->selectRoadPer();
		$data['teamType'] = $this->dict->SelectDict(1020);

		$this->load->view('admin/VehicleRescue/SaveCommentDetail',$data);
	}


	/**
	 * @desc   '救援评价'->新增/查看救援评价->保存操作后数据
	 * @data   2015-9-23 18:39:47
	 * @return [type]      [description]
	 */
	public function saveSaveCommentMsg(){
		$id = $this->input->post('id');

		$data = array(
			'name' => $this->input->post('name'),
			'managerid' => $this->input->post('managerid'),
			'phone' => $this->input->post('phone'),
			'managerzone' => $this->input->post('managerzone'),
			'type' => $this->input->post('typeSel'),
			'seq' => $this->input->post('seq')
			);

		if ($id == '0') {//新增
			$res = $this->savecomment->insertSaveCommentMsg($data);
		}else{//修改
			$data['id'] = $id;
			$res = $this->savecomment->updateSaveCommentMsg($data);
		}

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
		
	}


	/**
	 * @desc   '救援评价'->删除
	 * @data   2015-9-24 09:38:56
	 * @return [type]      [description]
	 */
	public function delSaveComment(){
		$deleteValue = $this->input->post('deleteValue');

		$deleteArr = explode(',', $deleteValue);

		$res = $this->savecomment->deleteSaveCommentMsg($deleteArr);

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

}