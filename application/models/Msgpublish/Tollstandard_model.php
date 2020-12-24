<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》收费标准控制器TollStandardLogic的数据库模型类
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class Tollstandard_model extends CI_Model{
	/**
	 * @desc   获取收费标准信息
	 * @param  [type]      $id [description]
	 * @return [type]          [description]
	 */
	public function selectTollStandardMsg($id,$newstype){
		$sql = 'select id,title,jpgurl,html from gde_news where id=?';
		$params = array($id);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


	/**
	 * @desc   保存新收费标准数据
	 * @param  array      $data [description]
	 * @return [type]            [description]
	 */
	function updateNewsMsg($id,$title,$html,$jpgurl){
		$updateArr = array(
			'id' => $id,
			'title' => $title,
			'html' => $html,
			'jpgurl' => $jpgurl
		);
		return $res = $this->mysqlhelper->Update('gde_news',$updateArr,'id');
	}

}