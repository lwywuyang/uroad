<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* ETC网点
*/
class Feedback extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('baseData/Feedback_model', 'AdPic');
        checksession();
    }


    /**
     * 列表查看
     */
    public function index() {
        $data['type'] = $this->AdPic->getDict(2009);
        $this->load->view('admin/BaseData/Feedback',$data);
    }


    /**
     * 查找数据
     */
    public function onLoadAdPic() {
        //查找员工数据
        $pageOnload = page_onload();
        // 判断排序是否存在
        if ($pageOnload['OrderDesc'] == "") {
            $pageOnload['OrderDesc'] = '';
        }
        $StartTime = $this->input->post('StartTime');
        $EndTime = $this->input->post('EndTime');
        $stateSel = $this->input->post('stateSel');
        $keyword = $this->input->post('keyword');

        $data = $this->AdPic->getAdPicData($StartTime, $EndTime, $stateSel,$keyword, $pageOnload);

        foreach ($data['data'] as $k => $v) {
            $data['data'][$k]['xh'] = $k+1;
            if ($v['images'] != '') {
                $images = explode(',',$v['images']);
                $img_html = '';
                foreach ($images as $index => $image) {
                    $img_html.='&nbsp;&nbsp;'.'<img src="' . $image . '" class="ad-image" onclick="showLayerImage(this.src)" />';
                }
                $data['data'][$k]['imageurl'] = $img_html;
            }
        }

        ajax_success($data['data'], $data["PagerOrder"]);
    }


    /**
     * 编辑添加
     */
    public function detailAdPic() {
        $id = $this->input->get('id');

        $data = [];
        if ($id != '0') {
            $data = $this->AdPic->checkAdPicData($id);
        }

        $this->load->view('admin/BaseData/AdPicDetail', $data);
    }


    /**
     * 保存操作
     */
    public function onSaveAdPic() {
        date_default_timezone_set('PRC');

        $AdPicdata['id'] = $this->input->post('id');
        $AdPicdata['redirecturl'] = $this->input->post('redirecturl');
        $AdPicdata['seq'] = $this->input->post('seq');
        $AdPicdata['imageurl'] = $this->input->post('imageurl');

        if ($AdPicdata['id'] == '0') {
            $AdPicdata['created'] = date('Y-m-d h:i:s');
            $AdPicdata['modified'] = date('Y-m-d h:i:s');
        } else
            $AdPicdata['modified'] = date('Y-m-d h:i:s');

        $res = $this->AdPic->saveAdPic($AdPicdata);

        if ($res === true)
            ajax_success(true, NULL);
        else
            ajax_error($res);
    }

    public function exportExcel(){
        $keyword = $this->input->get('keyword');
        $StartTime = $this->input->get('StartTime');
        $EndTime = $this->input->get('EndTime');
        $stateSel = $this->input->get('stateSel');

        $data = $this->AdPic->getAdPicDataExcel($StartTime,$EndTime,$stateSel,$keyword);

        $this->load->library('PHPExcel');
        //实例化PHPExcel对象
        $excel = new PHPExcel();
        //Excel表格式,设置表列
        $letter = array('A','B','C','D','E','F','G');

        //填充表头数组,设置表头(第一行)的列名
        $tableheader = array(
            '序号','用户','时间','类型','联系电话','截图','问题描述'
        );

        $excelSheet = $excel->getActiveSheet();
        //第一行大标题
        //合并单元格
        $excelSheet->mergeCells('A1:G1');
        //设置单元格内容
        date_default_timezone_set('PRC');
        $excelSheet->setCellValue('A1','意见反馈');
        //设置字体
        $excelSheet->getStyle('A1')->getFont()->setSize(20);
        $excelSheet->getStyle('A1')->getFont()->setBold(true);
        //设置行高,列宽
//        $excelSheet->getRowDimension(1)->setRowHeight(30);
        $excelSheet->getColumnDimension('A')->setWidth(20);
        $excelSheet->getColumnDimension('B')->setWidth(30);
        $excelSheet->getColumnDimension('C')->setWidth(20);
        $excelSheet->getColumnDimension('D')->setWidth(20);
        $excelSheet->getColumnDimension('E')->setWidth(20);
        $excelSheet->getColumnDimension('F')->setWidth(80);
//        $excelSheet->getDefaultRowDimension("F")->setRowHeight(100);
        $excelSheet->getColumnDimension('G')->setWidth(20);
        //设置水平居中
        $excelSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置垂直居中
        $excelSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置边框
        $excelSheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //第二行表标题
        for($i = 0;$i < count($tableheader);$i++) {
            $excelSheet->setCellValue("$letter[$i]2","$tableheader[$i]");
            $excelSheet->getStyle("$letter[$i]2")->getFont()->setBold(true);
        }
        $excelSheet->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $xh = 0;
        foreach ($data as $index => $datum) {
            $data[$index]['xh'] = (string)($xh+1);
            $xh++;
        }

        //第三行开始表内容
        for ($i = 3;$i <= count($data) + 2;$i++) {
            $j = 0;

            foreach ($data[$i - 3] as $k=>$value) {



                if ($k=='images'){

                    $imgs = explode(',', $value);
                    foreach ($imgs as $kk => $vv) {
                        $filename_image = explode('/',$vv);

                        $objDrawing[$kk] = new PHPExcel_Worksheet_Drawing(); //必须每次重新实例化
                        $objDrawing[$kk]->setPath('./img/'.end($filename_image));//这里是相对路径
                        $objDrawing[$kk]->setHeight(80);//照片高度
                        $objDrawing[$kk]->setWidth(80);
                        $objDrawing[$kk]->setCoordinates("$letter[$j]$i");
//                    // 图片偏移距离
                        $objDrawing[$kk]->setOffsetX(12+($kk*80));
                        $objDrawing[$kk]->setOffsetY(12);
                        $objDrawing[$kk]->setWorksheet($excel->getActiveSheet());
                        $excelSheet->getRowDimension($i)->setRowHeight(80);
                    }




                }else{
                    $excelSheet->setCellValue("$letter[$j]$i","$value");

                    $excelSheet->getStyle("$letter[$j]$i")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                }
                $j++;
            }
        }

        $filename = iconv('UTF-8', 'GB2312', '意见反馈.xlsx');
        ob_end_clean();//清除缓存,避免乱码
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl;charset=UTF-8");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$filename);//输出的表名
        header("Content-Transfer-Encoding:binary");

        //写表
        $write = new PHPExcel_Writer_Excel2007($excel);
        //程序运行到这里,页面会弹出下载提示框,用户可以下载Excel表
        $write->save('php://output');
    }
}

