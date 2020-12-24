<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('getsession')) {
	/**
	 * 判断有没有登录过期
	 * @return [type] [description]
	 */
	function checksession()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		if($CI->session->userdata($key."_EmplId")==''||$CI->session->userdata($key."_EmplId")==null||$CI->session->userdata($key."_EmplId")=='0'){
            session_write_close();
		    echo '<script>alert("登陆超时，请重新登陆");top.location.href="'.base_url('index.php/admin/login').'";</script>';
			exit;
		}
        session_write_close();

	}

	//获取empid
	function getsessionempid(){
		$CI =& get_instance();
		$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		$data = $CI->session->userdata($key."_EmplId");
        session_write_close();
		return $data;
	}

	//获取empname
	function getsessionempname(){
		$CI =& get_instance();
		$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		$data = $CI->session->userdata($key."_EmplName");
        session_write_close();
        return $data;
	}
	//获取部门id
	function getsessiondepaid(){
		$CI =& get_instance();
		$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		$data = $CI->session->userdata($key."_DepartmentID");
        session_write_close();
        return $data;
	}
	//获取部门
	function getsessiondepaname(){
		$CI =& get_instance();
		$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		$data = $CI->session->userdata($key."_DepaName");
        session_write_close();
        return $data;
	}

	//路段权限
	function getsessionuserbudata(){
		$CI =& get_instance();
		$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		$data = $CI->session->userdata($key."_UserBudata");
        session_write_close();
        return $data;
	}

}

