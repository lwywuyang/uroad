<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 高德列表控制器
 */
class GaoDeListLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('GaoDeLIst/Gaodelist_model', 'Gaodelist');
		checksessionloop();
	}

	/**
	 * 获取提醒内容
	 * @Author   RaK
	 * @DateTime 2017-05-04T09:10:16+0800
	 * @return   [type]                   [description]
	 */
	public function getEventNumToTipNew(){
		$empid = getsessionempid();//当前登录人ID
		$data = array();

		//是否存在提醒信息
		$data['istx'] = true;
        $data['gdlkdata'] = $this->getGaoDeMessage();//高德路况
		// $data['gdlkdata'] = "";//高德路况
        if(empty($data['gdlkdata'])){//高德路况为空则高德数量为0
            $data['gdlkdata']['totalnum'] = 0;
        }

        //事件
        $this->load->model('Meun_model','meun');
        $data['eventnum'] = $this->meun->checkPopMsg($empid);
        $data['eventnum'][0]['num'] = count($data['eventnum']);
        if(empty($data['gdlkdata']) && empty($data['eventnum'])){
            $data['istx'] = false;
        }

		ajax_success($data,null);
	}

	/**
	 * 从redis获取高德事件提醒
	 * @Author   RaK
	 * @DateTime 2017-05-04T10:59:42+0800
	 * @return   [type]                   [description]
	 */
	public function getGaoDeMessage(){
		$redis = new redis();
        $result = $redis->connect('127.0.0.1', 6379);

        // $redis->del('gaodetraffic');//删除redis里高德提醒数据
        $linelength = $redis->hKeys('gaodetraffic');//获取redis里高德提醒数据
        
        log_message("INFO","GAODETXDATA--->".json_encode($linelength));
		$this->load->helper('budata');
        $budata = budatabycode('M0001');//获取当前登录人员有哪些路段权限

   
		$newbudata = ','.$budata.',';
		$data = array();
        if(!empty($linelength)){
        	$size = count($linelength);//高德数据个数

        	$eventids = "";
            $data['totalnum'] = 0;
            $num = 1;
        	// $roadids = "";
        	foreach ($linelength as $key => $value) {
        		$gaodedata = $redis->hGet('gaodetraffic', $value);//获取对应eventid的数据
                log_message("INFO","GAODEDATA--->".$gaodedata);
        		$gaodedata = explode(',', $gaodedata);
                //当提醒为新增并且操作表不存在对应的eventid时，插入一条该eventid为未处理的数据
                // if($gaodedata[3]==1){
                    $sql = "select eventid from amap_handle where eventid=?";
                    $eventdata = $this->db->query($sql,array($gaodedata[0]))->row_array();
                    if(empty($eventdata)){//不存在记录则插入
                        $sql = "insert into amap_handle (eventid,eventstatus)values(?,1001001)";
                        $this->db->query($sql,array($gaodedata[0]));
                        // $recordid = $this->db->insert_id();
                       
                    }
                    $sql ="select eventid from amap_handleprocess where eventid=?";
                    $processdata = $this->db->query($sql,array($gaodedata[0]))->row_array();
                    if(empty($processdata)){//不存在记录则插入
                        $sql = "insert into amap_handleprocess (eventid,operatorname,operatortime,operatorstatus)values(?,'系统提醒',now(),1002005)";
                        $this->db->query($sql,array($gaodedata[0]));
                    }
                // }
        		if(strstr($newbudata,",".$gaodedata[4].",")!==false || $newbudata==",0,"){//判断是否存在该高德提醒路段权限
        			//获取redis里对应key的数据
	        		$data['totalnum'] = $num++;
	        		$eventids.= ','.$gaodedata[0];
	        		// $roadids.= ','.$gaodedata[4];
	        		$data['eventid'] = trim($eventids,',');
					$data['title'] = $gaodedata[1];
					$data['intime'] = $gaodedata[2];
					$data['status'] = $gaodedata[3];
					$data['roadid'] = $gaodedata[4];
        		}
                // $redis->hdel('gaodetraffic',$gaodedata[0]);
        	}
			$data['noticetype'] = 12601;
        }
        return $data;
	}


	/**
	 * 高德列表页
	 * @Author   RaK
	 * @DateTime 2017-05-04T11:24:18+0800
	 * @return   [type]                   [description]
	 */
	public function gaoDeIndexPage(){
        $ids = $this->input->get("ids");
        $selecttype = $this->input->get("selecttype");
		$nowpage = $this->input->get("nowpage");
        $data['ids'] = $ids;
        $data['selecttype'] = $selecttype;//1为当天 2 为历史
		$data['nowpage'] = $nowpage;//页码
		//操作权限
		$empid=getsessionempid();
//        $budata=getsessionuserbudata(); // getsession路段权限
		$data["hasCheckcg"]=GetUserHasFunPermission($empid,'m130201')===true?1:0;
		$data['tsstatus'] = $this->Gaodelist->getGaoDeDictByPid("1001");//获取高德提醒状态

		$this->load->view('admin/GaoDeLIst/CollectTrafficList/trafficList',$data);
	}


	/**
	 * 高德详情页
	 * @Author   RaK
	 * @DateTime 2017-05-04T09:08:14+0800
	 * @return   [type]                   [description]
	 */
	public function gaoDeHandleList(){
        //事件ID
		$data['eventid'] = $this->input->get("eventid");
        //区分是当天数据还是历史数据 1 当天 2历史
        $data['selecttype'] = $this->input->get("selecttype");
        //页码
        $data['nowpage'] = $this->input->get("nowpage");

        //按钮类型 0是处理 1是查看
        $data['type'] = $this->input->get("type");

		//操作权限
		$empid=getsessionempid();
		$data["hasCheckcg"]=GetUserHasFunPermission($empid,'m130201')===true?1:0;

		$data['empidcs'] = getsessionempid();
     
		$this->load->view('admin/GaoDeLIst/CollectTrafficList/gaoDeHandleEdit',$data);
	}

    /**
     * 获取高德事件详情数据
     * @Author   RaK
     * @DateTime 2017-05-05T14:11:28+0800
     * @return   [type]                   [description]
     */
    pubLic function onLoadGaoDeHandleList(){
        $eventid = $this->input->post("gaodeeventid");
        $selecttype = $this->input->post("selecttype");
        //对应事件ID数据
        $data = $this->Gaodelist->getEventDataByEventId($eventid,$selecttype);
        $data['jamdist'] = ($data['jamdist']/1000);//拥堵距离
        $data['jamspeed'] = intval($data['jamspeed']);//时速
        //查看当前事件的处理操作状态
        // $data['handleoperator'] = $this->Gaodelist->getEventHandleStatusByEventId($eventid);

        //当前登录人ID
        $data['empid'] = getsessionempid();
        //对应路段正在发生的事件
        $data['relationeventall'] = $this->Gaodelist->getRelationEvent($data['roadid']);
        ajax_success($data,null);
    }

    /**
     * 获取高德提醒列表数据
     * @Author   RaK
     * @DateTime 2017-05-05T08:49:05+0800
     * @return   [type]                   [description]
     */
    public function onLoadGaoDeList(){
        $pageOnload=page_onload();
        // 判断排序是否存在
        if($pageOnload['OrderDesc']=="")
        {
            $pageOnload['OrderDesc']='order by inserttime desc';
        }
        $keyword = $this->input->post("keyword");
        $status = $this->input->post("status");
        $ppstatus = $this->input->post("ppstatus");
        $ids = $this->input->post("ids");
        $starttime = $this->input->post("starttime");
        $endtime = $this->input->post("endtime");
        $selecttype = $this->input->post("type");//是否为当天数据
        // var_dump($selecttype);
        // if($selecttype==1){
            // $starttime=$endtime=date('Y-m-d');
        // }
        // $selecttype = 2;
        //获取数据
        $data=$this->Gaodelist->getTrafficdata($pageOnload,$status,$starttime,$endtime,$ids,$selecttype,$ppstatus,$keyword);
        $operatorid = getsessionempid();
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]['caozuo'] = "";//按钮
            $data['data'][$key]['pubRunStatus'] = "";//状态
            $btnname = "查 看";//按钮名称
            $type = 1;
            if($value['eventstatus']==1001001 || $value['eventstatus']==null ){
                $btnname = "处 理";
                $type = 0;
            }
            // $data['data'][$key]['caozuo'].= '<lable class="btn btn-success btn-xs m-3" onclick="checkInfo(\''.$data['data'][$key]['eventid'].'\','.$selecttype.')">查看地图</lable>';
            $data['data'][$key]['caozuo'].= '<lable class="btn btn-success btn-xs m-3" onclick="chuli(\''.$data['data'][$key]['eventid'].'\',1,'.$selecttype.')">查 看</lable>';

            // $isoperator = $this->Gaodelist->getOperatorStatus($data['data'][$key]['eventid']);//判断当前提醒是否正在处理中
            //$isoperator===true
            //是否显示操作按钮

            if(empty($data['data'][$key]['operatorstatus']) || $data['data'][$key]['eventstatus']==1001001 && ($data['data'][$key]['operatorstatus']==1002002 ||  $operatorid==$data['data'][$key]['operatorid'])){
                $data['data'][$key]['caozuo'].= '&nbsp;&nbsp;<lable class="btn btn-success btn-xs m-3" onclick="chuli(\''.$data['data'][$key]['eventid'].'\','.$type.','.$selecttype.')">'.$btnname.'</lable>';
              

            }

            //拥堵距离
            if (!empty($value['jamdist'])) {
                $data['data'][$key]['jamdist'] = ($data['data'][$key]['jamdist']/1000)." km";
            }
            //时速
            if (!empty($value['jamspeed'])) {
                $data['data'][$key]['jamspeed'] = intval($data['data'][$key]['jamspeed']);
            }
            //拥堵状态
            if($value['pubrunstatus']==1){
                $data['data'][$key]['pubRunStatus'] = '<span class="label label-sm label-warning">拥堵</span>';
            }else if($value['pubrunstatus']==2){
                $data['data'][$key]['pubRunStatus'] = '<span class="label label-sm label-danger">趋向严重</span>';
            }else if($value['pubrunstatus']==3){
                $data['data'][$key]['pubRunStatus'] = '<span class="label label-sm label-danger">趋向疏通</span>';
            }


            $data['data'][$key]['ydqj'] = "";
            // $data['data'][$key]['roadname'] = $remark['roadname'];
            if(!empty($data['data'][$key]['startstationid'])){
                $startmile = $data['data'][$key]['startmile'];
                $endmile = $data['data'][$key]['endmile'];

                $startmile = sprintf("%.3f", $startmile);
                $endmile = sprintf("%.3f", $endmile);
                $newstartmile = "K".str_replace('.','+',$startmile);
                $newendmile = "K".str_replace('.','+',$endmile);
                $data['data'][$key]['ydqj'] = $newstartmile."~".$newendmile."</br>".$data['data'][$key]['startstationname'].'~'.$data['data'][$key]['endstationname'];
            }

              // $data['data'][$key]['ydqj'] =$data['data'][$key]['startstationname'].'~'.$data['data'][$key]['endstationname'];
            // $data['data'][$key]['ydjl'] = sprintf("%.2f",abs($startmile-$endmile)).'Km';

        }
        ajax_success($data['data'],$data["PagerOrder"]);
    }

    /**
     * 查看对应高德事件地图
     * @Author   RaK
     * @DateTime 2017-05-05T12:30:18+0800
     * @return   [type]                   [description]
     */
    public function getCommentById(){
        $id = $this->input->get("id");
        $selecttype = $this->input->get("selecttype");
        $data = $this->Gaodelist->getEventDataByIdNew($id,$selecttype);

        if (!empty($data['gaodejamDist'])) {
            $data['event']['jamDist'] = ($data['gaodejamDist']/1000)." km";
        }
        if (!empty($data['gaodelongTime'])) {
            $data['event']['longTime'] = ($data['gaodelongTime']);
        }
        if (!empty($data['gaodejamSpeed'])) {
            $data['event']['jamSpeed'] = intval($data['gaodejamSpeed']);
        }
        $this->load->view('admin/GaoDeLIst/CollectTrafficList/trafficMapDetail',$data);
    }

    /**
     * 高德事件关联操作
     * @Author   RaK
     * @DateTime 2017-05-05T13:37:43+0800
     * @return   [type]                   [description]
     */
    public function gaoDeHandle(){
        $gaodeeventid=$this->input->post('gaodeeventid');//高德提醒ID
        $eventid=$this->input->post('eventid');//事件ID -2代表无效
        $msg=$this->input->post('msg');//无效原因
        $imgurl=$this->input->post('imgurl');//图片
        $eventstatus=$this->input->post('eventstatus');//事件结果状态
        $isnew=$this->input->post('isnew');//事件是否已经处理，已经处理的事件再次保存只保存图片
        $selecttype=$this->input->post('selecttype');//当天数据还是历史数据
        $data=$this->Gaodelist->saveGaoDeHandle($gaodeeventid,$eventid,$msg,$imgurl,$eventstatus,$isnew,$selecttype);
        if($data){
            ajax_success('',NULL);
        }else{
            ajax_error('保存失败');
        }
    }

    /**
     * 修改对应高德事件的处理状态
     * @Author   RaK
     * @DateTime 2017-05-09T15:11:50+0800
     * @return   [type]                   [description]
     */
    pubLic function savehandle(){
        $gaodeeventid = $this->input->post('gaodeeventid');
        $status = $this->input->post('status');
        $selecttype = $this->input->post('selecttype');
        $operatorname = getsessionempname();
        $data=$this->Gaodelist->savehandle($operatorname,$gaodeeventid,$status,$selecttype);
        if($data){
            $content = array('operatorname'=>$operatorname);
            ajax_success($content,NULL);
        }else{
            ajax_error('处理失败');
        }
    }

    /**
     * eventid='); ?>"+eventid+"&newintime="+newintime+"&newgaodejamDist="+gaodejamDist+"&newgaodejamSpeed="+gaodejamSpeed+"&gaodeeventid="+gaodeeventid
     */



    /**
     * 当选择关联时间前先判断是否要修改对外信息
     * @Author   RaK
     * @DateTime 2017-05-10T16:11:54+0800
     * @return   [type]                   [description]
     */
    public function updateReportout(){
        $data['eventid']=$this->input->get('eventid');//事件ID
        $newintime=$this->input->get('newintime');
        $newgaodejamDist=$this->input->get('newgaodejamDist');
        $newgaodejamSpeed=$this->input->get('newgaodejamSpeed');
        $data['gaodeeventid']=$this->input->get('gaodeeventid');

        $gdhtml = "据高德地图提示".$newintime."行驶缓慢区间".$newgaodejamDist."公里，行驶速度".$newgaodejamSpeed."公里/小时";
        $res = $this->Gaodelist->getReportoutByEventId($data['eventid']);
        $data['reportout'] = $res['oldreportout'].$gdhtml;
        // $data['reportout'].=$res['kt']."，";
        // if($res['startstake']==$res['endstake']){
        //     $data['reportout'].=$res['startnodename']."(K".str_replace('.','+',$res['startstake']).")";
        // }else{
        //     $data['reportout'].=$res['startnodename']."至".$res['endnodename']."(K".str_replace('.','+',$res['startstake'])."-K".str_replace('.','+',$res['endstake']).")";
        // }
        // $CompName = $this->Gaodelist->getCompName();
        // $CompNameNew = "【".$CompName."】";
        // $data['reportout'].="发生".$res['eventcausename']."事件，";
        // $data['reportout'].="现场交通".$res['roadtrafficcolor'].$gdhtml."；事件仍在进行中。".$CompNameNew;
        $data['oldreportout'] = $res['oldreportout'];

        $this->load->view('admin/GaoDeLIst/CollectTrafficList/updateReportoutlist',$data);
    }

    /**
     * 高德入口关联事件时修改对外信息
     * @Author   RaK
     * @DateTime 2017-05-10T16:24:07+0800
     * @return   [type]                   [description]
     */
    public function savereportout(){
        $eventid=$this->input->post('eventid');
        $isnew=$this->input->post('isnew');
        $gaodeeventid=$this->input->post('gaodeeventid');
        $reportout=$this->input->post('reportout');
        $res = $this->Gaodelist->savereportout($isnew,$eventid,$gaodeeventid,$reportout);
        if($res){
            ajax_success('',NULL);
        }else{
            ajax_error('保存失败');
        }
    }


    /**
     * 获取对应高德事件的历史进展
     * @Author   RaK
     * @DateTime 2017-05-11T15:06:17+0800
     * @return   [type]                   [description]
     */
    public function onLoadTrafficProcess(){
        $gaodeeventid = $this->input->post("gaodeeventid");
        $data = $this->Gaodelist->getTrafficProcess($gaodeeventid);
        foreach ($data as $key => $value) {
            if (!empty($value['jamdist'])) {
                $data[$key]['jamdist'] = ($value['jamdist']/1000)." km";
            }
            if (!empty($value['jamspeed'])) {
                $data[$key]['jamspeed'] = intval($value['jamspeed']);
            }
        }
        ajax_success($data,NULL);
    }

    /**
     * 获取对应高德事件的操作进展
     * @Author   RaK
     * @DateTime 2017-05-11T15:06:17+0800
     * @return   [type]                   [description]
     */
    public function onLoadHandleProcess(){
        $gaodeeventid = $this->input->post("gaodeeventid");
        $data = $this->Gaodelist->getHandleProcess($gaodeeventid);
        ajax_success($data,NULL);
    }

    /**
     * 导出高德提醒数据
     * @Author   RaK
     * @DateTime 2017-05-16T09:16:59+0800
     * @return   [type]                   [description]
     */
    function getTrafficdataExcel(){
        $keyword = $this->input->post("keyword");
        $status = $this->input->post("status");
        $ppstatus = $this->input->post("ppstatus");
        $ids = $this->input->post("ids");
        $starttime = $this->input->post("starttime");
        $endtime = $this->input->post("endtime");
        $selecttype = $this->input->post("type");//是否为当天数据

        //查询数据库
        $data=$this->Gaodelist->getTrafficdataExcel($status,$starttime,$endtime,$ids,$selecttype,$ppstatus,$keyword);
        ini_set('memory_limit','150M');
        $len=count($data);

        $this->load->database();
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('高德路况');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '高德路况列表');


        $objPHPExcel->getActiveSheet()->getStyle('A:I')->getFont()->setSize(12);

        // for ($i='A';$i != 'P'; $i++){
        //  $objPHPExcel->getActiveSheet()->getColumnDimension($i)->setWidth(20);
        // }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);


        $objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->setWrapText(true);


        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
            array(
                'font' => array (
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                )
            )
        );

        $objPHPExcel->getActiveSheet()->setCellValue('A2', '序号')
            ->setCellValue('B2', '道路名称')
            ->setCellValue('C2', '拥堵区间')
            ->setCellValue('D2', '拥堵距离')
            ->setCellValue('E2', '创建时间')
            ->setCellValue('F2', '拥堵状态')
            ->setCellValue('G2', '处理状态')
            ->setCellValue('H2', '处理时间')
            ->setCellValue('I2', '首次提醒时间');



        for($i=0;$i<count($data);$i++){
            $data[$i]['pubRunStatus'] = "";//状态

            //拥堵距离
            if (!empty($data[$i]['jamdist'])) {
                $data[$i]['jamdist'] = ($data[$i]['jamdist']/1000)." km";
            }
            //时速
            if (!empty($data[$i]['jamspeed'])) {
                $data[$i]['jamspeed'] = intval($data[$i]['jamspeed']);
            }
            //拥堵状态
            if($data[$i]['pubrunstatus']==1){
                $data[$i]['pubRunStatus'] = '拥堵';
            }else if($data[$i]['pubrunstatus']==2){
                $data[$i]['pubRunStatus'] = '趋向严重';
            }else if($data[$i]['pubrunstatus']==3){
                $data[$i]['pubRunStatus'] = '趋向疏通';
            }


            $data[$i]['ydqj'] = "";
            if(!empty($data[$i]['startstationid'])){
                $startmile = $data[$i]['startmile'];
                $endmile = $data[$i]['endmile'];

                $startmile = sprintf("%.3f", $startmile);
                $endmile = sprintf("%.3f", $endmile);
                $newstartmile = "K".str_replace('.','+',$startmile);
                $newendmile = "K".str_replace('.','+',$endmile);
                $data[$i]['ydqj'] = $newstartmile."~".$newendmile."\r\n".$data[$i]['startstationname'].'~'.$data[$i]['endstationname'];
            }



            $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(40);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($i+3), ($i+1),PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.($i+3), $data[$i]['roadname'])
                ->setCellValue('C'.($i+3), $data[$i]['ydqj'])
                ->setCellValue('D'.($i+3), $data[$i]['jamdist'])
                ->setCellValue('E'.($i+3), $data[$i]['inserttime'])
                ->setCellValue('F'.($i+3), $data[$i]['pubRunStatus'])
                ->setCellValue('G'.($i+3), $data[$i]['eventstatusname'])
                ->setCellValue('H'.($i+3), $data[$i]['operatortime'])
                ->setCellValue('I'.($i+3), $data[$i]['sctime']);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $failname=date("Ymd-His") . '-' . rand(100,999);
        $objWriter->save("excel/".$failname.".xlsx");
        $name['name']=$failname.".xlsx";
        ajax_success($name,NULL);
    }


    /**
     * 匹配收费站
     * @Author   RaK
     * @DateTime 2017-05-24T16:22:42+0800
     * @return   [type]                   [description]
     */
    function matchingPoi(){
        $xys = $this->input->post('xys');
        $eventid = $this->input->post('gaodeeventid');
        $roadName = $this->input->post('roadName');
        $url = "http://gst.u-road.com/gstmgr/index.php?/amap/GaoDeSyn/UpdateStartEndInfo";
        $params = array(
            'xys'=>$xys,
            'gaoderoadname'=>$roadName,
            'eventid'=>$eventid
        );
        $data = network_post($url,$params);
        echo $data;
    }

    /**
     * 点击处理按钮先检查当前提醒是否正在处理，没人处理则记录处理人信息
     * @Author   RaK
     * @DateTime 2017-05-26T16:45:38+0800
     * @return   [type]                   [description]
     */
    public function fillInOperator(){
        $eventid = $this->input->post('eventid');
        $selecttype = $this->input->post('selecttype');
        $data = $this->Gaodelist->fillInOperator($eventid,$selecttype);
        if($data){
            ajax_success("",NULL);
        }else{
            ajax_error("该提醒正在处理中！");
        }
    }

    public function recordlog(){
        $eventid = $this->input->post("eventid");
        $username = getsessionempname();
        $data = $this->Gaodelist->recordlog($eventid);
        log_message("INFO","tx--->".$username."：".$eventid);
        ajax_success("",NULL);
    }

    /**
     * 保存图片
     */
    public function imgupload2(){
        $this->load->helper('imgupload');
        $data=imgupload();
        if($data!=false){
            ajax_success($data,NULL);
        }else{
            ajax_error('保存图片失败');
        }
    }
}
