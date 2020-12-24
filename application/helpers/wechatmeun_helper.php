<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('wechatmeun')) {
  //输出顶级
     function wechatmeun()
    {
      $html='[';
      //获取顶级菜单
      $meun=gettopmeun();
      //var_dump($meun);exit;
      for ($i=0; $i <count($meun) ; $i++) { 
        $num = checkNumInThisMenu($meun[$i]["id"]);
        //var_dump($num);//exit;
        if ($num['num'] >= 5) {//子菜单已达到5个或以上
            if($meun[$i]["itype"]==0){//url跳转
              $html.='{ id: \''.$meun[$i]["id"].'\', pId: \''.$meun[$i]["pid"].'\', name:" '.$meun[$i]["title"].'", open: true ,icon:\''.base_url("/asset/images/company.gif").'\',meunurl:\''.$meun[$i]["url"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:0},';
            }else if($meun[$i]["itype"]==1){//关键词读表
              $html.='{ id: \''.$meun[$i]["id"].'\', pId: \''.$meun[$i]["pid"].'\', name:" '.$meun[$i]["title"].'", open: true ,icon:\''.base_url("/asset/images/company.gif").'\',meuncode:\''.$meun[$i]["developercode"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:0},';
            }else if($meun[$i]["itype"]==2){//其他
              $html.='{ id: \''.$meun[$i]["id"].'\', pId: \''.$meun[$i]["pid"].'\', name:" '.$meun[$i]["title"].'", open: true ,icon:\''.base_url("/asset/images/company.gif").'\',meunurl:\''.$meun[$i]["url"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:0},';
            }
        }else{//还可以添加子菜单
            if($meun[$i]["itype"]==0){//url跳转
              $html.='{ id: \''.$meun[$i]["id"].'\', pId: \''.$meun[$i]["pid"].'\', name:" '.$meun[$i]["title"].'", open: true ,icon:\''.base_url("/asset/images/company.gif").'\',meunurl:\''.$meun[$i]["url"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:1},';
            }else if($meun[$i]["itype"]==1){//关键词读表
              $html.='{ id: \''.$meun[$i]["id"].'\', pId: \''.$meun[$i]["pid"].'\', name:" '.$meun[$i]["title"].'", open: true ,icon:\''.base_url("/asset/images/company.gif").'\',meuncode:\''.$meun[$i]["developercode"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:1},';
            }else if($meun[$i]["itype"]==2){//其他
              $html.='{ id: \''.$meun[$i]["id"].'\', pId: \''.$meun[$i]["pid"].'\', name:" '.$meun[$i]["title"].'", open: true ,icon:\''.base_url("/asset/images/company.gif").'\',meunurl:\''.$meun[$i]["url"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:1},';
            }
        }
        
       
          //获取子集菜单
          $html.=getsubmeun($meun[$i]['id']);
      }
      $html.=']';
      return $html;

    }
    //输出子类
    function getsubmeun($pid)
    {
      $html="";
      //查询下级公司sql
        $meun=getsubmeundata($pid);
        for ($i=0; $i<count($meun) ; $i++) { 
          # code...
          if($meun[$i]["itype"]==0){
             $html.='{ id:  \''.$meun[$i]["id"].'\', pId:\''.$meun[$i]["pid"].'\', name: "'.$meun[$i]["title"].'",uri:"",icon:\''.base_url("/asset/images/company.gif").'\',meunurl:\''.$meun[$i]["url"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:0},';
          }else if($meun[$i]["itype"]==1){
             $html.='{ id:  \''.$meun[$i]["id"].'\', pId:\''.$meun[$i]["pid"].'\', name: "'.$meun[$i]["title"].'",uri:"",icon:\''.base_url("/asset/images/company.gif").'\',meuncode:\''.$meun[$i]["developercode"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:0},';
          }else if($meun[$i]["itype"]==2){
             $html.='{ id:  \''.$meun[$i]["id"].'\', pId:\''.$meun[$i]["pid"].'\', name: "'.$meun[$i]["title"].'",uri:"",icon:\''.base_url("/asset/images/company.gif").'\',meunurl:\''.$meun[$i]["url"].'\',meuntype:\''.$meun[$i]["itype"].'\',meunpid:\''.$meun[$i]["pid"].'\',showadd:0},';
          }
         
        }
        return $html;
    }
    //查询顶级菜单数据
    function gettopmeun(){
      $CI =& get_instance();
      $sql='select * from wx_menu where pid=0 and menustatusid=1010001';
      $data=$CI->mysqlhelper->Query($sql); 
      
      return $data;
    }

    //查找子集菜单数据
    function getsubmeundata($pid){
      $CI =& get_instance();
      $sql='select * from wx_menu where pid=? and menustatusid=1010001';
      $params=array($pid);
      $data=$CI->mysqlhelper->QueryParams($sql,$params); 
      return $data;
    }


    function checkNumInThisMenu($id){
      $CI =& get_instance();
      $sql = 'select count(*) num from wx_menu where pid=? and menustatusid=1010001';
      $params=array($id);
      $num=$CI->mysqlhelper->QueryParams($sql,$params); 
      return $num[0];
    }
  
}