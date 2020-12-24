<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Service_model extends CI_Model{
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
	 * @desc   '服务区维护'->加载顶部高速公路下拉框内容
	 * @return [type]      [description]
	 */
	public function selectAllRoad(){
		$sql = 'select roadoldid,concat(newcode,shortname) shortname from gde_roadold order by newcode';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   '服务区维护'->加载顶部类型下拉框内容
	 * @return [type]      [description]
	 */
	public function selectAllType(){
		$sql = 'select dictcode,name,remark from gde_dict where codetype=1003';
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   打开'服务区维护'页面->搜索所有服务区内容
	 *         改变下拉框或搜索框->提交查询->加载条件下服务区内容
	 * @return [type]                  [description]
	 */
	public function selectServiceMsg($roadperSel,$roadId,$type,$keyword,$pageOnload){
		//查询当前账号管辖下的服务区
		$serviceidsSql = 'select serviceids from gde_serviceemployee where userid=?';
		$userid = getsessionempid();
		$serviceidsParams = array($userid);
		$serviceid = $this->mysqlhelper->QueryParams($serviceidsSql,$serviceidsParams);
		if(empty($serviceid)){
			$serviceid[0]['serviceids'] = 0;
		}
		//根据管理处查询
		$subsql = '';
		if (!isEmpty($roadperSel)) {
			$sql_per = "select roadoldids from gde_roadper where id=".$roadperSel;
			$dataper = $this->mysqlhelper->Query($sql_per);
			$subsql = ' and b.roadoldid in('.$dataper[0]['roadoldids'].')';
		}

		$sql = "select a.poiid,a.name,a.newstationno,a.miles,a.stationcode,b.shortname,c.name styleName,d.remark styleDetail,b.newcode,a.coor_x,a.coor_y,e.id detailid,e.servicestatus,if(e.servicestatus=1,'可用','不可用') as statusname
				from gde_roadpoi a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_dict c on a.pointtype=c.dictcode
				left join gde_dict d on a.pointtype=d.dictcode
				left join gde_servicedetail e on a.poiid=e.id
				where pointtype in (
					select dictcode from gde_dict where codetype=1003
				) and poiid in (".$serviceid[0]['serviceids'].")".$subsql;

		
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
			$sql .= " and ((a.name like '%".$keyword."%') or (b.shortname like '%".$keyword."%' ))";
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		//var_dump($this->db->last_query());exit;
		//$data['sql'] = $this->db->last_query();
		$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}


	/**
	 * @desc   '服务区维护'->查看基础数据操作->查询该服务区的基础数据
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectBaseData($poiid){
		$sql = 'select stationcode,name,direction,coor_x,coor_y,miles,address,roadoldid,pointtype
				from gde_roadpoi
				where poiid=?';
		$params = array($poiid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

    public function checkCode($stationcode,$poiid) {
        $sql = 'SELECT count(1) num FROM gde_roadpoi WHERE stationcode=?';
        $params = [];
        array_push($params,$stationcode);
        if(!empty($poiid)){
            $sql.=' and poiid!=?';
            array_push($params,$poiid);
        }
        $num = $this->db->query($sql,$params)->row()->num;

        return empty($num)?true:false;
	}


	/**
	 * @desc   '服务区维护'->查看服务区详细->'服务区详细信息'页面->修改并点击确定->保存新服务区信息
	 * @return [type]                 [description]
	 */
	public function updateServiceMsg($poiid,$name,$typeSel,$roadSel,$direction,$coor_x,$coor_y,$miles,$address,$phone,$city,$stationcode){
		$updateArr = array(
			'poiid' => $poiid,
			'name' => $name,
			'pointtype' => $typeSel,
			'roadoldid' => $roadSel,
			'direction' => $direction,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y,
			'miles' => $miles,
			'address' => $address,
			'phone' => $phone,
			'stationcode' => $stationcode,
			'city' => $city
			);
		//$res = $this->mysqlhelper->Update('gde_roadpoi',$updateArr,'poiid');
		//return $res;
        $content = '修改服务区基础数据 '.$name.' id:'.$poiid;
        saveLog($content,2010001);
		$this->db->update('gde_roadpoi',$updateArr,array('poiid' => $poiid));
		$num = $this->db->affected_rows();
		if ($num >= 0)
			return true;
		else
			return false;
	}

	/**
	 * @desc   '服务区维护'->新增服务区操作->'新增服务区信息录入页面'->保存新服务区信息
	 * @return [boolean]   标记是否插入新数据成功
	 */
	public function insertServiceMsg($name,$typeSel,$roadSel,$direction,$coor_x,$coor_y,$miles,$address,$phone,$city,$stationcode){
		$insertArr = array(
			'name' => $name,
			'pointtype' => $typeSel,
			'roadoldid' => $roadSel,
			'direction' => $direction,
			'coor_x' => $coor_x,
			'coor_y' => $coor_y,
			'miles' => $miles,
			'address' => $address,
			'phone' => $phone,
			'stationcode' => $stationcode,
			'city' => $city
			);

		$content = '新增服务区 '.$name;
        saveLog($content,2010001);

		$res = $this->mysqlhelper->Insert('gde_roadpoi',$insertArr);
		return $res;
	}


	/**
	 * @desc   '服务区维护'->选择服务区并点击删除->删除相应服务区信息
	 * @param  [type]      $deleteValue [description]
	 * @return [type]                   [description]
	 */
	public function deleteService($deleteValue,$poiname){
		$deleteArr = explode(',', $deleteValue);
        $content = '删除服务区 '.$poiname;
        saveLog($content,2010001);

		$this->db->trans_begin();
		$sql = 'delete from gde_roadpoi where poiid=?';
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
	 * @desc   '服务区维护'页面->点击服务区详细信息->查询该服务区详细信息
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectServiceDetailMsg($poiid){
		$sql = 'select a.*,b.*
				from gde_roadpoi a
				left join gde_servicedetail b on a.poiid=b.id
				where a.poiid=?';
		$params = array($poiid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}

	/**
	 * @desc   '服务区维护'->查看详细->查询服务区图集
	 * @param  [type]      $poiid [description]
	 * @return [type]             [description]
	 */
	public function selectGallery($poiid,$galleryType,$pageOnload){
		$sql = 'select a.id,a.poiid,a.name,a.pic,a.price,a.type,b.name poiName
				from gde_servicephoto a 
				left join gde_roadpoi b on a.poiid=b.poiid 
				where a.poiid=?';
		$params = array($poiid);
		if (!isEmpty($galleryType)) {
			$sql .= ' and a.type=?';
			array_push($params,$galleryType);
		}
		$data['data'] = $this->mysqlhelper->QueryParams($sql,$params);
		$data['pageOnload']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		//$data['sql'] = $this->db->last_query();
		//var_dump($data);exit;
		return $data;
	}


	/**
	 * @desc   '服务区维护'->详细信息->查看操作->查询查看的图片的详细信息
	 * @param  [type]      $photoid [description]
	 * @return [type]               [description]
	 */
	public function selectPhotoMsg($photoid){
		$sql = 'select * from gde_servicephoto where id='.$photoid;
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * @desc   '服务区维护'->详细信息->新增操作->获取该路段下所有服务区信息用于下拉框
	 * @param  [type]      $roadoldid [description]
	 * @return [type]                 [description]
	 */
	public function selectAllService($roadoldid){
		$sql = 'select poiid,name
				from gde_roadpoi
				where pointtype in (select dictcode from gde_dict where codetype=1003) and roadoldid='.$roadoldid;
		$data = $this->mysqlhelper->Query($sql);
		return $data;
	}


	/**
	 * @desc   '服务区维护'页面->'服务区详细信息'页面->'新增服务区图片'页面->插入保存新图片信息
	 * @return [boolean]   标记是否插入成功
	 */
	public function insertNewServicePic($name,$poiid,$typeSel,$price,$imgurl){
		$insertArr = array(
			'name' => $name,
			'poiid' => $poiid,
			'type' => $typeSel,
			'price' => $price,
			'pic' => $imgurl,
			'seq' => 1
			);

		return $res = $this->mysqlhelper->Insert('gde_servicephoto',$insertArr);
	}


	/**
	 * @desc   '服务区维护'页面->'服务区详细信息'页面->'修改服务区图片'页面->保存新图片信息
	 * @return [type]                  [description]
	 */
	public function updateNewServicePic($photoid,$name,$poiid,$typeSel,$price,$imgurl){
		$updateArr = array(
			'id' => $photoid,
			'name' => $name,
			'poiid' => $poiid,
			'type' => $typeSel,
			'price' => $price,
			'pic' => $imgurl
			);
		return $res = $this->mysqlhelper->Update('gde_servicephoto',$updateArr,'id');
	}


	public function deletePhotoMsg($poiid,$deleteArr){
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_servicephoto where id='.$value;
			$res = $this->db->query($sql);
			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
		
	}


	/**
	 * @desc   '服务区详细'->服务区状态下拉内容
	 * @return [type]      [description]
	 */
	public function selectServiceStatus(){
		$sql = 'select dictcode,name from gde_dict where codetype=1020';
		//$sql = 'select dictcode , name from gde_dict where codetype=1020 and status=1';
		return $data = $this->mysqlhelper->Query($sql);
	}

	//查询油类表格的数据
	public function selectGasMsg($poiid){
		$sql = 'select * from gde_servicegas where serviceid=?';
		$params = array($poiid);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}


	//查询某条油类的数据
	public function selectGasMsgById($id){
		$sql = 'select * from gde_servicegas where id=?';
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}

	public function insertGasMsg($id,$poiid,$gasname,$price,$status){
		$insertArr = array(
			'serviceid' => $poiid,
			'gasname' => $gasname,
			'price' => $price,
			'status' => $status
		);
 		$res = $this->mysqlhelper->Insert('gde_servicegas',$insertArr);
 		if ($res)
 			return true;
 		else
 			return '保存新增油类失败!';
	}

	public function updateGasMsg($id,$poiid,$gasname,$price,$status){
		$updateArr = array(
			'id' => $id,
			'serviceid' => $poiid,
			'gasname' => $gasname,
			'price' => $price,
			'status' => $status
		);
 		$res = $this->mysqlhelper->Update('gde_servicegas',$updateArr,'id');
 		if ($res)
 			return true;
 		else
 			return '保存修改后的油类失败!';
	}

	public function delGasMsg($deleteValue){
		$deleteArr = explode(',', $deleteValue);
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_servicegas where id='.$value;
			$res = $this->db->query($sql);
			if (!$res) {
				$this->db->trans_rollback();
				return '删除服务区油类信息失败!';
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

	//获取服务区特色表格数据
	public function selectFeatureMsg($poiid){
		$sql = 'select id,title,jpgurl,html,poiid,status,seq from gde_news where poiid='.$poiid;
		return $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   保存新增的服务区特色信息
	 * @return [type]               [description]
	 */
	public function insertNewFeatureMsg($eventid,$poiid,$imgurl,$title,$seq,$html){
		$insertArr = array(
			'id' => $eventid,
			'poiid' => $poiid,
			'jpgurl' => $imgurl,
			'title' => $title,
			'html' => $html,
			'seq' => $seq,
			'status' => 1012004,
			'newstype' => 1011025,
		);
 		return  $res = $this->mysqlhelper->Insert('gde_news',$insertArr);
	}

	public function updateNewFeatureMsg($eventid,$poiid,$imgurl,$title,$seq,$html){
		$updateArr = array(
			'id' => $eventid,
			'poiid' => $poiid,
			'jpgurl' => $imgurl,
			'title' => $title,
			'html' => $html,
			'seq' => $seq
		);
 		return  $res = $this->mysqlhelper->Update('gde_news',$updateArr,'id');
	}

	/**
	 * @desc   查看服务区特色时,查询该条数据
	 */
	public function selectFeatureMsgById($eventid){
		$sql = 'select * from gde_news where id="'.$eventid.'"';
		$data = $this->mysqlhelper->Query($sql);
		return $data[0];
	}

	/**
	 * @desc   获取服务区图集信息
	 */
	public function selectImagesMsg($poiid){
		$sql = 'select * from gde_servicephoto where poiid=?';
		$params = array($poiid);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data;
	}

	public function selectImagesMsgById($id){
		$sql = 'select * from gde_servicephoto where id=?';
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}

	public function insertImagesMsg($id,$poiid,$imgurl,$title){
		$insertArr = array(
			'poiid' => $poiid,
			'pic' => $imgurl,
			'name' => $title
			);
		$res = $this->mysqlhelper->Insert('gde_servicephoto',$insertArr);
		if ($res)
			return true;
		else
			return '保存新增服务区图集信息失败!';
	}

	public function updateImagesMsg($id,$poiid,$imgurl,$title){
		$updateArr = array(
			'id' => $id,
			'poiid' => $poiid,
			'pic' => $imgurl,
			'name' => $title
			);
		$res = $this->mysqlhelper->Update('gde_servicephoto',$updateArr,'id');
		if ($res)
			return true;
		else
			return '保存更新的服务区图集信息失败!';
	}

	public function delImagesMsg($deleteValue){
		$deleteArr = explode(',', $deleteValue);
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_servicephoto where id='.$value;
			$res = $this->db->query($sql);
			if (!$res) {
				$this->db->trans_rollback();
				return '删除服务区图集信息失败!';
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}


	public function updateNewsTypeToCancel($id){
		$updateArr = array(
			'id' => $id,
			'status' => 1012005
		);
		return $res = $this->mysqlhelper->Update('gde_news',$updateArr,'id');
	}


	public function deleteFeatureMsgs($deleteValue){
		/*$this->db->trans_begin();
		$deleteArr = explode(',', $deleteValue);
		$sql = 'delete from gde_news where id=?';
		foreach ($deleteArr as $key => $value) {
			$params = array($value);
			$res = $this->mysqlhelper->ExecuteSqlParams($sql,$params);
			unset($params);
			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;*/

		$deleteArr = explode(',', $deleteValue);
		$this->db->trans_begin();
		foreach ($deleteArr as $key => $value) {
			$sql = 'delete from gde_news where id='.$value;
			$res = $this->db->query($sql);
			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}


	/**
	 * @desc   服务区详细信息->保存详细信息
	 * @return [type]                        [description]
	 */
	public function deplicateServiceDetailMsg($poiid,$imgUrl,$hasShop,$hasSpecial,$hasFood,$hasGas,$hasParking,$hasRepair,$hasToilet,$hasHotel,$serviceStatusSel,$hasSpeciallist,$serviceSummary,$shopHtml,$specialHtml,$foodHtml,$gasHtml,$parkingHtml,$repairHtml,$toiletHtml,$hotelHtml,$level,$haswifi,$hasrescue,$haschargingpile,$hasqizhan,$chargingpilenum,$parkingspacenum,$poiname){
		$sql = 'insert into gde_servicedetail (`id`,`pic`,`hasshop`,`hasspecial`,`hasfood`,`hasgasstation`,`hasparking`,`hasrepair`,`hastoilet`,`hashotel`,`servicestatus`,`hasspeciallist`,`content`,`shoptext`,`specialtext`,`foodtext`,`gastext`,`parkingtext`,`repairtext`,`toilettext`,`hoteltext`,`level`,haswifi,hasrescue,haschargingpile,hasqizhan,chargingpilenum,parkingspacenum) 
				values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
				ON DUPLICATE KEY UPDATE `pic`=?,`hasshop`=?,`hasspecial`=?,`hasfood`=?,`hasgasstation`=?,`hasparking`=?,`hasrepair`=?,`hastoilet`=?,`hashotel`=?,`servicestatus`=?,`hasspeciallist`=?,`content`=?,`shoptext`=?,`specialtext`=?,`foodtext`=?,`gastext`=?,`parkingtext`=?,`repairtext`=?,`toilettext`=?,`hoteltext`=?,`level`=?,haswifi=?,hasrescue=?,haschargingpile=?,hasqizhan=?,chargingpilenum=?,parkingspacenum=?';
				//values ('.$poiid.','.$imgUrl.','.$hasShop.','.$hasSpecial.','.$hasFood.','.$hasGas.','.$hasParking.','.$hasRepair.','.$hasToilet.','.$hasHotel.','.$serviceStatusSel.','.$hasSpeciallist.','.$serviceSummary.','.$shopHtml.','.$specialHtml.','.$foodHtml.','.$gasHtml.','.$parkingHtml.','.$repairHtml.','.$toiletHtml.','.$hotelHtml.') 
		$params = array($poiid,$imgUrl,$hasShop,$hasSpecial,$hasFood,$hasGas,$hasParking,$hasRepair,$hasToilet,$hasHotel,$serviceStatusSel,$hasSpeciallist,$serviceSummary,$shopHtml,$specialHtml,$foodHtml,$gasHtml,$parkingHtml,$repairHtml,$toiletHtml,$hotelHtml,$level,$haswifi,$hasrescue,$haschargingpile,$hasqizhan,$chargingpilenum,$parkingspacenum,$imgUrl,$hasShop,$hasSpecial,$hasFood,$hasGas,$hasParking,$hasRepair,$hasToilet,$hasHotel,$serviceStatusSel,$hasSpeciallist,$serviceSummary,$shopHtml,$specialHtml,$foodHtml,$gasHtml,$parkingHtml,$repairHtml,$toiletHtml,$hotelHtml,$level,$haswifi,$hasrescue,$haschargingpile,$hasqizhan,$chargingpilenum,$parkingspacenum);

        $content = '修改服务区详细信息 '.$poiname;
        saveLog($content,2010001);
		//var_dump($params);exit;
		$this->db->query($sql,$params);
		//$affectedRows = $this->db->affected_rows();
		//主键已存在执行插入操作,成功返回1
		//主键未存在执行更新操作,成功返回2
		return $this->db->affected_rows()>=0?true:false;
	}

	public function selectServiceMsgToReport($roadperSel,$roadId,$type,$keyword){
		$serviceidsSql = 'select serviceids from gde_serviceemployee where userid=?';
		$userid = getsessionempid();
		$serviceidsParams = array($userid);
		$serviceid = $this->mysqlhelper->QueryParams($serviceidsSql,$serviceidsParams);

		//根据管理处查询
		$subsql = '';
		if (!isEmpty($roadperSel)) {
			$sql_per = "select roadoldids from gde_roadper where id=".$roadperSel;
			$dataper = $this->mysqlhelper->Query($sql_per);
			$subsql = ' and b.roadoldid in('.$dataper[0]['roadoldids'].')';
		}

		$sql = 'select a.name,a.miles,b.newcode,b.shortname,a.phone,c.pic,c.content
				from gde_roadpoi a
				left join gde_roadold b on a.roadoldid=b.roadoldid
				left join gde_servicedetail c on a.poiid = c.id
				where pointtype in (
					select dictcode from gde_dict where codetype=1003
				) and poiid in ('.$serviceid[0]['serviceids'].')'.$subsql;

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
			$sql .= " and ((a.name like '%".$keyword."%') or (b.shortname like '%".$keyword."%' ))";
		}

		return $this->mysqlhelper->QueryParams($sql,$params);
	}

	public function updateServiceStatus($detailid,$status,$poiname){
        $statusname = '不可用';
        if($status==1){
            $statusname = '可用';
        }
        $content = '修改服务区 '.$poiname.' 状态为'.$statusname;
        saveLog($content,2010001);
		$updateArr = array(
			'id' => $detailid,
			'servicestatus' => $status
			);

		return $this->mysqlhelper->Update('gde_servicedetail',$updateArr,'id');
	}
}
