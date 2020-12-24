<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》多客服监控异常统计控制器MultiServiceLogic的模型类
 * @author hwq
 */
class Servicemonitor_model extends CI_Model{
	public function selectMsgInStatus(){
		/*$zjgsdkf = $this->load->database('zhgskf',true);
		$sql = 'select * from pro_status';
		return $zjgsdkf->query($sql)->result_array();*/

		$sql = 'select * from pro_status';
		return $this->mysqlhelper->Query($sql);
	}

	public function selectMsgInHistory($startTime,$endTime,$statusSel,$pageOnload){
		/*$zjgsdkf = $this->load->database('zhgskf',true);
		$sql = 'select * from pro_statuslog';
		return $zjgsdkf->query($sql)->result_array();*/

		$sql = 'select * from pro_statuslog';
		$sql_count = 'select count(*) allnum from pro_statuslog';
		$params = array();
		if (!isEmpty($startTime)) {
			$startTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(intime) >= UNIX_TIMESTAMP(?)';
			$sql_count .= ' and UNIX_TIMESTAMP(intime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$endTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(intime) <= UNIX_TIMESTAMP(?)';
			$sql_count .= ' and UNIX_TIMESTAMP(intime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$endTime);
		}
		$sql .= ' order by id desc';

		$PageSize = 10;
        $OrderDesc = $pageOnload['OrderDesc'];
        $CurrentPage = $pageOnload['CurrentPage']-1;
        $start = $PageSize*$CurrentPage;
        $pageSql = $sql.$OrderDesc." limit ".$start.",10";    
        $data = array();
        $data['data'] = $this->db->query($pageSql,$params)->result_array();
        $CurrentPage = $pageOnload['CurrentPage'];

        
		/*$params = array();
		if (!isEmpty($startTime)) {
			$startTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(intime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$endTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(intime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$endTime);
		}*/
		//$sql .= 'order by id desc';
		$num = $this->db->query($sql_count,$params)->row()->allnum;
        // $num=$num[0]['s'];
        $data['PagerOnload'] = array(
            'CurrentPage' => intval($CurrentPage),
            'OrderStr' => $OrderDesc,
            'PageSize' => $PageSize,
            'TotalCount' => $num,
            'TotalPage' => ceil($num/$PageSize)
            );
        return $data;
		//return $this->mysqlhelper->QueryParams($sql,$params);
		//$this->mysqlhelper->Query($sql);
	}

}