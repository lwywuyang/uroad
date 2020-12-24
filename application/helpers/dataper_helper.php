<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('BUDataType')) {
	function BUDataType()
	{
		$html='[{ id: \'1\', pId:\'0\', name: "系统平台", open: true ,uri:"",icon:\''.base_url("/asset/images/falt.gif").'\' },';
      //获取顶级管理平台
      $Forms=getBUDataType();
      for ($i=0; $i <count($Forms) ; $i++) { 
        $html.='{ id:\''.$Forms[$i]["ID"].'\', pId:\'1\', name:\''.$Forms[$i]["Name"].'\', open: true ,uri:\''.base_url("/index.php/admin/Organization/BusiDataPerLogic/BUDataTypeList").'/'.$Forms[$i]["ID"].'\',icon:\''.base_url("/asset/images/system.gif").' \' },';
        
           $html.=DataPer($Forms[$i]['ID']);
          // $html.= getDep($Forms[$i]["ID"]);
      }
      $html.=']';
      return $html;
	}

	//查找平台下面的权限
	function DataPer($systemid){
		$html_per="";
        $pers=getDataPer($systemid);
        for ($i=0; $i <count($pers) ; $i++) { 
          # code...
          $html_per.='{ id:  \''.$pers[$i]["ID"].'\', pId:\''.$pers[$i]["SystemID"].'\', name: "'.$pers[$i]["BuName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/BusiDataPerLogic/DataPerMian").'/'.$pers[$i]["ID"].'\',icon:\''.base_url("/asset/images/function.gif").'\'},';
         
        }
        return $html_per;
	}
	
	//查找所有管理
    function getBUDataType(){
      $CI =& get_instance();
      $CI->load->model('Organization/platform_model', 'plat');
      $data=$CI->plat->check('sys_platform');
      return $data;
      
    }

    //查找平台下面的权限数据
    function getDataPer($systemid){
      $CI =& get_instance();
      $CI->load->model('Organization/BusiDataPer_model', 'per');
      $data=$CI->per->getdataper($systemid);
      return $data;
    }
}
//权限事件
    if (!function_exists('PermBuData')) {
    	//输出权限下面的所有事件
		function PermBuData($budatatypeid){
			$html_data="[";
	        $datas=getTopPermBuData($budatatypeid);
	        for ($i=0; $i <count($datas) ; $i++) { 
	          # code...
	          $html_data.='{ id:  \''.$datas[$i]["BUDataID"].'\', pId:"", name: "'.$datas[$i]["BUDataName"].'", open: false},';
	           $html_data.=SubPermBuData($datas[$i]["BUDataID"],$budatatypeid);          
	        }
	        $html_data.="]";
	        return $html_data;
		}	
		//输出子集事件
		function SubPermBuData($pid,$budatatypeid){
			$html_Subdata="";
	        $datas=getChildPermBuData($pid,$budatatypeid);
	        for ($i=0; $i <count($datas) ; $i++) { 
	          # code...
	          $html_Subdata.='{ id:  \''.$datas[$i]["BUDataID"].'\', pId:\''.$datas[$i]["PID"].'\', name: "'.$datas[$i]["BUDataName"].'", open: false},';
	          
	        }
	        return $html_Subdata;
		}	
    	//查处权限下面的顶级事件
	    function getTopPermBuData($budatatypeid){
	      $CI =& get_instance();
	      $CI->load->model('Organization/BusiDataPer_model', 'per');
	      $data=$CI->per->getToppermbudata($budatatypeid);
	      return $data;
	    }
	    function getChildPermBuData($pid,$budatatypeid){
	      $CI =& get_instance();
	      $CI->load->model('Organization/BusiDataPer_model', 'per');
	      $data=$CI->per->getChildpermbudata($pid,$budatatypeid);
	      return $data;
	    }

    }


?>