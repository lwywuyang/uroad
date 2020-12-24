<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc 获取并返回xxx.php页面左侧菜单信息
 *       获取的信息中包含连接uri，控制右侧部分的信息显示
 * @return string html 包含左侧菜单信息的字符串
 */

//针对微信人员发送短信
if (!function_exists('getDepartmentAndMember')) {
    function getDepartmentAndMember(){
        $html = '[';
        $topDepartment = selectTopDep();//获取所有顶级部门信息
        //var_dump($department);exit;
        for($i=0;$i<count($topDepartment);$i++){
            $html .= '{ id: \''.$topDepartment[$i]['id'].'\', pId: 0, name:" '.$topDepartment[$i]['name'].'", open:true, uri:\''.base_url("/index.php/admin/CompanyWX/CompanyWechatAccountLogic/memberofDepment").'/'.$topDepartment[$i]["id"].'\', icon:\''.base_url("/asset/images/company.gif").'\'},';
            //查找下级队伍
            $html .= selectLowerDep($topDepartment[$i]['id']);
        }
        $html .= ']';
        return $html;
    }
    
    /**
     * @desc 查询数据库,获取部门数据
     * @return array 返回CI数据库select返回的结果数组，即为所有部门数据
     */
    function selectTopDep()
    {
        $CI = &get_instance();
        $CI->load->model('CompanyWX/CompanyWechatAccount_model', 'cwa');
        $data = $CI->cwa->selectTopDepartment();
        //var_dump($data);
        return $data;
    }
    
    /**
     * @desc 查询数据库,获取部门数据
     * @return array 返回CI数据库select返回的结果数组，即为所有部门数据
     */
    function selectLowerDep($fid)
    {
        $CI =&get_instance();
        $CI->load->model('CompanyWX/CompanyWechatAccount_model', 'cwa');
        $depData = $CI->cwa->selectTheDepartment($fid);
        $html_dep = '';
        if(count($depData) != 0){
            for($i=0;$i<count($depData);$i++){
                $html_dep .= '{ id: \''.$depData[$i]['id'].'\', pId: \''.$depData[$i]["parentid"].'\', name:" '.$depData[$i]['name'].'", open:true, uri:\''.base_url("/index.php/admin/CompanyWX/CompanyWechatAccountLogic/memberofDepment").'/'.$depData[$i]["id"].'\', icon:\''.base_url("/asset/images/company.gif").'\'},';
                //查找下级部门
                $html_dep .= selectLowerDep($depData[$i]['id']);
            }
        }
        
        return $html_dep;
    }

}



//针对标签发送短信
if (!function_exists('getTag')) {
    function getTag(){
        $CI =&get_instance();
        $CI->load->model('CompanyWX/TagSMS_model', 'tag');
        $tag = $CI->tag->selectAllTag();
        
        $html = '[';

        for($i=0;$i<count($tag);$i++){
            $html .= '{ id: \''.$tag[$i]['id'].'\', pId: 0, name:" '.$tag[$i]['tagname'].'", open:true, uri:\''.base_url("/index.php/admin/CompanyWX/TagSMSLogic/memberofTag").'/'.$tag[$i]["id"].'\', icon:\''.base_url("/asset/images/company.gif").'\'},';
        }
        $html .= ']';
        return $html;
    }
    
}
