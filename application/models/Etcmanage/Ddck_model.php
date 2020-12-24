<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 订单长款模型
 */
class Ddck_model extends CI_Model{
	/**
	 * 查询订单长款数据
	 */
	function getDDCKData($StartTime,$EndTime,$keyword,$pageOnload){
		$sql = "SELECT m.orderno,m.payno,m.price,a.name paytype,m.ordertime,m.username,m.phone,'' as cardno,m.paytime,b.name status
				FROM etc_ordercz AS m
				left join lnt_dict a on m.paytype = a.dictcode
				left join lnt_dict b on m.status = b.dictcode
				WHERE NOT EXISTS (SELECT 1 FROM lnt_checkcmbpay AS u WHERE u.payno = m.payno) 
				AND m.`status` > 1";

		$params = array();
		if(!isEmpty($StartTime)){
			$StartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(m.paytime) >= UNIX_TIMESTAMP(?)';
			array_push($params, $StartTime);
		}
		if(!isEmpty($EndTime)){
			$EndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(m.paytime) <= UNIX_TIMESTAMP(?)';
			array_push($params, $EndTime);
		}
		if(!isEmpty($keyword)){
			$sql .= " and (m.orderno like concat('%',?,'%') or m.payno like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

}