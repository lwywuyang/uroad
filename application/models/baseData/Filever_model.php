<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc 数据版本更新控制器对应的模型
 */
class Filever_model extends CI_Model{
	/**
	 * @desc   '数据版本更新'->查询页面信息
	 * @data   2015-9-29 15:36:54
	 * @param  [type]      $search     [description]
	 * @param  [type]      $pageOnload [description]
	 * @return [type]                  [description]
	 */
	public function selectFileVerMsg($search,$pageOnload){
		$sql = 'select a.fileid,a.name,filename,filetype,verno,updatetime,a.remark,b.name dataType
				from gde_filever a
				left join gde_dict b on a.filetype = b.dictcode
				where 1=1';
		$params = array();
		if (!isEmpty($search)) {
			$sql .= " and (a.name like '%".$search."%') or (filename like '%".$search."%')";
		}
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}



	/**
	 * @desc   '数据版本更新'页面->点击更新->获取所要更新的数据的内容
	 * @data   2015-9-30 10:59:03
	 * @param  [type]      $fileid [description]
	 * @return [type]              [description]
	 */
	public function selectFileVerMsgById($fileid){
		$sql = 'select fileid,verno,remark,isforce from gde_filever where fileid=?';
		$params = array($fileid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data;
	}


	public function updateFileVer($fileid,$verno,$remark,$isforce){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'fileid' => $fileid,
			'verno' => $verno,
			'remark' => $remark,
			'isforce' => $isforce,
			'updatetime' => date('Y-m-d H:i:s')
			);

		return $res = $this->mysqlhelper->Update('gde_filever',$updateArr,'fileid');
	}


}