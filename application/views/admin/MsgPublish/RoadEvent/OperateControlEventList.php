<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common'); ?>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
		.m-10{margin-right:10px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .vc_table{margin: 0;padding:0;}
        .td-width{text-align: right;width: 110px !important;}
        .vc_table .content{width:auto;max-width: 220px;width: 255px !important;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        a {cursor: pointer;}
        .content label{margin-right: 8px;}
        .dir-select{float: left;max-width: 150px;}
        .padding-0{padding-top:0 !important;padding-bottom: 0 !important;}
        .must{color: #FE4E4E;}
        .dis-none{display: none;}
	</style>
    <script type="text/javascript" language="javascript">
        var hasRescue = '<?php echo isset($hasRescue)?$hasRescue:'0' ?>';
        var eventId = "<?php echo isset($eventId)?$eventId:'0'; ?>";
        var startStationArr = new Array();

        var eventstatus = '<?php echo isset($data[0]['eventstatus'])?$data[0]['eventstatus']:'1012002' ?>';
        var eventtype = '<?php echo isset($eventtype)?$eventtype:'' ?>';

        $().ready(function(){

            //修改事件信息的时候,设置页面的初始值
            if (eventId != '0') {
                var startRoadOldId = "<?php echo isset($data[0]['roadoldid'])?$data[0]['roadoldid']:''; ?>";
                var startOccTime = "<?php echo isset($data[0]['occtime'])?$data[0]['occtime']:''; ?>";
                var startPlanoverTime = "<?php echo isset($data[0]['planovertime'])?$data[0]['planovertime']:''; ?>";
                var startRealoverTime = "<?php echo isset($data[0]['realovertime'])?$data[0]['realovertime']:''; ?>";
                var direction1 = "<?php echo isset($data[0]['direction1'])?$data[0]['direction1']:''; ?>";
                var direction2 = "<?php echo isset($data[0]['direction2'])?$data[0]['direction2']:''; ?>";
                var startDir1_come = "<?php echo isset($data[0]['g1'])?$data[0]['g1']:''; ?>";
                var startDir1_out = "<?php echo isset($data[0]['g2'])?$data[0]['g2']:''; ?>";
                var startDir2_come = "<?php echo isset($data[0]['g4'])?$data[0]['g4']:''; ?>";
                var startDir2_out = "<?php echo isset($data[0]['g5'])?$data[0]['g5']:''; ?>";
                var startReason = "<?php echo isset($data[0]['reportinfo'])?$data[0]['reportinfo']:''; ?>";
                var startContent = "<?php echo isset($data[0]['reportout'])?$data[0]['reportout']:''; ?>";

                $('#roadSel').find("option[value='"+startRoadOldId+"']").attr('selected',true);
                $('#occTime').val(startOccTime);
                $('#planovertime').val(startPlanoverTime);
                $('#realovertime').val(startRealoverTime);
                $('#direction1').html(direction1);
                $('#direction2').html(direction2);
                $('#dir1_come').find("option[value='"+startDir1_come+"']").attr('selected',true);
                $('#dir1_out').find("option[value='"+startDir1_out+"']").attr('selected',true);
                $('#dir2_come').find("option[value='"+startDir2_come+"']").attr('selected',true);
                $('#dir2_out').find("option[value='"+startDir2_out+"']").attr('selected',true);
                $('#reasonSel').find("option[value='"+startReason+"']").attr('selected',true);
                $('#pushContent').html(startContent);
                changeStation();

                var station = "<?php echo isset($station)?$station:'' ?>";

                startStationArr = station.split(',');

                //有权限-》待审核-》有发布，打回，关闭按钮
                //      -》发布中-》有保存，结束，关闭
                //      -》已结束-》有关闭
                //没有权限-》待审核-》有保存，关闭
                //        -》发布中-》有保存，结束，关闭
                //        -》已结束-》有关闭
                //提交审核save，保存save1，发布push，打回sendback，结束finish
                //首先判断操作权限,0则表示没有该权限,1表示有权限
                if (hasRescue == '1') {
                    if (eventstatus == '1012002') {
                        $('#push').removeClass('hidden');
                        $('#sendback').removeClass('hidden');
                    }else if (eventstatus == '1012004' || eventstatus == '1012005') {
                        $('#save1').removeClass('hidden');
                        $('#finish').removeClass('hidden');
                    }else if (eventstatus == '1012006') {

                    }
                    
                }else if (hasRescue == '0') {
                    if (eventstatus == '1012002') {
                        $('#save1').removeClass('hidden');
                    }else if (eventstatus == '1012004' || eventstatus == '1012005') {
                        $('#save1').removeClass('hidden');
                        $('#finish').removeClass('hidden');
                    }else if (eventstatus == '1012006') {

                    }
                }
            }else{//新增
                var d = new Date();
                var vYear = d.getFullYear();
                var vMon = d.getMonth() + 1;
                var vDay = d.getDate();
                var h = d.getHours(); 
                var m = d.getMinutes(); 
                var se = d.getSeconds(); 
                var s=vYear+'-'+(vMon<10 ? "0" + vMon : vMon)+'-'+(vDay<10 ? "0"+ vDay : vDay)+' '+(h<10 ? "0"+ h : h)+':'+(m<10 ? "0" + m : m)+':'+(se<10 ? "0" +se : se);
                $('#occTime').val(s);

                $('#save').removeClass('hidden');
            }
        });

        /**
         * [dropOut 返回上个页面]
         */
        function dropOut() {
            closeLayerPageJs();
        }


        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        
        //改变路段下拉框时,改变涉及收费站内容和两个方向
        function changeStation(){
            var roadoldid = $('#roadSel').val();

            if (roadoldid == '') {//请选择高速
                $('#stationCheckBox').html('');
                $('#direction1').html('');
                $('#direction2').html('');
                return;
            }
            //选择了具体高速
            JAjax("admin/MsgPublish/RoadEventLogic","getStationMsg",{roadoldid:roadoldid}, function (data){
                var stationArr = data.data;
                var html = '';

                for(var i=0;i<stationArr['station'].length;i++){
                    html += '<label><input type="checkbox" name="station" text="'+stationArr['station'][i]['name']+'" value="'+stationArr['station'][i]['poiid']+'">'+stationArr['station'][i]['name']+'</label>';
                }

                $('#stationCheckBox').html(html);

                $.each(startStationArr,function(n,value){
                    $('input:checkbox[value="'+value+'"]:checkbox').attr('checked','checked');
                });

                $('#direction1').html(stationArr['direction'][0]['direction1']);
                $('#direction2').html(stationArr['direction'][0]['direction2']);
            },null);
        }

        //组织发布内容并填入文本域
        function setInfoToPush(){
            var roadSel = $('#roadSel').find("option:selected").text();
            if (trimStr(roadSel) == '') {ShowMsg('请选择高速公路!');return;}
            var occTime = $('#occTime').val();
            if (trimStr(occTime) == '') {ShowMsg('时间不能为空!');return;}
            var date = StringToDateTime(occTime);
            var time = DateTimeToString(date, "yyyy年MM月dd日 hh时mm分");

            var station = getAllCheckedValues('station','text','、');
            //var station = station1.replace('/,/g','、');
            var direction1 = $('#direction1').html();
            var dir1_come = $('#dir1_come').find("option:selected").text();
            var dir1_out = $('#dir1_out').find("option:selected").text();
            var direction2 = $('#direction2').html();
            var dir2_come = $('#dir2_come').find("option:selected").text();
            var dir2_out = $('#dir2_out').find("option:selected").text();
            var reason = $('#reasonSel').find("option:selected").text();

            var pushContent = '';
            if (occTime != '')
                pushContent += time + ',';
            if (roadSel != '')
                pushContent += roadSel + ',';
            if (reason != '')
                pushContent += reason + ',';

            var num = 0;//每有一个方向管制则加法运算+1,根据最终值判断单向管制或双向管制
            if (dir1_come != '无' || dir1_out != '无') {
                num += 1;
            }
            if (dir2_come != '无' || dir2_out != '无') {
                num += 1;
            }

            if (num == 1) {//单向管制
                if (dir1_come != '无' || dir1_out != '无'){
                    pushContent += '往' + direction1;
                }else{
                    pushContent += '往' + direction2;
                }

                pushContent += '方向,禁止入站涉及的收费站有:' + station + '。预计管制时间2小时。';

            }else if (num == 2) {//双向方向管制
                pushContent += '临时关闭涉及的收费站有' + station + '。预计管制时间1小时。';
            }else{//无管制

            }
            /*if (dir1_come != '无' || dir1_out != '无')
                pushContent += direction1 + '方向';
            if (dir1_come != '无')
                pushContent += dir1_come + '入站,';
            if (dir1_out != '无')
                pushContent += dir1_out + '出站,';*/


            /*if (dir2_come != '无' || dir2_out != '无')
                pushContent += direction2 + '方向';
            if (dir2_come != '无')
                pushContent += dir2_come + '入站,';
            if (dir2_out != '无')
                pushContent += dir2_out + '出站,';
            if (station != '')
                pushContent += '临时关闭涉及的收费站有:'+station;*/
            $('#pushContent').html('');
            $('#pushContent').html(pushContent);
        }

        /**
         * @desc   获取站点多选框的选中值
         * @return {[type]}            [description]
         */
        function getAllCheckedValues(name,attr,tag) {
            var result = '';
            if (attr == 'text') {
                $("input[name='" + name + "']:checked").each(function(){ 
                    //result += $(this).attr('text') + ',';
                    result += $(this).attr('text') + tag;
                });
            }else{
                $("input[name='" + name + "']:checked").each(function(){ 
                    //result += $(this).attr('value') + ',';
                    result += $(this).attr('value') + tag;
                });
            }
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            return result;
        }

        //保存
        function saveMsg(tag){
            var eventstatustag = eventstatus;
            if (tag != '0') {
                eventstatustag = tag;
            }
            var roadSel = $('#roadSel').val();
            var occTime = $('#occTime').val();
            var planovertime = $('#planovertime').val();
            var realovertime = $('#realovertime').val();
            var station = getAllCheckedValues('station','value',',');//获取多选框的值
            var dir1_come = $('#dir1_come').val();
            var dir1_out = $('#dir1_out').val();
            var dir2_come = $('#dir2_come').val();
            var dir2_out = $('#dir2_out').val();
            var reasonSel = $('#reasonSel').val();
            var pushContent = $('#pushContent').val();

            if(trimStr(roadSel) == ''){alert('请选择高速!');return;}
            if(trimStr(occTime) == ''){alert('时间不能为空!');return;}
            if(trimStr(pushContent) == ''){
                alert('发布内容不能为空');return;
            }else{
                //console.log(pushContent);
                pushContent = pushContent.replace(/\r\n/g,'');
                pushContent = pushContent.replace(/\n/g,'');
                //console.log(pushContent);
            }

            if (eventId == '0') {//新增
                JAjax("admin/MsgPublish/RoadEventLogic","saveControlEventMsg",{eventId:0,roadSel:roadSel,occTime:occTime,planovertime:planovertime,realovertime:realovertime,station:station,dir1_come:dir1_come,dir1_out:dir1_out,dir2_come:dir2_come,dir2_out:dir2_out,reasonSel:reasonSel,pushContent:pushContent,eventtype:eventtype,eventstatus:1012002}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg(data.Message);
                    }
                },null);
            }else{//修改
                JAjax("admin/MsgPublish/RoadEventLogic","saveControlEventMsg",{eventId:eventId,roadSel:roadSel,occTime:occTime,planovertime:planovertime,realovertime:realovertime,station:station,dir1_come:dir1_come,dir1_out:dir1_out,dir2_come:dir2_come,dir2_out:dir2_out,reasonSel:reasonSel,pushContent:pushContent,eventtype:eventtype,eventstatus:eventstatustag}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg(data.Message);
                    }
                },null);
            }
        }

        function finish(){
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
                src: "<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/showRealovertime?eventid=') ?>"+eventId+'&eventtype='+eventtype,
                    scrolling: 'yes'
                },
                close: function(index){
                    closeLayerPageJs();
                }
            });
        }

        /**
         * [sendback 打回]
         * @version 2016-04-22 1.0
         */
        function sendback(){
            JAjax("admin/MsgPublish/RoadEventLogic","sendbackControlEvent",{eventId:eventId}, function (data){
                if (data.Success) {
                    closeLayerPageJs();
                }else{
                    ShowMsg(data.Message);
                }
            },null);
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">收费站出入口事件</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            <span class="must">*</span>所属路段:
                        </td>
                        <td class="content">
                            <select class="form-control" id="roadSel" onchange="changeStation();" style="float: left;">
                                <option value="">请选择高速</option>
                                <?php foreach($road as $item): ?>
                                    <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="td-width">
                            <span class="must">*</span>发生时间:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control" id="occTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            预计结束时间:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control" id="planovertime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                        </td>
                        <td class="td-width">
                            实际结束时间:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control" id="realovertime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            涉及收费站:
                        </td>
                        <td class="content" colspan="3" id="stationCheckBox">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <span id="direction1"></span>方向
                        </td>
                        <td class="content" colspan="3">
                            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:100%">
                                <tr>
                                    <td >
                                        入站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <select class="form-control dir-select" id="dir1_come">
                                            <option value="0">无</option>
                                            <option value="1">禁止</option>
                                        </select>
                                    </td>
                                    <td >
                                        出站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <select class="form-control dir-select" id="dir1_out">
                                            <option value="0">无</option>
                                            <option value="1">禁止</option>
                                            <!-- <option value="2">必须</option> -->
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <span id="direction2"></span>方向
                        </td>
                        <td class="content" colspan="3">
                            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:100%">
                                <tr>
                                    <td >
                                        入站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <select class="form-control dir-select" id="dir2_come">
                                            <option value="0">无</option>
                                            <option value="1">禁止</option>
                                        </select>
                                    </td>
                                    <td >
                                        出站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <select class="form-control dir-select" id="dir2_out">
                                            <option value="0">无</option>
                                            <option value="1">禁止</option>
                                            <!-- <option value="2">必须</option> -->
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            原因:
                        </td>
                        <td class="content" colspan="3">
                            <select class="form-control" id="reasonSel" style="float: left;">
                                <?php foreach($reason as $item): ?>
                                    <option value="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <span class="must">*</span>发布内容:
                            <!-- <input type="button" value="生成内容" onclick="setInfoToPush();" class="btn btn-success"> -->
                        </td>
                        <td class="content" colspan="3">
                            <textarea style="width: 100%;height: 100px;" id="pushContent"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="提交审核" id="save" onclick="saveMsg(1012002);" class="btn btn-info m-10 hidden" >
                <input type="button" value="保 存" id="save1" onclick="saveMsg(0);" class="btn btn-info m-10 hidden" >
                <input type="button" value="发 布" id="push" onclick="saveMsg(1012004);" class="btn btn-primary m-10 hidden" >
                <input type="button" value="打 回" id="sendback" onclick="sendback();" class="btn btn-warning m-10 hidden" >
                <input type="button" value="结 束" id="finish" onclick="finish();" class="btn btn-danger m-10 hidden" >
                <input type="button" value="关 闭" id="del" onclick="dropOut();" class="btn btn-danger" >
            </div>
        </div>
    </div>
</body>
</html>