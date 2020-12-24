<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc	基础数据-数据版本更新控制器类
 * 	      	涉及到的表-gde_filever
 * 	@author hwq
 * 	@date 	2015-9-29
 */
class RobotApiLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Robot/Robot_model', 'robot');
		
	}


    /*关注问题页面*/ 
    public function followpage()
    {
          $keyword=isset($_GET['keyword'])?$_GET['keyword']:'';
          $this->load->view('etc/followpage',array('keyword'=>$keyword));   
    }
    



	public function Newsdetail(){
        $questionid=$this->input->get("questionid");
        $data=$this->robot->selectOneProblem($questionid);
        if(empty($data)){
            exit;
        }
        $data[0]["answer"] = str_replace(array("\r\n", "\r", "\n"), "<br>", $data[0]["answer"]);

        $this->load->view('admin/Robot/detail',$data[0]);
        
    }
    public function Newsdetailtest(){
        $questionid=$this->input->get("questionid");
        $data=$this->robot->selectOneProblem($questionid);

        $data[0]["answer"] = str_replace(array("\r\n", "\r", "\n"), "<br>", $data[0]["answer"]);

        $this->load->view('admin/Robot/detailtest',$data[0]);
        
    }

}
