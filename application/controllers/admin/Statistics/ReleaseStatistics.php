<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》微信菜单点击量控制器类
 * 	     主要涉及到的表 -
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class ReleaseStatistics extends CI_Controller {
    /**
     * @desc 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('Statistics/Release_model', 'promotion');
        checksession();
        //$this->load->helper('network');
    }


    /**
     * @desc   打开'微信菜单点击量'页面
     */
    public function index(){
        $data['type'] = $this->promotion->getDict(2010);
        $this->load->view('admin/Statistics/ReleaseList',$data);
    }

    /**
     * @desc   查询微信菜单点击量总览数据
     * @return [type]      [description]
     */
    public function onLoadPopularizeStatistics(){
        $pageOnload = page_onload();
        // 判断排序是否存在
        if($pageOnload['OrderDesc'] == ""){
            $pageOnload['OrderDesc'] = ' order by intime desc';
        }
        $StartTime = $this->input->post('StartTime');
        $EndTime = $this->input->post('EndTime');
        $keyword = $this->input->post('keyword');
        $type = $this->input->post('subtypeSel');

        $data = $this->promotion->selectPopularizeStatistics($keyword,$StartTime,$EndTime,$type,$pageOnload);

        ajax_success($data['data'],$data['pageOnload']);
    }

}