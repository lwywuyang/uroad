<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》爆料信息控制器RoadEventLogic的模型类
 * @author hwq
 * @version 1.0
 */
class User_model extends CI_Model{
	/**
	 * @desc   获取下拉框信息
	 * @return [type]      [description]
	 */
	public function selectEventTypeMsg(){
		$sql = "select dictcode,name from gde_dict where codetype='1015' ";
		return $data = $this->mysqlhelper->Query($sql);
	}


	//查询用户信息
	public function selectUserMsg($startTime,$endTime,$keyword,$pageOnload){
		$sql = 'select userid,username,usernickname,status,phone,mails,createtime,iconfile 
				from gde_user 
				where 1=1 ';
		$params = array();
		if (!isEmpty($startTime)) {
			$sql .= ' and UNIX_TIMESTAMP(createtime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$sql .= ' and UNIX_TIMESTAMP(createtime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$endTime);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and (username like concat('%',?,'%') or usernickname like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}


}