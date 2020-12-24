<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 基础数据-》中心权限管理控制器类
 * 	     涉及到的表 - 
 * @author hwq
 * @date 2015-11-20
 * @version 1.0
 */
class CenterCompetenceLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Centercompetence_model', 'center');
		checksession();
	}


	/**
	 * @desc   打开'中心权限管理'页面
	 */
	public function indexPage(){
		$this->load->view('admin/BaseData/CenterCompetence/CenterCompetenceList');
	}
	

	/**
	 * @desc   获取'中心权限管理'页面信息
	 */
	public function onLoadCenterCompetenceMsg(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by id asc';
		}
		/*$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');*/
		$centerName = $this->input->post('centerName');
		//查询数据库
		$data = $this->center->selectCenterCompetenceMsg($centerName,$pageOnload);
		//var_dump($data);exit;
		foreach ($data['data'] as $k => $v) {
			$data['data'][$k]['road'] = $this->getRoadNameByIds($v['roadoldids']);
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="manageCompetence('.$v['id'].')">管理</lable>';
		}
		//var_dump($data['data']);exit;
		//$imgUrl = $this->config->item('img_url');
		ajax_success($data['data'],$data["pageOnload"]);
	}

	/**
	 * @desc   根据roadoldids字符串查询路段名称字符串
	 * @param  [type]      $roadOldIds [description]
	 * @return [type]                  [description]
	 */
	private function getRoadNameByIds($roadOldIds){
		if ($roadOldIds != '') {
			$roadOldIdArr = explode(',', $roadOldIds);
		}else{
			return '';
		}

		$roadName = '';
		foreach ($roadOldIdArr as $k => $v) {
			if ($v != '') {
				$roadName .= $this->center->selectRoadNameById($v).',';
			}
		}

		$roadName = substr($roadName, 0,count($roadName)-2);
		return $roadName;
	}


	/**
	 * @desc   新增或修改分中心信息
	 * @return [type]      [description]
	 */
	public function showCompetenceManageList(){
		$id = $this->input->get('id');//分中心id

		if ($id == '0') {
			$data['subMsg']['id'] = 0;
		}else{
			$data['subMsg'] = $this->center->selectSubCenterMsgById($id);
		}
		$data['road'] = $this->center->selectAllRoad();

		$this->load->view('admin/BaseData/CenterCompetence/CompetenceManageList',$data);
	}


	/**
	 * @desc   保存分中心信息,新增/修改
	 * @return [type]      [description]
	 */
	public function saveSubCenterMsg(){
		$id = $this->input->post('id');
		$centerName = $this->input->post('centerName');
		$roadIds = $this->input->post('roadIds');

		$res = $this->center->saveSubCenterMsg($id,$centerName,$roadIds);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('操作数据库失败!');
	}
	

	public function delSubCenter(){
		$deleteValue = $this->input->post('deleteValue');
		$deleteArr = explode(',',$deleteValue);
		//var_dump($deleteArr);
		$res = $this->center->deleteSubCenter($deleteArr);
		//var_dump($res);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('删除失败!');
		
	}
}