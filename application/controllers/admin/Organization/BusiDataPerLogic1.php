<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//数据权限
class BusiDataPerLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Organization/BusiDataPer_model', 'per');
		$this->load->library('session');
		checksession();
	}

	public function index()
	{ 	
		$this->load->view('admin/Organization/BusiDataPer/DataPerContainer.php');
	}
	//左边显示表
	public function DataLeft()
	{ 	
		$data['Data']=BUDataType();
		$this->load->view('admin/Organization/BusiDataPer/DataLeft.php',$data);
	}
	//右边默认显示
	public function Default1()
	{ 	
		$this->load->view('admin/Organization/BusiDataPer/Default.php');
	}
	//平台信息列表
	public function BUDataTypeList()
	{ 	
		//取出自己的id，也就是查询需要的系统id
		$id=$this->uri->segment(5);
		$data['systemid']=$id;
		$this->load->view('admin/Organization/BusiDataPer/BUDataTypeList.php',$data);
	}
	//载入平台下面权限列表
	public function onLoad(){
		$pageOnload=page_onload();
		// 判断排序是否存在
	 	if($pageOnload['OrderDesc']=="")
	 	{
	 		$pageOnload['OrderDesc']='order by ID asc';
	 	}
		// 关键字
		$key=$this->input->post('key');
		$SystemID=$this->input->post('SystemID');
		$data=$this->per->getAllPer($key,$pageOnload,$SystemID);
		ajax_success($data['data'],$data["PagerOrder"]);	
	}
	//平台信息修改

	public function addBUDataType()
	{ 	
		// 取出自己id
		$data['id']=$this->uri->segment(5);
		//取出所在系统的id
		$data['systemID']=$this->uri->segment(6);
		if($data['id']){
			$data['edit']=1;
			//数据库查找x信息，输出
			$data['BUData']=$this->per->checkId($data['id']);
			//p($data);
			$this->load->view('admin/Organization/BusiDataPer/BUDataTypeEdit.php',$data);
		}else{
			$data['edit']=0;
			$this->load->view('admin/Organization/BusiDataPer/BUDataTypeEdit.php',$data);
		}
		
	}
	
	public function doaddBUDataType()
	{ 
		
		//提取前台数据
		$BUDataTypedata=array(
				'ID'=>create_guid(),
				'DataTypeCode'=>$this->input->post('DataTypeCode'),
				'BuName'=>$this->input->post('BuName'),
				'BuTable'=>$this->input->post('BuTable'),
				'DisFiledID'=>$this->input->post('DisFiledID'),
				'DisFiledF'=>$this->input->post('DisFiledF'),
				'DisFiledS'=>$this->input->post('DisFiledS'),
				'SelfLinkFiled'=>$this->input->post('SelfLinkFiled'),
				'FrFiled'=>$this->input->post('FrFiled'),
				'SystemID'=>$this->input->post('SystemID'),
			);
		$editdata=array(
				'id'=>$this->input->post('ID'),
				'DataTypeCode'=>$this->input->post('DataTypeCode'),
				'BuName'=>$this->input->post('BuName'),
				'BuTable'=>$this->input->post('BuTable'),
				'DisFiledID'=>$this->input->post('DisFiledID'),
				'DisFiledF'=>$this->input->post('DisFiledF'),
				'DisFiledS'=>$this->input->post('DisFiledS'),
				'SelfLinkFiled'=>$this->input->post('SelfLinkFiled'),
				'FrFiled'=>$this->input->post('FrFiled')	
			);	
		// p($editdata);
		//判断书编辑还是添加
		if(!$editdata['id']){
		//检查是否重复
				$check=array();
				$check = $this->per->checkCode($BUDataTypedata['DataTypeCode']);
				//p($check); 
				if(!empty($check['isDataTypeCode'])){
					ajax_error('编码已存在' );
				} else {					
				 	//数据库添加
				 	$this->db->trans_begin();
					$this->per->save('sys_budatatype',$BUDataTypedata);
					if ($this->db->trans_status() === FALSE){
						    $this->db->trans_rollback();
						}else{
						    $this->db->trans_commit();
						}
					ajax_success($BUDataTypedata,NULL);		
					
				}
		}else{
			$this->per->save('sys_budatatype',$editdata);
			ajax_success($editdata,NULL);
		}	
	}
	//权限管理主要页面
	public function DataPerMian()
	{ 	
		$data['id']=$this->uri->segment(5);
		//数据库查找x信息，输出
		$data['BUData']=$this->per->checkId($data['id']);
		// 显示树
		$data['Dataper']=PermBuData($data['id']);


		 $this->load->view('admin/Organization/BusiDataPer/DataPerMian.php',$data);
	}
	//删除权限
	public function delete(){
		$Oid = $this->input->post('OID');
		$ary=array();
		//字符串转化为数组
		$ary=explode(',',$Oid); 
		// //循环删除
		 for($i=0;$i<count($ary);$i++){
		 	$id=$ary[$i];
			$this->per->delper($id);
		 }
		 //返回success
		 ajax_success(NULL,NULL);
	}

	/**
	 * 刷新页面
	 */
	
	public function RefashData(){
		// 取出前台数据

		$BuTable=$this->input->post('BuTable');
		$DisFiledID=$this->input->post('DisFiledID');
		$DisFiledF=$this->input->post('DisFiledF');
		$DisFiledS=$this->input->post('DisFiledS');
		
		$SelfLinkFiled=$this->input->post('SelfLinkFiled');
		$FrFiled=$this->input->post('FrFiled');
		$BuID=$this->input->post('BuID');
		//提取数据
		$data=$this->per->getBusiData($BuTable,$DisFiledID,$DisFiledF,$DisFiledS,$SelfLinkFiled);
		//插入数据
		for($i=0;$i<count($data);$i++){
			$data[$i]['ID']=create_guid();
			$data[$i]['BUDataTypeID']=$BuID;
			}
			//先删除
			$this->per->delBusiData($BuID);
			if($this->per->insertBusiData($data)){
			 ajax_success(NULL,NULL);
			}else{
				ajax_error('刷新失败');
			}
				
	}
}