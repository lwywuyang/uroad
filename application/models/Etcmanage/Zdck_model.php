<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 账单长款模型
 */
class Zdck_model extends CI_Model{
	/**
	 * 查询账单长款数据
	 */
	function getZDCKData($StartTime,$EndTime,$keyword,$pageOnload){
		$sql = "SELECT m.ordernum, m.payno, m.payamount,a.name paytype, m.paytime, '' AS username, '' AS phone, '' AS `money`, '' AS cardno, m.createtime
				FROM (SELECT ordernum,payno,payamount,'10103' AS paytype,paytime,createtime FROM lnt_checkcmbpay) AS m
				left join lnt_dict a on m.paytype = a.dictcode
				WHERE NOT EXISTS ( SELECT 1 FROM etc_ordercz AS u WHERE u.payno = m.payno)";

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
			$sql .= " and (m.ordernum like concat('%',?,'%') or m.payno like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

}