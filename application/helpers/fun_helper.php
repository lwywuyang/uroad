<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('getAllFun')) {
  //输出顶级功能
     function getAllFun()
    {
      $html='[{ id: \'1\', pId:\'0\', name: "系统平台", open: true ,uri:"",icon:\''.base_url("/asset/images/falt.gif").'\' },';
      //获取顶级管理平台
      $Forms=getPlatForm();
      for ($i=0; $i <count($Forms) ; $i++) { 
        $html.='{ id:\''.$Forms[$i]["ID"].'\', pId:\'1\', name:\''.$Forms[$i]["Name"].'\', open: true ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionList").'/'.$Forms[$i]["ID"].'\',icon:\''.base_url("/asset/images/system.gif").' \' },';
        
          //获取顶级事件公司
           $html.=getTopFun($Forms[$i]['ID']);
          // //查找下级部门
          // $html.= getDep($Forms[$i]["ID"]);
      }
      $html.=']';
      return $html;
    }
     //输出管理下的事件,顶级事件
    function getTopFun($systemid)
    {
      $html_fun="";
        $funs=getParentFun($systemid);
        for ($i=0; $i <count($funs) ; $i++) { 
          # code...
          $html_fun.='{ id:  \''.$funs[$i]["ID"].'\', pId:\''.$funs[$i]["SystemID"].'\', name: "'.$funs[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionMain").'/'.$funs[$i]["ID"].'/'.$funs[$i]["SystemID"].'\',icon:\''.base_url("/asset/images/menu.gif").'\'},';
           $html_fun.=getSubFun($funs[$i]['ID']);
        }
        return $html_fun;
    }
  //取出事件管理
    function getSubFun($id){
        $html_Subfun="";
      //查询下级公司sql
        $Subfuns=getChildFun($id);
        for ($i=0; $i <count($Subfuns) ; $i++) { 
          # code...
          $html_Subfun.='{ id:  \''.$Subfuns[$i]["ID"].'\', pId:\''.$Subfuns[$i]["PID"].'\', name: "'.$Subfuns[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionMain").'/'.$Subfuns[$i]["ID"].'/'.$Subfuns[$i]["SystemID"].'\',icon:\''.base_url("/asset/images/page.gif").'\'},';
          $html_Subfun.=getbtn($Subfuns[$i]['ID']);
        }

        return $html_Subfun;
    }
    //取出事件下面的按钮

  function getbtn($id){
        $html_btn="";
      //查询下级公司sql
        $btns=getChildFun($id);
        for ($i=0; $i <count($btns) ; $i++) { 
          # code...
          $html_btn.='{ id:  \''.$btns[$i]["ID"].'\', pId:\''.$btns[$i]["PID"].'\', name: "'.$btns[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionList").'/'.$btns[$i]["ID"].'\',icon:\''.base_url("/asset/images/button.gif").'\'},';
        }

        return $html_btn;
    }
   
    //查找所有管理
    function getPlatForm(){
      $CI =& get_instance();
      $CI->load->model('Organization/platform_model','plat');
      $data=$CI->plat->check('sys_platform');
      return $data;
    }
     //查找顶级事件
     function getParentFun($systemid)
    {
      $CI =& get_instance();
      $CI->load->model('Organization/function_model', 'fun');
      $data=$CI->fun->gettopfun($systemid);
      return $data;
    }
    //按照id事件管理
    function getChildFun($id){
      $CI =& get_instance();
      $CI->load->model('Organization/function_model', 'fun');
      $data=$CI->fun->gettsubfun($id);
      return $data;

    }
    

} 


