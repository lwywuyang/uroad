<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Roadpoi_model extends CI_Model{
	public function selectAllRoadPer(){
		$sql = 'select id,name,roadoldids from gde_roadper order by seq';
		return $this->mysqlhelper->Query($sql);
	}

	public function selectRoadInPer($roadoldids){
		$sql = 'select roadoldid,CONCAT(newcode,shortname) shortname 
				from gde_roadold
				where roadoldid in ('.$roadoldids.')
				order by newcode';
		$params = array($roadoldids);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   '收费站维护'->加载顶部高速公路下拉框内容
	 * @return [type]      [description]
	 */
	public function selectAllRoad(){
		$sql = 'select roadoldid,CONCAT(newcode,shortname) shortname ,CONCAT(newcode,shortname) roadName 
				from gde_roadold
				order by newcode';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   '收费站维护'->加载顶部类型下拉框内容
	 * @data   2015-9-17 11:48:32
	 * @return [type]      [description]
	 */
	public function selectAllType(){
		$sql = 'select dictcode,name from gde_dict where codetype=1002';
		return $data = $this->mysqlhelper->Query($sql);
	}

	public function selectAllStatus(){
		$sql = 'select dictcode,name from gde_dict where codetype=1010';
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   打开'收费站维护'页面->搜索所有站点内容
	 *         改变下拉框或搜索框->提交查询->加载条件下站点内容
	 * @data   2015-9-17 15:16:20
	 * @param  [type]      $roadId     [description]
	 * @param  [type]      $type      [description]
	 * @param  [type]      $keyword    [description]
	 * @param  [type]      $pageOnload [description]
	 * @return [type]                  [description]
	 */
	public function selectRoadPOIMsg($roadperSel,$roadId,$type,$keyword,$status,$pageOnload){
		$subsql = '';
		if (!isEmpty($roadperSel)) {
			$sql_per = "select roadoldids from gde_roadper where id=".$roadperSel;
			$dataper = $this->mysqlhelper->Query($sql_per);
			$subsql = ' and b.roadoldid in('.$dataper[0]['roadoldids'].')';
		}
		$sql = "select a.poiid,a.name,a.newstationno,a.miles,a.stationcode,b.shortname,c.name styleName,b.newcode,b.roadoldid,e.stationstatus,
		if(e.stationstatus=1,'可用','不可用') statusname,a.pointtype
				from gde_roadpoi a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_dict c on a.pointtype=c.dictcode
				left join gde_dict d on a.status = d.dictcode
				left join gde_roadpoidetail e on a.poiid=e.poiid
				where pointtype in (select dictcode from gde_dict where codetype=1002) ".$subsql;
		$params = array();

		if (!isEmpty($roadId)) {
			$sql .= ' and a.roadoldid=?';
			array_push($params,$roadId);
		}
		if (!isEmpty($type)) {
			$sql .= ' and a.pointtype=?';
			array_push($params,$type);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and ((a.name like '%".$keyword."%') or (b.shortname like '%".$keyword."%' ) or (a.stationcode like '%".$keyword."%' ))";
			array_push($params,$keyword);
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		if (!isEmpty($status)) {
			$sql .= ' and e.stationstatus=?';
			array_push($params,$status);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	/**
	 * @desc   '加油站维护'->查看站点详情->根据站点id查询站点信息
	 * @data   2015-9-17 15:30:46
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectPoiMsgById($poiid){
		/*$sql = 'select roadoldid,`name`,stationcode,miles,phone,pointtype,city,coor_x,coor_y,nowexitetcnum,nowexitwaynum,nowinetcnum,nowinwaynum,address,remark 
			from gde_roadpoi 
			where poiid='.$poiid;*/
		$sql = 'select *
			from gde_roadpoi 
			where poiid='.$poiid;
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '收费站维护'页面->'查看收费站详细'页面->修改信息并点击确定->更新相应收费站信息
	 * @data   2015-9-17 17:27:07
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
	public function updateRoadPoiMsg($poiid,$name,$typeSel,$stationcode,$roadSel,$phone,$city,$miles,$coor_x,$coor_y,$nowinwaynum,$nowexitwaynum,$nowinetcnum,$nowexitetcnum,$hub,$address,$nextRoadLeft,$nextRoadStraight,$nextRoadRight,$tagAddress,$comeRoad,$neighborRoad,$viewAndCompapny,$status,$direction1,$direction2){
		$update = array(
			'poiid' => $poiid,
			'name' => $name,
			'pointtype' => $typeSel,
			'stationcode' => $stationcode,
			'roadoldid' => $roadSel,
			'phone' => $phone,
			'remark' => $hub,
			'city' => $city,
			'miles' => $miles,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y,
			'nowinwaynum' => $nowinwaynum,
			'nowexitwaynum' => $nowexitwaynum,
			'nowinetcnum' => $nowinetcnum,
			'nowexitetcnum' => $nowexitetcnum,
			'address' => $address,
			'leadleft' => $nextRoadLeft,
			'leadright' => $nextRoadRight,
			'leadcenter' => $nextRoadStraight,
			'signplace' => $tagAddress,
			'avrride' => $comeRoad,
			'nearroad' => $neighborRoad,
			'scenery' => $viewAndCompapny,
			'status' => $status,
			'direction1' => $direction1,
			'direction2' => $direction2
			);
		//$res = $this->mysqlhelper->Update('gde_roadpoi',$update,'poiid');
        $content = '修改收费站数据 '.$name;
        saveLog($content,2010002);
		$res = $this->db->update('gde_roadpoi',$update,array('poiid' => $poiid));
		$num = $this->db->affected_rows();

		if ($num >= 0) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @desc   '路段维护'-》新增路段信息-》插入数据库
	 * @data   datatime
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
	public function insertRoadPoiMsg($name,$typeSel,$stationcode,$roadSel,$phone,$city,$miles,$coor_x,$coor_y,$nowinwaynum,$nowexitwaynum,$nowinetcnum,$nowexitetcnum,$hub,$address,$nextRoadLeft,$nextRoadStraight,$nextRoadRight,$tagAddress,$comeRoad,$neighborRoad,$viewAndCompapny,$status,$direction1,$direction2){
		$insert = array(
			//'poiid' => $poiid,
			'name' => $name,
			'pointtype' => $typeSel,
			'stationcode' => $stationcode,
			'roadoldid' => $roadSel,
			'phone' => $phone,
			'remark' => $hub,
			'city' => $city,
			'miles' => $miles,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y,
			'nowinwaynum' => $nowinwaynum,
			'nowexitwaynum' => $nowexitwaynum,
			'nowinetcnum' => $nowinetcnum,
			'nowexitetcnum' => $nowexitetcnum,
			'address' => $address,
			'leadleft' => $nextRoadLeft,
			'leadright' => $nextRoadRight,
			'leadcenter' => $nextRoadStraight,
			'signplace' => $tagAddress,
			'avrride' => $comeRoad,
			'nearroad' => $neighborRoad,
			'scenery' => $viewAndCompapny,
			'status' => $status,
			'direction1' => $direction1,
			'direction2' => $direction2
			);
        $content = '新增收费站 '.$name;
        saveLog($content,2010002);
		return $res = $this->mysqlhelper->Insert('gde_roadpoi',$insert);
	}


	/**
	 * @desc   '路段维护'-》删除路段信息
	 * @data   datatime
	 * @param  [type]      $deleteValue [description]
	 * @return [type]                   [description]
	 */
	public function deleteRoadPoi($deleteValue,$poiname){
		$deleteArr = explode(',', $deleteValue);
        $content = '删除收费站 '.$poiname;
        saveLog($content,2010002);
		//var_dump($deleteArr);
		$this->db->trans_begin();
		$sql = 'delete from gde_roadpoi where poiid=?';
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
	 * @data   2015-10-8 10:39:41
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectRoadExceptThis($poiid){
		$sql = 'select roadoldid,CONCAT(newcode,shortname) roadName 
				from gde_roadold
				where roadoldid <> (select roadoldid from gde_roadpoi where poiid = ?)
				order by newcode';
		$params = array($poiid);

		$data = $this->mysqlhelper->QueryParams($sql,$params);
		//var_dump($this->db->last_query());exit;
		return $data;
	}

	/**
	 * [selectUeditorDetail 根据poiid查询收费站图文]
	 * @version 2016-04-27 1.0
	 * @param   [string]     $poiid [收费站id]
	 * @return  [array]            [图文数组]
	 */
	public function selectUeditorDetail($poiid){
		$sql = 'select poiid,html,picurl from gde_roadpoidetail where poiid=?';
		$params = array($poiid);

		$data = $this->mysqlhelper->QueryParams($sql,$params);

		return (isset($data[0]))?$data[0]:array();
	}

	/**
	 * [updateUeditorDetail 存在poiid为主键的数据则更新,否则插入新数据]
	 * @version 2016-04-27 1.0
	 * @return  [type]            [description]
	 */
	public function updateUeditorDetail($poiid,$html,$jpgurl,$poiname){

		$sql = 'INSERT INTO gde_roadpoidetail (poiid,html,picurl) VALUES (?,?,?) ON DUPLICATE KEY UPDATE html=?,picurl=?';
		$params = array($poiid,$html,$jpgurl,$html,$jpgurl);
        $content = '新增收费站图文 '.$poiname;
        saveLog($content,2010002);
		$this->db->query($sql,$params);
		$affectedRow = $this->db->affected_rows();

		if ($affectedRow <= 0)
			return '修改图文数据失败!';
		else
			return true;
	}

	//导出excel
	public function selectRoadPOIMsgToReport($roadperSel,$roadId,$type,$keyword,$status){
		$subsql = '';
		if (!isEmpty($roadperSel)) {
			$sql_per = "select roadoldids from gde_roadper where id=".$roadperSel;
			$dataper = $this->mysqlhelper->Query($sql_per);
			$subsql = ' and b.roadoldid in('.$dataper[0]['roadoldids'].')';
		}

		$sql = 'select a.name,a.miles,b.newcode,b.shortname
				from gde_roadpoi a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				where pointtype in (select dictcode from gde_dict where codetype=1002) '.$subsql;
		$params = array();
		if (!isEmpty($roadId)) {
			$sql .= ' and a.roadoldid=?';
			array_push($params,$roadId);
		}
		if (!isEmpty($type)) {
			$sql .= ' and a.pointtype=?';
			array_push($params,$type);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and ((a.name like '%".$keyword."%') or (b.shortname like '%".$keyword."%' ) or (a.stationcode like '%".$keyword."%' ))";
			array_push($params,$keyword);
			array_push($params,$keyword);
			array_push($params,$keyword);
		}
		if (!isEmpty($status)) {
			$sql .= ' and a.status=?';
			array_push($params,$status);
		}

		return $this->mysqlhelper->QueryParams($sql,$params);
	}

	public function updateStationStatus($poiid,$status,$poiname){
		$updateArr = array(
			'poiid' => $poiid,
			'stationstatus' => $status
			);
        $statusname = '不可用';
        if($status==1){
            $statusname = '可用';
        }
        $content = '修改收费站 '.$poiname.' 状态为'.$statusname;
        $res = $this->mysqlhelper->Update('gde_roadpoidetail',$updateArr,'poiid');
        saveLog($content,2010002);
		return $res;
	}
}