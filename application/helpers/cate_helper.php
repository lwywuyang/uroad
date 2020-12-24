<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('getOrg')) {
  //输出顶级
     function getOrg()
    {
      $html='[{ id: 1, pId: 0, name: "组织机构", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyList").'\',icon:\''.base_url("/asset/images/Organization.gif").' \'},';
      //获取顶级公司sql
      $coms=getAllCom();
      for ($i=0; $i <count($coms) ; $i++) { 
        $html.='{ id: \''.$coms[$i]["ID"].'\', pId: \'1\', name:" '.$coms[$i]["CompName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyMain").'/'.$coms[$i]["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\'},';
          //获取第一级下级公司
          $html.=getCom($coms[$i]['ID']);
          //查找下级部门
          $html.= getDep($coms[$i]["ID"]);
      }
      $html.=']';
      return $html;

    }
 
    //输出子公司
    function getCom($pid)
    {
      $html_tmp="";
      //查询下级公司sql
        $coms=getSubCom($pid);
        for ($i=0; $i <count($coms) ; $i++) { 
          # code...
          $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["PID"].'\', name: "'.$coms[$i]["CompName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyMain").'/'.$coms[$i]["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\'},';
          //查找下级公司
          $html_tmp.= getCom($coms[$i]["ID"]);
          //查找下级部门
          $html_tmp.= getDep($coms[$i]["ID"]);
        }
        return $html_tmp;
    }
    //输出顶级部门
    function getDep($id)
    {
      $html_dep='';
      //获取顶级部门sql
      $deps=getAllDep($id);
      for ($i=0; $i <count($deps) ; $i++) { 
        $html_dep.='{ id: \''.$deps[$i]["ID"].'\', pId: \''.$deps[$i]["CompanyID"].'\', name:" '.$deps[$i]["DepaName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/DepartmentMain").'/'.$deps[$i]["ID"].'\',icon:\''.base_url("/asset/images/department.gif").'\'},';
          //获取第一级下级部门
          $html_dep.=getChildDep($deps[$i]['ID']);
          //获取部门到员工
          $html_dep.=getEmp($deps[$i]['ID']);
      }
      return $html_dep;
    }
    //输出子部门
    function getChildDep($id){
        $html_childdep='';
        $deps=getSubDep($id);
        for ($i=0; $i <count($deps) ; $i++) { 
          # code...
          $html_childdep.='{ id:  \''.$deps[$i]["ID"].'\', pId:\''.$deps[$i]["PID"].'\', name: "'.$deps[$i]["DepaName"].'", open: false ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/DepartmentMain").'/'.$deps[$i]["ID"].'\',icon:\''.base_url("/asset/images/department.gif").'\'},';
          //循环查找子部门
          $html_childdep.= getChildDep($deps[$i]["ID"]);
          // 获取部门到员工
          $html_childdep.=getEmp($deps[$i]['ID']);

        }
        return $html_childdep;
    }
    //输出员工
    function getEmp($id){
        $html_emp='';
        $emps=getAllEmp($id);
        for ($i=0; $i <count($emps) ; $i++) { 
        $html_emp.='{ id: \''.$emps[$i]["ID"].'\', pId: \''.$emps[$i]["DepartmentID"].'\', name:" '.$emps[$i]["EmplName"].'", open: false ,uri:"",icon:\''.base_url("/asset/images/personal.gif").'\'},';
      }

      return $html_emp;
    }
    //查找顶级公司
     function getAllCom()
    {
      $CI =& get_instance();
      $CI->load->model('Organization/orgmanage_model', 'org');
      $data=$CI->org->GetAllCom();
      return $data;
    }

    //查找下级公司
    //处处子id==pid的数据
     function getSubCom($pid)
    {
      $CI =& get_instance();
      $CI->load->model('Organization/orgmanage_model', 'org');
      $data=$CI->org->GetSubCom($pid);
      return $data;
    }
    //查找与公司相同的id的部门
    function getAllDep($id){
      $CI =& get_instance();
      $CI->load->model('Organization/orgmanage_model', 'org');
      $data=$CI->org->GetAllDep($id);
      return $data;
    }
    //查找子部门
    function getSubDep($id){
      $CI =& get_instance();
      $CI->load->model('Organization/orgmanage_model', 'org');
      $data=$CI->org->GetSubDep($id);
      return $data;
    }
    //查找部门下面的员工
    function getAllEmp($id){
      $CI =& get_instance();
      $CI->load->model('Organization/orgmanage_model', 'org');
      $data=$CI->org->GetAllEmp($id);
      return $data;
    }

} 

if (!function_exists('getRole')) {

  //获取所有公司，用于角色列表的输出
   function getRole()
    {
      $html='[{ id: 1, pId: 0, name: "组织机构", open: true ,uri:\''.base_url("/index.php/admin/Organization/OrgManageLogic/CompanyList").'\',icon:\''.base_url("/asset/images/Organization.gif").' \'},';
      //获取顶级公司sql
      $coms=getAllCom();
      for ($i=0; $i <count($coms) ; $i++) { 
        $html.='{ id: \''.$coms[$i]["ID"].'\', pId: \'1\', name:" '.$coms[$i]["CompName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/RoleLogic/RoleList").'/'.$coms[$i]["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\'},';
          //获取第一级下级公司
          $html.=getSubRole($coms[$i]['ID']);
      }
      $html.=']';
      return $html;
    }
    //输出子公司
    function getSubRole($pid)
    {
      $html_tmp="";
      //查询下级公司sql
        $coms=getSubCom($pid);
        for ($i=0; $i <count($coms) ; $i++) { 
          # code...
          $html_tmp.='{ id:  \''.$coms[$i]["ID"].'\', pId:\''.$coms[$i]["PID"].'\', name: "'.$coms[$i]["CompName"].'", open: true ,uri:\''.base_url("/index.php/admin/Organization/RoleLogic/RoleList").'/'.$coms[$i]["ID"].'\',icon:\''.base_url("/asset/images/company.gif").'\'},';
          //查找下级公司
          $html_tmp.= getSubRole($coms[$i]['ID']);
        }
        return $html_tmp;
    }

}
  if (!function_exists('getRoleData')) {
      //循环查处pid
        function getRoleData($id){
          $html_role='[';
      //获取顶级公司sql
          $Roles=getAllRole($id);
          // P($Roles);die;
          for ($i=0; $i <count($Roles) ; $i++) { 
            $html_role.='{"ID":"'.$Roles[$i]["ID"].'","RoleName":"'.$Roles[$i]["RoleName"].'","Remark":"'.$Roles[$i]["Remark"].'","CompanyID":"'.$Roles[$i]["CompanyID"].'","Status":"'.$Roles[$i]["Status"].'","PID":"'.$Roles[$i]["PID"].'"}';
            if($i<count($Roles)-1){
              $html_role.=',';
            }
            
          }
          $html_role.=']';
          return $html_role;
        }
        // 数据库查
        function getAllRole($id){
          $CI =& get_instance();
          $CI->load->model('Organization/orgmanage_model', 'org');
          $data=$CI->org->checkComRole($id);
          return $data;
        }

    }