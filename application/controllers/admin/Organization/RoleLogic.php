<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class RoleLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/role_model', 'role');
		$this->load->library('session');
		checksession();
	}
	//组织机构
	public function index(){
		$this->load->view('admin/Organization/Role/RoleContainer');
	}
	//左边树形列表
	public function RoleLeft(){
		// 取出数据	
		$data['com']=getRole();
		 // echo $data['com'];
		 $this->load->view('admin/Organization/Role/RoleLeft',$data);
	}
	//默认打开页面
	public function default1(){
		$this->load->view('admin/Organization/Role/Default');
	}

	//右边公司角色列表
	public function RoleList(){
		//得到公司id
		// echo "<script>alert('1');</script>";
		$id=$this->uri->segment(5);
		$data['id']=$id;
		//传出id就好
		$this->load->view('admin/Organization/Role/RoleList',$data);
	}
	//查处公司下的角色数据
	public function getComRole(){
		//取出必要的数据公司id
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		// 关键字
		$CompanyID = $this->input->post('CompanyID');
		$key=$this->input->post('key');
		$data=$this->role->getAllRole($key,$pageOnload,$CompanyID);
		ajax_success($data['data'],$data["PagerOrder"]);	

	}

	//添加和修改员工信息
	public function addRole(){
		// 取出本身id,如果是0就表示是添加的，不是的话就是编辑信息的id
		$data['id']=$this->uri->segment(5);
		//上级公司的id
		$data['comid']=$this->uri->segment(6);
		//检查是编辑还是添加
		if($data['id']){
			$data['edit']=1;
			//数据库查找
			$data['role']=$this->role->checkRoleId($data['id']);
			$this->load->view('admin/Organization/Role/RoleEdit',$data);
		}else{
			$data['edit']=0;
			$this->load->view('admin/Organization/Role/RoleEdit',$data);
		}
		
	}
	//添加和编辑操作
	public function doaddRole(){
		//提取前台数据
		$roledata=array(
				'ID'=>create_guid(),
				'RoleName'=>$this->input->post('RoleName'),
				'Remark'=>$this->input->post('Remark'),
				'CompanyID'=>$this->input->post('CompanyID')
			);
		$editdata=array(
				'id'=>$this->input->post('ID'),
				'RoleName'=>$this->input->post('RoleName'),
				'Remark'=>$this->input->post('Remark')
			);	
		//判断书编辑还是添加
		if(!$editdata['id']){
					$this->role->save('sys_role',$roledata);
					ajax_success($roledata,NULL);	
				
		}else{
			$this->role->save('sys_role',$editdata);
			ajax_success($editdata,NULL);
		}
	}

	// 删除员工
	public function delRole(){
		$Oid = $this->input->post('OID');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$Oid); 
		// //循环删除
		 for($i=0;$i<count($ary);$i++){
		 	$id=$ary[$i];
			$this->role->delrole($id);
		 }
		 //返回success
		 ajax_success(NULL,NULL);
	}

}