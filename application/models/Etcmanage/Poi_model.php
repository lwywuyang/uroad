<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Poi_model extends CI_Model{
	/**
	 * 分页查找数据$key,$Status,$Poitype,$txtstarttime,$txtendtime,$pageOnload
	 */
	function getPoiData($key,$city,$businesstypeid,$businessstatusid,$pagerOrder){
		$sql="select a.*,b.city cityname,b.zone zonename,c.name typename,d.name statusname 
				FROM etc_poi a 
				left JOIN etc_poizone b ON a.zone = b.id 
				left JOIN gde_dict c ON c.dictcode = a.businesstypeid 
				left JOIN gde_dict d ON d.dictcode = a.businessstatusid 
				WHERE 1 = 1";
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and (a.title like concat('%',?,'%') or a.address like concat('%',?,'%') or a.phone like concat('%',?,'%') or a.id like concat('%',?,'%'))";
			array_push($params, $key);
			array_push($params, $key);
			array_push($params, $key);
			array_push($params, $key);
		}	
		if(!isEmpty($city)){
			$sql.=" and b.pid = ?";
			array_push($params, $city);
		}
		if(!isEmpty($businesstypeid)){
			$sql.=" and a.businesstypeid = ?";
			array_push($params, $businesstypeid);
		}
		if(!isEmpty($businessstatusid)){
			$sql.=" and a.businessstatusid >= ?";
			array_push($params, $businessstatusid);
		}

		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);

		return $data;

	}
	
	/**
	 * 按照id查找数据
	 */
	function checkPoiData($id){
		$sql="select * from etc_poi where id = ?";
		$params=array($id);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);	
	 	return $data;
	}
	
	/**
	 * 修改
	 */	
	public function save($data)
	{
		return $this->mysqlhelper->SaveTrans("etc_poi",$data,'id');
	}
	/**
	 * 删除
	 */
	public function delPoi($id){
		//删除顶级
		 $sql="delete from etc_poi where id in (".$id.")";
		 $isSuccess=$this->mysqlhelper->ExecuteSql($sql);
		 return $isSuccess;		
	}


	/**
	 * @desc   查询所有城市
	 * @return [array]      [城市数组]
	 */
	public function selectAllCity(){
		$sql = 'select pid id,city from etc_poizone GROUP BY pid';
		return $data = $this->mysqlhelper->Query($sql);
	}

	/**
	 * @desc   根据城市id查找区
	 * @param  [string]      $id [城市id]
	 * @return [array]          [某城市下的区域数组]
	 */
	public function selectZone($id){
		$sql = 'select id,zone from etc_poizone where pid='.$id;
		return $data = $this->mysqlhelper->Query($sql);
	}

	public function selectStatus(){
		$sql = 'select dictcode,name from gde_dict where codetype=2005';
		return $data = $this->mysqlhelper->Query($sql);
	}


	/**
	 * 按照fieldname查找
	 */
	/*public function checkdict($fieldname){
		$sql="select * from gde_dict where filedname = ?";
		$params=array($fieldname);
		$data=$this->mysqlhelper->QueryParams($sql,$params);
		return $data;
	}*/

	public function save2($Poidata,$newid){
		if ($newid == '0') {
			return '网点编号不能为0!';
		}
		if ($Poidata['id'] == '0') {
			$sql = 'insert into etc_poi (id,title,address,longitude,latitude,city,zone,businesstypeid,businessstatusid,phone,businesstime,remark)values(?,?,?,?,?,?,?,?,?,?,?,?)';
			$params = array($newid,$Poidata['title'],$Poidata['address'],$Poidata['longitude'],$Poidata['latitude'],$Poidata['city'],$Poidata['zone'],$Poidata['businesstypeid'],$Poidata['businessstatusid'],$Poidata['phone'],$Poidata['businesstime'],$Poidata['remark']);
			$this->db->query($sql,$params);
			if ($this->db->affected_rows() <= 0) {
				return '新增失败!';
			}
		}else{
			if ($newid != $Poidata['id']) {
				$sql_id = 'select count(1) num from etc_poi where id='.$newid;
				$num = $this->db->query($sql_id)->result_array();
				if ($num[0]['num'] > 0) {
					return '网点编号已存在!';
				}
			}
			

			$sql = 'update etc_poi set id=?,title=?,address=?,longitude=?,latitude=?,city=?,zone=?,businesstypeid=?,businessstatusid=?,phone=?,businesstime=?,remark=? where id=?';
			$params = array($newid,$Poidata['title'],$Poidata['address'],$Poidata['longitude'],$Poidata['latitude'],$Poidata['city'],$Poidata['zone'],$Poidata['businesstypeid'],$Poidata['businessstatusid'],$Poidata['phone'],$Poidata['businesstime'],$Poidata['remark'],$Poidata['id']);
			$this->db->query($sql,$params);
			if ($this->db->affected_rows() < 0) {
				return '更新失败!';
			}
		}
		
		return true;
	}
}