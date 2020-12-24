<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
	<style>
		.m-5{margin-right:5px;}
        .m-10{margin-right:10px;}
        .panel-body{padding: 20px;}
        /*顶部站点地图拥堵情况样式 start*/
        .roadtable-div{width: 100%;height: 173px;padding: 0;overflow: scroll;border: 1px #A6A6A6 solid;overflow-y:hidden;background-color: #B0ADB0;}
        .roadtable{width: auto;height: 100%;}
        .roadtable{background-color: #B0ADB0;}
        .miles{width: auto;height: 10px;}
        .roadtable td[poiname] {
            background-image: url("<?php $this->load->helper('url');echo base_url('/asset/images/bg_station.png') ?>");
            background-position: center center;
            background-repeat: no-repeat;
            color: white;
            background-size: 35px 120px;
            font-size: 24px;
            height: 120px;
            padding-left: 20px;
            padding-right: 20px;
            width: 40px;
            text-align: center;
        }
        .roadtable-td-h{width: 10px;height: 100%;}
        .small-width{width: 59px !important;float: left;}
        .pic_green, .pic_red, .pic_yellow, .pic_gray {
            background-size: contain;
            border: 0px;
            width: 59px;
            display: block;
            height: 36px;
            margin-top: 40px;
        }
        .pic_green {background-image: url("<?php $this->load->helper('url');echo base_url('/asset/images/road_g.png') ?>");}
        .pic_red {background-image: url("<?php $this->load->helper('url');echo base_url('/asset/images/road_r.png') ?>");}
        .pic_yellow {background-image: url("<?php $this->load->helper('url');echo base_url('/asset/images/road_y.png') ?>");}
        .pic_gray {background-image: url("<?php $this->load->helper('url');echo base_url('/asset/images/road_gray.png') ?>");}
        /*顶部站点地图拥堵情况样式 end*/
        /*基础信息*/
        .panel-div-left{float: left;width: 555px;margin: 10px;margin-top: 0;}
        .panel-div-right{float: right;width: 450px;margin: 10px;margin-left: 0;margin-top: 0;}
        .div-base{height: 295px;}
        .filedset-base{width: 100%;height: 100%;border: 2px #A6A6A6 solid;}
        .legend{height: 30px;border: 0;margin: 0;margin-left: 10px;padding: 0px 3px;font-size: 26px;line-height: 30px;}
        .legend-base{width: 110px;}
        .vc_table .content{width: 37% !important;}
        .radio-d-s{margin-right: 3px;}
        /*发布信息*/
        .legend-pushinfo{width: 355px;}
        *{font-size: 15px;}
        .vc_table > tr {height: 40px;line-height: 40px;}
    </style>
    <script type="text/javascript" language="javascript">
        //权限判断,
        //var hasRescue = '<?php echo isset($hasRescue)?$hasRescue:'0' ?>';
        var page = 1;

        var direction_json = <?php echo $alongStation ?>;
        //创建变量,当路段下拉框改变时,将返回的起始站和结束站数据转换成对象后赋值到它们
        //var direction1_jsonObj,direction2_jsonObj;

        //查看详细信息的事件ID
        var eventid = '<?php echo $eventid ?>';
        var roadoldid = '<?php echo isset($roadoldid)?$roadoldid:'1' ?>';
        var directionno = '<?php echo isset($directionno)?$directionno:'1' ?>';
        var roadTrafficColor = '<?php echo isset($roadtrafficcolor)?$roadtrafficcolor:'1008001' ?>';
        var startnodeid = '<?php echo isset($startnodeid)?$startnodeid:'' ?>';
        var endnodeid = '<?php echo isset($endnodeid)?$endnodeid:'' ?>';
        var eventstatus = '<?php echo isset($eventstatus)?$eventstatus:'' ?>';

        $().ready(function(){
            checkRoadTable(roadoldid,directionno);

            if (eventstatus == '1012005') {
                $('#return1').addClass('hidden');
                $('#push').addClass('hidden');
                $('#return2').removeClass('hidden');
                $('#finish').removeClass('hidden');
            }
        });



        /**
         * @desc   获取页面顶部的站点地图
         */
        function checkRoadTable(roadoldid,direction){
            JAjax("admin/MsgPublish/RoadEventLogic", 'onLoadRoadTableMsg', {roadoldid:roadoldid,direction:direction}, function (data) {
                if(data.Success){
                    var station = data.data;
                    var tr1 = '';
                    var tr2 = '';
                    $(".roadtable-tr-1").html('');
                    $(".roadtable-tr-2").html('');
                    
                    //var roadTrafficColor=$('input:radio[name="roadTrafficColor"]:checked').val();
                    for(var i = 0; i < station.length; i++){
                        var miles = station[i]['miles'].toString();
                        var strArry = miles.split(".");
                        if(strArry[1] == undefined){
                            strArry[1] = '000';
                        }

                        if (i == (station.length-1)) {
                            tr1='<td class="miles">&nbsp;K'+strArry[0]+'+'+strArry[1]+'</td><td>&nbsp;</td>';
                            tr2='<td poiname="true">'+station[i]['name']+'</td><td class=" ';
                        }else{
                            tr1='<td class="miles">&nbsp;K'+strArry[0]+'+'+strArry[1]+'</td><td class="small-width">&nbsp;</td>';
                            tr2='<td poiname="true">'+station[i]['name']+'</td><td class="small-width ';
                        }

                        var tdIndexArr = getTdIndex();
                        if (i != station.length-1) {//只要不是最后一个站,添加路段图片
                            if (i>=(tdIndexArr[0]/2) && i<(tdIndexArr[1]/2)) {
                                tr2 += setRoadTrafficColorPic(roadTrafficColor);
                            }else{
                                tr2 += setRoadTrafficColorPic('1008001');
                            }
                        }
                        tr2 += '">&nbsp;</td>';
                        
                        $(".roadtable-tr-1").append(tr1);
                        $(".roadtable-tr-2").append(tr2);
                    }
                }else{
                    ShowMsg('站点地图数据查询出错!');
                }
            }, null);
        }


        /**
         * @desc  根据路段拥堵指数指定对应的路段图片
         * @param string    roadTrafficColor 路段状况单选框的选中结果
         */
        function setRoadTrafficColorPic(roadTrafficColor){
            var thisClass = '';
            if (roadTrafficColor == '1008004') {//中断
                thisClass = 'pic_gray';
            }else if (roadTrafficColor == '1008002') {//缓慢
                thisClass = 'pic_yellow';
            }else if (roadTrafficColor == '1008003') {//拥堵
                thisClass = 'pic_red';
            }else{//默认顺畅
                thisClass = 'pic_green';
            }
            return thisClass;
        }


        /**
         * @desc   根据起始站和结束站的选择内容,获取其选择内容在roadtable中的td位置
         * @return array    描述td位置的数组,包括起始站和结束站
         */
        function getTdIndex(){direction_json
            var startTdIndex,endTdIndex;//用于记录当前选择站点的数组下标index

            $.each(direction_json, function(index, value) {
                if (value.poiid == startnodeid) {
                    //记录下拉框选择状态的当前index,乘以2就是站点对应站点地图的td排序
                    startTdIndex = index*2;
                }
                if (value.poiid == endnodeid) {
                    endTdIndex = index*2;
                }
                //alert(index + ':' + value.poiid+ '=>' +value.name);
            });

            var tdIndexArr = new Array();
            tdIndexArr[0] = startTdIndex;
            tdIndexArr[1] = endTdIndex;
            return tdIndexArr;
        }
        


        /**
         * @desc   发布内容(修改)
         */
        function changeStatus(status){

            JAjax("admin/MsgPublish/EventCheckLogic", 'changeStatus', {eventid:eventid,status:status,eventstatus:eventstatus}, function (data) {

                if (data.Success)
                    closeLayerPageJs();
                else
                    ShowMsg(data.Message);
            }, null);
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="roadtable-div">
                <table align="center" class="roadtable" id="roadtable">
                    <tbody>
                        <tr class="roadtable-tr-1"></tr>
                        <tr class="roadtable-tr-2"></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-body">
            <div class="panel-div-left div-base">
                <fieldset class="filedset-base">
                    <legend class="legend legend-base">基础信息</legend>
                    <table cellspacing="1" cellpadding="4" class="vc_table" style="margin: 0px 10px;" >
                        <tr>
                            <td class="name" nowrap="nowrap">高速公路:</td>
                            <td class="content">
                                <?php echo isset($roadname)?$roadname:'' ?>
                            </td>
                            <td class="name" nowrap="nowrap">发生时间:</td>
                            <td class="content">
                                <?php echo isset($occtime)?$occtime:'' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" nowrap="nowrap">预计结束时间:</td>
                            <td class="content">
                                <?php echo isset($planovertime)?$planovertime:'' ?>
                            </td>
                            <td class="name" nowrap="nowrap">实际结束时间:</td>
                            <td class="content">
                                <?php echo isset($realovertime)?$realovertime:'' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" nowrap="nowrap">大类型:</td>
                            <td class="content">
                                <?php echo isset($eventTypeName)?$eventTypeName:'' ?>
                            </td>
                            <td class="name" nowrap="nowrap">子类型:</td>
                            <td class="content">
                                <?php echo isset($eventCauseName)?$eventCauseName:'' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" nowrap="nowrap">行车方向:</td>
                            <td class="content">
                                <?php echo isset($directionname)?$directionname:'' ?>
                            </td>
                            <td class="name" nowrap="nowrap">交通状况:</td>
                            <td class="content">
                                <?php echo isset($roadTrafficName)?$roadTrafficName:'' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" nowrap="nowrap">开始站:</td>
                            <td class="content">
                                <?php echo isset($startnodename)?$startnodename:'' ?>
                            </td>
                            <td class="name" nowrap="nowrap">结束站:</td>
                            <td class="content">
                                <?php echo isset($endnodename)?$endnodename:'' ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <div class="panel-div-right div-base">
                <fieldset class="filedset-base">
                    <legend class="legend legend-pushinfo" id="legendPushinfo">发布信息
                        <button class="btn btn-primary" id="push" onclick="changeStatus(1012004)">确认发布</button>
                        <button class="btn btn-warning" id="return1" onclick="changeStatus(1012003)">打回</button>
                        <button class="btn btn-warning hidden" id="return2" onclick="changeStatus(1012004)">打回</button>
                        <button class="btn btn-primary hidden" id="finish" onclick="changeStatus(1012006)">确认结束</button>
                        <button class="btn btn-danger" onclick="closeLayerPageJs();">关闭</button>
                    </legend>
                    <div style="padding: 15px;"><?php echo isset($reportout)?$reportout:'' ?></div>
                </fieldset>
            </div>
        </div>
    </div>
</body>
</html>