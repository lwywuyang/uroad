<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class EtcSubscribeTransact extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Etcmanage/Etcsubscribetransact_model', 'Etc');
        $this->load->model('Dict_model', 'dict');
        checksession();
    }

    /**
     * 显示 -> 列表页
     */
    public function index()
    {
        $this->load->view('admin/ETCManage/EtcSubscribeTransactList');
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
        $data['bankno'] = $this->input->post('bankno');
        $data['keyword'] = $this->input->post('keyword');
        $res = $this->Etc->selectListData($data, $pageOnload);
        foreach ($res['data'] as $k => $v) {
            // 按钮
            $res['data'][$k]['operation'] = '<lable class="btn btn-success btn-xs" onclick="edit(\'' . $v['id'] . '\')">查看</lable>';
        }
        ajax_success($res['data'], $res['PagerOrder']);
    }

    /**
     * 获取银行列表
     */
    public function getBankList()
    {
        $res = $this->Etc->selectBank();
        ajax_success($res, null);
    }

    /**
     * 显示 -> 编辑页
     */
    public function edit()
    {
        $data['id'] = $_GET['id'];
        $data['plateColor'] = $this->Etc->selectPlateColor();
        $data['bank'] = $this->Etc->selectBank();
        $this->load->view('admin/ETCManage/EtcSubscribeTransactEdit', $data);
    }

    /**
     * 获取详情数据
     */
    public function getDetails()
    {
        $data['id'] = $this->input->post('id');
        $res = $this->Etc->selectDetails($data);
        ajax_success($res[0], null);
    }

    /**
     * 保存修改
     */
    public function save()
    {
        $id = $this->input->post('id'); // 订单id
        $data['name'] = $this->input->post('name'); // 用户姓名
        $data['mobile'] = $this->input->post('mobile'); // 手机号
        $data['platenum'] = $this->input->post('platenum'); // 车牌号
        $data['colorno'] = $this->input->post('colorno'); // 车牌颜色编号
        $data['bankno'] = $this->input->post('bankno'); // 银行编号
        $data['address'] = $this->input->post('address'); // 就近地址
        $res = $this->Etc->updateDetails($data, $id);
        ajax_success($res, null);
    }

    /**
     * 导出Excel
     */
    public function exportExcel()
    {
        $data['startTime'] = $this->input->get('startTime');
        $data['endTime'] = $this->input->get('endTime');
        $data['bankno'] = $this->input->get('bankno');
        $data['keyword'] = $this->input->get('keyword');
        $res = $this->Etc->selectListData($data);
        // 引入PHPExcel
        ini_set('memory_limit', '150M');
        $this->load->database();
        $this->load->library('PHPExcel');
        // 初始化一个Excel表格
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('ETC预约办理');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ETC预约办理列表');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getstyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getstyle('A1')->getAlignment()->setHorizontal(PHPExcel_style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getstyle('A1')->getAlignment()->setVertical(PHPExcel_style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->freezePane('A3');
        $objPHPExcel->getActiveSheet()->getStyle('A:H')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getstyle('A:H')->getAlignment()->setHorizontal(PHPExcel_style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getstyle('A:H')->getAlignment()->setVertical(PHPExcel_style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(88);
        // 设置表头
        $objPHPExcel->getActiveSheet()->setCellValue('A2', '序号')
            ->setCellValue('B2', '姓名')
            ->setCellValue('C2', '手机号')
            ->setCellValue('D2', '车牌号')
            ->setCellValue('E2', '车牌颜色')
            ->setCellValue('F2', '办理银行')
            ->setCellValue('G2', '申请时间')
            ->setCellValue('H2', '就近地址');
        // 单元格赋值
        for ($i = 0; $i < count($res['data']); $i++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . ($i + 3), ($i + 1), PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B' . ($i + 3), $res['data'][$i]['name'])
                ->setCellValue('C' . ($i + 3), $res['data'][$i]['mobile'])
                ->setCellValue('D' . ($i + 3), $res['data'][$i]['platenum'])
                ->setCellValue('E' . ($i + 3), $res['data'][$i]['color'])
                ->setCellValue('F' . ($i + 3), $res['data'][$i]['bank'])
                ->setCellValue('G' . ($i + 3), $res['data'][$i]['createtime'])
                ->setCellValue('H' . ($i + 3), $res['data'][$i]['address']);
        }
        // 生成Excel文件
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $failname = date("Ymd-His") . '-' . rand(100, 999);
        ob_end_clean();
        header('Content-Disposition: attachment;filename="ETC预约办理(' . $failname . ').xls"');
        header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
        header('Cache-Control: max-age=0'); // 禁止缓存
        $objWriter->save('php://output');
        $objWriter->save("excel/" . $failname . ".xls");
        $name['name'] = $failname . ".xls";
        ajax_success($name, null);
    }
}
