<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Road_model extends CI_Model{
	public function selectRoadPer(){
		$sql = 'select id,name from gde_roadper order by seq';
		return $this->mysqlhelper->Query($sql);
	}
	/**
	 * 路段维护
	 * 分页查找数据
	 */
	function getRoadoldData($roadper,$keyword,$pageOnload){
		$subsql = '';
		if (!isEmpty($roadper)) {
			$sql_per = "select roadoldids from gde_roadper where id=".$roadper;
			$dataper = $this->mysqlhelper->Query($sql_per);
			$subsql = ' and roadoldid in('.$dataper[0]['roadoldids'].')';
		}
		$sql="select roadoldid,roadoldcode,shortname,startend,status,iconfile,newcode,remark,coor_x,coor_y,cctvnumber,relanteroadoldids,seq,areano,direction1,direction2,direction3,direction4,startcity,endcity,startnodename,endnodename,picurl
			FROM
				gde_roadold
			where 
				1=1 ".$subsql;
		$params = array();
		
		if (!isEmpty($keyword)) {
			$sql .= " and shortname like '%".$keyword."%'";
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//var_dump($this->db->last_query());exit;
		$data['sql'] = $this->db->last_query();
		$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

	/**
	 * @desc   '路段维护'页面->查看路段详情->查询相关路段信息并返回
	 * @param  [type]      $roadoldid  [description]
	 * @return [array]     具体路段信息
	 */
	function getRoadoldDataById($roadoldid){
		$sql="SELECT
				roadoldid,
				shortname,
				startend,
				newcode,
				seq,
				direction1,
				direction2,
				startcity,
				endcity,
				picurl
			FROM
				gde_roadold
			where 
				1=1";

		if (!isEmpty($roadoldid)) {
			$sql .= " and roadoldid = ".$roadoldid;
		}
		$data['data']=$this->mysqlhelper->Query($sql);
		//$data['sql'] = $this->db->last_query();
		//$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	/**
	 * @desc   '路段维护'页面->新增->'新增路段'页面->插入新路段信息
	 * @return [boolean]     标记是否插入成功
	 */
	public function insertNewRoad($roadName,$newCode,$directionUp,$directionDown,$startCity,$endCity,$location,$imgurl,$longitude,$latitude,$seq){
		date_default_timezone_set('PRC');
		$insertArr = array(
			'shortname' => $roadName,
			'newcode' => $newCode,
			'direction1' => $directionUp,
			'direction2' => $directionDown,
			'startcity' => $startCity,
			'endcity' => $endCity,
			'seq' => $seq,
			'startend' => $location,
			'picurl' => $imgurl,
			'coor_x' => $longitude,
			'coor_y' => $latitude
			);

		$res = $this->mysqlhelper->Insert('gde_roadold',$insertArr);

		if ($res)
			return true;
		else
			return '插入新路段信息失败!';
	}


	/**
	 * @desc   '路段维护'->查看路段详情->'路段详情'页面->更新路段信息
	 * @return [boolean]     标记是否更新路段信息成功
	 */
	public function updateRoadMsg($id,$roadName,$newCode,$directionUp,$directionDown,$startCity,$endCity,$location,$imgurl,$longitude,$latitude,$seq){
		date_default_timezone_set('PRC');
		$updateArr = array(
			'roadoldid' => $id,
			'shortname' => $roadName,
			'newcode' => $newCode,
			'direction1' => $directionUp,
			'direction2' => $directionDown,
			'startcity' => $startCity,
			'endcity' => $endCity,
			'seq' => $seq,
			'startend' => $location,
			'picurl' => $imgurl,
			'coor_x' => $longitude,
			'coor_y' => $latitude
			);

		///return $res = $this->mysqlhelper->Update('gde_roadold',$updateArr,'roadoldid');
		$this->db->update('gde_roadold',$updateArr,array('roadoldid'=>$id));
		$num = $this->db->affected_rows();
		
		if ($num >= 0)
			return true;
		else
			return '更新路段信息失败!';
	}


	/**
	 * @desc   '路段维护'->删除路段->根据删除路段id字符串deleteValue删除数据库内容
	 * @param  [type]      $deleteValue [description]
	 * @return [type]                   [description]
	 */
	public function deleteRoad($deleteValue){
		$deleteArr = explode(',', $deleteValue);

		$this->db->trans_begin();
		$sql = 'delete from gde_roadold where roadoldid=?';
		foreach ($deleteArr as $value) {

			$params = array($value);
			$result = $this->mysqlhelper->ExecuteSqlParams($sql,$params);

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
	 * @desc   '路段维护'->查看沿途站->根据roadoldid查询gde_roadpoi
	 * @param  [type]      $roadoldid  [description]
	 * @param  [type]      $pageOnload [description]
	 * @return [type]                  [description]
	 */
	public function selectPoiMsg($roadoldid){
		//旧版,trafficsplitcode由后台php生成
		/*$sql = 'select a.stationid,b.name,b.miles,a.seq,a.trafficsplitcode,a.stationcode
				from gde_roadlinestation a 
				left join gde_roadpoi b on a.stationid=b.poiid
				left join gde_roadold c on a.roadlineid=c.roadoldid
				where a.roadlineid=? and a.direction=1';*/

		//version2.0,将正反trafficsplitcode展示到列表,前端控制trafficsplitcode
		$sql = 'select a.*,b.reversecode,c.name,c.miles from(
					select stationid,seq,trafficsplitcode positivecode,stationcode from gde_roadlinestation where roadlineid=? and direction=1
				) a left join (
					select stationid,trafficsplitcode reversecode from gde_roadlinestation where roadlineid=? and direction=2
				) b on a.stationid = b.stationid 
				left join gde_roadpoi c on a.stationid = c.poiid';
		$params = array($roadoldid,$roadoldid);

		$data['data']=$this->mysqlhelper->QueryParams($sql,$params);
		//$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

	/**
	 * @desc   '路段维护'->查看沿途站->'路段沿途站详细'页面预加载路段下所有站点信息
	 * @param  [type]      $roadoldid [description]
	 * @return [type]                 [description]
	 */
	public function selectAllPoiMsg($roadoldid){
		//$sql = 'select poiid,name,miles from gde_roadpoi where pointtype in (1002001,1002002,1002003) roadoldid='.$roadoldid.' order by miles';
		//路段沿途站只针对三类收费站
		$sql = 'select poiid,name,miles,stationcode from gde_roadpoi where pointtype in (1002001,1002002,1002003) and roadoldid=? order by miles asc';
		$params = array($roadoldid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data;
	}


	/**
	 * @desc   '路段维护'-》查看沿途站-》保存-》更新数据库中的路段沿途站信息
	 * @param  [type]      $roadoldid [description]
	 * @param  [type]      $dataArr   [description]
	 * @return [type]                 [description]
	 */
	public function updateAllLineStation($roadoldid,$dataArr){
		
		$sqlDelete = 'delete from gde_roadlinestation where roadlineid='.$roadoldid;
		//$paramsDelete = array($roadoldid);
		$this->db->query($sqlDelete);
		$resDelete = $this->db->affected_rows();
		if ($resDelete < 0) {//影响行数大于等于0就是正确删除
			//$this->db->trans_rollback();
			return false;
		}
		if (!isset($dataArr)) {//如果为null
			return true;
		}
		//开启事务
		$this->db->trans_begin();
		
		//正方向
		foreach ($dataArr as $k => $v) {
			//version1.0
			/*if ( $k != (count($dataArr)-1)) {
				$insertArr1 = array(
					'roadlineid' => $roadoldid,
					'direction' => 1,
					'seq' => $v[0],
					'stationcode' => $v[4],
					'trafficsplitcode' => $v[4].$dataArr[$k+1][4],
					'stationid' => $v[3]
				);

				$this->db->insert('gde_roadlinestation',$insertArr1);
				$resInsert1 = $this->db->affected_rows();
				if ($resInsert1 <= 0) {//影响行数大于0才是正确插入
					$this->db->trans_rollback();
					return false;
				}

			}else {//if ($k == (count($dataArr)-1)) 
				$insertArr3 = array(
					'roadlineid' => $roadoldid,
					'direction' => 1,
					'seq' => $v[0],
					'stationcode' => $v[4],
					//'trafficsplitcode' => $v[3].$dataArr[$k+1][3],
					'stationid' => $v[3]
				);

				$this->db->insert('gde_roadlinestation',$insertArr3);
				$resInsert3 = $this->db->affected_rows();
				if ($resInsert3 <= 0) {//影响行数大于0才是正确插入
					$this->db->trans_rollback();
					return false;
				}
			}*/
			//version2.0
			$insertArr1 = array(
				'roadlineid' => $roadoldid,
				'direction' => 1,
				'seq' => $v[0],
				'stationcode' => $v[4],
				'trafficsplitcode' => $v[5],
				'stationid' => $v[3]
			);

			$this->db->insert('gde_roadlinestation',$insertArr1);
			$resInsert1 = $this->db->affected_rows();
			if ($resInsert1 <= 0) {//影响行数大于0才是正确插入
				$this->db->trans_rollback();
				return false;
			}
		}

		//反方向
		$count = count($dataArr);
		for ($j=$count-1; $j >= 0; $j--) { 
			/*if ( $j != 0) {
				$insertArr2 = array(
					'roadlineid' => $roadoldid,
					'direction' => 2,
					'seq' => $dataArr[$count-$j-1][0],
					'stationcode' => $dataArr[$j][4],
					'trafficsplitcode' => $dataArr[$j][4].$dataArr[$j-1][4],
					'stationid' => $dataArr[$j][3]
				);
				$this->db->insert('gde_roadlinestation',$insertArr2);
				$resInsert2 = $this->db->affected_rows();
				if ($resInsert2 <= 0) {//影响行数大于0才是正确插入
					$this->db->trans_rollback();
					return false;
				}
			}else{
				$insertArr4 = array(
					'roadlineid' => $roadoldid,
					'direction' => 2,
					'seq' => $dataArr[$count-$j-1][0],
					'stationcode' => $dataArr[$j][4],
					//'trafficsplitcode' => $v[3].$dataArr[$k+1][3],
					'stationid' => $dataArr[$j][3]
				);
				$this->db->insert('gde_roadlinestation',$insertArr4);
				$resInsert4 = $this->db->affected_rows();
				if ($resInsert4 <= 0) {//影响行数大于0才是正确插入
					$this->db->trans_rollback();
					return false;
				}
			}*/
			//version2.0
			$insertArr2 = array(
				'roadlineid' => $roadoldid,
				'direction' => 2,
				'seq' => $dataArr[$count-$j-1][0],
				'stationcode' => $dataArr[$j][4],
				'trafficsplitcode' => $dataArr[$j][6],
				'stationid' => $dataArr[$j][3]
			);
			$this->db->insert('gde_roadlinestation',$insertArr2);
			$resInsert2 = $this->db->affected_rows();
			if ($resInsert2 <= 0) {//影响行数大于0才是正确插入
				$this->db->trans_rollback();
				return false;
			}
		}


		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

	/**
	 * @desc   '路段维护'->点击'更新'->更新路段数据
	 */
	public function updateAllMsg(){
		$sql = 'call proc_model();';
		$this->db->query($sql);
		return $this->db->affected_rows()>0?true:false;
	}

	public function selectRoadMsgToReport($search){
		$sql="select newcode,shortname,direction1,direction2
			FROM
				gde_roadold
			where 
				1=1";
		$params = array();
		if (!isEmpty($keyword)) {
			$sql .= " and shortname like '%".$keyword."%'";
		}
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

}