<?php defined('BASEPATH') or exit('No direct script access allowed');

class EtcQuestion extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('News/Etcquestion_model', 'Etc');
        $this->load->model('Dict_model', 'dict');
        checksession();
    }

    /**
     * 显示 -> 列表页
     */
    public function index()
    {
        $this->load->view('admin/News/EtcQuestionList');
    }

    /**
     * 获取列表数据
     */
    public function getListData()
    {
        $pageOnload = page_onload();
        if ($pageOnload['OrderDesc'] == "") {
            $pageOnload['OrderDesc'] = "";
        }
        $data['startTime'] = $this->input->post('startTime');
        $data['endTime'] = $this->input->post('endTime');
        $data['status'] = $this->input->post('status');
        $data['keyword'] = $this->input->post('keyword');
        $res = $this->Etc->selectListData($data, $pageOnload);
        foreach ($res['data'] as $k => $v) {
            // 按钮
            $res['data'][$k]['operation'] = '<lable class="btn btn-primary btn-xs m-r-10" onclick="edit(\'' . $v['id'] . '\')">查看</lable>';
            // 状态
            if ($v['status'] == 1) {
                $res['data'][$k]['statusName'] = '发布';
                $res['data'][$k]['operation'] .= '<lable class="btn btn-warning btn-xs" onclick="changeStatus(\'' . $v['id'] . '\',0)">取消发布</lable>';
            } else {
                $res['data'][$k]['statusName'] = '登记';
                $res['data'][$k]['operation'] .= '<lable class="btn btn-success btn-xs" onclick="changeStatus(\'' . $v['id'] . '\',1)">发布</lable>';
            }
        }
        ajax_success($res['data'], $res['PagerOrder']);
    }

    /**
     * 改变状态
     */
    public function changeStatus()
    {
        $id = $this->input->post('id');
        $data['status'] = $this->input->post('status');
        $data['operatorid'] = getsessionempid();
        $data['operatorname'] = getsessionempname();
        $data['created'] = date('Y-m-d H:i:s');
        $res = $this->Etc->updateStatus($id, $data);
        if ($res) {
            ajax_success(true, null);
        } else {
            ajax_error("操作失败！");
            return;
        }
    }

    /**
     * 显示 -> 编辑页
     */
    public function edit()
    {
        $data['id'] = $_GET['id'];
        $this->load->view('admin/News/EtcQuestionEdit', $data);
    }

    /**
     * 保存
     * @desc 更新/新增
     */
    public function save()
    {
        $id = $this->input->post('id');
        $data['question'] = $this->input->post('question');
        $data['answer'] = $this->input->post('answer');
        $data['operatorid'] = getsessionempid();
        $data['operatorname'] = getsessionempname();
        $data['created'] = date('Y-m-d H:i:s');
        if ($id != 0) { // 更新
            $res = $this->Etc->updateQuestion($id, $data);
        } else { // 新增
            $res = $this->Etc->insertQuestion($data);
        }
        if ($res) {
            ajax_success(true, null);
        } else {
            ajax_error("操作失败！");
            return;
        }
    }

    /**
     * 获取问题详情
     */
    public function getDetails()
    {
        $id = $this->input->post('id');
        $res = $this->Etc->selectDetails($id);
        ajax_success($res[0], null);
    }

    /**
     * 撤销
     */
    public function repeal()
    {
        $ids = $this->input->post('ids');
        $res = $this->Etc->del($ids);
        if ($res) {
            ajax_success(true, null);
        } else {
            ajax_error("操作失败！");
            return;
        }
    }
}
