<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class FunctionLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/function_model', 'fun');
		$this->load->library('session');
		checksession();
	}
	//组织机构
	public function index(){
		$this->load->view('admin/Organization/Function/FunctionContainer');
	}
	//左边树形列表
	public function FunctionLeft(){
		// // 取出数据	
		 $data['fun']=getAllFun();
		 // echo $data['fun'];
		  $this->load->view('admin/Organization/Function/FunctionLeft',$data);
	}
	//默认打开页面
	public function default1(){
		$this->load->view('admin/Organization/Function/Default');
	}

	//右边事件列表
	public function FunctionList(){
		//取出系统id
		$id=$this->uri->segment(5);
		$data['id']=$id;
		//传出id就好
		$this->load->view('admin/Organization/Function/FunctionList',$data);
	}
	//查处系统下面的事件
	public function getFun(){
		//取出必要的数据公司id

		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		$key=$this->input->post('key');
		$PID='';
		$SystemID = $this->input->post('SystemID');
		$data=$this->fun->getAllFun($key,$pageOnload,$SystemID,$PID);
		ajax_success($data['data'],$data["PagerOrder"]);	
	}
	//编辑顶级事件
	public function addFun(){
		// 取出自己id
		$data['id']=$this->uri->segment(5);
		//取出所在系统的id
		$data['systemID']=$this->uri->segment(6);
		//取出pid
		$data['pID']=$this->uri->segment(7);
		//判断是平台还是事件
		$data['isFun']=$this->uri->segment(8);
		//设置选择
		//如果为0说明是最顶级了
		//否则说明是不是顶级
		if($data['id']){
			$data['edit']=1;
			//数据库查找x信息，输出
			$data['function']=$this->fun->checkId($data['id']);
			$this->load->view('admin/Organization/Function/FunctionEdit',$data);
		}else{
			$data['edit']=0;
			$this->load->view('admin/Organization/Function/FunctionEdit',$data);
		}		
	}

	/**
	 * 添加和编辑
	 */
	public function doaddFun(){
		//提取前台数据
		$TopFundata=array(
				'ID'=>create_guid(),
				'FuncCode'=>$this->input->post('FuncCode'),
				'FuncName'=>$this->input->post('FuncName'),
				'FuncType'=>$this->input->post('FuncType'),
				'FuncSerial'=>$this->input->post('FuncSerial'),
				'Status'=>$this->input->post('Status'),
				'URI'=>$this->input->post('URL'),
				'SystemID'=>$this->input->post('SystemID'),
				'PID'=>	$this->input->post('PID')	
			);
		$editdata=array(
				'id'=>$this->input->post('ID'),
				'FuncCode'=>$this->input->post('FuncCode'),
				'FuncName'=>$this->input->post('FuncName'),
				'FuncType'=>$this->input->post('FuncType'),
				'FuncSerial'=>$this->input->post('FuncSerial'),
				'Status'=>$this->input->post('Status'),
				'URI'=>$this->input->post('URL')	
			);	
		//判断书编辑还是添加
		if(!$editdata['id']){
		//检查是否重复
				$check=array();
				$check = $this->fun->checkCode('sys_functions',$TopFundata['FuncCode']);
				// if(!empty($check['isFuncCode'])){
				
				if(!empty($check['isFuncCode'])){
					ajax_error('编码已存在' );
				} else {					
				 	//数据库添加
				 	$this->db->trans_begin();
					$this->fun->save('sys_functions',$TopFundata);
					if ($this->db->trans_status() === FALSE){
						    $this->db->trans_rollback();
						}else{
						    $this->db->trans_commit();
						}
					ajax_success($TopFundata,NULL);		
					
				}
		}else{
			$this->fun->save('sys_functions',$editdata);
			ajax_success($editdata,NULL);
		}				
	}


	//删除顶级事件
	public function delFun(){
		$Oid = $this->input->post('OID');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$Oid); 
		// //循环删除
		 for($i=0;$i<count($ary);$i++){
		 	$id=$ary[$i];
			$this->fun->delfun($id);
		 }
		 //返回success
		 ajax_success(NULL,NULL);
	}
	
	
	// 显示事件下面的管理事件
	public function FunctionMain(){
		//取出自己id
		$id=$this->uri->segment(5);
		$data['id']=$id;
		$systemID=$this->uri->segment(6);
		$data['systemID']=$systemID;
		//数据库查处
		$data['function']=$this->fun->checkId($data['id']);
		$this->load->view('admin/Organization/Function/FunctionMain',$data);
	}
	//查处事件下面的管理事件
	public function getChildFun(){

		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		$key=$this->input->post('key');
		$SystemID = $this->input->post('SystemID');
		$PID = $this->input->post('ID');
		$data=$this->fun->getAllFun($key,$pageOnload,$SystemID,$PID);
		ajax_success($data['data'],$data["PagerOrder"]);
	}

}
