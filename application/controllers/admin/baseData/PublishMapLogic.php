<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	基础数据-简图发布段
 * 	涉及到的表- gde-roadold
 */
class PublishMapLogic extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Publishmap_model', 'publishmap');
		checksession();
	}

	/**
	 * @desc   展示'简图发布段'页面
	 */
	public function indexPage(){
		$this->load->view('admin/BaseData/PublishMap/PublishMapList');
	}

	/**
	 * @desc   '简图发布段'->获取简图信息
	 * @data   2015-10-19 16:28:53
	 */
	public function onLoadMap(){

		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by id desc';
		}
		$keyword = $this->input->post('keyword');
		//var_dump($keyword);
		
		$data=$this->publishmap->selectMapMsg($keyword,$pageOnload);
		//<th class="title" width="10%" itemvalue="" center="true" showtype="a" attr="onclick=changeMap('{id}') href='javascript:void(0)' " itemtext="修改">操作</th>
		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="changeMap('.$v['id'].')">修改</lable>';
		}
		//var_dump($data);exit;
		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function operateMapMsg(){
		$tag = $this->input->get('tag');
		if ($tag == 1) {//新增
			//$data = array();
			$data['id'] = 0;
			$data['data'] = array();
		}else{//修改
			$data['id'] = $this->input->get('id');
			$data['data'] = $this->publishmap->selectThisMapMsg($data['id']);
		}
		$this->load->view('admin/BaseData/PublishMap/OperateMapList',$data);
	}


	public function saveMapMsg(){
		//mapid:mapid,pubcode:pubcode,x:x,y:y
		$id = $this->input->post('id');
		$mapid = $this->input->post('mapid');
		$pubcode = $this->input->post('pubcode');
		$x = $this->input->post('x');
		$y = $this->input->post('y');
		//var_dump($id,$mapid,$pubcode,$x,$y);exit;
		if ($id == 0) {//新增
			$res = $this->publishmap->insertMapMsg($mapid,$pubcode,$x,$y);
		}else{
			$res = $this->publishmap->updateMapMsg($id,$mapid,$pubcode,$x,$y);
		}

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}


	public function delMapMsg(){
		$deleteValue = $this->input->post('deleteValue');
		$deleteArr = explode(',', $deleteValue);
		$res = $this->publishmap->deleteMapMsg($deleteArr);
		//var_dump($res);exit;
		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}


	public function exportExcel(){
		$keyword = $this->input->get('keyword');

		$data = $this->publishmap->selectMapMsgToExcel($keyword);

		$this->load->library('PHPExcel');
		//实例化PHPExcel对象
        $excel = new PHPExcel();
        //Excel表格式,设置表列
        $letter = array('A','B','C','D');

        //填充表头数组,设置表头(第一行)的列名
        $tableheader = array(
            '简图ID','编码','X','Y'
        );

        $excelSheet = $excel->getActiveSheet();
    	//第一行大标题
		//合并单元格
		$excelSheet->mergeCells('A1:D1');
		//设置单元格内容
		date_default_timezone_set('PRC');
		$excelSheet->setCellValue('A1','简图发布段'.date('Y-m-d H:i'));
		//设置字体
		$excelSheet->getStyle('A1')->getFont()->setSize(20);
		$excelSheet->getStyle('A1')->getFont()->setBold(true);
		//设置行高,列宽
		$excelSheet->getRowDimension(1)->setRowHeight(30);
		$excelSheet->getColumnDimension('A')->setWidth(20);
		$excelSheet->getColumnDimension('B')->setWidth(30);
		$excelSheet->getColumnDimension('C')->setWidth(20);
		$excelSheet->getColumnDimension('D')->setWidth(20);
		//设置水平居中
		$excelSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//设置垂直居中
		$excelSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置边框
		$excelSheet->getStyle('A1:D1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //第二行表标题
        for($i = 0;$i < count($tableheader);$i++) {
            $excelSheet->setCellValue("$letter[$i]2","$tableheader[$i]");
            $excelSheet->getStyle("$letter[$i]2")->getFont()->setBold(true);
        }
        $excelSheet->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        //第三行开始表内容
        for ($i = 3;$i <= count($data) + 2;$i++) {
            $j = 0;
            foreach ($data[$i - 3] as $value) {
                $excelSheet->setCellValue("$letter[$j]$i","$value");
                
                $excelSheet->getStyle("$letter[$j]$i")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

				$j++;
            }
        }

        $filename = iconv('UTF-8', 'GB2312', '简图发布段数据表'.date('Y-m-d_H:i').'.xlsx');
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