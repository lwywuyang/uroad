<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getEmpPerOrg')) {
  //输出顶级
     function getEmpPerOrg()
    {
      $html='[{ id: 1, pId: 0, name: "组织机构", open: true ,uri:"",icon:\''.base_url("/asset/images/Organization.gif").' \'},';
      //获取顶级公司sql
      $coms=getAllCom();
      for ($i=0; $i <count($coms) ; $i++) { 
        $html.='{ id: \''.$coms[$i]["ID"].'\', pId: \'1\', name:" '.$coms[$i]["CompName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\'},';
          //获取第一级下级公司
          $html.=getEmpPerCom($coms[$i]['ID']);
          //查找下级部门
          $html.= getEmpPerDep($coms[$i]["ID"]);
      }
      $html.=']';
      return $html;

    }
    //输出子公司
    function getEmpPerCom($pid)
    {
      $html_tmp="";
      //查询下级公司sql
        $coms=getSubCom($pid);
        for ($i=0; $i <count($coms) ; $i++) { 
          # code...
          $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["PID"].'\', name: "'.$coms[$i]["CompName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\'},';
          //查找下级公司
          $html_tmp.= getEmpPerCom($coms[$i]["ID"]);
          //查找下级部门
          $html_tmp.= getEmpPerDep($coms[$i]["ID"]);
        }
        return $html_tmp;
    }
    //输出顶级部门
    function getEmpPerDep($id)
    {
      $html_dep='';
      //获取顶级部门sql
      $deps=getAllDep($id);
      for ($i=0; $i <count($deps) ; $i++) { 
        $html_dep.='{ id: \''.$deps[$i]["ID"].'\', pId: \''.$deps[$i]["CompanyID"].'\', name:" '.$deps[$i]["DepaName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/department.gif").'\'},';
          //获取第一级下级部门
          $html_dep.=getChildEmpPerDep($deps[$i]['ID']);
          //获取部门到员工
          $html_dep.=getEmpPerEmp($deps[$i]['ID']);
      }
      return $html_dep;
    }
    //输出子部门
    function getChildEmpPerDep($id){
        $html_childdep='';
        $deps=getSubDep($id);
        for ($i=0; $i <count($deps) ; $i++) { 
          # code...
          $html_childdep.='{ id:  \''.$deps[$i]["ID"].'\', pId:\''.$deps[$i]["PID"].'\', name: "'.$deps[$i]["DepaName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/department.gif").'\'},';
          //循环查找子部门
          $html_childdep.= getChildEmpPerDep($deps[$i]["ID"]);
          // 获取部门到员工
          $html_childdep.=getEmpPerEmp($deps[$i]['ID']);

        }
        return $html_childdep;
    }
    //输出员工
    function getEmpPerEmp($id){
        $html_emp='';
        $emps=getAllEmp($id);
        for ($i=0; $i <count($emps) ; $i++) { 
        $html_emp.='{ id: \''.$emps[$i]["ID"].'\', pId: \''.$emps[$i]["DepartmentID"].'\', name:" '.$emps[$i]["EmplName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/EmpPerLogic/EmpPerMain").'?empid='.$emps[$i]["ID"].'\',icon:\''.base_url("/asset/images/personal.gif").'\'},';
      }

      return $html_emp;
    }
  
}

/**************************员工选择的权限显示****************************************/

//取出功能
if (!function_exists('AllEmpPerFun')) {
  /**
   * 输出固定的系统平台
   */
     function AllEmpPerFun($empid)
    {
      $html='[{ id:1, pId:\'0\', name: "系统平台", open: true ,uri:"",icon:\''.base_url("/asset/images/falt.gif").'\'},';
      //获取顶级管理平台
      $Forms=getPlatForm();
      for ($i=0; $i <count($Forms) ; $i++) { 
        $html.='{ id:\''.$Forms[$i]["ID"].'\', pId:1, name:\''.$Forms[$i]["Name"].'\', open: true ,uri:"",icon:\''.base_url("/asset/images/system.gif").' \'},';     
          //获取顶级功能
          $html.=TopEmpPerFun($Forms[$i]['ID'],$empid);
      }
      $html.=']';
      return $html;
    }

/**
 * 获取顶级平台下的顶级菜单
 */
    function TopEmpPerFun($systemid,$empid)
    {
        $html_fun="";
        $funs=getParentFun($systemid);
        // 查找对应角色选择的功能        
        $check=CheckFunPer($empid); 
        // 查处员工选择的功能
        $checkEmpFunPre=Checkempfunper($empid);
        for ($i=0; $i <count($funs) ; $i++) {
           $flag=0;
           //循环 查出角色拥有的功能
          for($j=0;$j < count($check); $j++){
                if($funs[$i]["ID"] == $check[$j]['FunctionID']){
                  //验证角色选择的功能，设置为已经打勾,并且不能修改
                       $html_fun.='{ id:  \''.$funs[$i]["ID"].'\', pId:\''.$funs[$i]["SystemID"].'\', name: "'.$funs[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/menu.gif").'\',font:{\'color\':\'red\'},checked:true,chkDisabled:true},';
                        $flag=1;
                  }
                     
             } 
          //验证员工选择的功能
          for($k=0;$k<count($checkEmpFunPre);$k++){
              //如果员工选择的功能不等于角色选择的功能，设置打勾，但是可以修改
              if($funs[$i]["ID"]==$checkEmpFunPre[$k]['FunctionID']){
                  $html_fun.='{ id:  \''.$funs[$i]["ID"].'\', pId:\''.$funs[$i]["SystemID"].'\', name: "'.$funs[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/menu.gif").'\',checked:true,chkDisabled:false},';
                  $flag=1;
                }
            }
                                
            
          if($flag==0){
            $html_fun.='{ id:  \''.$funs[$i]["ID"].'\', pId:\''.$funs[$i]["SystemID"].'\', name: "'.$funs[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/menu.gif").'\',checked:false,chkDisabled:false},';
          }
           $html_fun.=SubEmpPerFun($funs[$i]['ID'],$empid);
        }
        return $html_fun;
    }
  //取出事件管理
    function SubEmpPerFun($id,$empid){
        $html_Subfun="";
      //查询下级公司sql
        $Subfuns=getChildFun($id,$empid);
         $check=CheckFunPer($empid);
         $checkEmpFunPre=Checkempfunper($empid);
        for ($i=0; $i <count($Subfuns) ; $i++) { 
          # code...
          $flag=0;
            for($j=0;$j < count($check); $j++){
               if($Subfuns[$i]["ID"] == $check[$j]['FunctionID']){
                  $html_Subfun.='{ id:  \''.$Subfuns[$i]["ID"].'\', pId:\''.$Subfuns[$i]["PID"].'\', name: "'.$Subfuns[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/page.gif").'\',font:{\'color\':\'red\'},checked:true,chkDisabled:true},';
                   $flag=1;
               }             
            }
  

           for($k=0;$k<count($checkEmpFunPre);$k++){
              if($Subfuns[$i]["ID"]==$checkEmpFunPre[$k]['FunctionID']){
                  $html_Subfun.='{ id:  \''.$Subfuns[$i]["ID"].'\', pId:\''.$Subfuns[$i]["PID"].'\', name: "'.$Subfuns[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/page.gif").'\',checked:true,chkDisabled:false},';
                  $flag=1;
                }
              }
                                 
       
            if($flag==0){
              $html_Subfun.='{ id:  \''.$Subfuns[$i]["ID"].'\', pId:\''.$Subfuns[$i]["PID"].'\', name: "'.$Subfuns[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/page.gif").'\',checked:false,chkDisabled:false},';
            }
          
          $html_Subfun.=EmpPerbtn($Subfuns[$i]['ID'],$empid);
        }

        return $html_Subfun;
    }
    //取出事件下面的按钮

  function EmpPerbtn($id,$empid){
        $html_btn="";
      //查询下级公司sql
        $btns=getChildFun($id);
       $check=CheckFunPer($empid);
       $checkEmpFunPre=Checkempfunper($empid);
        for ($i=0; $i <count($btns) ; $i++) { 
          # code...
          $flag=0;
            for($j=0;$j < count($check); $j++){
               if($btns[$i]["ID"] == $check[$j]['FunctionID']){ 

                     $html_btn.='{ id:  \''.$btns[$i]["ID"].'\', pId:\''.$btns[$i]["PID"].'\', name: "'.$btns[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/button.gif").'\',font:{\'color\':\'red\'},checked:true,chkDisabled:true},';
                $flag=1;
               }
             }

                        for($k=0;$k<count($checkEmpFunPre);$k++){
                          if($btns[$i]["ID"]==$checkEmpFunPre[$k]['FunctionID']){
                              $html_btn.='{ id:  \''.$btns[$i]["ID"].'\', pId:\''.$btns[$i]["PID"].'\', name: "'.$btns[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/page.gif").'\',checked:true,chkDisabled:false},';
                               $flag=1;
                          }
                        }
                      
                             
               
               
             if($flag==0){
              $html_btn.='{ id:  \''.$btns[$i]["ID"].'\', pId:\''.$btns[$i]["PID"].'\', name: "'.$btns[$i]["FuncName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/button.gif").'\',checked:false,chkDisabled:false},';
             }        
        }
        return $html_btn;
    }
    
/**
 * 按照员工id取出角色id
 */
    function CheckFunPer($empid){
      $CI =& get_instance();
      $CI->load->model('Organization/empper_model', 'empper');
      $data=$CI->empper->checkfunper($empid);
      return $data;
    }
    /**
     * 查处员工选择的功能
     */
    function Checkempfunper($empid){
      $CI =& get_instance();
      $CI->load->model('Organization/empper_model', 'empper');
      $data=$CI->empper->checkempfunper($empid);
      return $data;
    }
}

//取出业务权限
if (!function_exists('EmpPerDataType')) {
function EmpPerDataType()
  {
    $html='[{ id: \'system\', pId:\'0\', name: "系统平台", open: true ,uri:"",icon:\''.base_url("/asset/images/falt.gif").'\' },';
      //获取顶级管理平台
      $Forms=getBUDataType();
      for ($i=0; $i <count($Forms) ; $i++) { 
        $html.='{ id:\''.$Forms[$i]["ID"].'\', pId:\'system\', name:\''.$Forms[$i]["Name"].'\', open: true ,uri:"",icon:\''.base_url("/asset/images/system.gif").' \' },';      
          //获取顶级事件公司
           $html.=SubEmpPerDataPer($Forms[$i]['ID']);
          // //查找下级部门
      }
      $html.=']';
      return $html;
  }

  //输出平台下面的权限
  function SubEmpPerDataPer($systemid){
    $html_per="";
        $pers=getDataPer($systemid);
        for ($i=0; $i <count($pers) ; $i++) { 
          # code...
          $html_per.='{ id:  \''.$pers[$i]["ID"].'\', pId:\''.$pers[$i]["SystemID"].'\', name: "'.$pers[$i]["BuName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/function.gif").'\'},';
         
        }
        return $html_per;
  }
}
//查找所有公司下面的角色
// 查出顶级公司
if (!function_exists('getEmpRole')) {

  //获取所有公司，用于角色列表的输出
   function getEmpRole($empid)
    {
      $html='[{ id: \'system\', pId: 0, name: "组织机构", open: true ,uri:"",icon:\''.base_url("/asset/images/Organization.gif").' \',isemp:0},';
      //获取顶级公司sql
      $coms=getAllCom();
      for ($i=0; $i <count($coms) ; $i++) { 
        $html.='{ id: \''.$coms[$i]["ID"].'\', pId: \'system\', name:" '.$coms[$i]["CompName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\'},';
          //获取第一级下级公司
          $html.=getSubEmpRole($coms[$i]['ID'],$empid);
          $html.=getEmpRoleData($coms[$i]["ID"],$empid);
      }
      $html.=']';
      return $html;
    }
    //输出子公司
    function getSubEmpRole($pid,$empid)
    {
      $html_tmp="";
      //查询下级公司sql
        $coms=getSubCom($pid);
        for ($i=0; $i <count($coms) ; $i++) { 
          # code...
          $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["PID"].'\', name: "'.$coms[$i]["CompName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\',isemp:0},';
          //查找下级公司
          $html_tmp.= getSubEmpRole($coms[$i]['ID'],$empid);
          $html_tmp.=getEmpRoleData($coms[$i]["ID"],$empid);

        }
        return $html_tmp;
    }
    //输出公司下到角色
    function getEmpRoleData($comid,$empid){
      $html_tmp="";
      //查询下级公司sql
        $coms=checkeproleData($comid);
        $check=checkRoledata($empid);
        for ($i=0; $i <count($coms); $i++) { 
          # code...
          $flag=0;
          for($j=0;$j<count($check);$j++){
            
            if($check[$j]["RoleID"]==$coms[$i]["ID"]){
              $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["CompanyID"].'\', name: "'.$coms[$i]["RoleName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/role.gif").'\',font:{\'color\':\'red\'},isemp:1,checked:true,chkDisabled:true,},';
              $flag=1;
            }
          }
          if($flag==0){
             $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["CompanyID"].'\', name: "'.$coms[$i]["RoleName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/role.gif").'\',isemp:1,checked:false,chkDisabled:false},';
          }
          
          //查找下级公司
         
        }
        return $html_tmp;
    }
    //数据库取出角色数据
    function checkeproleData($comid){
      $CI =& get_instance();
      $CI->load->model('Organization/empper_model', 'empper');
      $data=$CI->empper->emproledata($comid);
      return $data;
    }
    //取出已经选中的roleid
    function checkRoledata($empid){
      $CI =& get_instance();
      $CI->load->model('Organization/empper_model', 'empper');
      $data=$CI->empper->checkroledata($empid);
      return $data;
    }

}
if (!function_exists('GetUserHasFunPermission')) {
  function GetUserHasFunPermission($empid,$funcode)
  {
    $CI =& get_instance();
    $sql="select  sys_funpermission.FunctionId ,min(PermType) as PermType  
                        from sys_funpermission 
                        join sys_functions on sys_funpermission.FunctionID=sys_functions.ID
                        left join sys_userrole on sys_funpermission.RoleID = sys_userrole.RoleID 
                        left join sys_role on sys_funpermission.RoleID=sys_role.ID 
                        where ( sys_userrole.EmpID=? or EmployeeID=?) and sys_functions.FuncCode=?
                        group by sys_funpermission.FunctionId";
    $params=array($empid,$empid,$funcode);
    return $CI->mysqlhelper->QueryRow($sql,$params)>0?true:false;
    
  }
}