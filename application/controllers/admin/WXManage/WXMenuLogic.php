<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WXMenuLogic extends CI_Controller {
	/**
	 * 微信菜单配置
	 * yupeng
	 * 17:00 2015/8/20
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Wxmenu_model', 'wechat');
		$this->load->model('Dict_model', 'dict');
		checksession();
	}
	/**
	 * 列表查看
	 */
	public function indexPage(){
		// 拿到所有地区
		$this->load->helper('wechatmeun');
		$data['meun']=wechatmeun();
		// echo $data['meun'];
		//查看是否能增加顶级菜单
		$topnum=$this->wechat->gettopnum();
		$data['topnum']=$topnum['topnum'];
		//var_dump($data);exit;
		//拿到全部数据
		$this->load->view('admin/WXManage/WXMenu/Wechatmeunset',$data);
		
	}
	/**
	 * 查找数据
	 */
	public function onLoadwechat(){
		$pageOnload=page_onload();
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by registertime desc';
		}
		// 关键字
		$key=$this->input->post('key');
		$starttime=$this->input->post('starttime');
		$endtime=$this->input->post('endtime');
		if($starttime!=''){
		   $starttime=date("Y-m-d",strtotime($starttime));
		   $starttime=$starttime.' 00:00:00';
		  }
		  if($endtime!=''){
		   $endtime=date("Y-m-d",strtotime($endtime));
		   $endtime=$endtime.' 23:59:59';
		  }
		$data=$this->wechat->getwechatData($key,$starttime,$endtime,$pageOnload);
		for($i=0;$i<count($data['data']);$i++){
			if($data['data'][$i]['headpic']!=""){
					$imgurlthumbnail=$data['data'][$i]['headpic'];
					$data['data'][$i]['imgurlsmall']='<img src="'.$imgurlthumbnail.'" width="40px">';
				}
		}
		
		ajax_success($data['data'],$data["PagerOrder"]);
	}
	/**
	 * 编辑添加
	 */
	/**
	 * @desc   新增菜单
	 */
	public function wechatedit(){
		//数据字典拿出类型
		$id=$this->input->get('id');
		$pid=$this->input->get('pid');
		if($id==0){
			// 新增
			$data['id']=$id;
			$data['pid']=$pid;
		}else{
			$data=$this->wechat->checkwechatmeun($id);
		}
		//var_dump($data);exit;
		//$data['itypeids']=$this->wechat->selectItypeIds();
		$this->load->view('admin/WXManage/WXMenu/WechatmeunEdit',$data);	
	}
	/**
	 * 保存操作,保存新增的菜单数据
	 */
	public function onSave(){
		//提取前台数据
		$id=$this->input->post('id');
		$pid=$this->input->post('pid');
		if($id=="0"){//新增
		}else{//修改
			$wechatdata['id']=$id;
		}
		$wechatdata['pid']=$pid;
		$wechatdata['title']=$this->input->post('title');
		$wechatdata['sort']=$this->input->post('sort');
		$wechatdata['itype']=$this->input->post('itypeid');
		
		if($wechatdata['itype']=='0'){
			$wechatdata['url']=$this->input->post('url');
		}
		if($wechatdata['itype']=='1'){
			$wechatdata['cateid']=$this->input->post('cateid');
			$wechatdata['developercode']='t_'.$wechatdata['cateid'];
		}
		$wechatdata['menustatusid']="1010001";
		$wechatdata['ishow']="0";
		//判断书编辑还是添加
		log_message('info',print_r($wechatdata, 1));
		if($this->wechat->save($wechatdata)===true){
			ajax_success('',NULL);	
		}else{
			ajax_error('保存失败');
		}	
	}
	
	/**
	 * 删除
	 */
	public function deletewechatmeun(){
		$id = $this->input->post('id');
		$isSuccess=$this->wechat->deletewechatmeun($id);
		 //返回success
		if($isSuccess){
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}
	/**
	 * 显示新闻列表
	 */
	public function shownewlist(){
		
		$id=$this->input->post('id');
		
		$data=$this->wechat->getnewlist($id);
		//var_dump($data);exit;
		$url=$this->config->item("img_url");
		for($i=0;$i<count($data);$i++){
			/*if($data[$i]['imgurlthumbnail']!=''&&$data[$i]['imgurl']!=''){
				$imgurlthumbnail=$url.$data[$i]['imgurlthumbnail'];
				$imgurl=$url.$data[$i]['imgurl'];

				$data[$i]['imgurl']=$imgurl;
				$data[$i]['imgurlsmall']='<img src="'.$imgurlthumbnail.'" width="40px">';
			}else{
				$data[$i]['imgurl']='';
				$data[$i]['imgurlsmall']="";
			}*/
			$data[$i]['picture'] = '<img class="picture" src="'.$data[$i]['imgurl'].'" >';
			//发布
			if($data[$i]['status']=='1'){//已发布
				$data[$i]['statusName'] = '已发布';
				//$data[$i]['statuschange']='<a onclick="detailnew('.$data[$i]["id"].','.$data[$i]["newid"].')">查看</a>&nbsp;&nbsp;&nbsp; <a onclick="changestatus('.$data[$i]["newid"].',0,'.$data[$i]["id"].')">取消发布</a>';
				$data[$i]['statuschange'] = '<lable class="btn btn-success btn-xs" onclick="detailnew('.$data[$i]["id"].','.$data[$i]["newid"].')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="changestatus('.$data[$i]["newid"].',0,'.$data[$i]["id"].')">取消发布</lable>';
				
			}else if($data[$i]['status']=='0'){//未发布
				$data[$i]['statusName'] = '未发布';
				//$data[$i]['statuschange']='<a onclick="detailnew('.$data[$i]["id"].','.$data[$i]["newid"].')">查看</a>&nbsp;&nbsp;&nbsp; <a onclick="changestatus('.$data[$i]["newid"].',1,'.$data[$i]["id"].')">发布</a>';
				$data[$i]['statuschange'] = '<lable class="btn btn-success btn-xs" onclick="detailnew('.$data[$i]["id"].','.$data[$i]["newid"].')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="changestatus('.$data[$i]["newid"].',1,'.$data[$i]["id"].')">发布</lable>';
			}
			
		}
		//var_dump($data);exit;
		ajax_success($data,null);
	}
	/**
	 * 编辑图文信息
	 */
	public function detailnew(){
		//拿到code
		$id=$this->input->get('id');
		$newid=$this->input->get('newid');
		//查出code
		$data=$this->wechat->checkwechatmeun($id);
		//var_dump($data);
		if($newid=='0'){
			// 新增
			$newdata['id']=$newid;
			$newdata['cateid']=$data['cateid'];
		}else{
			$newdata=$this->wechat->checknew($newid);
		}
		//var_dump($newdata);exit;
		
		
		$this->load->view('admin/WXManage/WXMenu/WechatnewEdit',$newdata);	
	}
	/**
	 * 保存图文信息
	 */
	public function onSavenew(){
		//提取前台数据
		$id=$this->input->post('id');			
		if($id=="0"){//新增,id由数据库自增
			//$data['status']='1002002';
		}else{
			$data['id']=$id;
		}
		date_default_timezone_set('PRC');
		$data['title']=$this->input->post('title');
		$data['content']=$this->input->post('html');
		$data['intro']=$this->input->post('summay');
		$data['status']=$this->input->post('status');
		$data['url']=$this->input->post('url');
		$data['sort']=$this->input->post('sort');
		/*$data['operatetime']=date('Y-m-d H:i:s',time());
		$data['operator']=$this->session->userdata('EmplName');*/
		$data['imgurl']=$this->input->post('imgurl');
		//$data['imgurlthumbnail']=$this->input->post('imgurlthumbnail');
		$data['cateid']=$this->input->post('cateid');
		$data['intime']=date('Y-m-d H:i:s');
		// if($data['newsstatusid']=='1002001'){
		// 	$num=$this->etcactive->checknewnum($data["newstypeid"]);
		// 	if($num['num']>=5){
		// 		ajax_error('发布条数不能超过10条');
		// 		exit;
		// 	}
		// }
		//var_dump($data);exit;
		if($this->wechat->savenew($data)==true){
				ajax_success('',NULL);

		}else{
				ajax_error('保存失败');
		}
		
	}

	/**
	 * 删除
	 */
	public function delnew(){
		$Oid = $this->input->post('OID');
		$isSuccess=$this->wechat->delnew($Oid);	 
		 //返回success
		if($isSuccess){
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}
	/**
	 * 点发布
	 */
	/**
	 * @desc   取消发布/发布->保存内容
	 */
	public function statuschange(){
		//var_dump('a');
		date_default_timezone_set('PRC');
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		//var_dump($id);var_dump($type);exit;
		if($type=='1'){//改为发布
			$status='1';
			$time = date('Y-m-d H:i:s');
		}else{//改为未发布
			$status='0';
			$time = '';
		}
		$isSuccess=$this->wechat->statuschange($id,$status,$time);
		 //返回success
		if($isSuccess){
			ajax_success(NULL,NULL);
		}else{
			ajax_error('失败');
		}
	}
	/**
	 * 直接保存url
	 */
	public function saveurl(){
		$id = $this->input->post('id');
		$url = $this->input->post('url');
		$isSuccess=$this->wechat->saveurl($id,$url);	 
		 //返回success
		if($isSuccess){
			ajax_success(NULL,NULL);
		}
	}
/**
 * 最终配置
 */
public function setweixinmeun(){

	$this->load->helper('network');
	$url='http://test.u-road.com/HeNanGSWechatAPIServer/index.php?/wechatserver/ReadDatabaseCreateMenu';
	$content=array();
	$data=network_get($url,$content);
	$data=json_decode($data,true);
	log_message('info',print_r($data, 1));
	if($data['status']=='OK'){
		ajax_success(NULL,NULL);
	}else{
		ajax_error('失败');
	}

}


	
}