<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 高德路况简图
 */
class ShowGaoDeSPubStatus extends CI_Controller {

    /**
     * 根据eventid获取对应的事件数据
     * @Author   RaK
     * @DateTime 2017-05-18T13:53:50+0800
     * @return   [type]                   [description]
     */
    public function index()
    {
        $gaodeeventid = $this->input->get('gaodeeventid');
        $selecttype = $this->input->get('selecttype');
        if(empty($gaodeeventid)){
           echo "ID不能为空！";
           return false;
        }
        $tablename = "amap_traffic";//当天表
        if($selecttype==2){
            $tablename = "amap_traffic_history";//历史表
        }
        $sql = "select a.roadid,a.direction,b.newcode,a.startstack,a.endstack,a.pubrunstatus,a.direction from $tablename a
                JOIN gde_roadold b on a.roadid=b.roadoldid
                where a.eventid=?";
        $remark = $this->db->query($sql,array($gaodeeventid))->row_array();
        $remark = '{"roadoldid":"'.$remark['roadid'].'","dir":"'.$remark['direction'].'","traffic":[{"code":"'.$remark['newcode'].'","startmile":"'.$remark['startstack'].'","endmile":"'.$remark['endstack'].'","pubRunStatus":"'.$remark['pubrunstatus'].'","direction":"'.$remark['direction'].'"}]}';
        $remarkarr = json_decode($remark,true);
        $this->getStatusHtmlWithTrafficObj2($remark);

    }

