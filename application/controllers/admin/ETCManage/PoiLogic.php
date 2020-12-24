<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* ETC网点
*/
class PoiLogic extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/Poi_model', 'Poi');
		$this->load->model('Dict_model', 'dict');
		checksession();
	}

	/**
	 * 列表查看
	 */
	public function indexPage(){
		// 拿到所有城市
		$data['city'] = $this->Poi->selectAllCity();
		$data['status'] = $this->Poi->selectStatus();
		//网点类型
		$data['businesstypeid']=$this->dict->selectDict('2004');
		//var_dump($data['businesstypeid']);exit;
		$this->load->view('admin/ETCManage/PoiList',$data);
	}

	/**
	 * 查找数据
	 */
	public function onLoadPoi(){
		//查找员工数据
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by zone asc';
		}
		// 关键字
		$key=$this->input->post('key');
		$city=$this->input->post('city');
		$businesstypeid=$this->input->post('businesstypeid');
		$businessstatusid=$this->input->post('businessstatusid');
		
		$data=$this->Poi->getPoiData($key,$city,$businesstypeid,$businessstatusid,$pageOnload);
		
		ajax_success($data['data'],$data["PagerOrder"]);
	}


	/**
	 * 编辑添加
	 */
	public function detailPoi(){
		//取出数据
		// 根据板块查出标签
		//查处所有标签
 		$this->load->library('session');
		$id=$this->input->get('id');
		if($id=='0'){
			$data['id']=$id;
		}else{
			$data=$this->Poi->checkPoiData($id);
		}
		//拿出地区
		//$data['allzone']=$this->Poi->getAllzone();
		$data['businesstypeids'] = $this->dict->selectDict('2004');
		//$data['status'] = $this->Poi->selectStatus();
		$data['status'] = $this->dict->selectDict('2005');

		//城市和地区的下拉框联动内容查询
		$data['allCity'] = $this->Poi->selectAllCity();
		foreach($data['allCity'] as $v){
			$zone[$v['id']] = $this->Poi->selectZone($v['id']);
		}
		$zoneId = array();$zoneName = array();
		foreach($zone as $k=>$v){
			if($v == null){
				$zoneId[$k] = array();
				$zoneName[$k] = array();
			}else{
				foreach($v as $kk=>$vv){
					$zoneId[$k][$kk] = $vv['id'];
					$zoneName[$k][$kk] = $vv['zone'];
				}
			}
		}
		$data['zoneId_json'] = json_encode($zoneId);
		$data['zoneName_json'] = json_encode($zoneName);

		$this->load->view('admin/ETCManage/PoiEdit',$data);	
	}
	
	/**
	 * 保存操作
	 */
	public function onSavePoi(){
		//提取前台数据
		/*$id=$this->input->post('id');
		if($id=="0"){

		}else{
			$Poidata['id']=$id;
		}*/
		$Poidata['id']=$this->input->post('id');
		$newid = $this->input->post('newid');
		$Poidata['title']=$this->input->post('title');
		$Poidata['address']=$this->input->post('address');
		$Poidata['longitude']=$this->input->post('longitude');
		$Poidata['latitude']=$this->input->post('latitude');
		$Poidata['city']=$this->input->post('city');
		$Poidata['zone']=$this->input->post('zone');
		$Poidata['businesstypeid']=$this->input->post('businesstypeid');
		$Poidata['businessstatusid']=$this->input->post('businessstatusid');
		$Poidata['phone']=$this->input->post('phone');
		$Poidata['businesstime']=$this->input->post('businesstime');
		$Poidata['remark']=$this->input->post('remark');

		$isMob="/^1[3-5,8]{1}[0-9]{9}$/";  
		$tel='010-87876787';  
		$isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";  
		
		 if(!preg_match($isMob,$Poidata['phone']) && !preg_match($isTel,$Poidata['phone']))  
		 {  
		  ajax_error('手机号码格式不正确，请确认后重新输入！');
		  exit;    
		 }  
		 
		//var_dump($Poidata);exit;
		//判断书编辑还是添加
		$res = $this->Poi->save2($Poidata,$newid);
		if($res===true){
			ajax_success(true,NULL);
		}else{
			ajax_error($res);
		}	
	}
	
	/**
	 * 删除
	 */
	public function delPoi(){
		$Oid = $this->input->post('OID');
		$isSuccess=$this->Poi->delPoi($Oid);	 
		 //返回success
		if($isSuccess){
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}
	
}