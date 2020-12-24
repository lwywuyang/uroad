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
        .table{margin-bottom: 0;}
        .form-inline select{margin-right: 20px;}
        .photo-img{max-width: 50px;}
        a {cursor: pointer;}
        .heading-ul{width: 100%;height: 50px;line-height: 50px;padding: 0;}
        .heading-li{width: 50%;height: 50px;float: left;list-style: none;margin: 0 auto;border-radius: 3px;text-align: center;}
        .li-hover:hover{background-color: #D1DEF0;}
        .li-color{background-color: #428BCA;}
        .li-color a{color: white;}
        .heading-a{font-size: 24px;width: 100% !important;height: 100% !important;display: block;}
        .heading-a span {font-size: 14px;}
        .dis-none{display: none;}
        /* .small-font{} */
	</style>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/echarts-2.2.7/build/dist/echarts.js') ?>"></script>
    <script type="text/javascript" language="javascript">
        var intime,ETCProfessional,TravelServices,PrizeActivity,Tips,AboutOurselves,HistoryMessage,SnatchRedPackage;
        var nowDay,lastDay;

        function reLoad() {
            Load(page);
        }

        function subValueStr(str){
            return str.substring(0,(str.length-1));
        }

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
        
        function Load() {
            checkMultiServiceByDate();
        }

        /*$().ready(function(){
            var now = new Date();
            nowDay = now.getFullYear()+"-"+((now.getMonth()+1)<10?"0":"")+(now.getMonth()+1)+"-"+(now.getDate()<10?"0":"")+now.getDate();

            var lastDate = new Date(now.getTime() - 7 * 24 * 3600 * 1000);
            var year = lastDate.getFullYear();
            var month = lastDate.getMonth() + 1;
            var day = lastDate.getDate();
            lastDay = year + '-' + month + '-' + day;

            $('#startTime').val(lastDay);
            $('#endTime').val(nowDay);
        });*/


        /***************按日期***************/
        //读取内容
        function checkMultiServiceByDate(){
            $('#MultiServiceByDateLi').addClass('li-color');
            $('#MultiServiceByDateLi').removeClass('li-hover');
            $('#MultiServiceByServiceLi').removeClass('li-color');
            $('#MultiServiceByServiceLi').addClass('li-hover');

            $('#MultiServiceByDateDiv').removeClass('dis-none');
            $('#MultiServiceByServiceDiv').addClass('dis-none');

            JAjax("admin/Statistics/MultiServiceLogic", 'onLoadMsgByDate', {}, function (data) {
                ReloadTb('MultiServiceByDateTable', data.data);
            }, null);
        }


        /***************按客服***************/
        //获取内容
        function checkMultiServiceByService(){
            $('#MultiServiceByDateLi').removeClass('li-color');
            $('#MultiServiceByDateLi').addClass('li-hover');
            $('#MultiServiceByServiceLi').addClass('li-color');
            $('#MultiServiceByServiceLi').removeClass('li-hover');

            $('#MultiServiceByDateDiv').addClass('dis-none');
            $('#MultiServiceByServiceDiv').removeClass('dis-none');

            JAjax("admin/Statistics/MultiServiceLogic", 'onLoadMsgByService', {}, function (data) {
                ReloadTb('MultiServiceByServiceTable', data.data);
            }, null);
        }


</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="MultiServiceByDateLi">
                        <a onclick="checkMultiServiceByDate();" class="heading-a">
                            接入数统计<span class="small-font">(按日期)</span>
                        </a>
                    </li>
                    <li class="heading-li" id="MultiServiceByServiceLi">
                        <a onclick="checkMultiServiceByService();" class="heading-a">
                            接入数统计<span class="small-font">(按客服)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="MultiServiceByDateDiv">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="MultiServiceByDateTable">
                        <thead>
                            <tr>
                                <th class="title" width="50%" itemvalue="date" center="true">日期
                                </th>
                                <!-- <th class="title" width="15%" itemvalue="itypeName" center="true">菜单类型
                                </th>
                                <th class="title" width="15%" itemvalue="menuStatusName" center="true">菜单状态
                                </th> -->
                                <th class="title" width="50%" itemvalue="count" center="true">接入数
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 数据 -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer"></div>
        </div>
        <div id="MultiServiceByServiceDiv" class="dis-none">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="MultiServiceByServiceTable">
                        <thead>
                            <tr>
                                <th class="title" width="50%" itemvalue="worker" center="true">客服人员
                                </th>
                                <th class="title" width="50%" itemvalue="count" center="true">接入数
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 数据 -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- panel-body -->
            <div class="panel-footer"></div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load();
    </script>
</body>
</html>