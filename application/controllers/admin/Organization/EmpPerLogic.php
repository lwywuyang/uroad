<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class EmpPerLogic extends CI_Controller {

	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/empper_model', 'empper');
		$this->load->library('session');
		checksession();
	}
	
	// 主页面
	public function index(){
		$this->load->view('admin/Organization/EmpPer/EmpPerContainer');
	}
	public function Default1(){
		$this->load->view('admin/Organization/EmpPer/Default');
	}
	/**
	 * 显示左边树状图页面
	 */
	public function EmpPerLeft(){
		$getEmpPerOrg=getEmpPerOrg();
		$data['getEmpPerOrg']=$getEmpPerOrg;
		$this->load->view('admin/Organization/EmpPer/EmpPerLeft',$data);
	}
	/**
	 * 显示右边配置
	 */
	public function EmpPerMain(){
		//取出员工Id
		$empid=$this->input->get('empid');

		$data['empid']=$empid;
		//获取员工的全已经选择的功能
		$AllEmpPerFun=AllEmpPerFun($empid);
		$data['AllEmpPerFun']=$AllEmpPerFun;
		//获取员工的全已经选数据权限
		$EmpPerDataType=EmpPerDataType();
		$data['EmpPerDataType']=$EmpPerDataType;
		//对应的角色
		$getEmpRole=getEmpRole($empid);
		$data['getEmpRole']=$getEmpRole;
		
		$this->load->view('admin/Organization/EmpPer/EmpPerMain',$data);
	}
	/**
	 * 添加员工的功能
	 */
	public function addFunEmp(){
		// 取出数据
		//取出前台数据
		$EmpId=$this->input->post('EmpId');
		$CheckValue=$this->input->post('CheckValue');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$CheckValue); 

	
			$this->empper->deleteEmpfun($EmpId);
			//再添加进去
			for($i=0;$i<count($ary);$i++){	
					$data=array(
						'ID'=>create_guid(),
					 	'FunctionID'=>$ary[$i],
					 	'EmployeeID'=>$EmpId,
					 	'PermMode'=>'1101201',
					 	'PermType'=>'1101701'
						); 
										 	
					if($this->empper->addFunEmp($data)===false){
						ajax_error('保存失败');
					}

				}
			ajax_success('',NULL);
		
	}

	/**
	 * 显示数据业务权限
	 */
	public function LoadDataPer(){
		// 按照id与roleid取出数据
		$EmpID=$this->input->post('EmpID');
		$TypeID=$this->input->post('TypeID');
		//查出所有事件
		$this->load->model('Organization/roleper_model', 'roleper');	
		$data=$this->roleper->getpermbudata($TypeID);
		//判断哪个已经选中过
		$RoleCheck=$this->empper->checkempper($EmpID);
		
		for($j=0;$j<count($data);$j++){
			for($i=0;$i<count($RoleCheck);$i++){	
				
					if($RoleCheck[$i]['PermBUDataID']==$data[$j]['ID']){
						if($RoleCheck[$i]['EmployeeID']==null){
						$data[$j]['IsRoleCheck']=1;
				  }else{
				  	$data[$j]['IsEmpCheck']=1;
				  }
				}
				
			}		
		}
		
		ajax_success($data,NULL);
	}

	/**
	 * 添加员工的业务权限
	 */
	public function addEmpPer(){
		// 取出数据
		//取出前台数据
		$EmpId=$this->input->post('EmpId');
		$CheckValue=$this->input->post('CheckValue');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$CheckValue); 

		if(empty($CheckValue)){
 			$suc['staut']=0;
 			ajax_success($suc,NULL);
		}else{

			$this->db->trans_begin();
			$this->empper->deleteEmpPer($EmpId);		
			//再添加进去
			for($i=0;$i<count($ary);$i++){	
					$data=array(
						'ID'=>create_guid(),
					 	'PermBUDataID'=>$ary[$i],
					 	'EmployeeID'=>$EmpId,
					 	'PermMode'=>'1101201',
					 	'PermType'=>'1101701'
						); 
					$this->load->model('Organization/roleper_model', 'roleper');						 	
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
	}
	/**
	 * 添加员工的角色
	 */
	public function addEmpRole(){
		// 取出数据
		//取出前台数据
		$EmpId=$this->input->post('EmpId');
		$CheckValue=$this->input->post('CheckValue');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$CheckValue); 
		if(empty($CheckValue)){
 			$suc['staut']=0;
 			ajax_success($suc,NULL);
		}else{
			$this->db->trans_begin();
			//再添加进去

			for($i=0;$i<count($ary);$i++){	
					$data=array(
						'ID'=>create_guid(),
					 	'RoleID'=>$ary[$i],
					 	'EmpID'=>$EmpId
						); 
					$this->load->model('Organization/roleper_model', 'roleper');						 	
					$this->roleper->adduserRole($data);
				}
			if ($this->db->trans_status() === FALSE){
				    $this->db->trans_rollback();
				}else{
				    $this->db->trans_commit();
				    $suc['staut']=1;
				}

			ajax_success($suc,NULL);
		}
			
	}


}