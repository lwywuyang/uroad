<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析 -》推广码统计控制器类对应的模型类
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class Popularize_model extends CI_Model{
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
	public function selectPopularizeStatistics($keyword,$StartTime,$EndTime,$pageOnload){
		$subsql = '';
		if (!isEmpty($StartTime)) {
			$StartTime .= ' 00:00:00';
			$subsql .= ' and UNIX_TIMESTAMP(created) >= UNIX_TIMESTAMP(\''.$StartTime.'\')';
		}
		if (!isEmpty($EndTime)) {
			$EndTime .= ' 23:59:59';
			$subsql .= ' and UNIX_TIMESTAMP(created) <= UNIX_TIMESTAMP(\''.$EndTime.'\')';
		}
		$params = array();
		if (!isEmpty($keyword)) {
			$subsql .= " and (recfrom like concat('%',?,'%') or recto like concat('%',?,'%'))";
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		//查询所有推广码统计
		/*$sql = "select promotioncode,sum(if(tag='all',usenum,0)) as allnum,sum(if(tag='android',usenum,0)) as androidnum,sum(if(tag='ios',usenum,0)) as iosnum
				from(
					select count(1) usenum,a.promotioncode,'all' as tag
					from gde_promotioninfo a
					left join gde_promotionperson b on a.promotioncode = b.promotioncode
					where 1=1 ".$subsql." group by a.promotioncode
					union all
					select count(1) usenum,a.promotioncode,'android' as tag
					from gde_promotioninfo a
					left join gde_promotionperson b on a.promotioncode = b.promotioncode
					where a.devicetype=1 ".$subsql." group by a.promotioncode
					union all
					select count(1) usenum,a.promotioncode,'ios' as tag
					from gde_promotioninfo a
					left join gde_promotionperson b on a.promotioncode = b.promotioncode
					where a.devicetype=2 ".$subsql." group by a.promotioncode
				) a
				group by promotioncode";*/

		$sql = 'select * from gde_regrecommend where 1=1 '.$subsql;

		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,$params,$pageOnload);
		//return $this->mysqlhelper->Query($sql);
		return $data;
	}

}