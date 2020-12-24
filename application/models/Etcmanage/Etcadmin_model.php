<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Etcadmin_model extends CI_Model{
	/**
	 * @desc   '收费站维护'->加载顶部高速公路下拉框内容
	 * @return [type]      [description]
	 */
	public function selectAllRoad(){
		$sql = 'select roadoldid,CONCAT(newcode,shortname,roadname) shortname 
				from gde_roadold
				order by newcode';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   '收费站维护'->加载顶部类型下拉框内容
	 * @return [type]      [description]
	 */
	public function selectAllType(){
		$sql = 'select dictcode,name from gde_dict where codetype=1002';
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   打开'收费站维护'页面->搜索所有站点内容
	 *         改变下拉框或搜索框->提交查询->加载条件下站点内容
	 * @param  [type]      $roadId     [description]
	 * @param  [type]      $type      [description]
	 * @param  [type]      $keyword    [description]
	 * @param  [type]      $pageOnload [description]
	 * @return [type]                  [description]
	 */
	public function selectETCAdminMsg($roadId,$type,$keyword,$pageOnload){
		$sql = "SELECT
					*, CASE
				WHEN vehtype = 1 THEN
					'一型客'
				WHEN vehtype = 2 THEN
					'二型客'
				WHEN vehtype = 3 THEN
					'三型客'
				WHEN vehtype = 4 THEN
					'四型客'
				WHEN vehtype in(11,12,13,14,15)  THEN
					'计重车'
				END vehtype1,
				CASE
				WHEN platecolor = 0 THEN
					'蓝色'
				WHEN platecolor = 1 THEN
					'黄色'
				WHEN platecolor = 2 THEN
					'黑色'
				WHEN platecolor = 3 THEN
					'白色'
				END platecolor1
				FROM
					base_ytk WHERE TRUE ";
		$params = array();
		if (!isEmpty($roadId)) {
			$sql .= ' and vehtype=?';
			array_push($params,$roadId);
		}
		if (!isEmpty($type)) {
			if($type==15){
				$sql .= ' and platecolor in (11,12,13,14,15)';
			}else{
				$sql .= ' and platecolor=?';
				array_push($params,$type);
			}
		}
		if (!isEmpty($keyword)) {
			$sql .= " and ((username like '%".$keyword."%') or (relateman like '%".$keyword."%' ) or (numberplate like '%".$keyword."%' ))";
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		$totalDate = 40000;
		$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload,$totalDate);
		return $data;
	}


	/**
	 * @desc   '加油站维护'->查看站点详情->根据站点id查询站点信息
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectETCAdminMsgById($id){
		$sql = 'SELECT * FROM base_ytk where id='.$id;

		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '收费站维护'页面->'查看收费站详细'页面->修改信息并点击确定->更新相应收费站信息
	 * @param  [type]      $poiid         [description]
	 * @param  [type]      $name          [description]
	 * @param  [type]      $typeSel       [description]
	 * @param  [type]      $stationcode   [description]
	 * @param  [type]      $roadSel       [description]
	 * @param  [type]      $phone         [description]
	 * @param  [type]      $city          [description]
	 * @param  [type]      $miles         [description]
	 * @param  [type]      $coor_x        [description]
	 * @param  [type]      $coor_y        [description]
	 * @param  [type]      $nowinwaynum   [description]
	 * @param  [type]      $nowexitwaynum [description]
	 * @param  [type]      $nowinetcnum   [description]
	 * @param  [type]      $nowexitetcnum [description]
	 * @param  [type]      $address       [description]
	 * @return [boolean]   标记是否更新成功
	 */
	public function updateRoadPoiMsg($id,$content){
//		$update = array(
//			'poiid' => $poiid,
//			'name' => $name,
//			'pointtype' => $typeSel,
//			'stationcode' => $stationcode,
//			'roadoldid' => $roadSel,
//			'phone' => $phone,
//			'remark' => $hub,
//			'city' => $city,
//			'miles' => $miles,
//			'coor_x' => $coor_x,
//			'coor_y' => $coor_y,
//			'nowinwaynum' => $nowinwaynum,
//			'nowexitwaynum' => $nowexitwaynum,
//			'nowinetcnum' => $nowinetcnum,
//			'nowexitetcnum' => $nowexitetcnum,
//			'address' => $address,
//			);
		//$res = $this->mysqlhelper->Update('gde_roadpoi',$update,'poiid');
		$this->db->update('base_ytk',$content,array('id' => $id));
		$num = $this->db->affected_rows();
		//var_dump($num);exit;
		if ($num >= 0) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @desc   '路段维护'-》新增路段信息-》插入数据库
	 * @param  [type]      $name          [description]
	 * @param  [type]      $typeSel       [description]
	 * @param  [type]      $stationcode   [description]
	 * @param  [type]      $roadSel       [description]
	 * @param  [type]      $phone         [description]
	 * @param  [type]      $city          [description]
	 * @param  [type]      $miles         [description]
	 * @param  [type]      $coor_x        [description]
	 * @param  [type]      $coor_y        [description]
	 * @param  [type]      $nowinwaynum   [description]
	 * @param  [type]      $nowexitwaynum [description]
	 * @param  [type]      $nowinetcnum   [description]
	 * @param  [type]      $nowexitetcnum [description]
	 * @param  [type]      $address       [description]
	 * @return [type]                     [description]
	 */
	public function insertRoadPoiMsg($content){
//		$insert = array(
//			//'poiid' => $poiid,
//			'name' => $name,
//			'pointtype' => $typeSel,
//			'stationcode' => $stationcode,
//			'roadoldid' => $roadSel,
//			'phone' => $phone,
//			'remark' => $hub,
//			'city' => $city,
//			'miles' => $miles,
//			'coor_x' => $coor_x,
//			'coor_y' => $coor_y,
//			'nowinwaynum' => $nowinwaynum,
//			'nowexitwaynum' => $nowexitwaynum,
//			'nowinetcnum' => $nowinetcnum,
//			'nowexitetcnum' => $nowexitetcnum,
//			'address' => $address,
//			);
		return $res = $this->mysqlhelper->Insert('base_ytk',$content);
	}


	/**
	 * @desc   '路段维护'-》删除路段信息
	 * @param  [type]      $deleteValue [description]
	 * @return [type]                   [description]
	 */
	public function deleteRoadPoi($deleteValue){
		$deleteArr = explode(',', $deleteValue);
		//var_dump($deleteArr);
		$this->db->trans_begin();
		$sql = 'delete from base_ytk where id=?';
		foreach ($deleteArr as $value) {
			//var_dump($value);
			$params = array($value);
			$result = $this->mysqlhelper->ExecuteSqlParams($sql,$params);
			//var_dump($this->db->last_query());
			//var_dump($result);
			unset($params);

			if (!$result) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}


	/**
	 * @desc   '路段维护'->查看->获取关联路段信息，用于多选下拉框
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectRoadExceptThis($poiid){
		$sql = 'select roadoldid,CONCAT(newcode,shortname,roadname) shortname 
				from gde_roadold
				where roadoldid <> (select roadoldid from gde_roadpoi where poiid = ?)
				order by newcode';
		$params = array($poiid);

		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//var_dump($this->db->last_query());exit;
		return $data;
	}

}