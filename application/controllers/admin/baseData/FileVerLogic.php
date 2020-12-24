<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc	基础数据-数据版本更新控制器类
 * 	      	涉及到的表-gde_filever
 * 	@author hwq
 * 	@date 	2015-9-29
 */
class FileVerLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Filever_model', 'filever');
		checksession();
	}

	public function indexPage(){
		$this->load->view('admin/BaseData/FileVer/FileVerList');
	}


	/**
	 * @desc   '数据版本更新'页面->读取页面列表信息
	 * @data   2015-9-29 15:32:35
	 */
	public function onLoadFileVer(){
		$pageOnload = page_onload();
		if ($pageOnload['OrderDesc'] == '') {
			$pageOnload['OrderDesc'] = '';
		}
		$search = $this->input->post('search');

		$data = $this->filever->selectFileVerMsg($search,$pageOnload);

		//<th class="title" width="5%" center="true" showtype="a" attr="onclick= changeMsg('{fileid}') href='javascript:void(0)' " itemtext="更新">操作
		foreach($data['data'] as $k=>$v ){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="changeMsg('.$v['fileid'].')">更新</lable>';

			if ($v['name'] == '路况简图') {
				$data['data'][$k]['remark'] = '<img src="'.$v['remark'].'" class="publishmap-image" onclick="showLayerImage(this.src)">';
				$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="changeMsg2('.$v['fileid'].')">更新</lable>';
			}
		}
		ajax_success($data['data'],$data['pageOnload']);
	}


	/**
	 * @desc   '数据版本更新'页面->点击更新->获取所要操作的数据并展示'更新具体数据版本'子窗口
	 * @data   2015-9-30 11:00:43
	 * @return [type]      [description]
	 */
	public function changeMsgLogic(){
		$data['isMap'] = $this->input->get('isMap');
		$fileid = $this->input->get('fileid');
		$data['data'] = $this->filever->selectFileVerMsgById($fileid);
		$this->load->view('admin/BaseData/FileVer/changeMsgList',$data);
	}


	public function changeFileVer(){
		$fileid = $this->input->post('fileid');
		$verno = $this->input->post('verno');
		$remark = $this->input->post('remark');
		$isforce = $this->input->post('isforce');

		$res = $this->filever->updateFileVer($fileid,$verno,$remark,$isforce);
		ajax_success($res,null);
		//var_dump($res);exit;
	}
}