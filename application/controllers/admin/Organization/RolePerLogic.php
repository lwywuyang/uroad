<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class RolePerLogic extends CI_Controller {

	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/roleper_model', 'roleper');
		$this->load->library('session');
		checksession();
	}
	//组织机构
	public function index(){
		$this->load->view('admin/Organization/RolePer/RolePerContainer');
	}
	//左边树形列表
	public function RolePerLeft(){
		// 取出数据	
		 $data['Role']=AllRole();
		 // echo $data['Role'];
		 $this->load->view('admin/Organization/RolePer/RolePerLeft',$data);
	}
	//默认打开页面
	public function Default1(){
		$this->load->view('admin/Organization/RolePer/Dedault');
	}

	//右边公司角色列表
	public function RolePerMain(){

		// echo "<script>alert('1');</script>";
		$id=$this->uri->segment(5);
		$data['id']=$id;
		//传出id就好
		$data['fun']=AllFun($data['id']);
		$data['datatype']=DataType();
		$this->load->view('admin/Organization/RolePer/RolePerMain',$data);
	}
	//修改功能权限
	public function CheckFunPer(){
		//取出前台数据
		$RoleID=$this->input->post('RoleID');
		$CheckValue=$this->input->post('CheckValue');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$CheckValue); 
			//先删除所有与角色id相同的功能
			$this->db->trans_begin();
			$this->roleper->deleteFunRole($RoleID);			
			//再添加进去
			for($i=0;$i<count($ary);$i++){	
					$data=array(
						'ID'=>create_guid(),
					 	'FunctionID'=>$ary[$i],
					 	'RoleID'=>$RoleID,
					 	'PermMode'=>'1101201',
					 	'PermType'=>'1101701'
						); 					 	
					$this->roleper->updateFunRole($data);
				}
			if ($this->db->trans_status() === FALSE){
				    $this->db->trans_rollback();
				}else{
				    $this->db->trans_commit();
				    $suc['staut']=1;
				}

			ajax_success($suc,NULL);
	}
	//显示数据业务权限
	public function LoadBuDatas(){
		// 按照id与roleid取出数据
		$RoleID=$this->input->post('Role');
		$TypeID=$this->input->post('TypeID');
		$data=$this->roleper->getpermbudata($TypeID);
		//判断哪个已经选中过
		$RoleCheck=$this->roleper->RoleCheck($RoleID);
		for($i=0;$i<count($RoleCheck);$i++){
			for($j=0;$j<count($data);$j++){
				if($RoleCheck[$i]['PermBUDataID']==$data[$j]['ID']){
					$data[$j]['IsRoleCheck']=1;
				}

			}
			
		}
		
		ajax_success($data,NULL);
	}

	//修改功能权限
	public function CheckRolePer(){
		//取出前台数据
		$RoleID=$this->input->post('RoleID');
		$CheckValue=$this->input->post('CheckValue');

		$TypeID=$this->input->post('TypeID');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$CheckValue); 
			//先删除所有与角色id相同的功能
			$this->db->trans_begin();
			$this->roleper->deleteRolePer($RoleID,$TypeID);			
			//再添加进去
			for($i=0;$i<count($ary);$i++){	
					$data=array(
						'ID'=>create_guid(),
					 	'PermBUDataID'=>$ary[$i],
					 	'RoleID'=>$RoleID,
					 	'PermMode'=>'1101201',
					 	'PermType'=>'1101701'
						); 					 	
					$this->roleper->updateRolePer($data);
				}
			if ($this->db->trans_status() === FALSE){
				    $this->db->trans_rollback();
				}else{
				    $this->db->trans_commit();
				    $suc['staut']=1;
				}

			ajax_success($suc,NULL);
	}

	//查找拥有该角色的员工
	public function onloadroleemp(){
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='';
	 	}
		// 关键字
		$key=$this->input->post('key');
		$RoleID=$this->input->post('RoleID');
		$data=$this->roleper->checkRoleEmp($key,$pageOnload,$RoleID);
		ajax_success($data['data'],$data["PagerOrder"]);
	}

	//角色选择员工
	public function selectUser(){
		// 需要传入一个角色id
		$roleid=$this->uri->segment(5);
		// $data['id']=$id;
		$RoleEmp=Org($roleid);
		$data['RoleEmp']=$RoleEmp;
		// echo $RoleEmp;
		$data['roleid']=$roleid;
		$this->load->view('admin/Organization/RolePer/SelectUser',$data);
	}
	//添加角色员工
	public function addselectUser(){
		//取出前台数据
		$RoleID=$this->input->post('RoleID');
		$CheckValue=$this->input->post('CheckValue');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$CheckValue); 
		   // p($ary);die;
		
			$this->db->trans_begin();	
			//再添加进去
			for($i=0;$i<count($ary);$i++){	
					$data=array(
						'ID'=>create_guid(),
					 	'EmpID'=>$ary[$i],
					 	'RoleID'=>$RoleID
						); 					 	
					$this->roleper->adduserRole($data);
						// p($SelectRole);die;
				}
			if ($this->db->trans_status() === FALSE){
				    $this->db->trans_rollback();
				}else{
				    $this->db->trans_commit();
				    $suc['staut']=1;
				}
			ajax_success($suc,NULL);
	}
	//删除角色下的员工
	public function deleteroleemp(){
		$Oid = $this->input->post('OID');		
				 	//需要先删除所有的子id
				 $this->roleper->delroleemp($Oid);
		 //返回success
		 ajax_success(NULL,NULL);		
		
	}

}