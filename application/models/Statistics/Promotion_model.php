<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析 -》推广码统计控制器类对应的模型类
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class Promotion_model extends CI_Model{
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
	 * [selectPromotionStatistics 获取推广码统计数据]
	 * @version 2016-06-23 2.0
	 *          log 2.0 更换sql,使得列出所有推广码
	 */
	public function selectPromotionStatistics($StartTime,$EndTime,$pageOnload){
		$subsql = '';
		if (!isEmpty($StartTime)) {
			$StartTime .= ' 00:00:00';
			$subsql .= ' and UNIX_TIMESTAMP(created) >= UNIX_TIMESTAMP(\''.$StartTime.'\')';
		}
		if (!isEmpty($EndTime)) {
			$EndTime .= ' 23:59:59';
			$subsql .= ' and UNIX_TIMESTAMP(created) <= UNIX_TIMESTAMP(\''.$EndTime.'\')';
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

		$sql = 'select a.promotioncode,ifnull(b.num,0) allnum,ifnull(c.num,0) androidnum,ifnull(d.num,0) iosnum
				from gde_promotionperson a
				left join (
					select count(1) num,promotioncode from gde_promotioninfo where 1=1 '.$subsql.' group by promotioncode
				) b on a.promotioncode = b.promotioncode
				left join (
					select count(1) num,promotioncode from gde_promotioninfo where devicetype = 1 '.$subsql.' group by promotioncode
				) c on a.promotioncode = c.promotioncode
				left join (
					select count(1) num,promotioncode from gde_promotioninfo where devicetype = 2 '.$subsql.' group by promotioncode
				) d on a.promotioncode = d.promotioncode
				order by a.promotioncode';

		$data['data'] = $this->mysqlhelper->QueryPage($sql,array(),$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql,array(),$pageOnload);
		//return $this->mysqlhelper->Query($sql);
		return $data;
	}

}