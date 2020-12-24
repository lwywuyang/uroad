<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class PlatFormLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/platform_model', 'logic');
		$this->load->library('session');
		checksession();
	}

	public function indexPage()
	{ 	

		$this->load->view('admin/Organization/PlatForm/PlatFormList');

	}
	/**
	 * 提交获取数据
	 */
	public function onLoad(){		
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		// 关键字
		$key=$this->input->post('key');
		$data=$this->logic->loadPageData($key,$pageOnload);
		ajax_success($data['data'],$data["PagerOrder"]);		
	}


	/**
	 * 删除
	 */
	public function onDelete(){
		$Oid = $this->input->post('OID');
			$isSuccess=$this->logic->del($Oid);	 
		 //返回success
			if($isSuccess){
				ajax_success(NULL,NULL);
			}else{
				ajax_error();
			}
		 
	}
	//添加注册，编辑
	//
	public function detailPage(){
		// 取出id
		$data['ID']=$this->uri->segment(5);
		//设置选择
		if($data['ID']=="0"){
			$this->load->view('admin/Organization/PlatForm/PlatFormEdit',$data);
			
		}else{
			//数据库查找
			$data=$this->logic->GetDataById($data['ID']);
			$this->load->view('admin/Organization/PlatForm/PlatFormEdit',$data);
			
		}		
	}
	/**
	 * 添加和编辑
	 */
	public function onSave(){
		//提取前台数据
			$ID=$this->input->post('ID');	
			if($ID=="0"){
				$platdata['ID']=create_guid();
			}else{
				$platdata['ID']=$ID;
			}
			$platdata['Code']=$this->input->post('Code');
			$platdata['Name']=$this->input->post('Name');
			$platdata['SysType']=$this->input->post('SysType');
		//判断 编辑还是添加
		if($ID=="0"){
		//检查是否重复
				$check=array();
				$check = $this->logic->checkName($platdata['Name'],$platdata['Code']);
				if(!empty($check['isName'])){
					ajax_error('名称已存在' );
				} else {
					if(!empty($check['isCode'])){ 
						ajax_error('编码已存在' );
				 	}else{	 
						//数据库添加
						if($this->logic->save($platdata,'ID')===true){
							ajax_success($platdata,NULL);	
						}else{
							ajax_error('保存失败');
						}
						
						 		 
				 	}
				}
		}else{
			if($this->logic->save($platdata,'ID')){
					ajax_success($platdata,NULL);
			}else{
				ajax_error('保存失败');
			}
			
			
		}
				
	}	
}