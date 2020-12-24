<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	基础数据-服务区维护
 * 	涉及到的表- 
 */
class ServiceLogic extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Service_model', 'service');
		checksession();
	}

	/**
	 * 展示'服务区维护'页面
	 */
	public function indexPage(){
		//获取下拉框内容
		$select['roadper'] = $this->service->selectAllRoadPer();

		foreach($select['roadper'] as $k => $v){
			$roadold[$v['id']] = $this->service->selectRoadInPer((string)$v['roadoldids']);
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
		$select['roadoldidArr'] = json_encode($roadoldid);
		$select['roadoldnameArr'] = json_encode($roadname);


		$select['road'] = $this->service->selectAllRoad();
		$select['type'] = $this->service->selectAllType();
		//var_dump($select['type']);
		foreach($select['type'] as $k=>$v){
			$select['type'][$k]['name'] = $v['name'].'('.$v['remark'].')';
		}
		$this->load->view('admin/BaseData/ManageService/ServiceList',$select);
	}

	
	/**
	 * @desc   打开'服务区维护'->加载页面相应内容
	 */
	public function onLoadServiceMsg(){
		//分页
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by newcode';
		}
		$roadperSel = $this->input->post('roadperSel');
		$roadId = $this->input->post('roadId');
		$type = $this->input->post('type');
		$keyword = $this->input->post('keyword');
		//var_dump($keyword);
		
		$data=$this->service->selectServiceMsg($roadperSel,$roadId,$type,$keyword,$pageOnload);

		foreach($data['data'] as $k=>$v ){
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs m-5" onclick="checkBaseData('.$v['poiid'].')">基础数据</lable><lable class="btn btn-info btn-xs m-5" onclick="checkDetail('.$v['poiid'].',\''.$v['shortname'].'\',\''.$v['name'].'\')">详细信息</lable>';
			if ($v['servicestatus'] == '1') {
				$data['data'][$k]['operate'] .= '<lable class="btn btn-warning btn-xs" onclick="changeStatus('.$v['detailid'].',0,\''.$v['name'].'\')">设为不可用</lable>';
			}else{
				$data['data'][$k]['operate'] .= '<lable class="btn btn-warning btn-xs" onclick="changeStatus('.$v['detailid'].',1,\''.$v['name'].'\')">设为可用</lable>';
			}

			$data['data'][$k]['styleName'] = $v['styleName'].'('.$v['styleDetail'].')';
		}
		
		ajax_success($data['data'],$data["pageOnload"]);
	}


	/**
	 * @desc   '服务区维护'->新增操作->展示'新增服务区'页面
	 *         查看基础数据操作->展示'基础数据'页面
	 */
	public function operateServiceMsg(){
		$poiid = $this->input->get('poiid');
		if ($poiid) {//查询基础数据
			$data['poiid'] = $poiid;
			$data['data'] = $this->service->selectBaseData($poiid);
		}else{//新增
			$data['poiid'] = 0;
			$data['data'] = array(array('roadoldid'=>0,'pointtype'=>0));
			//$this->load->view('admin/BaseData/ManageService/OperateBaseDataList');
		}
		$data['road'] = $this->service->selectAllRoad();
		$data['type'] = $this->service->selectAllType();
		foreach($data['type'] as $k=>$v){
			$data['type'][$k]['name'] = $v['name'].'('.$v['remark'].')';
		}
		$this->load->view('admin/BaseData/ManageService/OperateBaseDataList',$data);
	}


	/**
	 * @desc   '服务区维护'->新增/点击'基础数据'查看基础信息->确定保存->保存新/更新旧数据
	 */
	public function saveServiceMsg(){
		$poiid = $this->input->post('poiid');
		$name = $this->input->post('name');
		$typeSel = $this->input->post('typeSel');
		$roadSel = $this->input->post('roadSel');
		$direction = $this->input->post('direction');
		$coor_x = $this->input->post('coor_x');
		$coor_y = $this->input->post('coor_y');
		$miles = $this->input->post('miles');
		$phone = $this->input->post('phone');
		$city = $this->input->post('city');
		$address = $this->input->post('address');
		$stationcode = $this->input->post('stationcode');

		$res = $this->service->checkCode($stationcode,$poiid);

		if($res==false && !empty($stationcode)){
		    ajax_error('编码已存在');
		    exit;
        }

		if ($poiid == '0') {//新增
			$data = $this->service->insertServiceMsg($name,$typeSel,$roadSel,$direction,$coor_x,$coor_y,$miles,$address,$phone,$city,$stationcode);
			//return $res;
		}else{//修改
			$data = $this->service->updateServiceMsg($poiid,$name,$typeSel,$roadSel,$direction,$coor_x,$coor_y,$miles,$address,$phone,$city,$stationcode);
			
		}
		//var_dump($data);exit;
		ajax_success($data,null);
	}

	/**
	 * @desc   '服务区维护'->选取服务区并删除操作->删除所选服务区信息
	 * @return [type]      [description]
	 */
	public function deleteServiceMsg(){
		$deleteValue = $this->input->post('deleteValue');
		$poiname = $this->input->post('poiname');

		$res = $this->service->deleteService($deleteValue,$poiname);
		//var_dump($res);
		ajax_success($res,null);
	}


	/**
	 * @desc   '服务区维护'->查询服务区详细信息
	 */
	public function checkServiceDetail(){
		$data['poiid'] = $this->input->get('poiid');
		$data['poiname'] = $this->input->get('name');
		//服务区状态下拉框内容
		$data['status'] = $this->service->selectServiceStatus();
		$data['data'] = $this->service->selectServiceDetailMsg($data['poiid']);
		//$data['gallery'] = $this->service->selectGallery($poiid);

		
		//var_dump($data);exit;
		$this->load->view('admin/BaseData/ManageService/ServiceDetailList',$data);
	}


	/**
	 * @desc   '服务区维护'->查看服务区详细->页面load服务区下的图集信息
	 */
	public function onLoadGallery(){
		//分页
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by id desc';
		}
		$poiid = $this->input->post('poiid');
		$galleryType = $this->input->post('galleryType');

		$data = $this->service->selectGallery($poiid,$galleryType,$pageOnload);
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['picImg'] = "<img src=".$value['pic']." class='imgSize' alt='没有封面图'>";
			switch ($value['type']) {
				case 1:
					$data['data'][$key]['typeName'] = '菜式';
					break;
				case 2:
					$data['data'][$key]['typeName'] = '环境';
					break;
				case 3:
					$data['data'][$key]['typeName'] = '节目表';
					break;
				default:
					$data['data'][$key]['typeName'] = '';
			}
		}
		ajax_success($data['data'],$data['pageOnload']);
	}


	/**
	 * @desc 新增或者修改服务区的图集信息
	 */
	public function ManageGallery(){
		$data['poiid'] = $this->input->get('poiid');//服务区id
		$data['photoid'] = $this->input->get('id');//服务区图片id,如果是0则是新增
		$roadoldid = $this->input->get('roadoldid');//路段id

		if ($data['photoid']) {//不为0就是修改
			$data['photoMsg'] = $this->service->selectPhotoMsg($data['photoid']);
		}else{//为0就是新增
			
		}
		$data['allService'] = $this->service->selectAllService($roadoldid);
		$this->load->view('admin/BaseData/ManageService/OperateGalleryList',$data);
	}


	/**
	 * @desc   '服务区维护'->查看服务区详细信息->添加服务区图片->'添加图片信息录入'页面->操作并点击添加->保存
	 */
	public function saveGalleryMsg(){
		$photoid = $this->input->post('photoid');
		$name = $this->input->post('name');
		$poiid = $this->input->post('poiid');
		$typeSel = $this->input->post('typeSel');
		$price = $this->input->post('price');
		$imgurl = $this->input->post('imgurl');

		//$res = $this->service->
		//var_dump($photoid);exit;
		if (is_null($photoid)) {//判断是否为数字
			ajax_success('false',null);
		}else if ($photoid == 0) {//新增
			//var_dump('000');
			$res = $this->service->insertNewServicePic($name,$poiid,$typeSel,$price,$imgurl);
		}else{//更新路段
			$res = $this->service->updateNewServicePic($photoid,$name,$poiid,$typeSel,$price,$imgurl);
		}
		//var_dump($res);//exit;
		ajax_success($res,null);
		//return $res;
	}




	public function deletePhoto(){
		$poiid = $this->input->post('poiid');
		$deletePhoto = $this->input->post('deleteValue');
		$deleteArr = explode('--', $deletePhoto);
		//var_dump($deleteArr);exit;
		$res = $this->service->deletePhotoMsg($poiid,$deleteArr);
		ajax_success($res,null);
	}

	/**
	 * @desc   '服务区详细信息'->获取油类表格数据和服务区特色表格数据
	 */
	public function getGasAndFeatureAndImagesMsg(){
		$poiid = $this->input->post('poiid');
		$data['gas'] = $this->service->selectGasMsg($poiid);//暂时没有内容
		$data['feature'] = $this->service->selectFeatureMsg($poiid);
		$data['images'] = $this->service->selectImagesMsg($poiid);
		/*var_dump(count($data['feature']));
		var_dump($data);exit;*/
		if (isset($data['gas'][0])) {
			foreach ($data['gas'] as $key1 => $value1) {
				$data['gas'][$key1]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkGas(\''.$value1['id'].'\')">修改</lable>';
			}
		}
		if (isset($data['feature'][0])) {
			foreach ($data['feature'] as $key2 => $value2) {
				$data['feature'][$key2]['jpgimages'] = '<img src="'.$value2['jpgurl'].'" class="imageslist" onclick="showLayerImage(this.src)" />';
				if ($value2['status'] == 1012004) {
					$data['feature'][$key2]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkFeature(\''.$value2['id'].'\')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="cancelPush(\''.$value2['id'].'\')">取消发布</lable>';
				}else{
					$data['feature'][$key2]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkFeature(\''.$value2['id'].'\')">查看</lable>&nbsp;&nbsp;&nbsp;&nbsp;<lable class="btn btn-info btn-xs" onclick="push(\''.$value2['id'].'\')">发布</lable>';
				}
			}
		}
		
		if (isset($data['images'][0])) {
			foreach ($data['images'] as $key => $value) {
				$data['images'][$key]['picture'] = '<img src="'.$value['pic'].'" class="imageslist" onclick="showLayerImage(this.src)" />';
			}
		}
		//var_dump($data['feature']);exit;
		ajax_success($data,null);
	}

	public function operateGasMsg(){
		$data['poiid'] = $this->input->get('poiid');
		$data['id'] = $this->input->get('id');
		if ($data['id'] == '0') {//新增

		}else{//查看
			$data['data'] = $this->service->selectGasMsgById($data['id']);
		}
		$this->load->view('admin/BaseData/ManageService/OperateGasList',$data);
	}

	public function saveGasMsg(){
		$id = $this->input->post('id');
		$poiid = $this->input->post('poiid');
		$gasname = $this->input->post('gasname');
		$price = $this->input->post('price');
		$status = $this->input->post('status');

		if ($id == '0') {
			$res = $this->service->insertGasMsg($id,$poiid,$gasname,$price,$status);
		}else{
			$res = $this->service->updateGasMsg($id,$poiid,$gasname,$price,$status);
		}

		if ($res)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

	public function deleteGasMsg(){
		$deleteValue = $this->input->post('value');
		//var_dump($deleteValue);exit;
		$res = $this->service->delGasMsg($deleteValue);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

	/**
	 * @desc   新增/修改服务区特色
	 * @return [type]      [description]
	 */
	public function operateFeatureMsg(){
		$data['poiid'] = $this->input->get('poiid');
		$data['eventid'] = $this->input->get('eventid');
		if ($data['eventid'] == '0') {//新增
			# code...
		}else{//查看
			$data['data'] = $this->service->selectFeatureMsgById($data['eventid']);
			//var_dump($data['data']);exit;
		}
		//var_dump($data);exit;
		$this->load->view('admin/BaseData/ManageService/OperateFeatureList',$data);
	}


	//跳转新增/修改服务区图集页面
	public function operateImagesMsg(){
		$data['poiid'] = $this->input->get('poiid');
		$data['id'] = $this->input->get('id');
		if ($data['id'] == '0') {//新增
			# code...
		}else{//查看
			$data['data'] = $this->service->selectImagesMsgById($data['id']);
			//var_dump($data['data']);exit;
		}
		//var_dump($data);exit;
		$this->load->view('admin/BaseData/ManageService/OperateImagesList',$data);
	}


	//保存新增/修改的服务区图集数据
	public function saveImagesMsg(){
		$id = $this->input->post('id');
		$poiid = $this->input->post('poiid');
		$imgurl = $this->input->post('imgurl');
		$title = $this->input->post('title');

		if ($id == '0') {
			$res = $this->service->insertImagesMsg($id,$poiid,$imgurl,$title);
		}else{
			$res = $this->service->updateImagesMsg($id,$poiid,$imgurl,$title);
		}

		if ($res)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

	public function deleteImagesMsg(){
		$deleteValue = $this->input->post('value');
		//var_dump($deleteValue);exit;
		$res = $this->service->delImagesMsg($deleteValue);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error($res);
	}


	/**
	 * @desc   保存新增或修改服务区特色的结果
	 * @return [type]      [description]
	 */
	public function saveFeatureMsg(){
		$eventid = $this->input->post('eventid');
		$poiid = $this->input->post('poiid');
		$imgurl = $this->input->post('imgurl');
		$title = $this->input->post('title');
		$seq = $this->input->post('seq');
		$html = $this->input->post('html');
		//var_dump($seq);
		if ($eventid == '0') {//新增
			$eventid = create_guid();
			$res = $this->service->insertNewFeatureMsg($eventid,$poiid,$imgurl,$title,$seq,$html);
		}else{
			$res = $this->service->updateNewFeatureMsg($eventid,$poiid,$imgurl,$title,$seq,$html);
		}

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作失败!');

	}

	/**
	 * @desc   取消发布服务区特色
	 * @return [type]      [description]
	 */
	public function cancelPush(){
		$id = $this->input->post('id');
		$res = $this->service->updateNewsTypeToCancel($id);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作失败!');
	}


	public function deleteFeatureMsg(){
		$deleteValue = $this->input->post('value');
		//var_dump($deleteValue);exit;
		$res = $this->service->deleteFeatureMsgs($deleteValue);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('删除数据操作失败!');
	}

	public function saveDetailMsg(){
		//poiid:poiid,imgUrl:imgUrl,hasShop:hasShop,hasSpecial:hasSpecial,hasFood:hasFood,hasGas:hasGas,hasParking:hasParking,hasRepair:hasRepair,hasToilet:hasToilet,hasHotel:hasHotel,serviceStatusSel:serviceStatusSel,hasSpeciallist:hasSpeciallist,serviceSummary:serviceSummary,shopHtml:shopHtml,specialHtml:specialHtml,foodHtml:foodHtml,gasHtml:gasHtml,parkingHtml:parkingHtml,repairHtml:repairHtml,toiletHtml:toiletHtml,hotelHtml:hotelHtml
		$poiid = $this->input->post('poiid');
		$imgUrl = $this->input->post('imgUrl');
		$level = $this->input->post('level');

		$hasShop = $this->input->post('hasShop');
		$hasSpecial = $this->input->post('hasSpecial');
		$hasFood = $this->input->post('hasFood');
		$hasGas = $this->input->post('hasGas');
		$hasParking = $this->input->post('hasParking');
		$hasRepair = $this->input->post('hasRepair');
		$hasToilet = $this->input->post('hasToilet');
		$hasHotel = $this->input->post('hasHotel');

		$haswifi = $this->input->post('haswifi');
		$hasrescue = $this->input->post('hasrescue');
		$haschargingpile = $this->input->post('haschargingpile');
		$hasqizhan = $this->input->post('hasqizhan');
		$chargingpilenum = $this->input->post('chargingpilenum');
		$parkingspacenum = $this->input->post('parkingspacenum');

		$serviceStatusSel = $this->input->post('serviceStatusSel');
		$hasSpeciallist = $this->input->post('hasSpeciallist');
		$serviceSummary = $this->input->post('serviceSummary');
		$shopHtml = $this->input->post('shopHtml');
		$specialHtml = $this->input->post('specialHtml');
		$foodHtml = $this->input->post('foodHtml');
		$gasHtml = $this->input->post('gasHtml');
		$parkingHtml = $this->input->post('parkingHtml');
		$repairHtml = $this->input->post('repairHtml');
		$toiletHtml = $this->input->post('toiletHtml');
		$hotelHtml = $this->input->post('hotelHtml');
		$poiname = $this->input->post('poiname');
		//var_dump($poiid,$imgUrl,$hasShop,$hasSpecial,$hasFood,$hasGas,$hasParking,$hasRepair,$hasToilet,$hasHotel,$serviceStatusSel,$hasSpeciallist,$serviceSummary,$shopHtml,$specialHtml,$foodHtml,$gasHtml,$parkingHtml,$repairHtml,$toiletHtml,$hotelHtml);exit;
		//var_dump($serviceStatusSel);exit;
		//
		$res = $this->service->deplicateServiceDetailMsg($poiid,$imgUrl,$hasShop,$hasSpecial,$hasFood,$hasGas,$hasParking,$hasRepair,$hasToilet,$hasHotel,$serviceStatusSel,$hasSpeciallist,$serviceSummary,$shopHtml,$specialHtml,$foodHtml,$gasHtml,$parkingHtml,$repairHtml,$toiletHtml,$hotelHtml,$level,$haswifi,$hasrescue,$haschargingpile,$hasqizhan,$chargingpilenum,$parkingspacenum,$poiname);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('更新数据操作失败!');
	}

	public function exportExcel(){
		$roadperSel = $this->input->get('roadperSel');
		$roadId = $this->input->get('roadId');
		$type = $this->input->get('type');
		$keyword = $this->input->get('keyword');

		$data = $this->service->selectServiceMsgToReport($roadperSel,$roadId,$type,$keyword);

		$this->load->library('PHPExcel');
		//实例化PHPExcel对象
        $excel = new PHPExcel();

        //Excel表格式,设置表列
        $letter = array('A','B','C','D','E','F','G');

        //填充表头数组,设置表头(第一行)的列名
        $tableheader = array(
            '服务区名称','桩号','国标','所属高速','电话','图片地址','介绍'
        );

        $excel->getSheet(0)->setTitle('服务区');

        $excelSheet = $excel->getActiveSheet();

        $excelSheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('0000B050');
        $excelSheet->getColumnDimension('A')->setWidth(20);
        $excelSheet->getColumnDimension('B')->setWidth(15);
        $excelSheet->getColumnDimension('C')->setWidth(15);
        $excelSheet->getColumnDimension('D')->setWidth(25);
        $excelSheet->getColumnDimension('E')->setWidth(15);
        $excelSheet->getColumnDimension('F')->setWidth(35);
        $excelSheet->getColumnDimension('G')->setWidth(70);

        $rowNum = count($data)+1;
        $excelSheet->getStyle('A1:G'.$rowNum)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $excelSheet->getStyle('A2:G'.$rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

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

        $filename = iconv('UTF-8', 'GB2312', '服务区导出表.xlsx');
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

	public function setServiceStatus(){
		//detailid: detailid,status:status
		$detailid = $this->input->post('detailid');
		$status = $this->input->post('status');
		$poiname = $this->input->post('poiname');

		$res = $this->service->updateServiceStatus($detailid,$status,$poiname);

		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error('设置服务区状态失败!');
	}

}