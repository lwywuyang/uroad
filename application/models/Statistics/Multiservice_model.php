<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》多客服接入数控制器MultiServiceLogic的模型类
 * @author hwq
 */
class Multiservice_model extends CI_Model{

	public function selectMsgByDate(){
		$zjgsdkf = $this->load->database('zhgskf',true);
		$sql = 'select count(*) count,DATE(created) date 
				from t_zjgs_workercustomer
				group by Date(created) order by Date(created) desc';
		return $zjgsdkf->query($sql)->result_array();
	}

	public function selectMsgByService(){
		$zjgsdkf = $this->load->database('zhgskf',true);
		$sql = 'select count(t_zjgs_workercustomer.workerid) count,t_zjgs_workers.workername worker
				from t_zjgs_workercustomer
				join t_zjgs_workers on t_zjgs_workercustomer.workerid=t_zjgs_workers.workerid
				group by t_zjgs_workercustomer.workerid order by count(t_zjgs_workercustomer.workerid) desc';
		return $zjgsdkf->query($sql)->result_array();
	}

}