<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》服务热线控制器RoadEventLogic的模型类
 *       主要的表 - gde_phone
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class Hotline_model extends CI_Model{
	public function selectHotLineMsg($pageOnload){
		$sql = 'select id,phonenumber,remark,seq
				from gde_phone';
		$params = array();
		
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        //$data['sql'] =  $this->db->last_query();
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}


	public function selectHotLineDetail($id){
		$sql = 'select id,phonenumber,remark,seq,istop from gde_phone where id=?';
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}

	public function insertNewMsg($remark,$phone,$seq,$topSel){
		$insertArr = array(
			'remark' => $remark,
			'phonenumber' => $phone,
			'seq' => $seq,
			'istop' => $topSel,
		);
		return $res = $this->mysqlhelper->Insert('gde_phone',$insertArr);
	}

	public function UpdateMsg($id,$remark,$phone,$seq,$topSel){
		$updateArr = array(
			'id' => $id,
			'remark' => $remark,
			'phonenumber' => $phone,
			'seq' => $seq,
			'istop' => $topSel,
		);
		return $res = $this->mysqlhelper->Update('gde_phone',$updateArr,'id');
	}

	public function deleteHotLineMsg($deleteArr){
		$this->db->trans_begin();
		$sql = 'delete from gde_phone where id=?';
		foreach ($deleteArr as $k => $v) {
			$params = array($v);
			$res = $this->mysqlhelper->ExecuteSqlParams($sql,$params);

			if (!$res) {
				$this->db->trans_rollback();
				return false;
			}
		}
		$this->db->trans_commit();
		$this->db->trans_complete();
		return true;
	}

}