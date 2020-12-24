<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 基础数据=>>设备维护
 * @author hwq
 */
class BaseDeviceLogic extends CI_Controller{
	/**
	 * @desc 构造方法
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Basedevice_model','basedevice');
		checksession();
	}


	/**
	 * @desc   展示'设备维护'页面
	 */
	public function index(){
		$select['roadold'] = $this->basedevice->selectRoadOldMsg();

		$this->load->view('admin/BaseData/ManageBaseDevice/BaseDeviceList',$select);
	}



	/**
	 * @desc   '设备维护'->load设备信息
	 */
	public function onLoadBaseDevice(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by deviceid desc';
		}
		$road = $this->input->post('road');
		$status = $this->input->post('status');
		$search = $this->input->post('search');

		$data = $this->basedevice->selectBaseDeviceMsg($road,$status,$search,$pageOnload);

		$imgBaseUrl = 'http://113.247.232.10:9003/video/';
		foreach($data['data'] as $k => $v){
			$data['data'][$k]['operate'] = '<button class="btn btn-success btn-xs m-5" onclick="checkDetail(\''.$v['deviceid'].'\')">查看</button>';

			if ($v['status'] == '0') {
				$data['data'][$k]['status'] = '无效';
				$data['data'][$k]['operate'] .= '<button class="btn btn-info btn-xs" onclick="changeStatus(\''.$v['deviceid'].'\',1)">设为有效</button>';
			}else if ($v['status'] == '1') {
				$data['data'][$k]['status'] = '有效';
				$data['data'][$k]['operate'] .= '<button class="btn btn-danger btn-xs" onclick="changeStatus(\''.$v['deviceid'].'\',0)">设为无效</button>';
			}

			$data['data'][$k]['pic'] = '';
			$pictureFile = $imgBaseUrl.$v['sn'].'.jpg';

			/*if ($this->checkHTTPStatus($pictureFile) != '404') {
				$image_info = getimagesize($pictureFile);
				$base64_image = "data:".$image_info['mime'].";base64,".chunk_split(base64_encode(file_get_contents($pictureFile)));
				$data['data'][$k]['pic'] = '<img class="sn-img" src="'.$base64_image.'" onclick="checkPic(\''.$pictureFile.'\')">';
			}else
				$data['data'][$k]['pic'] = '';*/
			


			$data['data'][$k]['pic'] = '<img class="sn-img" onclick="checkPic(\''.$pictureFile.'\')" src="'.$pictureFile.'"/>';

			//$data['data'][$k]['operate'] .= '<button class="btn btn-info btn-xs" onclick="checkDetail(\''.$v['deviceid'].'\')">查看</button>';
		}

		ajax_success($data['data'],$data['pageOnload']);
	}

	/**
	 * [checkHTTPStatus 获取HTTP状态码]
	 * @version 2016-05-03 1.0
	 * @param   [type]     $str [description]
	 */
	private function checkHTTPStatus($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $status;
	}

	public function changeStatus(){
		$deviceid = $this->input->post('deviceid');
		$status = $this->input->post('status');

		$res = $this->basedevice->updateBaseDevice($deviceid,$status);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}


	/**
	 * @desc   '设备维护'页面->新增/查看->管理设备信息
	 *         tag=0位新增,tag=1为查看
	 * @return [type]      [description]
	 */
	public function operateBaseDevice(){
		$deviceid = $this->input->get('deviceid');
		
		$data = $this->basedevice->selectBaseDeviceMsgById($deviceid);
		$data['deviceid'] = $deviceid;

		$this->load->view('admin/BaseData/ManageBaseDevice/OperateBaseDeviceDetail',$data);
	}


	public function saveDetailMsg(){
		$deviceid = $this->input->post('deviceid');
		$coor_x = $this->input->post('coor_x');
		$coor_y = $this->input->post('coor_y');

		$res = $this->basedevice->updateBaseDeviceDetail($deviceid,$coor_x,$coor_y);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

}