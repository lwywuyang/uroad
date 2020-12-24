<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》首次关注控制器类
 * 	     主要的表 - 
 * @author hwq
 * @date 2015-11-4
 * @version 1.0
 */
class FirstAttentionLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Firstattention_model', 'attention');
		checksession();
	}


	/**
	 * @desc   打开'首次关注'页面
	 * @data   2015-10-9 17:47:24
	 */
	public function index(){
		$this->load->view('admin/WXManage/FirstAttention/FirstAttentionList');
	}
	

	/**
	 * @desc   获取'首次关注'页面信息
	 */
	public function onLoadFirstAttentionMsg(){
		/*$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by seq asc';
		}*/
		//查询数据库
		$data = $this->attention->selectFirstAttentionMsg();

		for($i=0;$i<count($data);$i++){
			$data[$i]['picture'] = '<img class="picture" src="'.$data[$i]['imgurl'].'" >';
			//发布
			if($data[$i]['status']=='1'){//已发布
				$data[$i]['statusName'] = '已发布';
				//$data[$i]['statuschange']='<a onclick="detailMsg('.$data[$i]["id"].')">查看</a>&nbsp;&nbsp;&nbsp; <a onclick="changestatus('.$data[$i]["id"].',0)">取消发布</a>';
				$data[$i]['statuschange'] = '<lable class="btn btn-success btn-xs" onclick="detailMsg('.$data[$i]["id"].')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="changestatus('.$data[$i]["id"].',0)">取消发布</lable>';
			}else if($data[$i]['status']=='0'){//未发布
				$data[$i]['statusName'] = '未发布';
				//$data[$i]['statuschange']='<a onclick="detailMsg('.$data[$i]["id"].')">查看</a>&nbsp;&nbsp;&nbsp; <a onclick="changestatus('.$data[$i]["id"].',1)">发布</a>';
				$data[$i]['statuschange'] = '<lable class="btn btn-success btn-xs" onclick="detailMsg('.$data[$i]["id"].')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="changestatus('.$data[$i]["id"].',1)">发布</lable>';
			}
		}

		ajax_success($data,null);
	}


	public function detailnew(){
		//拿到code
		$id=$this->input->get('id');
		//var_dump($data);
		if($id=='0'){// 新增
			$data['id']=0;
			$data['cateid'] = 10600;
		}else{

			$data = $this->attention->selectDetailMsg($id);
		}
		
		
		$this->load->view('admin/WXManage/FirstAttention/OperateFirstAttentionList',$data);
	}



	/**
	 * [statuschange 操作信息的发布状态]
	 * @version 2016-12-09 1.0
	 */
	public function statuschange(){
		$id = $this->input->post('id');
		$type = $this->input->post('type');

		$res = $this->attention->updateStatus($id,$type);

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}


	public function onSavenew(){
		//title:title,html:html,summay:summay,id:id,status:status,url:url,imgurl:imgurl,cateid:cateid,sort:sort
		//提取前台数据
		$id = $this->input->post('id');
		if($id == "0"){//新增,id由数据库自增
			//$data['status']='1002002';
		}else{
			$data['id'] = $id;
		}
		date_default_timezone_set('PRC');
		$data['title'] = $this->input->post('title');
		$data['content'] = $this->input->post('html');
		$data['intro'] = $this->input->post('summay');
		$data['status'] = $this->input->post('status');
		$data['url'] = $this->input->post('url');
		$data['sort'] = $this->input->post('sort');
		$data['imgurl'] = $this->input->post('imgurl');
		$data['cateid'] = $this->input->post('cateid');
		$data['intime'] = date('Y-m-d H:i:s');

		if($this->attention->savenew($data) === true)
			ajax_success(true,null);
		else
			ajax_error('保存首次关注资讯失败');
	}



	public function delnew(){
		$Oid = $this->input->post('OID');
		$isSuccess = $this->attention->delnew($Oid);
		//返回success
		if($isSuccess)
			ajax_success(true,NULL);
		else
			ajax_error('删除数据失败！');
	}
}