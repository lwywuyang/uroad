<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》关键字管理控制器RoadEventLogic的模型类
 *       主要的表 - alipay_autoreply_rule和alipay_autoreply_keyword
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class Keywordmanage_model extends CI_Model{
	/********************公用start********************/
	/**
	 * @desc   '微信管理'->'关键字管理'->'路况'/'关键字回复'->'修改'某条规则信息->读取该规则的详细信息
	 *         调用此方法的Logic方法有
	 *         onLoadRoadKeyword
	 * @return [type]      [description]
	 */
	public function selectDetailMsg($ruleId){
		$sql = 'select rule_id,rule_name,remark from alipay_autoreply_rule where rule_id=?';
		$params = array($ruleId);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		return $data[0];
	}

	/**
	 * @desc   '微信管理'->'关键字管理'->'路况'/'关键字回复->'修改'某条规则信息->读取该规则下的关键字
	 *         调用此方法的Logic方法有
	 *         onLoadRoadKeyword
	 * @param  [type]      $ruleId [description]
	 * @return [type]              [description]
	 */
	public function selectKeywordMsg($ruleId){
		$sql = 'select key_id,keyword from alipay_autoreply_keyword where rule_id=?';
		$params = array($ruleId);
		return $data = $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * @desc   删除关键字
	 * @param  [type]      $deleteArr [description]
	 * @return [type]                 [description]
	 */
	public function deleteKeywordMsg($deleteArr){
		$this->db->trans_begin();
		$sql = 'delete from alipay_autoreply_keyword where key_id=?';
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

	/**
	 * @desc   '微信管理'->关键字管理->路况/关键字回复->修改规则信息->保存
	 * @param  [type]      $ruleId       [description]
	 * @param  [type]      $ruleName     [description]
	 * @param  [type]      $checkedValue [description]
	 * @return [type]                    [description]
	 */
	public function updateMsg($ruleId,$ruleName,$remark){
		$updateArr = array(
			'rule_id' => $ruleId,
			'rule_name' => $ruleName,
			'remark' => $remark,
		);
		return $res = $this->mysqlhelper->Update('alipay_autoreply_rule',$updateArr,'rule_id');
	}
	/********************公用end********************/

	/********************路况start********************/
	public function selectRoadMsg($keyword,$pageOnload){
		$sql = "select a.rule_id,rule_name,group_concat(b.keyword SEPARATOR '—')as keystring
				from alipay_autoreply_rule a
				left join alipay_autoreply_keyword b on a.rule_id=b.rule_id
				where weixintype=1";
		$params = array();
		if (!isEmpty($keyword)) {
			$sql .= " and rule_name like concat('%',?,'%')";
			array_push($params,$keyword);
		}
		$sql .= ' group by rule_id';
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        //$data['sql'] =  $this->db->last_query();
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}

	public function selectAllRoad(){
		$sql = 'select roadoldid,concat(newcode,shortname)as roadName from gde_roadold order by newcode asc';
		return $data = $this->mysqlhelper->Query($sql);
	}


	


	/**
	 * @desc   '微信管理'->关键字管理->路况->新增规则信息->保存
	 * @param  [type]      $ruleId       [description]
	 * @param  [type]      $ruleName     [description]
	 * @param  [type]      $checkedValue [description]
	 * @return [type]                    [description]
	 */
	public function insertRoadRuleMsg($ruleName,$checkedValue){
		$insertArr = array(
			'rule_name' => $ruleName,
			'remark' => $checkedValue,
			'weixintype' => 1
		);
		return $res = $this->mysqlhelper->Insert('alipay_autoreply_rule',$insertArr);
	}


	/**
	 * @desc   '微信管理'->关键字管理->路况->修改规则信息->保存
	 * @param  [type]      $ruleId       [description]
	 * @param  [type]      $ruleName     [description]
	 * @param  [type]      $checkedValue [description]
	 * @return [type]                    [description]
	 */
	/*public function updateRoadRuleMsg($ruleId,$ruleName,$checkedValue){
		$updateArr = array(
			'rule_id' => $ruleId,
			'rule_name' => $ruleName,
			'remark' => $checkedValue,
		);
		return $res = $this->mysqlhelper->Update('alipay_autoreply_rule',$updateArr,'rule_id');
	}*/


	


	/**
	 * @desc   保存对某规则新增的关键字
	 * @param  [type]      $ruleId     [description]
	 * @param  [type]      $newKeyword [description]
	 * @return [type]                  [description]
	 */
	public function insertNewKeyword($ruleId,$newKeyword){
		$insertArr = array(
			'keyword' => $newKeyword,
			'rule_id' => $ruleId
		);
		return $res = $this->mysqlhelper->Insert('alipay_autoreply_keyword',$insertArr);
	}
	/********************路况end********************/

	/********************关键字回复start********************/
	public function selectRemarkMsg($keyword,$pageOnload){
		$sql = "select a.rule_id,rule_name,group_concat(b.keyword SEPARATOR '—')as keystring,remark
				from alipay_autoreply_rule a
				left join alipay_autoreply_keyword b on a.rule_id=b.rule_id
				where weixintype=12";
		$params = array();
		if (!isEmpty($keyword)) {
			$sql .= " and rule_name like concat('%',?,'%')";
			array_push($params,$keyword);
		}
		$sql .= ' group by rule_id';
		$data['data'] = $this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
        //$data['sql'] =  $this->db->last_query();
        $data['pageOnload'] = $this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
        
        return $data;
	}



	/**
	 * @desc   '微信管理'->关键字管理->路况->新增规则信息->保存
	 * @param  [type]      $ruleId       [description]
	 * @param  [type]      $ruleName     [description]
	 * @param  [type]      $checkedValue [description]
	 * @return [type]                    [description]
	 */
	public function insertRemarkMsg($ruleName,$remarkContent){
		$insertArr = array(
			'rule_name' => $ruleName,
			'remark' => $remarkContent,
			'weixintype' => 12
		);
		return $res = $this->mysqlhelper->Insert('alipay_autoreply_rule',$insertArr);
	}


	

	

}