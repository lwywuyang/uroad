<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc 	基础数据->欢迎页面控制器
 * 	       	涉及到的表:gde-welcomejpg
 * 	@author hwq
 * 	@date 	2015-11-5
 */

class DevicePoiLogic extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/DevicePoi_model', 'devicepoi');
		checksession();
	}


	public function indexPage(){
		$this->load->view('admin/BaseData/DevicePoi/DevicePoiList');
	}


	public function onLoadDevicePoiMsg(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by deviceid desc';
		}
		/*$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');*/
		
		$data = $this->devicepoi->selectDevicePoiMsg($pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['picturefile']='<img class="picture" onclick="showLayerImageJs(this.src)" src="'.$v['picturefile'].'">';
			$data['data'][$k]['operate'] = '<button class="btn btn-success btn-xs" onclick="uploadPicture(\''.$v['deviceid'].'\')">上传图片</button>';
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function showUploadList(){
		$data['deviceid'] = $this->input->get('deviceid');

		$data['data'] = $this->devicepoi->selectDevicePicture($data['deviceid']);

		$this->load->view('admin/BaseData/DevicePoi/uploadList',$data);
	}


	public function savePictureMsg(){
		$deviceid = $this->input->post('deviceid');
		$picture = $this->input->post('picture');

		$res = $this->devicepoi->updatePicInDevicePoi($deviceid,$picture);
		
		if($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作失败!');
	}


	public function delDevicePoiMsg(){
		$delValue = $this->input->post('deleteValue');

		$res = $this->devicepoi->deleteDevicePoiMsg($delValue);

		if($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作失败!');
	}


}