<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* ETC网点
*/
class AdPicLogic extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/AdPic_model', 'AdPic');
		checksession();
	}


	/**
	 * 列表查看
	 */
	public function index(){
		$this->load->view('admin/ETCManage/AdPicList');
	}


	/**
	 * 查找数据
	 */
	public function onLoadAdPic(){
		//查找员工数据
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc'] = '';
		}
		$StartTime = $this->input->post('StartTime');
		$EndTime = $this->input->post('EndTime');
		$stateSel = $this->input->post('stateSel');

		$data = $this->AdPic->getAdPicData($StartTime,$EndTime,$stateSel,$pageOnload);

		foreach ($data['data'] as $k => $v) {
			if($v['imageurl'] != ''){
				$data['data'][$k]['imageurl']='<img src="'.$v['imageurl'].'" class="ad-image" onclick="showLayerImage(this.src)" />';
			}

			if($v['state'] == '1'){
				$data['data'][$k]['state'] = '发布中';
				$data['data'][$k]['operate'] = '<lable class="btn btn-danger btn-xs m-5" onclick="changeState(\''.$v['id'].'\',0)">取消发布</lable>';
			}else{
				$data['data'][$k]['state'] = '未发布';
				$data['data'][$k]['operate'] = '<lable class="btn btn-info btn-xs m-5" onclick="changeState(\''.$v['id'].'\',1)">发布</lable>';
			}

			$data['data'][$k]['operate'] .= '<lable class="btn btn-success btn-xs m-5" onclick="detail(\''.$v['id'].'\')">详情</lable>';
			
		}

		ajax_success($data['data'],$data["PagerOrder"]);
	}


	/**
	 * 编辑添加
	 */
	public function detailAdPic(){
		$id = $this->input->get('id');

		$data = [];
		if($id != '0'){
			$data = $this->AdPic->checkAdPicData($id);
		}

		$this->load->view('admin/ETCManage/AdPicDetail',$data);
	}


	/**
	 * 保存操作
	 */
	public function onSaveAdPic(){
		date_default_timezone_set('PRC');

		$AdPicdata['id'] = $this->input->post('id');
		$AdPicdata['redirecturl'] = $this->input->post('redirecturl');
		$AdPicdata['seq'] = $this->input->post('seq');
		$AdPicdata['imageurl'] = $this->input->post('imageurl');

		if ($AdPicdata['id'] == '0') {
			$AdPicdata['created'] = date('Y-m-d h:i:s');
			$AdPicdata['modified'] = date('Y-m-d h:i:s');
		}else
			$AdPicdata['modified'] = date('Y-m-d h:i:s');

		$res = $this->AdPic->saveAdPic($AdPicdata);

		if($res === true)
			ajax_success(true,NULL);
		else
			ajax_error($res);
	}


	public function setNewState(){
		$id = $this->input->post('id');
		$state = $this->input->post('state');

		$res = $this->AdPic->updateState($id,$state);

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}
}