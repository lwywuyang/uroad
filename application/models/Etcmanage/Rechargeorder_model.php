<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 充值订单模型
 */
class Rechargeorder_model extends CI_Model{
	/**
	 * 查询充值订单数据
	 */
	function getRechargeOrderData($StartTime,$EndTime,$keyword,$pageOnload){
		$sql = "SELECT m.orderno,m.payno ,m.price ,a.name paytype ,m.ordertime ,m.username ,m.phone ,'' as cardno,m.paytime ,b.name status
				FROM etc_ordercz AS m
				left join lnt_dict a on m.paytype = a.dictcode
				left join lnt_dict b on m.status = b.dictcode
				where 1 = 1";

		$params = array();
		if(!isEmpty($StartTime)){
			$StartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(m.ordertime) >= UNIX_TIMESTAMP(?)';
			array_push($params, $StartTime);
		}
		if(!isEmpty($EndTime)){
			$EndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(m.ordertime) <= UNIX_TIMESTAMP(?)';
			array_push($params, $EndTime);
		}
		if(!isEmpty($keyword)){
			$sql .= " and (m.orderno like concat('%',?,'%') or m.payno like concat('%',?,'%') or m.username like concat('%',?,'%') or m.phone like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
			array_push($params, $keyword);
			array_push($params, $keyword);
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

}