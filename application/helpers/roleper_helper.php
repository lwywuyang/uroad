<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//取出所有角色
if (!function_exists('Role')) {
	//取出所有角色
	//输出顶级g公司
	//获取所有公司，用于角色列表的输出
   function AllRole()
    {
      $html='[{ id: 1, pId: 0, name: "组织机构", open: true ,uri:"",icon:\''.base_url("/asset/images/Organization.gif").' \'},';
      //获取顶级公司sql
      $coms=getAllCom();
      for ($i=0; $i <count($coms) ; $i++) { 
        $html.='{ id: \''.$coms[$i]["ID"].'\', pId: \'1\', name:" '.$coms[$i]["CompName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\'},';
          //获取第一级下级公司
          $html.=SubRole($coms[$i]['ID']);
          $html.=ComRole($coms[$i]['ID']);
      }
      $html.=']';
      return $html;
    }
    //输出子公司
    function SubRole($pid)
    {
      $html_tmp="";
      //查询下级公司sql
        $coms=getSubCom($pid);
        for ($i=0; $i <count($coms) ; $i++) { 
          # code...
          $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["PID"].'\', name: "'.$coms[$i]["CompName"].'", open: true ,uri:"",icon:\''.base_url("/asset/images/company.gif").'\'},';
          //查找下级公司
          $html_tmp.= ComRole($coms[$i]['ID']);
        }
        return $html_tmp;
    }
	
	//	查处每个公司下面是的角色
	function ComRole($companyid){
		$html_role="";
        $Roles=getComRole($companyid);
        for ($i=0; $i <count($Roles) ; $i++) { 
          $html_role.='{ id:  \''.$Roles[$i]["ID"].'\', pId:\''.$Roles[$i]["CompanyID"].'\', name: "'.$Roles[$i]["RoleName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/RolePerLogic/RolePerMain").'/'.$Roles[$i]["ID"].'/'.$Roles[$i]["CompanyID"].'\',icon:\''.base_url("/asset/images/role.gif").'\'},';

        }
        return $html_role;
	}
	//数据库查找
	function getComRole($companyid){
		 $CI =& get_instance();
          $CI->load->model('Organization/orgmanage_model', 'org');
          $data=$CI->org->checkComRole($companyid);
          return $data;
	}

}
//取出所有功能
if (!function_exists('AllFun')) {
	//输出顶级功能
     function AllFun($RoleId)
    {
      $html='[{ id: \'system\', pId:\'0\', name: "系统平台", open: true ,uri:"",icon:\''.base_url("/asset/images/falt.gif").'\' },';
      //获取顶级管理平台
      $Forms=getPlatForm();

      for ($i=0; $i <count($Forms) ; $i++) { 
        $html.='{ id:\''.$Forms[$i]["ID"].'\', pId:\'system\', name:\''.$Forms[$i]["Name"].'\', open: true ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionList").'/'.$Forms[$i]["ID"].'\',icon:\''.base_url("/asset/images/system.gif").' \' },';
        
          //获取顶级事件公司
           $html.=TopFun($Forms[$i]['ID'],$RoleId);
          // //查找下级部门
          // $html.= getDep($Forms[$i]["ID"]);
      }
      $html.=']';
      return $html;
    }
     //输出管理下的事件,顶级事件
    function TopFun($systemid,$RoleId)
    {
        $html_fun="";
        $funs=getParentFun($systemid);
        // 查找已经选择的功能
        $check=CheckRoleId($RoleId);
        for ($i=0; $i <count($funs) ; $i++) {
           $flag=0;
          for($j=0;$j < count($check); $j++){
                if($funs[$i]["ID"] == $check[$j]['FunctionID']){
                  $html_fun.='{ id:  \''.$funs[$i]["ID"].'\', pId:\''.$funs[$i]["SystemID"].'\', name: "'.$funs[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionMain").'/'.$funs[$i]["ID"].'/'.$funs[$i]["SystemID"].'\',icon:\''.base_url("/asset/images/menu.gif").'\',checked:true},';
                  $flag=1;
            }
          }    
          if($flag==0){
            $html_fun.='{ id:  \''.$funs[$i]["ID"].'\', pId:\''.$funs[$i]["SystemID"].'\', name: "'.$funs[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionMain").'/'.$funs[$i]["ID"].'/'.$funs[$i]["SystemID"].'\',icon:\''.base_url("/asset/images/menu.gif").'\'},';
          }
          
          
           $html_fun.=SubFun($funs[$i]['ID'],$RoleId);
        }
        return $html_fun;
    }
  //取出事件管理
    function SubFun($id,$RoleId){
        $html_Subfun="";
      //查询下级公司sql
        $Subfuns=getChildFun($id,$RoleId);
         $check=CheckRoleId($RoleId);
        for ($i=0; $i <count($Subfuns) ; $i++) { 
          # code...
          $flag=0;
            for($j=0;$j < count($check); $j++){
               if($Subfuns[$i]["ID"] == $check[$j]['FunctionID']){
                 $html_Subfun.='{ id:  \''.$Subfuns[$i]["ID"].'\', pId:\''.$Subfuns[$i]["PID"].'\', name: "'.$Subfuns[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionMain").'/'.$Subfuns[$i]["ID"].'/'.$Subfuns[$i]["SystemID"].'\',icon:\''.base_url("/asset/images/page.gif").'\',checked:true},';
                 $flag=1;
               }
             
            }
            if($flag==0){
              $html_Subfun.='{ id:  \''.$Subfuns[$i]["ID"].'\', pId:\''.$Subfuns[$i]["PID"].'\', name: "'.$Subfuns[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionMain").'/'.$Subfuns[$i]["ID"].'/'.$Subfuns[$i]["SystemID"].'\',icon:\''.base_url("/asset/images/page.gif").'\'},';
            }
          
          $html_Subfun.=btn($Subfuns[$i]['ID'],$RoleId);
        }

        return $html_Subfun;
    }
    //取出事件下面的按钮

  function btn($id,$RoleId){
        $html_btn="";
      //查询下级公司sql
        $btns=getChildFun($id);
        $check=CheckRoleId($RoleId);
        for ($i=0; $i <count($btns) ; $i++) { 
          # code...
          $flag=0;
            for($j=0;$j < count($check); $j++){
               if($btns[$i]["ID"] == $check[$j]['FunctionID']){
                $html_btn.='{ id:  \''.$btns[$i]["ID"].'\', pId:\''.$btns[$i]["PID"].'\', name: "'.$btns[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionList").'/'.$btns[$i]["ID"].'\',icon:\''.base_url("/asset/images/button.gif").'\',checked:true},';
                $flag=1;
               }
             }
             if($flag==0){
              $html_btn.='{ id:  \''.$btns[$i]["ID"].'\', pId:\''.$btns[$i]["PID"].'\', name: "'.$btns[$i]["FuncName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/FunctionLogic/FunctionList").'/'.$btns[$i]["ID"].'\',icon:\''.base_url("/asset/images/button.gif").'\'},';
             }        
        }
        return $html_btn;
    }
    //验证是否要选中
    // 数据库查出对应角色id已经有的权限
    function CheckRoleId($RoleId){
    	    $CI =& get_instance();
          $CI->load->model('Organization/roleper_model', 'roleper');
          $data=$CI->roleper->checkroleid($RoleId);
          return $data;
    }
  

}
if (!function_exists('DataType')) {
function DataType()
  {
    $html='[{ id: \'system\', pId:\'0\', name: "系统平台", open: true ,uri:"",icon:\''.base_url("/asset/images/falt.gif").'\' },';
      //获取顶级管理平台
      $Forms=getBUDataType();
      for ($i=0; $i <count($Forms) ; $i++) { 
        $html.='{ id:\''.$Forms[$i]["ID"].'\', pId:\'system\', name:\''.$Forms[$i]["Name"].'\', open: true ,uri:\''.base_url("/index.php/admin/Organization/BusiDataPerLogic/BUDataTypeList").'/'.$Forms[$i]["ID"].'\',icon:\''.base_url("/asset/images/system.gif").' \' },';
        
          //获取顶级事件公司
           $html.=SubDataPer($Forms[$i]['ID']);
          // //查找下级部门
          // $html.= getDep($Forms[$i]["ID"]);
      }
      $html.=']';
      return $html;
  }

  //查找平台下面的权限
  function SubDataPer($systemid){
    $html_per="";
        $pers=getDataPer($systemid);
        for ($i=0; $i <count($pers) ; $i++) { 
          # code...
          $html_per.='{ id:  \''.$pers[$i]["ID"].'\', pId:\''.$pers[$i]["SystemID"].'\', name: "'.$pers[$i]["BuName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/BusiDataPerLogic/DataPerMian").'/'.$pers[$i]["ID"].'\',icon:\''.base_url("/asset/images/function.gif").'\'},';
         
        }
        return $html_per;
  }
}

if (!function_exists('Org')) {
     //输出顶级
     function Org($roleid)
    {
      $html='[{ id: 1, pId: 0, name: "组织机构", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyList").'\',icon:\''.base_url("/asset/images/Organization.gif").' \',checked:false,isempl:0},';
      //获取顶级公司sql
      $coms=getAllCom();
      for ($i=0; $i <count($coms) ; $i++) { 
        $html.='{ id: \''.$coms[$i]["ID"].'\', pId: \'1\', name:" '.$coms[$i]["CompName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyMain").'/'.$coms[$i]["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\',checked:false,isempl:0},';
          //获取第一级下级公司
          $html.=Com($coms[$i]['ID'],$roleid);
          //查找下级部门
          $html.= Dep($coms[$i]["ID"],$roleid);
      }
      $html.=']';
      return $html;

    }
 
    //输出子公司
    function Com($pid,$roleid)
    {
      $html_tmp="";
      //查询下级公司sql
        $coms=getSubCom($pid);
        for ($i=0; $i <count($coms) ; $i++) { 
          # code...
          $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["PID"].'\', name: "'.$coms[$i]["CompName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyMain").'/'.$coms[$i]["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\',checked:false,isempl:0},';
          //查找下级公司
          $html_tmp.= Com($coms[$i]["ID"],$roleid);
          //查找下级部门
          $html_tmp.= Dep($coms[$i]["ID"],$roleid);
        }
        return $html_tmp;
    }
    //输出顶级部门
    function Dep($id,$roleid)
    {
      $html_dep='';
      //获取顶级部门sql
      $deps=getAllDep($id);
      for ($i=0; $i <count($deps) ; $i++) { 
        $html_dep.='{ id: \''.$deps[$i]["ID"].'\', pId: \''.$deps[$i]["CompanyID"].'\', name:" '.$deps[$i]["DepaName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/DepartmentMain").'/'.$deps[$i]["ID"].'\',icon:\''.base_url("/asset/images/department.gif").'\',checked:false,isempl:0},';
          //获取第一级下级部门
          $html_dep.=ChildDep($deps[$i]['ID'],$roleid);
          //获取部门到员工
          $html_dep.=Emp($deps[$i]['ID'],$roleid);
      }
      return $html_dep;
    }
    //输出子部门
    function ChildDep($id,$roleid){
        $html_childdep='';
        $deps=getSubDep($id);
        for ($i=0; $i <count($deps) ; $i++) { 
          # code...
          $html_childdep.='{ id:  \''.$deps[$i]["ID"].'\', pId:\''.$deps[$i]["PID"].'\', name: "'.$deps[$i]["DepaName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/DepartmentMain").'/'.$deps[$i]["ID"].'\',icon:\''.base_url("/asset/images/department.gif").'\',checked:false,isempl:0},';
          //循环查找子部门
          $html_childdep.= ChildDep($deps[$i]["ID"],$roleid);
          // 获取部门到员工
          $html_childdep.=Emp($deps[$i]['ID'],$roleid);

        }
        return $html_childdep;
    }
    //输出员工,已经选择的就不要显示出来
    function Emp($id,$roleid){
        $html_emp='';
        $emps=getAllEmp($id);
        //查找已经选中的
        $select=selectemp($roleid);
        for ($i=0; $i <count($emps) ; $i++) { 
          // 循环比较，看看有没有选中过
          $flag=0;
          for($j=0;$j<count($select);$j++){
            if($emps[$i]['ID']==$select[$j]['EmpID']){
              $flag=1;
            }
          }
          if($flag==0){
            $html_emp.='{ id: \''.$emps[$i]["ID"].'\', pId: \''.$emps[$i]["DepartmentID"].'\', name:" '.$emps[$i]["EmplName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/personal.gif").'\',checked:false,isempl:1 },';
          }
        
      }

      return $html_emp;
    }
    //查找员工
    function selectemp($roleid){
      $CI =& get_instance();
      $CI->load->model('Organization/roleper_model', 'roleper');
      $data=$CI->roleper->SelectEmp($roleid);
      return $data;
    }
}