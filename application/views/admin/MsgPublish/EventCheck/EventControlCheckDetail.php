<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common'); ?>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
		.m-15{margin-right:15px;}
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
        //var hasRescue = '<?php echo isset($hasRescue)?$hasRescue:'0' ?>';
        var eventid = "<?php echo isset($eventid)?$eventid:'0'; ?>";

        var startStationArr = new Array();

        var eventstatus = '<?php echo isset($eventstatus)?$eventstatus:'' ?>';
        var eventtype = '<?php echo isset($eventtype)?$eventtype:'' ?>';

        var direction1 = '<?php echo isset($direction1)?$direction1:'--' ?>';
        var direction2 = '<?php echo isset($direction2)?$direction2:'--' ?>';
        var g1 = '<?php echo isset($g1)?$g1:'' ?>';
        var g2 = '<?php echo isset($g2)?$g2:'' ?>';
        var g4 = '<?php echo isset($g4)?$g4:'' ?>';
        var g5 = '<?php echo isset($g5)?$g5:'' ?>';

        $().ready(function(){
            g1 = (g1 == '0')?'无':(g1 == '1')?'禁止':'';
            g2 = (g2 == '0')?'无':(g2 == '1')?'禁止':(g2 == '2')?'必须':'';
            g4 = (g4 == '0')?'无':(g4 == '1')?'禁止':'';
            g5 = (g5 == '0')?'无':(g5 == '1')?'禁止':(g5 == '2')?'必须':'';

            $('#g1').html(g1);
            $('#g2').html(g2);
            $('#g4').html(g4);
            $('#g5').html(g5);

            if (eventstatus == '1012005') {
                $('#finish').removeClass('hidden');
                $('#return2').removeClass('hidden');

            }else if (eventstatus == '1012002') {
                $('#push').removeClass('hidden');
                $('#return1').removeClass('hidden');
            }
        });

        /**
         * @desc   发布内容(修改)
         */
        function changeStatus(status){

            JAjax("admin/MsgPublish/EventCheckLogic", 'changeControlStatus', {eventid:eventid,status:status,eventstatus:eventstatus}, function (data) {

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
        <div class="panel-heading">管制事件</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            <span class="must">*</span>所属路段:
                        </td>
                        <td class="content">
                            <?php echo isset($roadname)?$roadname:'' ?>
                        </td>
                        <td class="td-width">
                            <span class="must">*</span>发生时间:
                        </td>
                        <td class="content">
                            <?php echo isset($occtime)?$occtime:'' ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            涉及收费站:
                        </td>
                        <td class="content" colspan="3" id="stationCheckBox">
                            <?php echo isset($station)?$station:'' ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <?php echo isset($direction1)?$direction1:'--' ?>
                        </td>
                        <td class="content" colspan="3">
                            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:100%">
                                <tr>
                                    <td >
                                        入站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <span id="g1"></span>
                                    </td>
                                    <td >
                                        出站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <span id="g2"></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <?php echo isset($direction2)?$direction2:'--' ?>
                        </td>
                        <td class="content" colspan="3">
                            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:100%">
                                <tr>
                                    <td >
                                        入站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <span id="g4"></span>
                                    </td>
                                    <td >
                                        出站:
                                    </td>
                                    <td style="padding-top:0 !important;padding-bottom: 0 !important;">
                                        <span id="g5"></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            管制原因:
                        </td>
                        <td class="content" colspan="3">
                            <?php echo isset($reportinfo)?$reportinfo:'' ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <span class="must">*</span>发布内容:
                            <!-- <input type="button" value="生成内容" onclick="setInfoToPush();" class="btn btn-success"> -->
                        </td>
                        <td class="content" colspan="3">
                            <!-- <textarea rows="5" cols="73" id="pushContent"></textarea> -->
                            <?php echo isset($reportout)?$reportout:'' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <button class="btn btn-primary hidden m-15" id="push" onclick="changeStatus(1012004)">确认发布</button>
                <button class="btn btn-warning hidden m-15" id="return1" onclick="changeStatus(1012003)">打回</button>
                <button class="btn btn-primary hidden m-15" id="finish" onclick="changeStatus(1012006)">确认结束</button>
                <button class="btn btn-warning hidden m-15" id="return2" onclick="changeStatus(1012004)">打回</button>
                <button class="btn btn-danger" onclick="closeLayerPageJs();">关闭</button>
            </div>
        </div>
    </div>
</body>
</html>