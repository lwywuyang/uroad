<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》出行提示的控制器类
 * 	     涉及到的表 - gde-eventtraffic
 * @author hwq
 * @version 1.0
 */
class RoadSummaryLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Roadsummary_model', 'roadsummary');
		checksession();
	}


	/**
	 * @desc   打开'信息发布'页面
	 */
	public function index(){
		//事件类型
		$this->load->view('admin/MsgPublish/RoadSummary/RoadSummaryList');
	}
	

	/**
	 * @desc   获取'信息发布'页面信息
	 */
	public function onLoadRoadSummary(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by updatetime desc';
		}
		$status = $this->input->post('status');
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data = $this->roadsummary->selectRoadSummary($status,$keyword,$pageOnload);

		foreach($data['data'] as $k => $v){
			switch ($v['eventstatus']) {
				case '1012004':
					$data['data'][$k]['statusName'] = '发布中';
					break;
				case '1012005':
					$data['data'][$k]['statusName'] = '已结束';
					break;
				default:
					break;
			}

			if ($v['imgurl']) {
				$data['data'][$k]['image'] = '<img src="'.$v['imgurl'].'" onclick="showLayerImage(this.src)" class="roadsummary-image" >';
			}

			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkDetail(\''.$v['eventid'].'\')">查看</lable>';
		}
		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function operateRoadSummaryDetail(){
		$eventid = $this->input->get('eventid');

		$data['eventid'] = $eventid;
		if ($eventid != '0') {
			$data['data'] = $this->roadsummary->selectRoadSummaryById($eventid);
		}

		//$data['road'] = $this->roadsummary->selectAllRoad();

		$this->load->view('admin/MsgPublish/RoadSummary/RoadSummaryDetail',$data);
	}

	public function saveRoadSummary(){
		//eventid:eventid,title:title,reportinfo:reportinfo,jpgurl:jpgurl
		$eventid = $this->input->post('eventid');
		$title = $this->input->post('title');
		$intime = $this->input->post('intime');
		$reportinfo = $this->input->post('reportinfo');
		$jpgurl = $this->input->post('jpgurl');
		$status = $this->input->post('status');
		$EmplId = getsessionempid();
		$EmplName = getsessionempname();

		if ($eventid == '0'){//新增
			$res = $this->roadsummary->insertNewRoadSummary($title,$intime,$reportinfo,$jpgurl,$status,$EmplId,$EmplName);
		}else{//修改
			$res = $this->roadsummary->updateRoadSummary($eventid,$title,$intime,$reportinfo,$jpgurl,$status,$EmplId,$EmplName);
		}
		
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作出错!');
	}


	public function delRoadSummary(){
		$deleteValue = $this->input->post('deleteValue');
		$deleteArr = explode(',',$deleteValue);

		$res = $this->roadsummary->deleteRoadSummaryMsg($deleteArr);

		if ($res === true) {
			ajax_success(true,null);
		}else{
			ajax_error($res);
		}
	}

}