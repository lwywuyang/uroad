<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class OrgManageLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/orgmanage_model', 'logic');
		$this->load->library('session');
		checksession();
	}
	//组织机构
	public function indexPage(){
		$this->load->view('admin/Organization/OrgManage/OrgContainer');
	}
	//左边树形列表
	public function orgLeftPage(){
		// 取出树图数据	
		$data['com']=getOrg();
		 $this->load->view('admin/Organization/OrgManage/OrgLeft',$data);
	}
	/**
	 * 右边公司列表
	 */
	public function CompanyList(){
		// 查所有顶级公司
		$this->load->view('admin/Organization/OrgManage/CompanyList');
	}
	/**
	 * 提交获取数据
	 */
	public function onloadCom(){
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		// 关键字
		$key=$this->input->post('key');
		$data=$this->logic->loadTopComData($key,$pageOnload);
		ajax_success($data['data'],$data["PagerOrder"]);		
	}
	/**
	 * 删除公司
	 */
	public function onDelCom(){
		$Oid = $this->input->post('OID');	
		$isSuccess=$this->logic->del($Oid);	 
		 //返回success
		if($isSuccess){
			//插入日志
			adminlog('删除公司操作，id为'.$Oid,'orgmanage','delcom');
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}

	/**
	 * 删除部门
	 */
	public function deleteDep(){
		$Oid = $this->input->post('OID');
		$isSuccess=$this->logic->delDep($Oid);	 
		 //返回success
		if($isSuccess){
			//插入日志
			adminlog('删除部门操作，id为'.$Oid,'orgmanage','deldep');
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
		
	}
	/**
	 * 删除员工
	 */
	public function deleteEmp(){
		$Oid = $this->input->post('OID');
		$isSuccess=$this->logic->delEmp($Oid);
		 //返回success
		if($isSuccess){
			//插入日志
			adminlog('删除员工操作，id为'.$Oid,'orgmanage','delemp');
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}

	public function disableEmp(){
		$Oid = $this->input->post('OID');
		$isSuccess=$this->logic->disableEmployee($Oid);
		 //返回success
		if($isSuccess){
			//插入日志
			adminlog('禁用员工操作，id为'.$Oid,'orgmanage','delemp');
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}

	//添加注册，编辑
	public function detailPageCom(){
		// 取出id
		$data['ID']=$this->uri->segment(5);
		$data['PID']=$this->uri->segment(6);
		//设置选择
		 // p($data['id']);die;
		if($data['ID']=="0"){
			//添加
			$this->load->view('admin/Organization/OrgManage/CompanyEdit',$data);		
		}else{
			// 编辑
		//数据库查找
			$data=$this->logic->GetComDataById($data['ID']);
			$this->load->view('admin/Organization/OrgManage/CompanyEdit',$data);
		}		
	}

	/**
	 * 添加编辑公司
	 */
	public function onSaveCom(){
		//提取前台数据
		$pid=$this->input->post('PID');
		if(empty($pid)){
			$pid=null;
		}
		$ID=$this->input->post('ID');	
			if($ID=="0"){
				$companydata['ID']=create_guid();
			}else{
				$companydata['ID']=$ID;
			}
		$companydata['CompCode']=$this->input->post('CompCode');
		$companydata['CompShortName']=$this->input->post('CompShortName');
		$companydata['CompName']=$this->input->post('CompName');
		$companydata['CompName']=$this->input->post('CompName');
		$companydata['PID']=$pid;
		//判断书编辑还是添加
		if($ID=="0"){
		//检查是否重复
				$check=array();
				$check = $this->logic->checkName($companydata['CompShortName'],$companydata['CompCode'],$companydata['CompName']);
				//p($check); 
				if(!empty($check['isShortName'])){
					ajax_error('简称已存在' );
				} else {
					if(!empty($check['isCode'])){ 
						ajax_error('编码已存在' );
				 	}else{	 
				 		if(!empty($check['isName'])){
				 			ajax_error('全称已经存在' );
				 		}else{
				 			//数据库添加
						if($this->logic->save('org_company',$companydata,'ID')===true){

							//插入日志
							adminlog('添加公司操作,名称为'.$companydata['CompName'],'orgmanage','addcom');

								ajax_success($companydata,NULL);	
							}else{
								ajax_error('保存失败');
							}	
					 	}
						 		 
				 	}
				}
		}else{
			if($this->logic->save('org_company',$companydata,'ID')===true){

				//插入日志
				adminlog('编辑公司操作,名称为'.$companydata['CompName'],'orgmanage','editcom');

					ajax_success($companydata,NULL);	
				}else{
					ajax_error('保存失败');
				}	
			}			
	}

	/**
	 * 公司信息页面，包含子公司和部门
	 */
	public function CompanyMain(){
		//得到自己公司id,就是子公司的pid
		$ID=$this->uri->segment(5);
		// 查找出本身信息
		$data['comdata']=$this->logic->checkId($ID);
		$data['ID']=$ID;
		$this->load->view('admin/Organization/OrgManage/CompanyMain',$data);
	}

	/**
	 * 获取子公司数据
	 */
	public function onloadChildCom(){
		$PID= $this->input->post('PID');
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		// 关键字
		$key=$this->input->post('key');
		$data=$this->logic->getChildCom($key,$pageOnload,$PID);
		ajax_success($data['data'],$data["PagerOrder"]);	
	}
	/**
	 * 获取公司下的部门信息
	 */
	public function onLoadDepartment(){
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		$key=$this->input->post('key');
		$CompanyID= $this->input->post('CompanyID');
		$data=$this->logic->getDep($key,$pageOnload,$CompanyID);
		ajax_success($data['data'],$data["PagerOrder"]);	
	}
	
	// 增加和编辑部门信息
	public function detailPageDep(){
		// 取出本身id,如果是0就表示是添加的，不是的话就是编辑信息的id
		$data['ID']=$this->uri->segment(5);
		//取出所属公司的id
		$data['CompanyID']=$this->uri->segment(6);
		//取出所属部门的id.如果是0表示，这是顶级部门
		$data['PID']=$this->uri->segment(7);

		if($data['PID']==0){
			$pid=null;
		}else{
			// $pid=$data['PID'];
		}
		// $data['PID']=$pid;
		//设置选择
		if($data['ID']=="0"){
			//添加
			log_message('info',print_r($data, 1));
			$this->load->view('admin/Organization/OrgManage/DepartmentEdit',$data);		
		}else{
			// 编辑
			$data=$this->logic->GetDepDataById($data['ID']);

			$this->load->view('admin/Organization/OrgManage/DepartmentEdit',$data);
		}	
	}

	public function onSaveDep(){
		$pid=$this->input->post('PID');
		if(empty($pid)){
			$pid=null;
		}
		$ID=$this->input->post('ID');	
			if($ID=="0"){
				$departmentdata['ID']=create_guid();
			}else{
				$departmentdata['ID']=$ID;
			}
		$departmentdata['DepaCode']=$this->input->post('DepaCode');
		$departmentdata['DepaName']=$this->input->post('DepaName');
		$departmentdata['DepaSerial']=$this->input->post('DepaSerial');
		$departmentdata['Depamanager']=$this->input->post('Depamanager');
		$departmentdata['CompanyID']=$this->input->post('CompanyID');
		$departmentdata['PID']=$pid;
		//判断书编辑还是添加
		if($ID=="0"){
		//检查是否重复
				$check=array();
				$check = $this->logic->checkDepName($departmentdata['DepaCode'],$departmentdata['DepaName'],$departmentdata['DepaSerial']);
				//p($check); 
				if(!empty($check['isDepaCode'])){
					ajax_error('编码已存在' );
				} else {
					if(!empty($check['isDepaName'])){ 
						ajax_error('名称已存在' );
				 	}else{	 
				 		if(!empty($check['isDepaSerial'])){
				 			ajax_error('排序已经存在' );
				 		}else{
				 			if($this->logic->save('org_department',$departmentdata,'ID')===true){

				 				//插入日志
								adminlog('添加部门操作,名称为'.$departmentdata['DepaName'],'orgmanage','adddep');


								ajax_success($departmentdata,NULL);	
							}else{
								ajax_error('保存失败');
							}	
				 		}
						 		 
				 	}
				}
		}else{
			if($this->logic->save('org_department',$departmentdata,'ID')===true){

				 	//插入日志
					adminlog('编辑部门操作,名称为'.$departmentdata['DepaName'],'orgmanage','editdep');

					ajax_success($departmentdata,NULL);	
				}else{
					ajax_error('保存失败');
			}
		}
				
	}
	//显示部门列表和部门下的员工信息
	public function DepartmentMain(){
		//得到顶级部门id
		$id=$this->uri->segment(5);
		 // 查找出本身信息
		  $data['depdata']=$this->logic->checkDepId($id);
		  $data['ID']=$id;
		  //得到顶级部门id
		 $this->load->view('admin/Organization/OrgManage/DepartmentMain',$data);
	}
	/**
	 * 获取部门下的部门信息
	 */
	public function getChildDepartment(){
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		$key=$this->input->post('key');
		$PID= $this->input->post('PID');
		$data=$this->logic->getChildDep($key,$pageOnload,$PID);
		ajax_success($data['data'],$data["PagerOrder"]);	
	}

	/**
	 * 获取部门下的员工信息
	 */
	public function getEmployee(){
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by status asc,ID asc';
	 	}
		$key=$this->input->post('key');
		$DepartmentID= $this->input->post('DepartmentID');
		$data=$this->logic->getEmp($key,$pageOnload,$DepartmentID);
		/*for($i=0;$i<count($data['data']);$i++){
			if($data['data'][$i]['Status']=='1'){
				$data['data'][$i]['Status']='确认';
			}else if($data['data'][$i]['Status']=='0'){
				$data['data'][$i]['Status']='作废';
			}
		}*/
		ajax_success($data['data'],$data["PagerOrder"]);	

	}

	//添加和修改员工信息,需要结合角色表，查找出这个人员所在的顶级公司下的全部角色，用于可以选择使用
	public function detailEmployee(){
		// 取出本身id,如果是0就表示是添加的，不是的话就是编辑信息的id
		$data['ID']=$this->uri->segment(5);
		// 取出所属公司id
		$data['CompanyID']=$this->uri->segment(6);
		//取出所属部门的id
		$data['DepartmentID']=$this->uri->segment(7);
		//检查是编辑还是添加
		// 根据公司id查处公司的顶级公司拥有的角色，在角色表中查出该公司下的角色			
		$pid=$data['CompanyID'];
		while(true){
			//数据库查出pid
			$reData=$this->logic->checkTopCompid($pid);
			//按照pid去出id
			if($reData[0]['PID']==null){
				$pid=$reData[0]['ID'];
				break;
			}
			else
			{
				$pid=$reData[0]['PID'];
			}		
		}

		//根据最顶级公司id查处所拥有的角色
		$data['role']=getRoleData($pid);
		if($data['ID']=="0"){

			$this->load->view('admin/Organization/OrgManage/EmployeeEdit',$data);
		}
		else
		{
			//数据库查找
			$data['emp']=$this->logic->GetEmpDataById($data['ID']);
			//查找已经拥有的角色
			$data['RoleEmp']=$this->logic->checkRoleId($data['ID']);
			$this->load->view('admin/Organization/OrgManage/EmployeeEdit',$data);
		}
		
	}

	//添加和编辑操作
	public function onSaveEmp(){
		//提取前台数据

		$ID=$this->input->post('ID');	
			if($ID=="0"){
				$employeedata['ID']=create_guid();
			}else{
				$employeedata['ID']=$ID;
		}
		$employeedata['EmplCode']=$this->input->post('EmplCode');
		$employeedata['EmplName']=$this->input->post('EmplName');
		$employeedata['Mobile']=$this->input->post('Mobile');
		$employeedata['Status']=$this->input->post('Status');
		$employeedata['DepartmentID']=$this->input->post('DepartmentID');
		$PassWord=$this->input->post('PassWord');
		$res = preg_match('/^(\w*(?=\w*\d)(?=\w*[A-Za-z])\w*){8,16}$/', $PassWord);
		if (!$res) {
			ajax_error('8-16位字符（英文/数字/符号）至少两种或下划线组合');
			exit;
		}
		if($PassWord!=''){
			$employeedata['PassWord']=md5($PassWord);
		}else{
			$employeedata['PassWord']=md5('Gstlw654321');
		}
		//选择角色的id字符串md5($this->input->post('PassWord'));
		$SelectRole = $this->input->post('SelectRole');
		//判断编辑还是添加
		if($ID=="0"){
		//检查是否重复
				$check=array();
				$check = $this->logic->checkEmpName($employeedata['EmplCode']);
				if(!empty($check['isEmplCode'])){
					ajax_error('编码已存在' );
				} else {//数据库添加
					//循环添加到员工角色表sys_userrole
					if(!empty($SelectRole)){
					 for($i=0;$i<count($SelectRole);$i++){					 	
					 	$id=create_guid();
					 	$roleid=$SelectRole[$i];
					 	$empid=$employeedata['ID'];
					 	$this->logic->addRoleEmp($id,$roleid,$empid);		 						
						}
					}
					if($this->logic->save('org_employee',$employeedata,'ID')===true){

						//插入日志
						adminlog('添加员工操作,名称为'.$employeedata['EmplName'],'orgmanage','addemp');
						

						ajax_success($employeedata,NULL);	
					}else{
						ajax_error('保存失败');
					}
				}
		}else{
			//角色部分需要先删除所有和员工id相同的角色id
			$this->logic->deleteRole($employeedata['ID']);		
			//再添加进去
			if(!empty($SelectRole)){
			for($i=0;$i<count($SelectRole);$i++){	 	
					 	$id=create_guid();
					 	$roleid=$SelectRole[$i];
					 	$empid=$employeedata['ID'];
						$this->logic->addRoleEmp($id,$roleid,$empid);
				}
			}
			if($this->logic->save('org_employee',$employeedata,'ID')===true){

					//插入日志
					adminlog('编辑员工操作,名称为'.$employeedata['EmplName'],'orgmanage','editemp');

					ajax_success($employeedata,NULL);	
				}else{
					ajax_error('保存失败');
			}
		}
	}
	

	//显示员工信息
	public function empMain(){
		//得到顶级部门id
		$id=$this->uri->segment(5);
		 // 查找出本身信息
		  $data['empdata']=$this->logic->empmaindata($id);
		  $data['ID']=$id;
		  //得到顶级部门id
		 $this->load->view('admin/Organization/OrgManage/EmploteeMian',$data);
	}


}
