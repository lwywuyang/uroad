<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析 -》推广码统计控制器类对应的模型类
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class Release_model extends CI_Model{
	//构造函数
	public function __construct(){
		parent::__construct();
		//$dbDefault = $this->load->database('default',true);
	}

	/**
	 * @desc
	 * @return [type]      [description]
	 */
	/**
	 * [selectPopularizeStatistics 获取推广码统计数据]
	 * @version 2016-06-23 2.0
	 *          log 2.0 更换sql,使得列出所有推广码
	 */
	public function selectPopularizeStatistics($keyword,$StartTime,$EndTime,$type,$pageOnload){
		$subsql = '';
        $params = array();
		if(!isEmpty($type)){
            $subsql.= ' and a.type=?';
            array_push($params,$type);
		}

		if (!isEmpty($StartTime)) {
			$StartTime .= ' 00:00:00';
			$subsql .= ' and UNIX_TIMESTAMP(a.intime) >= UNIX_TIMESTAMP(\''.$StartTime.'\')';
		}
		if (!isEmpty($EndTime)) {
			$EndTime .= ' 23:59:59';
			$subsql .= ' and UNIX_TIMESTAMP(a.intime) <= UNIX_TIMESTAMP(\''.$EndTime.'\')';
		}

		if (!isEmpty($keyword)) {
			$subsql .= " and (a.content like concat('%',?,'%') or a.empname like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}

		$sql = 'select a.empname,a.intime,a.content,b.name from sys_adminlog a join gde_dict b on a.type=b.dictcode where  b.codetype="2010" '.$subsql;

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		//return $this->mysqlhelper->Query($sql);
		return $data;
	}


    public function getDict($type) {
        $sql = 'SELECT * FROM gde_dict WHERE codetype=?';
        $data = $this->db->query($sql,[$type])->result_array();
        return $data;
	}

}