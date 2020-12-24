<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 基础数据=>>设备维护
 * @time 2015-9-23 10:01:37
 * @author hwq
 */
class DeviceLogic extends CI_Controller{
	/**
	 * @desc 构造方法
	 * @data 2015-9-23 08:53:02
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Device_model','device');
		checksession();
	}


	/**
	 * @desc   获取页面下拉框内容,包括路段下拉框和类型下拉框
	 * @data   2015-9-23 11:28:38
	 * @return [array]      [页面选择下拉框内容]
	 */
	private function getSelectionMsg(){
		$select['roadold'] = $this->device->selectRoadOldMsg();
		$select['type'] = $this->device->selectTypeMsg();
		return $select;
	}


	/**
	 * @desc   展示'设备维护'页面
	 * @data   2015-9-23 08:54:34
	 */
	public function indexPage(){
		//调用本类私有方法,获取下拉框信息
		$select = $this->getSelectionMsg();
		$this->load->view('admin/BaseData/ManageDevice/DeviceList',$select);
	}



	/**
	 * @desc   '设备维护'->load设备信息
	 * @data   2015-9-23 10:01:17
	 */
	public function onLoadDevice(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by deviceid desc';
		}
		$road = $this->input->post('road');
		$type = $this->input->post('type');
		$search = $this->input->post('search');

		$data = $this->device->selectDeviceMsg($road,$type,$search,$pageOnload);
		//<th class="title" width="10%" center="true" showtype="a|a" attr="onclick= checkDevice('{deviceid}') href='javascript:void(0)'|onclick= checkPic('{picturefile}') href='javascript:void(0)'" itemtext="查看|快拍">操作
		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs m-r-5" onclick="checkDevice(\''.$v['deviceid'].'\')">查看</lable>';
			//<lable class="btn btn-info btn-xs m-r-5" onclick="checkPic(\''.$v['picturefile'].'\')">快拍</lable>
			$data['data'][$k]['picture']='<img src="'.$v['picturefile'].'" onclick="checkPic(\''.$v['picturefile'].'\')" class="picture">';
			switch ($v['status']) {
				case '1':
					$data['data'][$k]['statusname']='开启';
					$data['data'][$k]['operate'].='<lable class="btn btn-danger btn-xs" onclick="changeStatus(\''.$v['deviceid'].'\',0)">关闭</lable>';
					break;
				case '0':
					$data['data'][$k]['statusname']='关闭';
					$data['data'][$k]['operate'].='<lable class="btn btn-warning btn-xs" onclick="changeStatus(\''.$v['deviceid'].'\',1)">开启</lable>';
					break;
				default:
					break;
			}
		}
		//var_dump($data['sql']);exit;
		ajax_success($data['data'],$data['pageOnload']);
	}


	/**
	 * @desc   '设备维护'页面->新增/查看->管理设备信息
	 *         tag=0位新增,tag=1为查看
	 * @data   2015-9-23 11:24:11
	 * @return [type]      [description]
	 */
	public function operateDevice(){
		$data['tag'] = $this->input->get('tag');
		//获取下拉框信息
		$data['select'] = $this->getSelectionMsg();
		if ($data['tag'] == '0') {//新增
			$data['deviceid'] = 0;
		}else if ($data['tag'] == '1') {//查看
			$data['deviceid'] = $this->input->get('deviceid');

			$data['data'] = $this->device->selectDeviceMsgById($data['deviceid']);

		}else{
			exit('传递参数出错');
		}
		$this->load->view('admin/BaseData/ManageDevice/OperateDeviceList',$data);
	}


	/**
	 * @desc   '设备维护'->新增/查看设备信息->保存操作后数据
	 * @data   2015-9-23 18:39:47
	 * @return [type]      [description]
	 */
	public function saveDeviceMsg(){
		//deviceid:deviceid,name:name,type:type,roadold:roadold,direction:direction,coor_x:coor_x,coor_y:coor_y,miles:miles,location:location,picture:picture
		$deviceid = $this->input->post('deviceid');
		$name = $this->input->post('name');
		$type = $this->input->post('type');
		$roadold = $this->input->post('roadold');
		$direction = $this->input->post('direction');
		$coor_x = $this->input->post('coor_x');
		$coor_y = $this->input->post('coor_y');
		$miles = $this->input->post('miles');
		$remark = $this->input->post('remark');
		$picture = $this->input->post('picture');

		if ($deviceid == 0) {//新增
			$res = $this->device->insertDeviceMsg($name,$type,$roadold,$direction,$coor_x,$coor_y,$miles,$remark,$picture);
		}else{//修改
			$res = $this->device->updateDeviceMsg($deviceid,$name,$type,$roadold,$direction,$coor_x,$coor_y,$miles,$remark,$picture);
		}
		ajax_success($res,null);
	}


	/**
	 * @desc   '设备维护'->删除
	 * @data   2015-9-24 09:38:56
	 * @return [type]      [description]
	 */
	public function delDevice(){
		$deleteValue = $this->input->post('deleteValue');
		//var_dump($deleteValue);exit;
		$deleteArr = explode(',', $deleteValue);
		//$deleteArr = array(1,2);
		$res = $this->device->deleteDeviceMsg($deleteArr);
		//var_dump($res);exit;
		ajax_success($res,null);
	}



	public function setNewStatus(){
		$deviceid = $this->input->post('deviceid');
		$newstatus = $this->input->post('newstatus');

		$res = $this->device->updateNewStatus($deviceid,$newstatus);


		if ($res)
			ajax_success(true,null);
		else
			ajax_error('修改设备"是否进行快拍截图"状态失败！');
		
	}

}