    /**
     * 生成简图
     * @Author   RaK
     * @DateTime 2017-05-18T13:54:31+0800
     * @param    [type]                   $remark [description]
     * @return   [type]                           [description]
     */
    public function getStatusHtmlWithTrafficObj2($remark){
        $remarkarr = json_decode($remark,true);
        $roadoldid = $remarkarr['roadoldid'];

        $sql = "SELECT  c.name,c.pointtype, c.miles,d.newcode  FROM gde_roadlinestation a   inner  join gde_roadpoi c on a.stationid=c.poiid JOIN gde_roadold d ON c.roadoldid = d.roadoldid WHERE a.roadlineid=? and c.pointtype in ('1002001','1002002','1002003')  and a.direction=1 order by a.seq ";
        $data = $this->db->query($sql,array($roadoldid))->result_array();

        if(empty($data)){
            echo "获取数据异常";
            return false;
        }

        $currentNewCodeCheck = "";
        $currentBeenAddCodeList = array();
        $stationList = array();
        for ($i=1; $i <=count($data); $i++) {
            $dr = $data[$i-1];
            $name = $dr['name'];
            $pointtype = $dr['pointtype'];
            $miles = $dr['miles'];
            $newcode = $dr['newcode'];
            if(empty($currentNewCodeCheck)){
                $currentNewCodeCheck = $newcode;
                array_push($currentBeenAddCodeList, $currentNewCodeCheck);
            }else{
                if($currentNewCodeCheck != $newcode){
                    $currentNewCodeCheck = $newcode;
                    array_push($currentBeenAddCodeList, $currentNewCodeCheck);
                }
            }


            $StationEty= array();
            $StationEty['name'] = $name;
            $StationEty['mile'] = round($miles,2);
            $StationEty['pointtype'] = $pointtype;
            $StationEty['newcode'] = $newcode;
            $stationList[$i-1]=$StationEty;
        }


        $stationCount = count($data);

        $roadLenCount = $stationCount - 1;


        $stationRoadLen = 140;
        $stationRoadHei = 14;
        $canvasHei = 170;
        $leftRightMargin = 100;
        $stationDrawWidth = 120;
        $stationDrawHeight = 38;
        $stationArcLen = 4;
        $diffTopModify = 0;
        $stationBottomParam = 56;
        $textHei = 20;
        $mergeHei = $stationRoadHei * 2;
        $canvasWith = ($roadLenCount * $stationRoadLen) + $leftRightMargin * 2;
        $topInit = ($canvasHei - $mergeHei) / 2;
        $sb = "";
        $sb.="<!doctype html>";
        $sb.="<html id='kl'>";
        $sb.="<head>";
        $sb.="<meta charset=\"UTF-8\">";
        $sb.="</head>";
        $sb.="<style type=\"text/css\">";
        $sb.="canvas{}";
        $sb.="#kl{}";
        $sb.="</style>";
        $sb.='<script src="https://gst.u-road.com/gstmgr/asset/js/jquery-2.1.1.min.js"></script>';
        $sb.='<script src="https://gst.u-road.com/gstmgr/asset/js/jquery.nicescroll.js"></script>';
        $sb.="<script type=\"text/javascript\">";
        $sb.=" function $$(id){";
        $sb.="return document.getElementById(id);";
        $sb.="}";
        $sb.=" function pageLoad(){";
        $sb.="var can = $$('can');";
        $sb.="  var cans = can.getContext('2d');";
        $sb.="cans.fillStyle = '#00FF00';";
        $sb.="cans.fillRect(" . $leftRightMargin . "," . ($topInit + $diffTopModify) . "," . ($roadLenCount * $stationRoadLen) . "," . $mergeHei . ");";
        $sb.="cans.fillStyle = \"black\";";
        $sb.="cans.font = \"bold 15px sans-serif\";";
        $sb.="cans.textBaseline = 'top';";

        $currentDrawDirection1Count = 0;//正向
        $currentDrawDirection2Count = 0;//反向

        $traffic = $remarkarr['traffic'];
// var_dump($traffic);
        // for ($i = 1; $i <= count($traffic); $i++){

        //     $startmile = $traffic[$i - 1]["startmile"];
        //     $endmile = $traffic[$i - 1]["endmile"];
        //     $code = $traffic[$i - 1]["code"];
        //     $successStart = false;
        //     $successEnd = false;
        //     $startmileValue = round($startmile,2);
        //     $endmileValue = round($endmile,2);

        //     $oStart = $this->getOccupy2($stationList, $startmileValue, $code,$successStart);
        //     $oEnd = $this->getOccupy2($stationList, $endmileValue, $code,$successEnd);
        //     // var_dump($oStart);
        //     //$oEnd > $oStart
        //     $oStart = empty($oStart)?1:$oStart;
        //     $oEnd = empty($oEnd)?1:$oEnd;
        //     if ($traffic[$i - 1]["direction"]==0)// 正向
        //     {
        //         $currentDrawDirection1Count += 1;
        //     }
        //     else
        //     {
        //         $currentDrawDirection2Count += 1;
        //     }

        // }

        for ($i = 1; $i <= count($traffic); $i++)
            {
                $data0 = $traffic[$i - 1];

                $startmile = $data0["startmile"];
                $endmile = $data0["endmile"];
                $code = $data0["code"];

                $pubRunStatus = $data0["pubRunStatus"];

                $startmileValue = (float)round($startmile,2);
                $endmileValue = (float)round($endmile,2);

                $successStart = false;
                $successEnd = false;
                // var_dump($stationList);
                $oStart = $this->getOccupy2($stationList,$startmileValue,$code,$successStart);
                $oEnd = $this->getOccupy2($stationList,$endmileValue,$code,$successEnd);
                $oStart = empty($oStart)?1:$oStart;
                $oEnd = empty($oEnd)?1:$oEnd;
                // var_dump($endmileValue);
                // var_dump($oEnd);
                //不懂~
                // if (!($successStart && $successEnd))
                // {
                //     continue;
                // }


                $omid = ($oStart + $oEnd) / 2;


                if ($data0['direction']==0)// 正向
                {

                    $left = $oStart * ($roadLenCount * $stationRoadLen) + $leftRightMargin;
                    $right = $oEnd * ($roadLenCount * $stationRoadLen) + $leftRightMargin;
                    $mid = ($left + $right) / 2;

                    $drawlen = $right - $left;

                    if ($pubRunStatus == "1")
                    {
                        $sb.="cans.fillStyle = 'red';";
                    }
                    else
                    {
                        $sb.="cans.fillStyle = '#811B27';";
                    }

                    $sb.="cans.fillRect(" . ($left) . "," . ($canvasHei / 2 + $diffTopModify) . " ," . ($drawlen) . "," . ($stationRoadHei) . ");";

                    $midValue = ($startmileValue + $endmileValue) / 2;


                    if ($currentDrawDirection1Count <= 1)
                    {
                       $sb.="cans.fillStyle = \"black\";";
                       $sb.="cans.font = \"bold 15px sans-serif\";";
                       $sb.="cans.textBaseline = 'top';";

                        $sb.="cans.fillText('".$this->convertMileShowText($midValue) . "', " ."(" . $mid . "-" . " (cans.measureText('" . $this->convertMileShowText($midValue) . "').width" . "/2" . "))" . ", " . ($canvasHei / 2 + $stationRoadHei + 4 + $diffTopModify) . ");";


                    }


                }
                else// 反向
                {
                    $left = $oEnd * ($roadLenCount * $stationRoadLen) + $leftRightMargin;
                    $right = $oStart * ($roadLenCount * $stationRoadLen) + $leftRightMargin;
                    $mid = ($left + $right) / 2.0;
                    $drawlen = $right - $left;

                    if ($pubRunStatus == "1")
                    {

                        $sb.="cans.fillStyle = 'red';";
                    }
                    else
                    {
                        $sb.="cans.fillStyle = '#811B27';";
                    }

                    $sb.="cans.fillRect(" . ($left) . "," . ($canvasHei / 2 - $stationRoadHei + $diffTopModify) . " ," . ($drawlen) . "," . ($stationRoadHei) . ");";

                    $midValue = ($startmileValue + $endmileValue) / 2.0;

                    if ($currentDrawDirection2Count <= 1)
                    {
                        $sb.="cans.fillStyle = \"black\";";
                        $sb.="cans.font = \"bold 15px sans-serif\";";
                        $sb.="cans.textBaseline = 'top';";
                        $sb.="cans.fillText('" . $this->convertMileShowText($midValue) . "', " . "(" . $mid . " - cans.measureText('" . $this->convertMileShowText($midValue) . "').width / 2)" . ", " . ($canvasHei / 2 - $stationRoadHei - $textHei + 0 + $diffTopModify) . ");";
                    }

                }

            }

            for ($i = 1; $i <= count($stationList); $i++)
            {


                $ety = $stationList[$i - 1];
                $name = $ety['name'];

                $indx = $i - 1;

                $left = $stationRoadLen * $indx + $leftRightMargin;
                $top = ($canvasHei - $stationRoadHei * 2) / 2.0;
                $bottom = $canvasHei / 2 + $stationRoadHei;

                $currentTargetTop = $top + 1;
                $step = 0;
                $dashLen = 2;
                $drawLimitLen = 10;

                $targetLimitBottom = $bottom;
                while (!($currentTargetTop >= $targetLimitBottom))
                {
                    $targetTop = $currentTargetTop + $dashLen;
                    if ($targetTop >= $targetLimitBottom)
                    {
                        $targetTop = $bottom;
                    }
                    if ($step == 0)
                    {
                        $sb.="cans.moveTo(" . ($left) . "," . ($currentTargetTop + $diffTopModify) . ");";

                        $step = 1;
                    }
                    else
                    {
                        $sb.="cans.lineTo(" . ($left) . "," . ($currentTargetTop + $diffTopModify) . ");";
                        $sb.="cans.stroke();";

                        $step = 0;
                    }
                    $currentTargetTop = $targetTop;
                }
            }


            for ($i = 1; $i <= count($stationList); $i++) //绘制圆角矩形
            {

                $ety = $stationList[$i - 1];
                $name = $ety['name'];

                $indx = $i - 1;

                $left = $stationRoadLen * $indx - $stationDrawWidth / 2.0 + $leftRightMargin;
                $top = ($canvasHei - $stationDrawHeight) / 2.0;

                //  $sb.="cans.lineWidth=1;");
                $sb.="cans.fillStyle = 'white';";

                $sb.="cans.moveTo(" . ($left + $stationArcLen) . "," . ($top + $diffTopModify + $stationBottomParam) . ");";
                $sb.="cans.lineTo(" . ($left + $stationDrawWidth - $stationArcLen) . "," . ($top + $diffTopModify + $stationBottomParam) . ");";
                $sb.="cans.arcTo(" . ($left + $stationDrawWidth) . "," . ($top + $diffTopModify + $stationBottomParam) . "," . ($left + $stationDrawWidth) . "," . ($top + $stationArcLen + $diffTopModify + $stationBottomParam) . "," . $stationArcLen . ");";
                $sb.="cans.lineTo(" . ($left + $stationDrawWidth) . "," . ($top + $stationDrawHeight - $stationArcLen + $diffTopModify + $stationBottomParam) . ");";
                $sb.="cans.arcTo(" . ($left + $stationDrawWidth) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam) . "," . ($left + $stationDrawWidth - $stationArcLen) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam) . "," . $stationArcLen . ");";
                $sb.="cans.lineTo(" . ($left + $stationArcLen) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam) . ");";
                $sb.="cans.arcTo(" . ($left) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam) . "," . ($left) . "," . ($top + $stationDrawHeight - $stationArcLen + $diffTopModify + $stationBottomParam) . "," . $stationArcLen . ");";
                $sb.="cans.lineTo(" . ($left) . "," . ($top + $stationArcLen + $diffTopModify + $stationBottomParam) . ");";
                $sb.="cans.arcTo(" . ($left) . "," . ($top + $diffTopModify + $stationBottomParam) . "," . ($left + $stationArcLen) . "," . ($top + $diffTopModify + $stationBottomParam) . "," . $stationArcLen . ");";

                //  $sb.="cans.stroke();");
                $sb.="cans.fill();";


                $diff2 = (-$stationBottomParam * 2);



                $sb.="cans.moveTo(" . ($left + $stationArcLen) . "," . ($top + $diffTopModify + $stationBottomParam + $diff2) . ");";
                $sb.="cans.lineTo(" . ($left + $stationDrawWidth - $stationArcLen) . "," . ($top + $diffTopModify + $stationBottomParam + $diff2) . ");";
                $sb.="cans.arcTo(" . ($left + $stationDrawWidth) . "," . ($top + $diffTopModify + $stationBottomParam + $diff2) . "," . ($left + $stationDrawWidth) . "," . ($top + $stationArcLen + $diffTopModify + $stationBottomParam + $diff2) . "," . $stationArcLen . ");";
                $sb.="cans.lineTo(" . ($left + $stationDrawWidth) . "," . ($top + $stationDrawHeight - $stationArcLen + $diffTopModify + $stationBottomParam + $diff2) . ");";
                $sb.="cans.arcTo(" . ($left + $stationDrawWidth) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam + $diff2) . "," . ($left + $stationDrawWidth - $stationArcLen) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam + $diff2) . "," . $stationArcLen . ");";
                $sb.="cans.lineTo(" . ($left + $stationArcLen) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam + $diff2) . ");";
                $sb.="cans.arcTo(" . ($left) . "," . ($top + $stationDrawHeight + $diffTopModify + $stationBottomParam + $diff2) . "," . ($left) . "," . ($top + $stationDrawHeight - $stationArcLen + $diffTopModify + $stationBottomParam + $diff2) . "," . $stationArcLen . ");";
                $sb.="cans.lineTo(" . ($left) . "," . ($top + $stationArcLen + $diffTopModify + $stationBottomParam + $diff2) . ");";
                $sb.="cans.arcTo(" . ($left) . "," . ($top + $diffTopModify + $stationBottomParam + $diff2) . "," . ($left + $stationArcLen) . "," . ($top + $diffTopModify + $stationBottomParam + $diff2) . "," . $stationArcLen . ");";

                //  $sb.="cans.stroke();");
                $sb.="cans.fill();";

                if ($i != count($stationList)) 
                {

                    $arrTipCenterX = $left + $stationDrawWidth / 2.0 + $stationRoadLen / 2.0;
                    $arrTipCenterY = $top + $stationDrawHeight / 2.0 + $stationBottomParam;

                    $diffLen = 6;
                    $arrowHinner = 4;

                    $sb.="cans.strokeStyle = 'black';";
                    $sb.="cans.lineWidth=1;";

                    $sb.="cans.moveTo(" . ($arrTipCenterX - $diffLen) . ", " . ($arrTipCenterY) . ");";
                    $sb.="cans.lineTo(" . ($arrTipCenterX + $diffLen) . ", " . ($arrTipCenterY) . ");";
                    $sb.="cans.stroke();";


                    $sb.="cans.moveTo(" . ($arrTipCenterX + $diffLen - $arrowHinner) . ", " . ($arrTipCenterY - $arrowHinner) . ");";
                    $sb.="cans.lineTo(" . ($arrTipCenterX + $diffLen) . ", " . ($arrTipCenterY) . ");";
                    $sb.="cans.lineTo(" . ($arrTipCenterX + $diffLen - $arrowHinner) . ", " . ($arrTipCenterY + $arrowHinner) . ");";
                    $sb.="cans.stroke();";



                    $arrTipCenterY += $diff2;

                    $sb.="cans.moveTo(" . ($arrTipCenterX - $diffLen) . ", " . ($arrTipCenterY) . ");";
                    $sb.="cans.lineTo(" . ($arrTipCenterX + $diffLen) . ", " . ($arrTipCenterY) . ");";
                    $sb.="cans.stroke();";

                    $sb.="cans.moveTo(" . ($arrTipCenterX - $diffLen + $arrowHinner) . ", " . ($arrTipCenterY - $arrowHinner) . ");";
                    $sb.="cans.lineTo(" . ($arrTipCenterX - $diffLen) . ", " . ($arrTipCenterY) . ");";
                    $sb.="cans.lineTo(" . ($arrTipCenterX - $diffLen + $arrowHinner) . ", " . ($arrTipCenterY + $arrowHinner) . ");";
                    $sb.="cans.stroke();";
                }
            }

            $sb.="cans.fillStyle = \"black\";";
            $sb.="cans.font = \"bold 13px sans-serif\";";
            $sb.="cans.textBaseline = 'top';";

            for ($i = 1; $i <= count($stationList); $i++)
            {
                // if (i == 1 || i == stationList.Count) { continue; }

                $ety = $stationList[$i - 1];
                $name = $ety['name'];
                $mile = $ety['mile'];
                $mileString = $this->convertMileShowText($mile);

                $mileString = "(" . $ety['newcode'] . ")" . $mileString;
                $indx = $i - 1;

                $left = $stationRoadLen * $indx - $stationDrawWidth / 2.0 + $leftRightMargin;
                $top = ($canvasHei - $stationDrawHeight) / 2.0;


                $topDiff = 4;
                $data0['direction'];

                $sb.="cans.fillText('" . $name . "',(" . $left . "+  (" . $stationDrawWidth . "- cans.measureText('" . $name . "').width )/2) ," . ($top + $topDiff + $diffTopModify + $stationBottomParam) . "," . ($stationDrawWidth) . ");";
                $sb.="cans.fillText('" . $mileString . "',(" . $left . "+  (" . $stationDrawWidth . "- cans.measureText('" . $mileString . "').width )/2) ," . ($top + $topDiff + $textHei - 4 + $diffTopModify + $stationBottomParam) . "," . ($top + $textHei + $topDiff) . "," . ($stationDrawWidth) . ");";

                $diff2 = (-$stationBottomParam * 2);


                $sb.="cans.fillText('" . $name . "',(" . $left . "+  (" . $stationDrawWidth . "- cans.measureText('" . $name . "').width )/2) ," . ($top + $topDiff + $diffTopModify + $stationBottomParam + $diff2) . "," . ($stationDrawWidth) . ");";
                $sb.="cans.fillText('" . $mileString . "',(" . $left . "+  (" . $stationDrawWidth . "- cans.measureText('" . $mileString . "').width )/2) ," . ($top + $topDiff + $textHei - 4 + $diffTopModify + $stationBottomParam + $diff2) . "," . ($top + $textHei + $topDiff) . "," . ($stationDrawWidth) . ");";

            }

            $directionTop = ($topInit - $textHei + 16);
            $arrowLen = 15;
            $arrowH = 6;
            $insertArrowLen = 10;
            //    $sb.="cans.fillStyle = 'gray';");
            $sb.="cans.fillRect(" . ($leftRightMargin) . "," . ($canvasHei / 2 + $diffTopModify) . "," . (($roadLenCount * $stationRoadLen)) . "," . 1 . ");";


            $sb.="cans.fillStyle = 'black';";
            $sb.="cans.fillRect(" . ($leftRightMargin - $arrowLen) . "," . ($directionTop + $diffTopModify) . "," . (($roadLenCount * $stationRoadLen) + $arrowLen) . "," . 1 . ");";
            $sb.="cans.fillRect(" . ($leftRightMargin) . "," . ($canvasHei - $directionTop + $diffTopModify) . "," . (($roadLenCount * $stationRoadLen) + $arrowLen) . "," . 1 . ");";


            $sb.="cans.strokeStyle = 'black';";
            $sb.="cans.lineWidth=1;";




            $sb.="cans.moveTo(" . ($leftRightMargin + ($roadLenCount * $stationRoadLen) + $insertArrowLen) . ", " . (($canvasHei - $directionTop) - $arrowH + $diffTopModify) . ");";
            $sb.="cans.lineTo(" . ($leftRightMargin + ($roadLenCount * $stationRoadLen) + $arrowLen + $insertArrowLen) . ", " . (($canvasHei - $directionTop + $diffTopModify)) . ");";
            $sb.="cans.lineTo(" . ($leftRightMargin + ($roadLenCount * $stationRoadLen) + $insertArrowLen) . ", " . (($canvasHei - $directionTop) + $arrowH + $diffTopModify) . ");";
            $sb.="cans.stroke();";


            $sb.="cans.moveTo(" . ($leftRightMargin - $insertArrowLen) . ", " . (($directionTop) - $arrowH + $diffTopModify) . ");";
            $sb.="cans.lineTo(" . ($leftRightMargin - $arrowLen - $insertArrowLen) . ", " . (($directionTop) + $diffTopModify) . ");";
            $sb.="cans.lineTo(90, 73);";
            $sb.="cans.stroke();";



            $sb.="}";
            $sb.="   </script>";
            $sb.="<body onload=\"pageLoad();\">";
            $sb.="<canvas id=\"can\" width=\"" . $canvasWith . "px\" height=\"" . $canvasHei . "px\"></canvas>";
            $sb.=" </body>";
            $sb.="</html>";
            $sb.="<script type=\"text/javascript\">";
            $sb.=' $("#kl").niceScroll({cursorcolor: "#ccc",cursoropacitymax: 1,touchbehavior: false,cursorwidth: "8px",cursorborder: "0",cursorborderradius: "5px",autohidemode: true});';
            $sb.="</script>";
            echo $sb;
    }

    public function convertMileShowText($showtxt) {
        $mile = 0;
        try
        {
            $mile = round($showtxt,2);
        }catch(Exception $e) { }
        $intValue = (int)$mile;

        $m = $mile - $intValue;
        $mm = $m * 1000;
        $mmint = 0;
        try{
            $mmint = (int)$mm;
        }catch(Exception $e) { }
         

        $mmintString = $mmint;

        $mmintStringLen = strlen($mmintString);

        $len = 3;

        $loopLen = $len - $mmintStringLen;
        for ($i = 1; $i <= $loopLen; $i++) 
        {
            $mmintString = "0".$mmintString;
        }


        $mergeTxt = "K".$intValue."+".$mmintString;
        return $mergeTxt;
    }


    public function getOccupy2($stationList,$mile,$code,$successDraw)
        {


            $LenCount = count($stationList) - 1;
            $per = 1 / $LenCount;

            $occupy = 0;
            // var_dump($mile);
            $getPreMile = -1;
            $beenMatch = false;

            $newCodeIndxDic = array();

            $preEtyTemp = null;

            $directionTowrad = false;
            $hasMakeDirection = false;
            $directionTempMile = -1;
            $beenGetFirst = false;

            for ($i = 1; $i <= count($stationList); $i++)
            {
                $e = $stationList[$i - 1];
                $stationCode = $e['newcode'];

                if ($stationCode != $code)
                {
                    continue;
                }

                $milethis = $e['mile'];
                if (!$beenGetFirst)
                {
                    $directionTempMile = $milethis;
                    $beenGetFirst = true;
                }
                else
                {
                    if ($milethis >= $directionTempMile)
                    {
                        $directionTowrad = true;
                        $hasMakeDirection = true;
                        break;
                    }
                    else if ($milethis <= $directionTempMile)
                    {
                        $directionTowrad = false;
                        $hasMakeDirection = true;
                        break;
                    }
                    $directionTempMile = $milethis;
                }

            }

            if (!$hasMakeDirection)
            {
                $successDraw = false;
                return 0;
            }
                


            for ($i = 1; $i <= count($stationList); $i++) 
            {
                $e = $stationList[$i - 1];
                $newCode = $e['newcode'];
                if ($preEtyTemp == null)
                {
                    $preEtyTemp = $e;
                }
                else
                {
                    if ($preEtyTemp['newcode'] != $newCode) 
                    {
                        //不知道是什么~~
                        // $newCodeIndxDic[$preEtyTemp['newcode']] = $stationList.IndexOf($preEtyTemp);
                    }
                    $preEtyTemp = $e;
                }
            }
            // $last = $stationList[0];

            $newCodeIndxDic[$code] = count($stationList) - 1;


                    // var_dump($stationList);
            for ($i = 1; $i <= count($stationList); $i++)
            {
                $e = $stationList[$i - 1];
                $stationCode = $e['newcode'];
                if ($stationCode != $code) 
                {
                    continue;
                }

                $stationmile = (float)$e['mile'];

                if ($directionTowrad)//递增
                {

                    if ($mile >= $stationmile)
                    {
                        // var_dump($mile.">=".$stationmile);
                        // var_dump(1);
                        $getPreMile = $stationmile;
                    }
                    else
                    {

                         // var_dump(2);
                        if ($getPreMile == -1)
                        {
                            $indx = $i - 1;
                            $occindx = $indx - 1;

                            $occupy = $per * $occindx + $per * 1;
                            $beenMatch = true;
                            break;
                        }
                        else
                        {


                            // var_dump($stationmile);
                            $stationDiff = $stationmile - $getPreMile;
                            $d1 = $mile - $getPreMile+0.05;
                            $o1 = $d1 / $stationDiff;

                            $indx = $i - 1;
                            $occindx = $indx - 1;

                            $occupy = $per * $occindx + $per * $o1;

                            $beenMatch = true;
                            break;
                        }

                    }
                }
                else //递减
                {

                    if ($mile <= $stationmile)
                    {
                        $getPreMile = $stationmile;
                    }
                    else
                    {
                        if ($getPreMile == -1)
                        {
                            $indx = $i - 1;
                            $occindx = $indx - 1;

                            $occupy = $per * $occindx + $per * 1;
                            $beenMatch = true;
                            break;
                        }
                        else
                        {
                            $stationDiff = $getPreMile - $stationmile;
                            $d1 = -($mile - $getPreMile);

                            $o1 = $d1 / $stationDiff;

                            $indx = $i - 1;
                            $occindx = $indx - 1;

                            $occupy = $per * $occindx + $per * $o1;
                            $beenMatch = true;
                            break;
                        }
                    }
                
                }

     
            }

            if (!$beenMatch)
            {
                $indxtemp = $newCodeIndxDic[$code];
                $occupy = $per * $indxtemp;
            }

            $successDraw = true;
            return $occupy;
        }


}