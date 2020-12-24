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
        .dis-none{display: none;}
	</style>

    <script type="text/javascript" language="javascript">
        function subValueStr(str){
            return str.substring(0,(str.length-1));
        }

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
        
        function Load() {
            checkPersonalEvent();
        }

        /***************个人事件发布量***************/
        //读取内容
        function checkPersonalEvent(){
            $('#event').addClass('li-color');
            $('#event').removeClass('li-hover');
            $('#service').removeClass('li-color');
            $('#service').addClass('li-hover');

            $('#eventData').removeClass('dis-none');
            $('#serviceData').addClass('dis-none');
            $('#check1').removeClass('dis-none');
            $('#check2').addClass('dis-none');

            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            JAjax("admin/StatisticsManage/PersonalStatisticsLogic",'onLoadEventMsg',{startTime:startTime,endTime:endTime}, function (data) {
                ReloadTb('eventData', data.data);
            }, null);
        }

        /***************个人多客服回复量***************/
        //获取内容
        function checkPersonalService(){
            $('#event').removeClass('li-color');
            $('#event').addClass('li-hover');
            $('#service').addClass('li-color');
            $('#service').removeClass('li-hover');

            $('#eventData').addClass('dis-none');
            $('#serviceData').removeClass('dis-none');
            $('#check1').addClass('dis-none');
            $('#check2').removeClass('dis-none');

            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            JAjax("admin/StatisticsManage/PersonalStatisticsLogic",'onLoadServiceMsg',{startTime:startTime,endTime:endTime}, function (data) {
                ReloadTb('serviceData', data.data);
            }, null);
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default">
        <div class="panel-heading">
            <!-- <div class="form-inline mb10">
                <label for="startTime">时间:</label>
                <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="endTime">至</label>
                <input type="text" class="form-control" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <input type="button" value="查 询" id="check1" onclick="checkPersonalEvent();" class="btn btn-primary m-15" >
                <input type="button" value="查 询" id="check2" onclick="checkPersonalService();" class="btn btn-primary m-15 dis-none" >
            </div> -->
        </div>
        <div class="panel-body">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="event"><a onclick="checkPersonalEvent();" class="heading-a">个人事件发布量</a></li>
                    <li class="heading-li" id="service"><a onclick="checkPersonalService();" class="heading-a">个人多客服回复量</a></li>
                </ul>
            </div>
            <!--个人事件发布量-->
            <div class="table-responsive" id="eventTable">
                <table class="table table-hover table-bordered dataTable" id="eventData">
                    <thead>
                        <tr>
                            <th class="title" width="15%" itemvalue="part" center="true">单位
                            </th>
                            <th class="title" width="20%" itemvalue="name" center="true">发布者
                            </th>
                            <th class="title" width="10%" itemvalue="num" center="true">发布数量
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
            </div>
            <!--个人多客服回复量-->
            <div class="table-responsive" id="serviceTable">
                <table class="table table-hover table-bordered dataTable dis-none" id="serviceData">
                    <thead>
                        <tr>
                            <th class="title" width="15%" itemvalue="name" center="true">发布者
                            </th>
                            <th class="title" width="15%" itemvalue="num" center="true">发布数量
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
            </div>
        </div>
            <!-- <div class="panel-footer">
                <div id="pagerRoad" fun="checkRoad" class="pager" pagerobj="">
                </div>
            </div> -->
    </div>
    <script type="text/javascript" language="javascript">
        Load();
    </script>
</body>
</html>