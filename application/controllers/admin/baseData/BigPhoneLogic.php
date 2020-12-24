<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc 	基础数据->大手机维护
 * 	       	涉及到的表:gde-bigphone
 * 	@author hwq
 * 	@date 	2015年9月25日
 */

class BigPhoneLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Bigphone_model', 'bigphone');
		checksession();
	}

	/**
	 * [indexPage 路段维护]
	 * @return [type] [description]
	 */
	public function indexPage(){
		$this->load->view('admin/BaseData/BigPhone/BigPhoneList');
	}

	/**
	 * [onLoadNews 路段维护-查询数据]
	 * @return [type] [description]
	 */
	public function onLoadBigPhone(){

		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by id desc';
		}
		$keyword = $this->input->post('search');
		//var_dump($keyword);
		
		$url = 'http://guizhougstapi.u-road.com:8080/bigphoneAdminInterface/index.php?/admin/baseData/BigPhoneLogic/onLoadBigPhone';
		$paramsArr = array('search'=>$keyword,'page'=>$pageOnload['CurrentPage']);
		//log_message("info",'params--'.$pageOnload['CurrentPage']);
		//print_r($paramsArr);//exit;
		$result = network_post($url,$paramsArr);
		$phpArr = json_decode($result,true);

		//<th class="title" width="10%" itemvalue="" center="true" showtype="a" attr="onclick= checkBigPhone('{id}') href='javascript:void(0)' " itemtext="查看">操作
		foreach($phpArr['data'] as $k=>$v){
			$phpArr['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="checkBigPhone('.$v['id'].')">查看</lable>';
		}
		ajax_success($phpArr['data'],$phpArr["PagerOrder"]);
		/*print_r($result);
		var_dump($result);exit;*/
		/*$data=$this->bigphone->selectBigPhoneMsg($keyword,$pageOnload);
		//var_dump($data['sql']);exit;
		foreach ($data['data'] as $k => $v) {
			switch ($v['status']) {
				case 1:
					$data['data'][$k]['statusName'] = '正常';
					break;
				case 0:
					$data['data'][$k]['statusName'] = '异常';
					break;
				default:
					$data['data'][$k]['statusName'] = '';
			}
		}

		//var_dump($data);exit;
		ajax_success($data['data'],$data["pageOnload"]);*/
	}


	/**
	 * @desc   '大手机维护'->点击管理设备所在城市->展示'维护大手机信息'页面
	 *         '大手机维护'->新增大手机设备->展示'新增大手机设备信息'页面
	 * @data   2015-9-25 15:02:58
	 */
	public function operateBigPhoneMsgList(){
		$tag = $this->input->get('tag');//预设的tag有两种值,0修改,1新增
		//var_dump($tag);exit;
		if ($tag === '1') {
			//$data['id'] = 0;
			//新增和修改的页面有些许差别,就不整合在一个页面了,分开两个页面
			$this->load->view('admin/BaseData/BigPhone/AddBigPhoneList');
		}else if ($tag === '0') {
			$data['id'] = $this->input->get('id');
			$data['data'] = $this->bigphone->selectBigPhoneMsgById($data['id']);
			foreach ($data['data'] as $k => $v) {
			switch ($v['status']) {
				case 1:
					$data['data'][$k]['statusName'] = '正常';
					break;
				case 0:
					$data['data'][$k]['statusName'] = '异常';
					break;
				default:
					$data['data'][$k]['statusName'] = '';
			}
		}
			$this->load->view('admin/BaseData/BigPhone/CheckBigPhoneList',$data);
		}else{
			echo "<script>alert('非法的tag参数!');</script>";
		}
		
		//var_dump($data);exit;
	}


	public function checkDeviceId(){
		$deviceid = $this->input->post('deviceid');
		$phoneid = $this->input->post('phoneid');
		$res = $this->bigphone->checkIdExist($deviceid,$phoneid);
		echo $res;
		//var_dump($res);
		//return $res;
	}



	/**
	 * @desc   '大手机维护'->点击管理所在城市->保存数据并返回
	 * @data   2015-9-25 15:31:05
	 * @return [type]      [description]
	 */
	public function saveBigPhoneMsg(){
		$phoneid = $this->input->post('phoneid');
		$deviceid = $this->input->post('deviceid');
		$devicename = $this->input->post('devicename');
		$longitude = $this->input->post('longitude');
		$latitude = $this->input->post('latitude');
		$remark = $this->input->post('remark');
		$city = $this->input->post('city');

		if ($phoneid == 0) {//新增
			$res = $this->bigphone->insertBigPhoneMsg($deviceid,$devicename,$longitude,$latitude,$remark,$city);
		}else{//修改
			$res = $this->bigphone->updateBigPhoneMsg($phoneid,$deviceid,$devicename,$longitude,$latitude,$remark,$city);
		}


		//$res = $this->bigphone->updateCityMsg($id,$city);
		ajax_success($res,null);
	}


	/**
	 * @desc '大手机维护'->点击新增->展示'新增大手机'页面
	 * @data 2015-9-25 15:53:02
	 */
	/*public function addNewBigPhone(){
		$this->load->view('admin/BaseData/BigPhone/AddBigPhoneList');
	}
*/

	/**
	 * @desc   '大手机维护'->新增->确定->保存新大手机信息
	 * @data   2015-9-25 16:05:53
	 * @return [type]      [description]
	 */
	//信息不全,暂时舍弃
	public function saveNewBigPhoneMsg(){
		/*//name:name,typeSel:typeSel,longitude:longitude,latitude:latitude,city:city
		$name = $this->input->post('name');
		$type = $this->input->post('typeSel');
		$longitude = $this->input->post('longitude');
		$latitude = $this->input->post('latitude');
		$city = $this->input->post('city');

		return $res = $this->bigphone->insertNewBigPhoneMsg($name,$type,$longitude,$latitude,$city);*/
	}


	/**
	 * @desc   '大手机维护'->删除->删除所选内容
	 * @data   2015-9-25 16:24:11
	 * @return [boolean]      [返回操作结果]
	 */
	public function delBigPhone(){
		$deleteValue = $this->input->post('deleteValue');
		//var_dump($deleteValue);exit;
		$deleteArr = explode(',',$deleteValue);
		//var_dump($deleteArr);
		$res = $this->bigphone->deleteBigPhone($deleteArr);
		//var_dump($res);
		ajax_success($res,null);
	}


}