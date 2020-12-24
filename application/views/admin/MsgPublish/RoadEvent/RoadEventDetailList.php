<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
        .m-20{margin-right:20px;}
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
        .panel-div-left{float: left;width: 59%;margin: 10px;margin-top: 0;}
        .panel-div-right{float: right;width: 38%;margin: 10px;margin-left: 0;margin-top: 0;}
        .div-base{height: 400px;}
        .filedset-base{width: 100%;height: 100%;border: 2px #A6A6A6 solid;}
        .legend{height: 30px;border: 0;margin: 0;margin-left: 10px;padding: 0px 3px;font-size: 26px;line-height: 30px;}
        .legend-base{width: 110px;}
        .td-name{width: 15%;text-align: right;padding-right: 5px;}
        .td-content{width: 35%;}
        .radio-d-s{margin-right: 3px;}
        .trafficname{/* width: 28px; */height: 21px;float: left;margin:0 auto;}
        .background-green{background-color: #008000;color: white;}
        .background-yellow{background-color: #FFFF00;}
        .background-red{background-color: #FF0000;color: white;}
        .background-gray{background-color: #E3E3E3;}
        .background-lightred{background-color: #F44E4E;color: white;}
        .background-crimson{background-color: #CE1B22;color: white;}
        /*发布信息*/
        /* .div-pushinfo{float: right;width: 480px;height: 370px;margin: 10px;} */
        .legend-pushinfo{width: 315px;}
        .small-button{height: 30px;border: 0;border-radius: 3px;padding: 2px 12px;transition:all 0.2s ease-out 0s;font-size:16px;margin-right: 5px;}
	</style>
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
                    <table cellspacing="1" cellpadding="4" class="vc_table" style="width: 98%;">
                        <tr>
                            <!-- <td class="td-name" nowrap="nowrap">管理处:</td>
                            <td class="td-content">
                                <select class="form-control" id="roadperSel" onchange="changeRoadper(this.value);">
                                    <option value="">全部</option>
                                    <?php foreach($roadper as $item): ?>
                                        <option value="<?=$item['id']?>"><?=$item['name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td> -->
                            <td class="td-name" nowrap="nowrap">高速公路:</td>
                            <td class="td-content" colspan="3">
                                <select class="form-control" id="roadSel" onchange="changeRoad(this.value);">
                                    <option value="">请选择高速公路</option>
                                    <?php foreach($roadSel as $item): ?>
                                        <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-name" nowrap="nowrap">发生时间:</td>
                            <td class="td-content">
                                <input type="text" class="form-control" id="occtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php if(isset($data[0]['occtime'])){echo $data[0]['occtime'];}?>" />
                            </td>
                            <td class="td-name" nowrap="nowrap">预计结束时间:</td>
                            <td class="td-content">
                                <input type="text" class="form-control" id="planovertime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php if(isset($data[0]['planovertime'])){echo $data[0]['planovertime'];}?>"/>
                            </td>
                        </tr>
                        <tr id="finishTimeTr" class="hidden">
                            <td class="td-name" nowrap="nowrap">持续时长:</td>
                            <td class="td-content">
                                <?php echo isset($duration)?$duration:''; ?>
                            </td>
                            <td class="td-name" nowrap="nowrap">实际结束时间:</td>
                            <td class="td-content">
                                <?php echo isset($data[0]['realovertime'])?$data[0]['realovertime']:''; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-name" nowrap="nowrap">大类型:</td>
                            <td class="td-content">
                                <select class="form-control" id="eventTypeSel" onchange="changeEventType(this.value)">
                                    <option value="<?php echo $data[0]['eventtype'] ?>"><?php echo $data[0]['eventTypeName'] ?></option>
                                </select>
                            </td>
                            <td class="td-name" nowrap="nowrap">子类型:</td>
                            <td class="td-content">
                                <select class="form-control" id="eventCauseSel" onchange="">
                                    <?php foreach($road as $item): ?>
                                        <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-name" nowrap="nowrap">行车方向:</td>
                            <td class="td-content">
                                <div style="width: 100%">
                                    <label>
                                        <span class="radio-d-s" id="direction1">
                                            <input type="radio" name="direction" value="1" onclick="changeDirectionRadio(this.value)" text="<?php if(isset($data[0]['direction1'])){echo $data[0]['direction1'];}?>" /><?php if(isset($data[0]['direction1'])){echo $data[0]['direction1'];}?>
                                        </span>
                                    </label>
                                    <label>
                                        <span class="radio-d-s" id="direction2">
                                            <input type="radio" name="direction" value="2" onclick="changeDirectionRadio(this.value)" text="<?php if(isset($data[0]['direction2'])){echo $data[0]['direction2'];}?>" /><?php if(isset($data[0]['direction2'])){echo $data[0]['direction2'];}?>
                                        </span>
                                    </label>
                                    <label>
                                        <span class="radio-d-s" id="direction3">
                                            <input type="radio" name="direction" value="0" onclick="changeDirectionRadio(this.value)" text="双向" />双向
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="td-name" nowrap="nowrap">交通状况:</td>
                            <td class="td-content">
                                <div style="width: 100%;" id="trafficcolor-div"><!-- height: 21px; -->
                                    <?php foreach($roadTrafficColor as $item): ?>
                                        <span class="radio-d-s">
                                            <label>
                                                <input type="radio" name="roadTrafficColor" text="<?php echo $item['name'] ?>" value="<?php echo $item['dictcode'] ?>" onclick="changeRoadTable()" style="float: left" />
                                                <p class="trafficname" text="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></p>
                                            </label>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-name" nowrap="nowrap">开始站:</td>
                            <td class="td-content">
                                <select class="form-control" id="startStationSel" onchange="changeRoadTable()">
                                </select>
                            </td>
                            <td class="td-name" nowrap="nowrap">结束站:</td>
                            <td class="td-content">
                                <select class="form-control" id="endStationSel" onchange="changeRoadTable()">
                                </select>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
            <!--********************发布信息********************-->
            <div class="panel-div-right div-base">
                <fieldset class="filedset-base">
                    <legend class="legend legend-pushinfo" id="legendPushinfo">发布信息
                        <!-- <input type="button" class="small-button btn-success" style="margin-left: 5px;" onclick="setInfoToPush();" value="生成事件内容"></input> -->
                        <button id="pushButton" class="small-button btn-info hidden" onclick="pushInfoMsg(1012004)">发布</button>
                        <button id="saveButton" class="small-button btn-info hidden" onclick="pushInfoMsg(0)">保存</button>
                        <button id="sendback" class="small-button btn-warning hidden" onclick="sendback();">打回</button>
                        <button id="playoff" class="small-button btn-danger hidden" onclick="playoff();">结束</button>
                        <button id="dropout" class="small-button btn-success" onclick="closeLayerPageJs();">关闭</button>
                    </legend>
                    <textarea style="width: 98%;height: 85%;margin-top: 10px;margin-left: 1%;" id="infoToPush"><?php if(isset($data[0]['reportout'])){echo $data[0]['reportout'];}?></textarea>
                </fieldset>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" language="javascript">
    //权限判断
    var hasRescue = '<?php echo isset($hasRescue)?$hasRescue:'0' ?>';
    var page = 1;
    var eventCauseId = <?php echo $eventCauseId_json ?>;
    var eventCauseName = <?php echo $eventCauseName_json ?>;
    var direction1_jsonObj = <?php echo $alongStation['direction1_json'] ?>;
    var direction2_jsonObj = <?php echo $alongStation['direction2_json'] ?>;
    //创建变量,当路段下拉框改变时,将返回的起始站和结束站数据转换成对象后赋值到它们
    //var direction1_jsonObj,direction2_jsonObj;
    //

    //查看详细信息的事件ID
    var eventid = '<?php echo $eventid ?>';
    var eventtype = '<?php echo $data[0]['eventtype'] ?>';

    //定义全局的方向
    //作用,改变起始站和结束站时,根据当前方向的站点排列数组比对当前选择的起始站和结束站的前后关系,从而改变站点地图样式,所以需要有全局的方向变量记录当前方向,以确定比对根据的是方向1或2的站点数组
    var directionno = '';

    //定义全局的起始站和结束站id,初始值为数据库值
    //全局的startnodeid和endnodeid用于路段状况单选按钮改变时,改变顶部站点地图的样式
    var startnodeid = '<?php echo $data[0]['startnodeid'] ?>';
    var endnodeid = '<?php echo $data[0]['endnodeid'] ?>';

    $().ready(function(){

        //设置高速公路下拉框的初始选中状态
        var startRoadOldId = '<?php echo $data[0]['roadoldid'] ?>';
        $("#roadSel").find("option[value='"+startRoadOldId+"']").attr("selected",true);
        //$("#roadSel").find("option[value='"+startRoadOldId+"']").eq(0).html();
        //只有一个符合条件时,效果等同于
        //$("#roadSel").find("option[value='"+startRoadOldId+"']").html();
        //设置大类小类下拉框内容和初始状态
        var startEventType = '<?php echo $data[0]['eventtype'] ?>';//大类初始值
        $('#eventTypeSel').find("option[value='"+startEventType+"']").attr('selected',true);
        changeEventType(startEventType);//小类内容
        var startEventCause = '<?php echo $data[0]['eventcauseno'] ?>';//小类初始值
        $('#eventCauseSel').find("option[value='"+startEventCause+"']").attr('selected',true);

        //设置行车方向的初始状态
        var startDirectionno = '<?php echo $data[0]['directionno'] ?>';
        directionno = startDirectionno;
        $("input[type=radio][name=direction][value="+startDirectionno+"]").attr('checked','checked');
        /*$("input:radio[name='eventType']:checked").each(function() {
            var value = $(this).val();
            checkboxValue = checkboxValue + value + ',';
        });*/

        //设置路段状况的初始状态
        var startRoadTrafficColor = '<?php echo $data[0]['roadtrafficcolor'] ?>';
        if (trimStr(startRoadTrafficColor) == '') {
            startRoadTrafficColor = '1008001';
        }
        $("input[type=radio][name=roadTrafficColor][value="+startRoadTrafficColor+"]").attr('checked','checked');

        //给路段状态设置背景颜色
        $('#trafficcolor-div').find("p[text='1008001']").eq(0).addClass('background-green');
        $('#trafficcolor-div').find("p[text='1008002']").eq(0).addClass('background-yellow');
        $('#trafficcolor-div').find("p[text='1008003']").eq(0).addClass('background-lightred');
        $('#trafficcolor-div').find("p[text='1008004']").eq(0).addClass('background-crimson');
        $('#trafficcolor-div').find("p[text='1008005']").eq(0).addClass('background-crimson');
        $('#trafficcolor-div').find("p[text='1008006']").eq(0).addClass('background-gray');

        //更新站点地图,同时重置起始站和结束站的内容
        changeDirectionRadio(startDirectionno);

        //根据行车方向设置起始站点和终点站的下拉内容和初始状态
        $('#startStationSel').find("option[value='"+startnodeid+"']").attr('selected',true);
        $('#endStationSel').find("option[value='"+endnodeid+"']").attr('selected',true);

        //获取事件状态,判断事件是否已发布或已结束,用于设置结束按钮和关闭按钮的显示
        //判断当前事件是否是已结束,是则隐藏结束按钮,显示关闭按钮,否则保持默认的结束按钮
        //有权限-》待审核-》有发布，打回，关闭按钮
        //      -》发布中-》有保存，结束，关闭
        //      -》已结束-》有关闭
        //没有权限-》待审核-》有保存，关闭
        //        -》发布中-》有保存，结束，关闭
        //        -》已结束-》有关闭
        //按钮：发布pushButton,保存saveButton,打回sendback,结束playoff
        var eventStatus = '<?php echo $data[0]['eventstatus'] ?>';

        //首先判断操作权限,0则表示没有该权限,1表示有权限
        if (hasRescue == '1') {
            if (eventStatus == '1012002') {
                $('#pushButton').removeClass('hidden');
                $('#sendback').removeClass('hidden');
            }else if (eventStatus == '1012004' || eventStatus == '1012005') {
            	$('#saveButton').removeClass('hidden');
                $('#playoff').removeClass('hidden');
            }else if (eventStatus == '1012006') {

            }
            
        }else if (hasRescue == '0') {
            if (eventStatus == '1012002') {
                $('#saveButton').removeClass('hidden');
            }else if (eventStatus == '1012004' || eventStatus == '1012005') {
            	$('#saveButton').removeClass('hidden');
                $('#playoff').removeClass('hidden');
            }else if (eventStatus == '1012006') {

            }
        }

    });
    

    /**
     * @desc  截取字符串，获取去掉末尾符号的字符串
     */
    function subValueStr(str){
        return str.substring(0,(str.length-1));
    }

    /**
     * @desc  去除字符串两边的空格
     */
    function trimStr(str){
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }

    //管理处和路段下拉框联动
    /*function changeRoadper(roadper){
        if(roadper == ''){
            roadper = 0;
        }
        var roadSel = document.getElementById('roadSel');
        roadSel.length = 1;

        for(var i=0;i<roadoldidArr[roadper].length;i++){
            roadSel.options.add(new Option(roadoldnameArr[roadper][i],roadoldidArr[roadper][i]));
        }
    }*/


    /**
     * @desc  根据路段拥堵指数指定对应的路段图片
     * @param string    roadTrafficColor 路段状况单选框的选中结果
     */
    function setRoadTrafficColorPic(roadTrafficColor){
        var thisClass = '';
        if (roadTrafficColor == '1008004' || roadTrafficColor == '1008005') {//阻断或管制
            thisClass = 'pic_red';
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
     * @desc   获取页面顶部的站点地图
     */
    function checkRoadTable(roadoldid,direction){
        JAjax("admin/MsgPublish/RoadEventLogic", 'onLoadRoadTableMsg', {roadoldid:roadoldid,direction:direction}, function (data) {
            if(data.Success){
                var station= data.data;
                var tr1='';
                var tr2='';
                $(".roadtable-tr-1").html('');
                $(".roadtable-tr-2").html('');
                
                var roadTrafficColor=$('input:radio[name="roadTrafficColor"]:checked').val();
                for(var i=0;i<station.length;i++){
                    var miles=station[i]['miles'].toString();
                    var strArry = miles.split(".");
                    if(strArry[0]==undefined){
                        strArry[0]='000';
                    }
                    if(strArry[1]==undefined){
                        strArry[1]='000';
                    }

                    if (i == (station.length-1)) {
                        tr1='<td class="miles">&nbsp;K'+strArry[0]+'+'+strArry[1]+'</td><td>&nbsp;</td>';
                        tr2='<td poiname="true">'+station[i]['name']+'</td><td class=" ';
                    }else{
                        tr1='<td class="miles">&nbsp;K'+strArry[0]+'+'+strArry[1]+'</td><td class="small-width">&nbsp;</td>';
                        tr2='<td poiname="true">'+station[i]['name']+'</td><td class="small-width ';
                    }
                    //tr1='<td class="miles">&nbsp;K'+strArry[0]+'+'+strArry[1]+'</td><td class="small-width">&nbsp;</td>';
                    //tr2='<td nowrap="">&nbsp;'+station[i]['name']+'</td><th class="trleft">&nbsp;</th>';
                    var tdIndexArr = getTdIndex();
                    //tr2='<td poiname="true" >'+station[i]['name']+'</td><td class="small-width ';
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
     * @desc   改变大类下拉框时,更新小类下拉框内容
     */
    function changeEventType(eventType){
        var eventCauseSel = document.getElementById('eventCauseSel');
        eventCauseSel.length = 0;
        
        for(var i=0;i<eventCauseId[eventType].length;i++){
            eventCauseSel.options.add(new Option(eventCauseName[eventType][i],eventCauseId[eventType][i]));
        }
    }


    /**
     * @desc   更改方向时,改变页面顶部站点地图,同时重置起始站和结束站的内容
     */
    function changeDirectionRadio(newDirectionNo){
        if (newDirectionNo == 0 || newDirectionNo == 1) {//正向或者双向时,展示正向样式
            directionno = 1;
        }else{//否则为方向样式
            directionno = 2;
        }
        //改变全局变量directionno的值
        //directionno = newDirectionNo;

        //var roadoldid = "<?php if(isset($data[0]['roadoldid'])){echo $data[0]['roadoldid'];}?>";
        var roadoldid = $('#roadSel').find('option:selected').val();
        checkRoadTable(roadoldid,directionno);//调用顶部站点地图方法,展示站点地图

        //重置起始站和终点站下拉框的内容
        var startStationSel = document.getElementById('startStationSel');
        var endStationSel = document.getElementById('endStationSel');
        startStationSel.length = 0;
        endStationSel.length = 0;
        var station_jsJsonArr;
        if (directionno == 1) {
            station_jsJsonArr = direction1_jsonObj;
        }else{
            station_jsJsonArr = direction2_jsonObj;
        }

        for(var i=0;i<station_jsJsonArr.length;i++){
            startStationSel.options.add(new Option(station_jsJsonArr[i].name,station_jsJsonArr[i].poiid));
            endStationSel.options.add(new Option(station_jsJsonArr[i].name,station_jsJsonArr[i].poiid));
        }
        
    }


    /**
     * @desc   根据起始站和结束站的选择内容,获取其选择内容在roadtable中的td位置
     * @return array    描述td位置的数组,包括起始站和结束站
     */
    function getTdIndex(){
        var directionJsArr;//要遍历的站点方向数组
        var startTdIndex,endTdIndex;//用于记录当前选择站点的数组下标index
        //获取起始站和结束站的选择内容,以及路段状况的选择内容
        var startStationSelected = $('#startStationSel').val();
        var endStationSelected = $('#endStationSel').val();

        if (directionno == 2) {
            directionJsArr = direction2_jsonObj;
        }else{//默认方向1
            directionJsArr = direction1_jsonObj;
        }
        $.each(directionJsArr, function(index, value) {
            if (value.poiid == startStationSelected) {
                //记录下拉框选择状态的当前index,乘以2就是站点对应站点地图的td排序
                startTdIndex = index*2;
            }
            if (value.poiid == endStationSelected) {
                endTdIndex = index*2;
            }
            //alert(index + ':' + value.poiid+ '=>' +value.name);
        });

        if ((directionno == 1 && (endTdIndex < startTdIndex)) || (directionno == 2 && (endTdIndex < startTdIndex))) {
            ShowMsg('您选择的行车方向与起始站终点站方向不一致,请重新选择!');
        }

        var tdIndexArr = new Array();
        tdIndexArr[0] = startTdIndex;
        tdIndexArr[1] = endTdIndex;
        return tdIndexArr;
    }

    /**
     * @desc   改变起始站下拉框时调用,改变全局变量startnodeid的值,以及站点地图
     */
    function changeRoadTable(){
        var tdIndexArr;
        var TrafficColorSelected = $('input:radio[name="roadTrafficColor"]:checked').val();
        //根据路段状况获取新站点地图路段HTML代码的class
        var newClass = setRoadTrafficColorPic(TrafficColorSelected);
        //调用方法获取起始站和结束站下拉框选择的站点对应roadtable的td的位置
        tdIndexArr =  getTdIndex();

        //if (tdIndexArr[0] < tdIndexArr[1]) {//起始站在结束站前面时改变站点地图样式
            var $tmpTr = $('#roadtable tbody tr').eq(1).find('td');
            var length = $tmpTr.length;

            $tmpTr.each(function(i){
            //$('#roadtable').children('tbody').children('tr:eq(1)').each(function(i){
                if (i>tdIndexArr[0] && i<tdIndexArr[1] && (i%2!==0) && (i != length-1)) {
                    //remove所有的样式样式,重新设置class
                    $tmpTr.eq(i).removeClass().addClass(newClass);
                }else if (i%2!==0 && (i != length-1)){
                    $tmpTr.eq(i).removeClass().addClass('pic_green');
                }
            });
        //}
    }


    /**
     * @desc 基础信息改变,点击'生成事件内容',改变发布内容的信息
     */
    function setInfoToPush(){
        var content = '';
        var roadSelected = $('#roadSel').find("option:selected").text();
        if (trimStr(roadSelected) == '') {ShowMsg('请选择高速公路!');return;}
        var occtime = $('#occtime').val();
        if (trimStr(occtime) == '') {ShowMsg('时间不能为空!');return;}
        var date = StringToDateTime(occtime);

        var time = DateTimeToString(date, "yyyy年MM月dd日 hh时mm分");

        var eventTypeSelected = $('#eventTypeSel').find("option:selected").text();
        var eventCauseSelected = $('#eventCauseSel').find("option:selected").text();
        var directionNo = $('input:radio[name="direction"]:checked').val();
        var directionChecked = $('input:radio[name="direction"]:checked').attr('text');
        var TrafficColorChecked = $('input:radio[name="roadTrafficColor"]:checked').attr('text');
        var startStationSelected = $('#startStationSel').find("option:selected").text();
        var endStationSelected = $('#endStationSel').find("option:selected").text();

        //区分重大事件和计划事件
        var eventTypeSelected = $('#eventTypeSel').find("option:selected").val();


        if (occtime != "")
            content += time + ",";
        if (roadSelected != "")
            content += roadSelected;
        if (directionNo == '1' || directionNo == '2') {//单向
            if (directionChecked != "")
                content += '往' + directionChecked+'方向';
        }else{
            content += '双向方向';
        }
        
        /*if (startStationSelected != "")
            content += "(" + startStationSelected + "至";
        if (endStationSelected != "")
            content += endStationSelected + ")";*/
        if (startStationSelected != ""){
            var startStationName = startStationSelected.split('(',1);
            content += "(" + startStationName + "至";
        }
        if (endStationSelected != ""){
            var endStationName = endStationSelected.split('(',1);
            content += endStationName + ")";
        }

        content += "受" + eventCauseSelected + "影响";
        if (TrafficColorChecked) {
            if (TrafficColorChecked == "畅通")
                content += "，现场暂不影响通行，事故正在处理中。";
            else
                content += "，现场行车" + TrafficColorChecked + "，请谨慎驾驶。";
        }
        else
            content += "。";

        if (eventTypeSelected == '1006002')
            content += '预计工期天。';
        else
            content += '预计处理时间小时。';

        $('#infoToPush').html('');
        $('#infoToPush').html(content);
    }


    /**
     * @desc   发布内容(修改)
     */
    function pushInfoMsg(tag){
        var roadoldid = $('#roadSel').val();
        var occtime = $('#occtime').val();
        var planovertime = $('#planovertime').val();
        var eventType = $('#eventTypeSel').val();
        var eventCause = $('#eventCauseSel').val();
        var eventCauseName = $('#eventCauseSel').find("option:selected").text();
        var direction = $('input:radio[name="direction"]:checked').val();
        var directionName = $('input:radio[name="direction"]:checked').attr('text');
        var TrafficColor = $('input:radio[name="roadTrafficColor"]:checked').val();
        var startStationid = $('#startStationSel').val();
        var startStation = $('#startStationSel').find("option:selected").text();
        var endStationid = $('#endStationSel').val();
        var endStation = $('#endStationSel').find("option:selected").text();
        var pushInfo = $('#infoToPush').val();

        if (trimStr(roadoldid) == '') {ShowMsg('请选择高速公路!');return;}
        if (trimStr(occtime) == '') {ShowMsg('时间不能为空!');return;}
        if (trimStr(pushInfo) == '') {ShowMsg('发布内容不能为空!');return;}

        JAjax("admin/MsgPublish/RoadEventLogic", 'savePushInfo', {eventid:eventid,roadoldid:roadoldid,occtime:occtime,planovertime:planovertime,eventType:eventType,eventCause:eventCause,eventCauseName:eventCauseName,direction:direction,directionName:directionName,TrafficColor:TrafficColor,startStationid:startStationid,startStation:startStation,endStationid:endStationid,endStation:endStation,pushInfo:pushInfo,tag:tag}, function (data) {

            if (data.Success)
                closeLayerPageJs();
            else
                ShowMsg(data.Message);
        }, null);
    }

    /**
     * @desc   结束当前发布信息,并返回'信息发布'页面
     */
    function playoff(){
        //showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/showRealovertime?eventid=') ?>"+eventid,'请选择结束时间',600,400,closeLayerPageJs);
        
        layerwin = $.layer({
            type: 2,
            maxmin: true,
            shadeClose: true,
            title: '请选择结束时间',
            shade: [0.5, '#000'],
            offset: ['50px', ''],
            moveType: 1,
            shadeClose: false,
            area: ['600px', '400px'],
            iframe: {
            src: "<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/showRealovertime?eventid=') ?>"+eventid+'&eventtype='+eventtype,
                scrolling: 'yes'
            },
            close: function(index){
                closeLayerPageJs();
            }
        });
    }


    /**
     * @desc   路段下拉框改变时
     * @param  {[type]}    roadoldid [description]
     * @return {[type]}              [description]
     */
    function changeRoad(roadoldid){

        JAjax("admin/MsgPublish/RoadEventLogic", 'getNewRoadMsg', {roadoldid:roadoldid}, function (data) {
            if (data.Success) {
                var msgArr = data.data;
                //为方向添加内容
                var html1 = '<input type="radio" name="direction" value="1" checked="checked" onclick="changeDirectionRadio(this.value)" text="'+msgArr['direction'][0]['direction1']+'" />'+msgArr['direction'][0]['direction1'];
                $('#direction1').html(html1);
                var html2 = '<input type="radio" name="direction" value="2" onclick="changeDirectionRadio(this.value)" text="'+msgArr['direction'][0]['direction2']+'" />'+msgArr['direction'][0]['direction2'];
                $('#direction2').html(html2);
                var html3 = '<input type="radio" name="direction" value="0" onclick="changeDirectionRadio(this.value)" text="双向" />双向';
                $('#direction3').html(html3);

                //将json字符串转换成json对象,并将对象赋给全局变量direction1_jsonObj和direction2_jsonObj
                direction1_jsonObj = eval('('+msgArr['alongStation']['direction1_json']+')');
                direction2_jsonObj = eval('('+msgArr['alongStation']['direction2_json']+')');
                //alert(direction1_jsonObj.length);
                //设置起始站和结束站的初始状态,取direction1_jsonObj
                var startStationSel = document.getElementById('startStationSel');
                var endStationSel = document.getElementById('endStationSel');
                startStationSel.length = 0;
                endStationSel.length = 0;
                for(var i=0; i<direction1_jsonObj.length; i++){
                    startStationSel.options.add(new Option(direction1_jsonObj[i].name,direction1_jsonObj[i].poiid));
                    endStationSel.options.add(new Option(direction1_jsonObj[i].name,direction1_jsonObj[i].poiid));
                }

                directionno = 1;
                //展示站点地图
                checkRoadTable(roadoldid,directionno);

            }else
                ShowMsg('获取路段信息出错!');
        }, "pager");
    }

    function sendback(){
        //alert('功能尚未实现,请审核事件先移步事件审核菜单,谢谢!');return;
        JAjax("admin/MsgPublish/RoadEventLogic", 'sendEventback', {eventid:eventid}, function (data) {
            if (data.Success)
                closeLayerPageJs();
            else
                ShowMsg(data.Message);
        }, null);
    }

</script>
</html>