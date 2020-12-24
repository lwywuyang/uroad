<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	基础数据-路段维护
 * 	涉及到的表- gde-roadold
 */
class RoadLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Road_model', 'road');
		checksession();
	}

	/**
	 * [indexPage 路段维护]
	 * @return [type] [description]
	 */
	public function indexPage(){
		$data['roadper'] = $this->road->selectRoadPer();
		$this->load->view('admin/BaseData/ManageRoad/RoadLogic',$data);
	}

	/**
	 * [onLoadNews 路段维护-查询数据]
	 * @return [type] [description]
	 */
	public function onLoadRoad(){

		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by newcode asc,roadoldid desc';
		}
		$roadper = $this->input->post('roadper');
		$keyword = $this->input->post('search');
		
		$data=$this->road->getRoadoldData($roadper,$keyword,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="detail('.$v['roadoldid'].')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="checkPoi('.$v['roadoldid'].',\''.$v['shortname'].'\')">查看沿途站</lable>';
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}

	/**
	 * [detail 详情]
	 * @return [type] [description]
	 */
	public function detail(){
		$roadoldid = $_GET['id'];
		if($roadoldid != '0'){//修改
			$data = $this->road->getRoadoldDataById($roadoldid);
			$resData = array("status"=>"OK", "data"=>$data["data"], "id"=>$roadoldid);
			$this->load->view('admin/BaseData/ManageRoad/RoadDetail',$resData);
		}else{//新增
			$this->load->view('admin/BaseData/ManageRoad/RoadDetail');//AddRoad
		}
	}


	/**
	 * @desc   '路段维护'页面->新增/查看详情->保存新增/更新的路段信息
	 * @return [type]      [description]
	 */
	public function saveDetailMsg(){
		$id = $this->input->post('id');
		$roadName = $this->input->post('roadName');
		$newCode = $this->input->post('newCode');
		$directionUp = $this->input->post('directionUp');
		$directionDown = $this->input->post('directionDown');
		$startCity = $this->input->post('startCity');
		$endCity = $this->input->post('endCity');
		$longitude = $this->input->post('longitude');
		$latitude = $this->input->post('latitude');
		$seq = $this->input->post('seq');
		$location = $this->input->post('location');
		$imgurl = $this->input->post('imgurl');

		if ($id == '0') {//新增
			$res = $this->road->insertNewRoad($roadName,$newCode,$directionUp,$directionDown,$startCity,$endCity,$location,$imgurl,$longitude,$latitude,$seq);
		}else{//更新路段
			$res = $this->road->updateRoadMsg($id,$roadName,$newCode,$directionUp,$directionDown,$startCity,$endCity,$location,$imgurl,$longitude,$latitude,$seq);
		}

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_error($res);
		}
		
	}

	/**
	 * @desc   '路段维护'页面->删除->删除路段信息
	 * @return [type]      [description]
	 */
	public function delRoad(){
		$deleteValue = $this->input->post('deleteValue');

		$res = $this->road->deleteRoad($deleteValue);

		ajax_success($res,null);
	}


	/**
	 * @desc   '路段维护'页面->查看沿途站->打开'查看路段沿途站信息'页面
	 * @return [type]      [description]
	 */
	public function showPoiMsg(){
		$ID['roadoldid'] = $this->input->get('roadoldid');
		$ID['roadname'] = $this->input->get('roadname');


		$this->load->view('admin/BaseData/ManageRoad/RoadPoiList',$ID);
	}


	/**
	 * @desc   打开'查看路段沿途站'页面->自动调用Load函数读取内容
	 * @return [type]      [description]
	 */
	public function onLoadRoadPoi(){
		/*$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by seq asc';
		}*/
		$roadoldid = $this->input->post('roadoldid');

		//$data = $this->road->selectPoiMsg($roadoldid,$pageOnload);弃用分页
		$data = $this->road->selectPoiMsg($roadoldid);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="deleteThisTr(this)">删除</lable>';
		}

		ajax_success($data['data'],null);
	}


	/**
	 * @desc   打开'查看路段沿途站'页面->页面预加载该路段的站点信息,用于新增站点
	 * @return [type]      [description]
	 */
	public function checkAllPoi(){
		$roadoldid = $this->input->post('roadoldid');

		$data = $this->road->selectAllPoiMsg($roadoldid);
		$arrayData = array();


		ajax_success($data,null);
	}


	/**
	 * @desc   打开'查看路段沿途站'页面->操作后点击保存->更新该路段下的所有站点信息
	 * @return [type]      [description]
	 */
	public function changeAllPoi(){
		$roadoldid = $this->input->post('roadoldid');
		$dataArr = $this->input->post('dataArr');
		
		$res = $this->road->updateAllLineStation($roadoldid,$dataArr);

		ajax_success($res,null);
	}

	/**
	 * @desc   '路段维护'->更新->调用存储过程
	 */
	public function updateMsg(){
		$res = $this->road->updateAllMsg();
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作出错!');
	}

	public function exportExcel(){
		$search = $this->input->get('search');

		$data = $this->road->selectRoadMsgToReport($search);

		$this->load->library('PHPExcel');
		//实例化PHPExcel对象
        $excel = new PHPExcel();

        //Excel表格式,设置表列
        $letter = array('A','B','C','D');

        //填充表头数组,设置表头(第一行)的列名
        $tableheader = array(
            '国标','路段名称','上行方向','下行方向'
        );

        $excel->getSheet(0)->setTitle('路段');

        $excelSheet = $excel->getActiveSheet();

        $excelSheet->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('0000B050');
        $excelSheet->getColumnDimension('A')->setWidth(20);
        $excelSheet->getColumnDimension('B')->setWidth(25);
        $excelSheet->getColumnDimension('C')->setWidth(30);
        $excelSheet->getColumnDimension('D')->setWidth(30);

        $rowNum = count($data)+1;
        $excelSheet->getStyle('A1:D'.$rowNum)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //第一行表标题
        for($i = 0;$i < count($tableheader);$i++) {
            $excelSheet->setCellValue("$letter[$i]1","$tableheader[$i]");
            $excelSheet->getStyle("$letter[$i]1")->getFont()->setBold(true);
        }

        //第二行开始表内容
        for ($i = 2;$i <= count($data) + 1;$i++) {
            $j = 0;
            foreach ($data[$i - 2] as $value) {
                $excelSheet->setCellValue("$letter[$j]$i","$value");
                
				$j++;
            }
        }

        $filename = iconv('UTF-8', 'GB2312', '路段导出表.xlsx');
		ob_end_clean();//清除缓存,避免乱码
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0, max-age=0");
        header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl;charset=UTF-8");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$filename);//输出的表名
        header("Content-Transfer-Encoding:binary");
		
		//写表
        //$write = new PHPExcel_Writer_Excel5($excel);
		//程序运行到这里,页面会弹出下载提示框,用户可以下载Excel表
        //$write->save('php://output');

		$objWriter = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
		$objWriter->save( 'php://output');
	}
}