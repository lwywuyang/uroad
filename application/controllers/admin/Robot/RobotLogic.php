<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc	基础数据-数据版本更新控制器类
 * 	      	涉及到的表-gde_filever
 * 	@author hwq
 * 	@date 	2015-9-29
 */
class RobotLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Robot/Robot_model', 'robot');
		checksession();
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

		$status = $this->input->post('status');
		$keyword = $this->input->post('keyword');
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by questionid desc';
		}

		$data = $this->robot->selectProblem($status,$keyword,$pageOnload);
		foreach($data['data'] as $k=>&$v){
				if($v['questiontype'] == 1){
					$data['data'][$k]['questiontype'] = '文本';
				}else if($v['questiontype'] == 2){
					$data['data'][$k]['questiontype'] = '图文';
				}else{
					$data['data'][$k]['questiontype'] = '路况';
					
					$data['data'][$k]['answer'] = $data['data'][$k]['f'];
				}
				
				$data['data'][$k]['operate'] = '<lable class="btn btn-info m-15" onclick="detail('.$v['questionid'].')">修改</lable>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-success m-15" onclick="read('.$v['questionid'].')">预览</lable>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-danger m-15" onclick="dodelete('.$v['questionid'].')">删除</lable>';
			
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

        $this->load->view('admin/Robot/detail',$data[0]);
        // if($data['status']=='1'){
        //     $this->load->view('News/newsYHdetail',$data['data']);   
        // }else{
        //     $this->load->view('News/newsYHdetail');   
        // }

    }

    //导出excel表
	public function Excel(){
		$typeSel = $this->input->get('typeSel');
		$keyword = $this->input->get('keyword');

		$data = $this->robot->selectExcelProblem($typeSel,$keyword);
		
		$this->load->library('PHPExcel');
		//实例化PHPExcel对象
        $excel = new PHPExcel();
        //Excel表格式,设置表列
        $letter = array('A','B','C','D','E','F','G','H','I','J');

        //填充表头数组,设置表头(第一行)的列名
        $tableheader = array(
            '问题','回复','关键字'
        );

        $excelSheet = $excel->getActiveSheet();
    	//第一行大标题
		//合并单元格
		$excelSheet->mergeCells('A1:C1');
		//设置单元格内容
		date_default_timezone_set('PRC');
		$excelSheet->setCellValue('A1','智能机器人回复'.$title);
		//设置字体
		$excelSheet->getStyle('A1')->getFont()->setSize(20);
		$excelSheet->getStyle('A1')->getFont()->setBold(true);
		//设置行高,列宽
		$excelSheet->getRowDimension(1)->setRowHeight(30);
		$excelSheet->getColumnDimension('A')->setWidth(30);
		$excelSheet->getColumnDimension('B')->setWidth(45);
		$excelSheet->getColumnDimension('C')->setWidth(30);
		

		//设置水平居中
		$excelSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//设置垂直居中
		$excelSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置边框
		$excelSheet->getStyle('A1:C1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        //第二行表标题
        for($i = 0;$i < count($tableheader);$i++) {
            $excelSheet->setCellValue("$letter[$i]2","$tableheader[$i]");
            $excelSheet->getStyle("$letter[$i]2")->getFont()->setBold(true);
        }
        $excelSheet->getStyle('A2:C2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        //第三行开始表内容
        for ($i = 0;$i < count($data);$i++) {
                $excelSheet->setCellValue("A".($i+3),$data[$i]['title']);
                $excelSheet->setCellValue("B".($i+3),$data[$i]['answer']);
                $excelSheet->setCellValue("C".($i+3),$data[$i]['keyword']);
      
                $excelSheet->getStyle("A".($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $excelSheet->getStyle("B".($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $excelSheet->getStyle("C".($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
        }

        $filename = '智能机器人'.date('Y-m-d_H:i').'.xls';
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
