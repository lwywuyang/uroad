<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc 数据版本更新控制器对应的模型
 */
class Robot_model extends CI_Model{
	

	public function insertProblem($title,$questiontype,$answer,$keyword,$creator,$created,$modifyer,$modified){
		date_default_timezone_set('PRC');
		
		$insertArr = array(
			'title' => $title,
			'questiontype' => $questiontype,
			'answer' => $answer,
			'creator' => $creator,
			'created' => $created,
			'modifyer' => $modifyer,
			'modified' => $modified
			);

		$res = $this->mysqlhelper->Insert('robot_question',$insertArr);


		if ($res){
		 	$questionid = $this->db->insert_id();
		 	if(!empty($keyword)){
		 		$keyword = explode('|',$keyword);
		 		for($i=0;$i<count($keyword);$i++)
		 		{
		 			$insertArr2 = array(
					'keyword' => $keyword[$i],
					'questionid' => $questionid,
					'creator' => $creator,
					'created' => $created
					);
				$res2 = $this->mysqlhelper->Insert('robot_keyword',$insertArr2);
		 		}
			 	

				
			}
			if($res){
				return true;
			}else{
				return '添加新问题失败!';
			}

		} else {
		 	return '添加新问题失败!';
		 }
	}

	public function updateProblem($questionid,$title,$questiontype,$answer,$keyword,$modifyer,$modified){
		date_default_timezone_set('PRC');

		$updateArr = array(
			'questionid' => $questionid,
			'title' => $title,
			'questiontype' => $questiontype,
			'answer' => $answer,
			'modifyer' => $modifyer,
			'modified' => $modified
			);
		$this->db->update('robot_question',$updateArr,array('questionid' => $questionid));
		$num = $this->db->affected_rows();

		if ($num > 0 ){
		 	
		 	$sql = "delete from robot_keyword where questionid = ".$questionid;
		 	$res2 = $this->db->query($sql);
		 	if($res2 > 0)
		 	{
		 		if(!empty($keyword)){
			 		$keyword = explode('|',$keyword);
			 		for($i=0;$i<count($keyword);$i++)
			 		{
			 			$insertArr2 = array(
						'keyword' => $keyword[$i],
						'questionid' => $questionid,
						'creator' => $modifyer,
						'created' => $modified
						);
					$res3 = $this->mysqlhelper->Insert('robot_keyword',$insertArr2);
			 		}

			 		if($res3){
						return true;
					}else{
						return '修改信息失败!';
					}		
				}

		 	}else{
		 		return '修改信息失败!';
		 	}

		} else {
		 	return '修改信息失败!';
		}
	}

	public function selectProblem($status,$keyword,$pageOnload){

		// $sql = 'SET GLOBAL group_concat_max_len = 10000000000'; 
		// $this->db->query($sql);

		//$sql = "select questionid,title,questiontype,answer,(select group_concat(keyword separator '|') k from robot_keyword WHERE questionid=a.questionid) keyword from robot_question a ;";
		$sql = "select questionid,title,questiontype,answer,(
		select group_concat(keyword SEPARATOR '|') k from robot_keyword	 WHERE questionid = a.questionid ) keyword from	robot_question a  where 1 = 1 ";
		
		$params = array();

		if (!isEmpty($status)) {
			$sql .= " and questiontype =?";
			array_push($params, $status);
	
		}

		if (!isEmpty($keyword)) {
			$sql .= " and (
	 (
		SELECT
			group_concat(keyword SEPARATOR '|') k
		FROM
			robot_keyword
		WHERE
			questionid = a.questionid
	) LIKE concat('%', ?, '%')
	OR title LIKE concat('%', ?, '%')
	OR answer LIKE concat('%', ?, '%')
)";
			array_push($params, $keyword);
			array_push($params, $keyword);
			array_push($params, $keyword);
		}


		$sql .= "group by questionid";
		
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pageOnload);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pageOnload);
		return $data;
	}

	public function selectOneProblem($questionid){
		$sql = "select questionid,title,questiontype,answer,(select group_concat(keyword separator '|') k from robot_keyword WHERE questionid=a.questionid) keyword from robot_question a where a.questionid = ".$questionid;

		
		$data=$this->mysqlhelper->query($sql);
		return $data;
	}

	public function deleteProblem($questionid){
		$sql = "delete from robot_keyword where questionid = ".$questionid;
		$res = $this->db->query($sql);
		if($res > 0){
			$sql = "delete from robot_question where questionid = ".$questionid;
			$res = $this->db->query($sql);
			if($res > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function getRoadoldData(){
		$sql="select roadoldid,shortname
			FROM
				gde_roadold
			where 
				1=1";
	
		$data=$this->mysqlhelper->query($sql);

		
		return $data;

	}


}
