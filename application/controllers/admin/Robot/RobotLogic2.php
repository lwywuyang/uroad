<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc	基础数据-数据版本更新控制器类
 * 	      	涉及到的表-gde_filever
 * 	@author hwq
 * 	@date 	2015-9-29
 */
class RobotLogic2 extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Robot/Robot_model', 'robot');
		
	}

	public function indexPage(){
		
		$this->load->view('admin/Robot/MenuList');
	}

	public function addProblemList(){
		$questionid = $this->input->get('questionid');
		$data['questionid'] = $questionid;
		$data['lk'] = $this->robot->getRoadoldData();
		if($questionid == 0){
			$this->load->view('admin/Robot/Problem',$data);
		}else{
			$data['data'] = $this->robot->selectOneProblem($questionid);
			$this->load->view('admin/Robot/Problem',$data);
		}
		
	}

	public function problemPage(){

		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by questionid desc';
		}

		$data = $this->robot->selectProblem($pageOnload);
		foreach($data['data'] as $k=>&$v){
				if($v['questiontype'] == 1){
					$data['data'][$k]['questiontype'] = '文本';
				}else if($v['questiontype'] == 2){
					$data['data'][$k]['questiontype'] = '链接';
				}else{
					$data['data'][$k]['questiontype'] = '路况';
					
					$data['data'][$k]['answer'] = $data['data'][$k]['f'];
				}
				
				$data['data'][$k]['operate'] = '<lable class="btn btn-success m-15" onclick="detail('.$v['questionid'].')">修改</lable>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info m-15" onclick="read('.$v['questionid'].')">预览</lable>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-danger m-15" onclick="dodelete('.$v['questionid'].')">删除</lable>';
			
		}
		ajax_success($data['data'],$data['PagerOrder']);
	}

	public function addProblem(){
		$questionid = $this->input->post('questionid');
		$title = $this->input->post('title');
		$questiontype = $this->input->post('questiontype');
		$answer = $this->input->post('answer');
		$keyword = $this->input->post('keyword');

		$keyword = rtrim($keyword ,'|');
		// 修改人
		$modifyer = getsessionempid();
		// 修改时间
		$modified = date('Y-m-d H:i:s');
			

		
		if($questionid == 0){
			// 创建人
			$creator = getsessionempid();
			// 创建时间
			$created = date('Y-m-d H:i:s');
			
			// 添加问题
			$res = $this->robot->insertProblem($title,$questiontype,$answer,$keyword,$creator,$created,$modifyer,$modified);		
			if($res){
				ajax_success(true,null);
			}else{
				ajax_error('数据库操作失败!');
			}
		}else{		
			
			// 修改问题
			$res = $this->robot->updateProblem($questionid,$title,$questiontype,$answer,$keyword,$modifyer,$modified);
			if($res){
				ajax_success(true,null);
			}else{
				ajax_error('数据库操作失败!');
			}
		}
	}

	public function deleteProblem(){
		$questionid = $this->input->post('questionid');
		$res = $this->robot->deleteProblem($questionid);
			if($res){
				ajax_success(true,null);
			}else{
				ajax_error('删除失败!');
			}
	}

	// 查询路况 
	public function selectRoad(){
	
		
		$data=$this->robot->getRoadoldData();

		ajax_success($data['data'],null);
	}


	public function Newsdetail(){
        $questionid=$this->input->get("questionid");
        $data=$this->robot->selectOneProblem($questionid);

        if(!empty($data)){
        	$data[0]["answer"] = str_replace(array("\r\n", "\r", "\n"), "<br>", $data[0]["answer"]);

        	$this->load->view('admin/Robot/detail',$data[0]);
        }
        
        // if($data['status']=='1'){
        //     $this->load->view('News/newsYHdetail',$data['data']);   
        // }else{
        //     $this->load->view('News/newsYHdetail');   
        // }

    }

}
