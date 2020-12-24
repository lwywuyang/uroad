<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 中队信息
 */
class TeamLogic extends CI_Controller{
	/**
	 * [__construct 构造方法]
	 * @version 2016-05-17 1.0
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('VehicleRescue/Team_model','team');
		$this->load->model('Dict_model','dict');
		checksession();
	}


	/**
	 * @desc   展示'中队信息'页面
	 * @data   2015-9-23 08:54:34
	 */
	public function index(){
		$data['teamType'] = $this->dict->SelectDict(1020);
		$data['manager'] = $this->team->selectRoadPer();
		$this->load->view('admin/VehicleRescue/TeamList',$data);
	}



	/**
	 * @desc   '中队信息'->load中队信息
	 * @data   2015-9-23 10:01:17
	 */
	public function onLoadTeam(){
		$pageOnload = page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ""){
			$pageOnload['OrderDesc'] = '';
		}
		$managerSel = $this->input->post('managerSel');
		$typeSel = $this->input->post('typeSel');
		$search = $this->input->post('search');

		$data = $this->team->selectTeamMsg($managerSel,$typeSel,$search,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="operateTeam(\''.$v['id'].'\')">查看</lable>';
		}

		ajax_success($data['data'],$data['pageOnload']);
	}


	/**
	 * @desc   '中队信息'页面->新增/查看->管理中队信息
	 *         tag=0位新增,tag=1为查看
	 * @data   2015-9-23 11:24:11
	 * @return [type]      [description]
	 */
	public function operateTeamMsg(){
		$id = $this->input->get('id');

		
		if ($id != '0') {
			$data = $this->team->selectTeamDetail($id);
		}
		$data['id'] = $id;
		$data['roadper'] = $this->team->selectRoadPer();
		$data['teamType'] = $this->dict->SelectDict(1020);

		$this->load->view('admin/VehicleRescue/TeamDetail',$data);
	}


	/**
	 * @desc   '中队信息'->新增/查看中队信息->保存操作后数据
	 * @data   2015-9-23 18:39:47
	 * @return [type]      [description]
	 */
	public function saveTeamMsg(){
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
			$res = $this->team->insertTeamMsg($data);
		}else{//修改
			$data['id'] = $id;
			$res = $this->team->updateTeamMsg($data);
		}

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
		
	}


	/**
	 * @desc   '中队信息'->删除
	 * @data   2015-9-24 09:38:56
	 * @return [type]      [description]
	 */
	public function delTeam(){
		$deleteValue = $this->input->post('deleteValue');

		$deleteArr = explode(',', $deleteValue);

		$res = $this->team->deleteTeamMsg($deleteArr);

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

}