<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('order')) {
	function getstatusname($status,$ordertype)
	{
			
		if($ordertype==1){

			if($status=='0'){
				$statusname='取消 ';
			}else if($status=='1'){
				$statusname='待支付';
			}
			else if($status=='2'){
				$statusname='已支付';
			}
			return $statusname;
		}

	}

	function getdetailstatusname($status,$ordertype)
	{
			
		if($ordertype==1){

			if($status=='0'){
				$statusname='已取消 ';
			}else if($status=='1'){
				$statusname='待支付';
			}else if($status=='2'){
				$statusname='已支付';
			}else if($status=='3'){
				$statusname='已受理';
			}else if($status=='4'){
				$statusname='已发货';
			}else if($status=='5'){
				$statusname='已签收';
			}else if($status=='6'){
				$statusname='商品退回';
			}else if($status=='7'){
				$statusname='申请退款';
			}else if($status=='8'){
				$statusname='退款中';
			}else if($status=='9'){
				$statusname='已退款';
			}else if($status=='10'){
				$statusname='订单完成';
			}else if($status=='11'){
				$statusname='订单失败';
			}

			return $statusname;
		}

	}


}

/**
 * 判断主订单是否已经完成
 */

if (!function_exists('setorderstatus')) {


	
	function setorderstatus($orderid) { 
	//完成 
	$finisharr=array(0,5,9,10,11);
	//未完成 
	$notfinisharr=array(1,2,3,4,6,7,8);
	$CI =& get_instance();
	$sql='select status from ushop_orderdetail where ordermainid=?';
	$params=array(); array_push($params, $orderid);
	$data=$CI->mysqlhelper->QueryParams($sql, $params);  $flag=1;
	for($i=0;$i<count($data);$i++){
	if(in_array($data[$i]['status'],$notfinisharr)){ //只要有一个存在未完成里面 $flag=0; }
	}
		
			// 更新主表状态
			//主表关闭订单时间
			$sql='update ushop_orders set orderstatus=? where id=?';
			$CI->db->query($sql,array($flag,$orderid));


	}
}
}
/**
 * 发送
 */
if (!function_exists('sendordermsg')) {

	/**
	 * 发货完成之后发送
	 */
	
	function sendproductmsg($orderid) { 
		$CI =& get_instance();
		// 拿到订单终端
		$data=getorderdevice($orderid);
		//调用接口
		// 微信支付
		if($data['orderdevice']==2){
			$data=sendporductdata($orderid);
			return sendproductmsgweixin($orderid,$data);
		}
		


	}
	/**
	 * 退款完成
	 */
	function refundorder($orderid,$price,$remark) { 
		$CI =& get_instance();
		// 拿到订单终端
		$data=getorderdevice($orderid);
		//调用接口
		// 微信支付
		if($data['orderdevice']==2){
			$data=sendporductdata($orderid);
			return refundorderweixin($orderid,$data,$price,$remark);
		}


	}

	/**
	 * 订单完成
	 */
	function finishorder($orderid) { 
		$CI =& get_instance();
		// n拿到订单终端
	
		$data=getorderdevice($orderid);
		//调用接口
		$CI->load->helper('network');
		$url='';
		$content=array(
			);

		$data=network_post($url, $content);
		$data = json_decode($data, TRUE);
		// log_message('info',print_r($data, 1));
		if($data['status']=='OK'){
			 
			// $CI->load->view('json', array('jsonArray' => $data['data']));

		}else{
			$errorMsg = $data['msg'];
			// $CI->load->view('jsonerror', array('errorMsg' => $errorMsg));
			//echo json_encode($data);
		}

	}



	/**
	 * 微信支付
	 */
	function sendproductmsgweixin($orderid,$data){
		$CI =& get_instance();
		$CI->load->helper('network');
		$url='http://test.u-road.com/UShop/SendMessage/sendGoods';
		$content=array(
			"openid"=>$data['openid'],
			"memberid"=>$data['memberid'],
			"orderid"=>$orderid,
			"first"=>"亲，宝贝已经启程了，好想快点来到你身边",
			"remark"=>"如有问题请致电客服电话或直接在微信留言，小U将第一时间为您服务！",
			"orderno"=>$data['orderno'],
			"expressno"=>$data['expressno'],
			"expressname"=>$data['expressname']
			);
		$data=network_post($url, $content);
		$data = json_decode($data, TRUE);
		log_message('info',print_r($data, 1));
		if($data['status']=='OK'){
			return true;

		}else{
			return false;

		}
	}

	/**
	 * 微信退款提醒 
	 */
	function refundorderweixin($orderid,$data,$price,$remark){
		$CI =& get_instance();
		$CI->load->helper('network');
		$url='http://test.u-road.com/UShop/SendMessage/sendRefundMessage';
		$content=array(
			"openid"=>$data['openid'],
			"memberid"=>$data['memberid'],
			"orderid"=>$orderid,
			"first"=>"您好，您的商品已退款，微信会在7个工作日内到账。",
			"remark"=>"如有问题请致电客服电话或直接在微信留言，小U将第一时间为您服务！",
			"reason"=>$remark,
			"refund"=>$price
			);
		$data=network_post($url, $content);
		$data = json_decode($data, TRUE);
		log_message('info',print_r($data, 1));
		if($data['status']=='OK'){
			return true;

		}else{
			return false;

		}
	}

	/**
	 * 拿到终端
	 */
	function getorderdevice($orderid){
		$CI =& get_instance();
		$sql='SELECT
				a.*
			FROM
				ushop_orders a
			WHERE
				a.id = ?';
		$params=array(); 
		array_push($params, $orderid);
		$data=$CI->mysqlhelper->GetRecordBySql($sql, $params);  
	
		return $data;
	} 
	/**
	 * 拿到发货数据
	 */
	function sendporductdata($orderid){
		$CI =& get_instance();
		$sql='SELECT
				a.*,
			 b.memberid,
				b.wxcode openid,
			c.expressno,
			c.expressid,
			d.expressname
			FROM
				ushop_orders a
			LEFT JOIN ushop_wx b ON a.userid = b.memberid
			left join ushop_invoice c on a.id =c.orderid
			left join ushop_express d on d.id=c.expressid
			WHERE
				a.id = ?';
		$params=array(); 
		array_push($params, $orderid);
		$data=$CI->mysqlhelper->GetRecordBySql($sql, $params);  
		
		return $data;
	}
	
}