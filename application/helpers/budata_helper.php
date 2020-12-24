<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('budata')) {
	//渠道权限
	function budata()
	{
		$CI =& get_instance();
		$empid=getsessionempid();
		$sql="SELECT
			a.PermBUDataID,
			a.PermBUDataID,
			a.PermType,
			sys_permbudata.BUDataID,
			sys_permbudata.BUDataCode,
			sys_permbudata.BUDataName,
			sys_permbudata.PID,
			sys_permbudata.SelfLinkValue,
			sys_budatatype.ID AS BUDataTypeID,
			sys_budatatype.DataTypeCode,
			sys_budatatype.*
		FROM
			(
				SELECT
					PermBUDataID,
					min(PermType) AS PermType
				FROM
					sys_datapermission
				LEFT JOIN sys_userrole ON sys_userrole.ROleID = sys_datapermission.RoleID
				WHERE
					(
						(
							sys_datapermission.EmployeeID = ?
							AND sys_datapermission.ROleID IS NULL
						)
						OR (
							sys_userrole.EmpID = ?
							AND sys_datapermission.EmployeeID IS NULL
						)
					)
				GROUP BY
					PermBUDataID
			) AS a
		INNER JOIN sys_permbudata ON a.PermBUDataID = sys_permbudata.ID
		INNER JOIN sys_budatatype ON sys_permbudata.BUDataTypeID = sys_budatatype.ID
		where sys_budatatype.DataTypeCode=?";
		$data=$CI->db->query($sql,array($empid,$empid,'S_001'))->result_array();
		$budata='';
		for($i=0;$i<count($data);$i++){
			$budata.=$data[$i]['BUDataID'].',';
		}
		$budata = substr($budata,0,strlen($budata)-1);
		if($budata!=''){
			return $budata;
		}else{
			return '-1';
		}
		
	}


}


if (!function_exists('budatabycode')) {
	function budatabycode($code)
	{
		$CI =& get_instance();
		/*$CI->load->library('session');
		$key=$CI->config->item('sessionkey');
		$empid=$CI->session->userdata($key."_EmplId");*/
		$empid = getsessionempid();
		//var_dump($empid);var_dump($empid2);exit;
		$sql="SELECT
			a.PermBUDataID,
			a.PermBUDataID,
			a.PermType,
			sys_permbudata.BUDataID,
			sys_permbudata.BUDataCode BUDataCodeold,
			gde_roadper.roadoldids BUDataCode,
			sys_permbudata.BUDataName,
			sys_permbudata.PID,
			sys_permbudata.SelfLinkValue,
			sys_budatatype.ID AS BUDataTypeID,
			sys_budatatype.DataTypeCode,
			sys_budatatype.*
		FROM
			(
				SELECT
					PermBUDataID,
					min(PermType) AS PermType
				FROM
					sys_datapermission
				LEFT JOIN sys_userrole ON sys_userrole.ROleID = sys_datapermission.RoleID
				WHERE
					(
						(
							sys_datapermission.EmployeeID = ?
							AND sys_datapermission.ROleID IS NULL
						)
						OR (
							sys_userrole.EmpID = ?
							AND sys_datapermission.EmployeeID IS NULL
						)
					)
				GROUP BY
					PermBUDataID
			) AS a
		INNER JOIN sys_permbudata ON a.PermBUDataID = sys_permbudata.ID
		INNER JOIN sys_budatatype ON sys_permbudata.BUDataTypeID = sys_budatatype.ID
		INNER JOIN gde_roadper ON sys_permbudata.BUDataID = gde_roadper.id
		where sys_budatatype.DataTypeCode=?";
		$data=$CI->db->query($sql,array($empid,$empid,$code))->result_array();
		$budata='';
		for($i=0;$i<count($data);$i++){
			//$budata.=$data[$i]['BUDataID'].',';
			$budata.=$data[$i]['BUDataCode'].',';
		}
		$budata = substr($budata,0,strlen($budata)-1);
		if($budata!=''){
			return $budata;
		}else{
			return '-1';
		}
		
	}

	
}

//调存储过程
if (!function_exists('budatabycode')) {
	function budatabyfun($code)
	{
		$CI =& get_instance();
		$empid=getsessionempid();
		$sql="call PROC_GETDATAPERM(?,?,@cc);";
		$CI->db->query($sql,array($empid));
		$sql="select @cc oroadbudata;";
		$data=$CI->db->query($sql)->result_array(); 
		if($data[0]['oroadbudata']!=''){
			return $data[0]['oroadbudata'];
		}else{
			return '-1';
		}
		
	}

	
}

	