<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 订单账单金额不相等模型
 */
class Dznotequal_model extends CI_Model{
	/**
	 * 查询订单账单金额不相等数据
	 */
	function getDZNotEqualData($StartTime,$EndTime,$keyword,$pageOnload){
		$sql = "SELECT a.username, a.phone, a.orderno, d.name statusname, a.ordertime, c.name paytypename, a.price, a.payno, a.paytime, de.cardno 
				FROM etc_ordercz a  
				INNER JOIN etc_orderczdetail AS de 
				ON a.id = de.mainid
				INNER JOIN (SELECT payno,payamount FROM lnt_checkcmbpay) AS b on a.payno=b.payno
				LEFT JOIN lnt_dict c ON a.paytype=c.dictcode
				LEFT JOIN lnt_dict d ON a.status=d.dictcode
				WHERE a.`status`>1 AND a.price<>b.payamount";

		$params = array();
		if(!isEmpty($StartTime)){
			$StartTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(a.ordertime) >= UNIX_TIMESTAMP(?)';
			array_push($params, $StartTime);
		}
		if(!isEmpty($EndTime)){
			$EndTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(a.ordertime) <= UNIX_TIMESTAMP(?)';
			array_push($params, $EndTime);
		}
		if(!isEmpty($keyword)){
			$sql .= " and (a.username like concat('%',?,'%') or a.phone like concat('%',?,'%') or a.orderno like concat('%',?,'%'))";
			array_push($params, $keyword);
			array_push($params, $keyword);
			array_push($params, $keyword);
		}

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

}