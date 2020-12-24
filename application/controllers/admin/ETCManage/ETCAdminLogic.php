<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	基础数据-收费站维护
 * 	涉及到的表- gde-roadpoi
 */
class ETCAdminLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/Etcadmin_model', 'Etcadmin');
		checksession();
	}

	/**
	 * 展示'收费站维护'页面
	 * @date 2015-9-17 11:54:20 
	 */
	public function indexPage(){
		//获取下拉框内容
//		$select['road'] = $this->roadpoi->selectAllRoad();
//		$select['type'] = $this->roadpoi->selectAllType();
	
		$this->load->view('admin/ETCManage/ETCAdmin/ETCAdminList');
	}

	

	/**
	 * @desc   打开'加油站维护'->加载页面相应内容
	 * @data   2015-9-17 15:20:34
	 * @return [type]      [description]
	 */
	public function onLoadETCAdmin(){
		set_time_limit(0);
		//分页
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by intime DESC';
		}
		$roadId = $this->input->post('roadId');
		$type = $this->input->post('type');
		$keyword = $this->input->post('keyword');
		//var_dump($keyword);
		
		$data=$this->Etcadmin->selectETCAdminMsg($roadId,$type,$keyword,$pageOnload);
//		foreach($data['data'] as $k=>$v){
//			//<th class="title" width="10%" itemvalue="" center="true" showtype="a" attr="onclick= checkDetail('{poiid}') href='javascript:void(0)'" itemtext="查看">操作
//			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="checkDetail('.$v['poiid'].')">查看</lable>';
//		}

		ajax_success($data['data'],$data["pageOnload"]);
	}


	/**
	 * @desc   '加油站维护'->查看某站点详情操作->获取站点详情并展示'站点详情页面'
	 * @data   2015-9-17 15:22:18
	 * @return [type]      [description]
	 */
	public function checkETCAdminDetail(){
		$id = $_GET['id'];
//		$data['road'] = $this->roadpoi->selectAllRoad();
//		$data['type'] = $this->roadpoi->selectAllType();
		if(!empty($id)){//修改
			$data['id'] = $id;
			$data['data'] = $this->Etcadmin->selectETCAdminMsgById($id);
			if($data['data'][0]['vehtype']>=11 && $data['data'][0]['vehtype']<=15){
				$data['data'][0]['vehtype'] = 15;
			}
		}else{//站点id为空或0->新增
			$data['id'] = 0;
//			$data['data'] = array(array('roadoldid'=>0,'pointtype'=>0));
//			$data['hub'] = $this->Etcadmin->selectAllRoad();
		}
		
		//var_dump($data['hub']);exit;
		$this->load->view('admin/ETCManage/ETCAdmin/etcAdminDetail',$data);
	}

	/**
	 * @desc   '加油站维护'->查看站点详情->修改并点击确定->保存站点信息
	 * @data   2015-9-17 18:04:05
	 * @return [type]      [description]
	 */
	public function saveRoadPoiMsg(){
//        $poiid = $this->input->post('poiid');
//		$name = $this->input->post('name');
//		$typeSel = $this->input->post('typeSel');
//		$stationcode = $this->input->post('stationcode');
//		$roadSel = $this->input->post('roadSel');
//		$phone = $this->input->post('phone');
//		$city = $this->input->post('city');
//		$miles = $this->input->post('miles');
//		$coor_x = $this->input->post('coor_x');
//		$coor_y = $this->input->post('coor_y');
//		$nowinwaynum = $this->input->post('nowinwaynum');
//		$nowexitwaynum = $this->input->post('nowexitwaynum');
//		$nowinetcnum = $this->input->post('nowinetcnum');
//		$nowexitetcnum = $this->input->post('nowexitetcnum');
//		$hubArr = $this->input->post('hub');
//		$address = $this->input->post('address');
//
//		$hub = implode(',', $hubArr);
		//var_dump($hub);exit;
//		if ($poiid == '') {ajax_success('获取站点ID出错',null);exit;}
//		if ($name == '') {ajax_success('获取站点名称出错',null);exit;}
//		if ($typeSel == '') {ajax_success('获取站点类型出错',null);exit;}
//		if ($stationcode == '') {ajax_success('获取站点编号出错',null);exit;}
//		if ($roadSel == '') {ajax_success('获取站点地址出错',null);exit;}
		/*if ($phone == '') {ajax_success('获取站点电话出错',null);exit;}
		if ($city == '') {ajax_success('获取城市出错',null);exit;}
		if ($miles == '') {ajax_success('获取公里数出错',null);exit;}
		if ($coor_x == '') {ajax_success('获取站点经度出错',null);exit;}
		if ($coor_y == '') {ajax_success('获取站点纬度出错',null);exit;}
		if ($nowinwaynum == '') {ajax_success('获取入口车道数出错',null);exit;}
		if ($nowexitwaynum == '') {ajax_success('获取出口车道数出错',null);exit;}
		if ($nowinetcnum == '') {ajax_success('获取入口ETC车道数出错',null);exit;}
		if ($nowexitetcnum == '') {ajax_success('获取出口ETC车道数出错',null);exit;}
		if ($address == '') {ajax_success('获取站点地址出错',null);exit;}*/

		$id = $this->input->post('id');
		$content = $this->input->post('content');
		$content['id'] = $id;
		$content['modified'] = date('Y-m-d H:i:s');
		$data = $this->Etcadmin->updateRoadPoiMsg($id,$content);
		ajax_success($data,null);
	}


	/**
	 * @desc   '加油站维护'->新增站点->填写并点击确定->保存站点信息
	 * @data   2015-9-17 18:05:20
	 * @return [type]      [description]
	 */
	public function saveNewRoadPoiMsg(){
        //$poiid = $this->input->post('poiid');
//		$name = $this->input->post('name');
//		$typeSel = $this->input->post('typeSel');
//		$stationcode = $this->input->post('stationcode');
//		$roadSel = $this->input->post('roadSel');
//		$phone = $this->input->post('phone');
//		$city = $this->input->post('city');
//		$miles = $this->input->post('miles');
//		$coor_x = $this->input->post('coor_x');
//		$coor_y = $this->input->post('coor_y');
//		$nowinwaynum = $this->input->post('nowinwaynum');
//		$nowexitwaynum = $this->input->post('nowexitwaynum');
//		$nowinetcnum = $this->input->post('nowinetcnum');
//		$nowexitetcnum = $this->input->post('nowexitetcnum');
//		$hubArr = $this->input->post('hub');
//		$address = $this->input->post('address');
//
//		$hub = implode(',', $hubArr);

		//if ($poiid == '') {ajax_success('获取站点ID出错',null);exit;}
//		if ($name == '') {ajax_success('获取站点名称出错',null);exit;}
//		if ($typeSel == '') {ajax_success('获取站点类型出错',null);exit;}
//		if ($stationcode == '') {ajax_success('获取站点编号出错',null);exit;}
//		if ($roadSel == '') {ajax_success('获取站点地址出错',null);exit;}
		/*if ($phone == '') {ajax_success('获取站点电话出错',null);exit;}
		if ($city == '') {ajax_success('获取城市出错',null);exit;}
		if ($miles == '') {ajax_success('获取公里数出错',null);exit;}
		if ($coor_x == '') {ajax_success('获取站点经度出错',null);exit;}
		if ($coor_y == '') {ajax_success('获取站点纬度出错',null);exit;}
		if ($nowinwaynum == '') {ajax_success('获取入口车道数出错',null);exit;}
		if ($nowexitwaynum == '') {ajax_success('获取出口车道数出错',null);exit;}
		if ($nowinetcnum == '') {ajax_success('获取入口ETC车道数出错',null);exit;}
		if ($nowexitetcnum == '') {ajax_success('获取出口ETC车道数出错',null);exit;}
		if ($address == '') {ajax_success('获取站点地址出错',null);exit;}*/


		$content = $this->input->post('content');
		$content['intime'] = date('Y-m-d H:i:s');
		$content['operatorname'] = getsessionempname();
		$data = $this->Etcadmin->insertRoadPoiMsg($content);
		ajax_success($data,null);
	}


	/**
	 * @desc   '加油站维护'页面->删除->删除加油站信息
	 * @data   2015-9-16 11:21:51
	 * @return [type]      [description]
	 */
	public function delRoadPoi(){
		$deleteValue = $this->input->post('deleteValue');

		$res = $this->Etcadmin->deleteRoadPoi($deleteValue);
		//var_dump($res);
		ajax_success($res,null);
	}


}