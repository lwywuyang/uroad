<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	基础数据-收费站维护
 * 	涉及到的表- gde-roadpoi
 */
class RoadPOIlogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Roadpoi_model', 'roadpoi');
		checksession();
	}

	/**
	 * 展示'收费站维护'页面
	 */
	public function indexPage(){
		//获取下拉框内容
		$data['roadper'] = $this->roadpoi->selectAllRoadPer();

		$roadold['0'] = $this->roadpoi->selectAllRoad();
		foreach($data['roadper'] as $k => $v){
			$roadold[$v['id']] = $this->roadpoi->selectRoadInPer((string)$v['roadoldids']);
			//var_dump($roadold);exit;
		}

		$roadoldid = array();$roadname = array();
		foreach($roadold as $k=>$v){
			if($v == null){
				$roadoldid[$k] = array();
				$roadname[$k] = array();
			}else{
				foreach($v as $kk=>$vv){
					$roadoldid[$k][$kk] = $vv['roadoldid'];
					$roadname[$k][$kk] = $vv['shortname'];
				}
			}
		}
		$data['roadoldidArr'] = json_encode($roadoldid);
		$data['roadoldnameArr'] = json_encode($roadname);

		
		$data['road'] = $this->roadpoi->selectAllRoad();
		$data['type'] = $this->roadpoi->selectAllType();
		$data['status'] = $this->roadpoi->selectAllStatus();
		$this->load->view('admin/BaseData/ManageRoadPOI/RoadPOIList',$data);
	}

	

	/**
	 * @desc   打开'加油站维护'->加载页面相应内容
	 * @return [type]      [description]
	 */
	public function onLoadRoadPOI(){
		//分页
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by roadoldid asc,miles asc';
		}
		$roadperSel = $this->input->post('roadperSel');
		$roadId = $this->input->post('roadId');
		$type = $this->input->post('type');
		$keyword = $this->input->post('keyword');
		$status = $this->input->post('status');
		
		$data=$this->roadpoi->selectRoadPOIMsg($roadperSel,$roadId,$type,$keyword,$status,$pageOnload);
		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs m-5" onclick="checkDetail('.$v['poiid'].')">查看</lable>';

			if ($v['pointtype'] == '1002001' || $v['pointtype'] == '1002002') {
				$data['data'][$k]['operate'].='<lable class="btn btn-primary btn-xs m-5" onclick="checkUeditor('.$v['poiid'].',\''.$v['name'].'\')">图文</lable>';
				if ($v['stationstatus'] == '1') {
					$data['data'][$k]['operate'] .= '<lable class="btn btn-warning btn-xs" onclick="changeStatus('.$v['poiid'].',0,\''.$v['name'].'\')">设为不可用</lable>';
				}else{
					$data['data'][$k]['operate'] .= '<lable class="btn btn-warning btn-xs" onclick="changeStatus('.$v['poiid'].',1,\''.$v['name'].'\')">设为可用</lable>';
				}
			}else{
				$data['data'][$k]['statusname'] = '--';
			}
			
		}
		
		ajax_success($data['data'],$data["pageOnload"]);
	}


	/**
	 * @desc   '加油站维护'->查看某站点详情操作->获取站点详情并展示'站点详情页面'
	 * @return [type]      [description]
	 */
	public function checkPOIDetail(){
		$poiid = $_GET['poiid'];
		$data['road'] = $this->roadpoi->selectAllRoad();
		$data['type'] = $this->roadpoi->selectAllType();
		//$data['status'] = $this->roadpoi->selectAllStatus();

		if($poiid){//修改
			$data['poiid'] = $poiid;
			$data['data'] = $this->roadpoi->selectPoiMsgById($poiid);
			$data['data'][0]['coor_x'] = round($data['data'][0]['coor_x'],6);
			$data['data'][0]['coor_y'] = round($data['data'][0]['coor_y'],6);
			//查询关联枢纽
			$data['hub'] = $this->roadpoi->selectRoadExceptThis($poiid);
		}else{//站点id为空或0->新增
			$data['poiid'] = 0;
			$data['data'] = array(array('roadoldid'=>0,'pointtype'=>0));
			$data['hub'] = $data['road'];
		}
		
		$this->load->view('admin/BaseData/ManageRoadPOI/RoadPOIDetail',$data);
	}

	/**
	 * @desc   '加油站维护'->查看站点详情->修改并点击确定->保存站点信息
	 * @return [type]      [description]
	 */
	public function saveRoadPoiMsg(){
        $poiid = $this->input->post('poiid');
		$name = $this->input->post('name');
		$typeSel = $this->input->post('typeSel');
		$stationcode = $this->input->post('stationcode');
		$roadSel = $this->input->post('roadSel');
		$phone = $this->input->post('phone');
		$city = $this->input->post('city');
		$miles = $this->input->post('miles');
		$coor_x = $this->input->post('coor_x');
		$coor_y = $this->input->post('coor_y');
		$nowinwaynum = $this->input->post('nowinwaynum');
		$nowexitwaynum = $this->input->post('nowexitwaynum');
		$nowinetcnum = $this->input->post('nowinetcnum');
		$nowexitetcnum = $this->input->post('nowexitetcnum');
		$hubArr = $this->input->post('hub');
		$address = $this->input->post('address');
		$status = $this->input->post('status');

		//2015-11-19
		//nextRoadLeft:nextRoadLeft,nextRoadStraight:nextRoadStraight,nextRoadRight:nextRoadRight,tagAddress:tagAddress,comeRoad:comeRoad,neighborRoad:neighborRoad,viewAndCompapny:viewAndCompapny
		$nextRoadLeft = $this->input->post('nextRoadLeft');
		$nextRoadStraight = $this->input->post('nextRoadStraight');
		$nextRoadRight = $this->input->post('nextRoadRight');
		$tagAddress = $this->input->post('tagAddress');
		$comeRoad = $this->input->post('comeRoad');
		$neighborRoad = $this->input->post('neighborRoad');
		$viewAndCompapny = $this->input->post('viewAndCompapny');
		$direction1 = $this->input->post('direction1');
		$direction2 = $this->input->post('direction2');

		$hub = '';
		if ($hubArr != '') {
			$hub = implode(',', $hubArr);
		}
		//var_dump($hub);exit;
		if ($poiid == '') {ajax_success('获取站点ID出错',null);exit;}
		if ($name == '') {ajax_success('获取站点名称出错',null);exit;}
		if ($typeSel == '') {ajax_success('获取站点类型出错',null);exit;}
		if ($stationcode == '') {ajax_success('获取站点编号出错',null);exit;}
		if ($roadSel == '') {ajax_success('获取站点地址出错',null);exit;}
		

		$data = $this->roadpoi->updateRoadPoiMsg($poiid,$name,$typeSel,$stationcode,$roadSel,$phone,$city,$miles,$coor_x,$coor_y,$nowinwaynum,$nowexitwaynum,$nowinetcnum,$nowexitetcnum,$hub,$address,$nextRoadLeft,$nextRoadStraight,$nextRoadRight,$tagAddress,$comeRoad,$neighborRoad,$viewAndCompapny,$status,$direction1,$direction2);
		ajax_success($data,null);
	}


	/**
	 * @desc   '加油站维护'->新增站点->填写并点击确定->保存站点信息
	 * @return [type]      [description]
	 */
	public function saveNewRoadPoiMsg(){
        //$poiid = $this->input->post('poiid');
		$name = $this->input->post('name');
		$typeSel = $this->input->post('typeSel');
		$stationcode = $this->input->post('stationcode');
		$roadSel = $this->input->post('roadSel');
		$phone = $this->input->post('phone');
		$city = $this->input->post('city');
		$miles = $this->input->post('miles');
		$coor_x = $this->input->post('coor_x');
		$coor_y = $this->input->post('coor_y');
		$nowinwaynum = $this->input->post('nowinwaynum');
		$nowexitwaynum = $this->input->post('nowexitwaynum');
		$nowinetcnum = $this->input->post('nowinetcnum');
		$nowexitetcnum = $this->input->post('nowexitetcnum');
		$hubArr = $this->input->post('hub');
		$address = $this->input->post('address');
		$status = $this->input->post('status');

		//2015-11-19
		//nextRoadLeft:nextRoadLeft,nextRoadStraight:nextRoadStraight,nextRoadRight:nextRoadRight,tagAddress:tagAddress,comeRoad:comeRoad,neighborRoad:neighborRoad,viewAndCompapny:viewAndCompapny
		$nextRoadLeft = $this->input->post('nextRoadLeft');
		$nextRoadStraight = $this->input->post('nextRoadStraight');
		$nextRoadRight = $this->input->post('nextRoadRight');
		$tagAddress = $this->input->post('tagAddress');
		$comeRoad = $this->input->post('comeRoad');
		$neighborRoad = $this->input->post('neighborRoad');
		$viewAndCompapny = $this->input->post('viewAndCompapny');
		//var_dump($nextRoadLeft,$nextRoadStraight,$nextRoadRight,$tagAddress,$comeRoad,$neighborRoad,$viewAndCompapny);exit;
		$direction1 = $this->input->post('direction1');
		$direction2 = $this->input->post('direction2');

		$hub = '';
		if ($hubArr != '') {
			$hub = implode(',', $hubArr);
		}
		

		//if ($poiid == '') {ajax_success('获取站点ID出错',null);exit;}
		if ($name == '') {ajax_success('获取站点名称出错',null);exit;}
		if ($typeSel == '') {ajax_success('获取站点类型出错',null);exit;}
		if ($stationcode == '') {ajax_success('获取站点编号出错',null);exit;}
		if ($roadSel == '') {ajax_success('获取站点地址出错',null);exit;}

		$data = $this->roadpoi->insertRoadPoiMsg($name,$typeSel,$stationcode,$roadSel,$phone,$city,$miles,$coor_x,$coor_y,$nowinwaynum,$nowexitwaynum,$nowinetcnum,$nowexitetcnum,$hub,$address,$nextRoadLeft,$nextRoadStraight,$nextRoadRight,$tagAddress,$comeRoad,$neighborRoad,$viewAndCompapny,$status,$direction1,$direction2);
		//var_dump($data);exit;
		ajax_success($data,null);
	}


	/**
	 * @desc   '加油站维护'页面->删除->删除加油站信息
	 * @return [type]      [description]
	 */
	public function delRoadPoi(){
		$deleteValue = $this->input->post('deleteValue');
		$poiname = $this->input->post('poiname');

		$res = $this->roadpoi->deleteRoadPoi($deleteValue,$poiname);
		//var_dump($res);
		ajax_success($res,null);
	}

	/**
	 * [checkUeditorDetail 展示收费站图文页面]
	 * @version 2016-04-27 1.0
	 */
	public function checkUeditorDetail(){
		$poiid = $this->input->get('poiid');
		$name = $this->input->get('name');

		$data = $this->roadpoi->selectUeditorDetail($poiid);
		$data['poiid'] = $poiid;
		$data['name'] = $name;

		$this->load->view('admin/BaseData/ManageRoadPOI/UeditorDetail',$data);
	}

	/**
	 * [saveUeditor 保存图文]
	 * @version 2016-04-27 1.0
	 */
	public function saveUeditor(){
		$poiid = $this->input->post('poiid');
		$html = $this->input->post('html');
		$jpgurl = $this->input->post('jpgurl');
		$poiname = $this->input->post('poiname');

		$res = $this->roadpoi->updateUeditorDetail($poiid,$html,$jpgurl,$poiname);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

	public function exportExcel(){
		$roadperSel = $this->input->get('roadperSel');
		$roadId = $this->input->get('roadId');
		$type = $this->input->get('type');
		$keyword = $this->input->get('keyword');
		$status = $this->input->get('status');

		$data = $this->roadpoi->selectRoadPOIMsgToReport($roadperSel,$roadId,$type,$keyword,$status);

		$this->load->library('PHPExcel');
		//实例化PHPExcel对象
        $excel = new PHPExcel();

        //Excel表格式,设置表列
        $letter = array('A','B','C','D');

        //填充表头数组,设置表头(第一行)的列名
        $tableheader = array(
            '站点名称','桩号','国标','所属高速'
        );

        $excel->getSheet(0)->setTitle('收费站');

        $excelSheet = $excel->getActiveSheet();

        $excelSheet->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('0000B050');
        $excelSheet->getColumnDimension('A')->setWidth(20);
        $excelSheet->getColumnDimension('B')->setWidth(20);
        $excelSheet->getColumnDimension('C')->setWidth(20);
        $excelSheet->getColumnDimension('D')->setWidth(30);

        $rowNum = count($data)+1;
        $excelSheet->getStyle('A1:D'.$rowNum)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $excelSheet->getStyle('A2:D'.$rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

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

        $filename = iconv('UTF-8', 'GB2312', '收费站导出表.xlsx');
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
		$objWriter->save('php://output');
	}

	public function setRoadPoiStatus(){
		$poiid = $this->input->post('poiid');
		$status = $this->input->post('status');
		$name = $this->input->post('name');

		$res = $this->roadpoi->updateStationStatus($poiid,$status,$name);

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}
}