<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》关键字管理控制器类
 * 	     主要的表 - zj_autoreply_rule和zj_autoreply_keyword
 * @author hwq
 * @date 2016-12-19
 * @version 1.0
 */
class KeywordManageLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Keywordmanage_model', 'keyword');
		checksession();
	}

	/**
	 * @desc   展示'关键字管理'页面,默认展示的是路况模块内容
	 */
	public function index(){
		$this->load->view('admin/WXManage/Keyword/KeywordList');
	}


	/********************路况start********************/
	/**
	 * @desc   '关键字管理'->'路况'->获取路况模块的内容并返回
	 *         路况为默认展示模块
	 */
	public function onLoadMsg_RoadRule(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']=' order by rule_id desc';
		}
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data = $this->keyword->selectRoadMsg($keyword,$pageOnload);
		//var_dump($data);exit;
		foreach ($data['data'] as $k => $v) {
			//$data['data'][$k]['operate'] = "<a onclick='changeDetail_RoadRule(".$v['rule_id'].")'>修改</a>|<a onclick='addKeyword_RoadRule(".$v['rule_id'].")'>添加关键字</a>";
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="changeDetail_RoadRule('.$v['rule_id'].')">修改</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="addKeyword_RoadRule('.$v['rule_id'].')">添加关键字</lable>';
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}


	/**
	 * @desc   '微信管理'->'关键字管理'->'路况'->'新增'或'修改'操作,判断当前操作,拿到相关的内容并展示页面
	 */
	public function operate_RoadRule(){
		$tag = $this->input->get('tag');
		$data['road'] = $this->keyword->selectAllRoad();
		if ($tag == 1) {//新增
			//$this->load->view('admin/WXManage/Keyword/RoadRuleList',$data);
		}else{//修改
			$ruleId = $this->input->get('ruleId');
			$data['ruleData'] = $this->keyword->selectDetailMsg($ruleId);
			
			$keywordData = $this->keyword->selectKeywordMsg($ruleId);
			$data['keywordData'] = json_encode($keywordData);//转换成json格式,用于前端遍历表格输出
			//$this->load
		}
		$this->load->view('admin/WXManage/Keyword/RoadRuleList',$data);
	}


	/**
	 * @desc   '微信管理'->'关键字管理'->'路况'->'新增规则'/'修改某条规则信息'->保存
	 */
	public function saveMsg_RoadRule(){
		//ruleName:ruleName,checkedValue:checkedValue
		$ruleId = $this->input->post('ruleId');
		$ruleName = $this->input->post('ruleName');
		$checkedValue = $this->input->post('checkedValue');

		if ($ruleId == '')//新增
			$res = $this->keyword->insertRoadRuleMsg($ruleName,$checkedValue);
		else
			$res = $this->keyword->updateMsg($ruleId,$ruleName,$checkedValue);
		//---------------------
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作出错!');
	}


	/**
	 * @desc   '微信管理'->'关键字管理'->'路况'->'修改某条规则信息'->删除关键字
	 *         此方法和关键字回复共用
	 */
	public function delKeyword(){
		$deleteValue = $this->input->post('deleteValue');
		$deleteArr = explode(',', $deleteValue);

		$res = $this->keyword->deleteKeywordMsg($deleteArr);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作出错!');
	}


	/**
	 * @desc   '微信管理'->'关键字管理'->'路况'->修改某条规则信息->删除关键字->刷新关键字表格
	 *         此方法和关键字回复共用
	 */
	public function onLoadKeyword(){
		$ruleId = $this->input->post('ruleId');
		$keywordData = $this->keyword->selectKeywordMsg($ruleId);
		$data = json_encode($keywordData);//转换成json格式,用于前端遍历表格输出
		ajax_success($data,null);
	}

	/**
	 * @desc '微信管理'->'关键字管理'->'路况'->'添加关键字'->展示添加关键字页面
	 */
	public function addKeyword(){
		$data['ruleId'] = $this->input->get('ruleId');
		$this->load->view('admin/WXManage/Keyword/addKeywordList',$data);
	}


	public function saveNewKeyword(){
		$ruleId = $this->input->post('ruleId');
		$newKeyword = $this->input->post('newKeyword');

		$res = $this->keyword->insertNewKeyword($ruleId,$newKeyword);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作出错!');
	}
	/********************路况start********************/


	/********************关键字规则start********************/
	/**
	 * @desc   '关键字管理'->'关键字回复'->获取关键字回复的模块的内容并返回
	 */
	public function onLoadMsg_Remark(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']=' order by rule_id desc';
		}
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data = $this->keyword->selectRemarkMsg($keyword,$pageOnload);
		//var_dump($data);exit;
		foreach ($data['data'] as $k => $v) {
			//$data['data'][$k]['operate'] = "<a onclick='changeDetail_Remark(".$v['rule_id'].")'>修改</a>|<a onclick='addKeyword_Remark(".$v['rule_id'].")'>添加关键字</a>";
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="changeDetail_Remark('.$v['rule_id'].')">修改</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="addKeyword_Remark('.$v['rule_id'].')">添加关键字</lable>';
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}


	/**
	 * @desc   '微信管理'->'关键字管理'->'关键字回复'->'新增'或'修改'操作,判断当前操作,拿到相关的内容并展示页面
	 */
	public function operate_Remark(){
		$tag = $this->input->get('tag');
		$data = array();
		//$data['road'] = $this->keyword->selectAllRoad();
		if ($tag == 1) {//新增
			//$this->load->view('admin/WXManage/Keyword/RoadRuleList',$data);
		}else{//修改
			$ruleId = $this->input->get('ruleId');
			$data['ruleData'] = $this->keyword->selectDetailMsg($ruleId);
			
			$keywordData = $this->keyword->selectKeywordMsg($ruleId);
			$data['keywordData'] = json_encode($keywordData);//转换成json格式,用于前端遍历表格输出
			//var_dump($data);exit;
		}
		$this->load->view('admin/WXManage/Keyword/RemarkList',$data);
	}


	public function saveMsg_Remark(){
		$ruleId = $this->input->post('ruleId');
		$ruleName = $this->input->post('ruleName');
		$remarkContent = $this->input->post('remarkContent');

		if ($ruleId == '')//新增
			$res = $this->keyword->insertRemarkMsg($ruleName,$remarkContent);
		else
			$res = $this->keyword->updateMsg($ruleId,$ruleName,$remarkContent);
		//---------------------
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作出错!');
	}


	
}