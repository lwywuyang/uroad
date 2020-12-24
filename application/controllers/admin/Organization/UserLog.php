<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserLog extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Organization/Userlog_model', 'UserLog');
    checksession();
  }

  public function index()
  {
    $this->load->view("admin/Organization/UserLogUI/index");
  }


  public function getUserLogList()
  {
    $pageOnload=page_onload();
    $startTime = $this->input->post('startTime');
    $endTime = $this->input->post('endTime');
    $keyword = $this->input->post('keyword');

    if($pageOnload['OrderDesc']=="")
    {
      $pageOnload['OrderDesc']='order by intime desc';
    }
    $data = $this->UserLog->getUserLogListAll($startTime, $endTime, $keyword, $pageOnload);
    ajax_success($data['data'],$data["pageOnload"]);
  }
}
