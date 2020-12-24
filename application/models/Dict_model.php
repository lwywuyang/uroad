<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登陆管理模型
 */
class Dict_model extends CI_Model{

	/**
	 * 按照fieldname查找
	 */
	public function SelectDict($codetype){
		$sql="select dictcode,name from gde_dict where codetype = ?";
		$params=array($codetype);
		return $this->mysqlhelper->QueryParams($sql,$params);
	}
	
}

?>