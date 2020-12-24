<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//取出所有后台用户的json串
if (!function_exists('getAllUser')) {

    function getAllUser(){
        $html = '[{ id: 1, pId: 0, name: "所有后台用户", open: true ,uri:"",icon:\''.base_url("/asset/images/Organization.gif").' \'},';
        //获取顶级公司sql
        //$coms = getAllCom();
        $userArr = getUser();

        foreach ($userArr as $k => $v) {
            $html .= '{ id: \''.$v["ID"].'\', pId: \'1\', name:" '.$v["EmplName"].'", open: false ,uri:\''.base_url("/index.php/admin/baseData/UserServiceLogic/UserServiceMain").'/'.$v["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\'},';
        }

        $html = substr($html, 0, -1);

        $html .= ']';

        return $html;
    }

    //查询有效用户
    function getUser(){
        $CI = & get_instance();
        $CI->load->model('baseData/Userservice_model','userservice');
        return $CI->userservice->selectAllUser();
    }

}



//去除所有的服务区
if (!function_exists('getALLService')) {
  //输出顶级功能
    function getALLService(){

        $html='[{ id: \'1\', pId:\'0\', name: "平台服务区", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\' },';
        //获取顶级管理平台
        $service = getService();

        foreach ($service as $k => $v) {
            $html.='{ id:\''.$v["poiid"].'\', pId:\'1\', name:\''.$v["name"].'\', open: false ,uri:"",icon:\''.base_url("/asset/images/system.gif").' \' },';
        }

        $html.=']';
        return $html;
    }

    //查询服务区
    function getService(){
        $CI = & get_instance();
        $CI->load->model('baseData/Userservice_model','userservice');
        return $CI->userservice->selectAllService();
    }


}