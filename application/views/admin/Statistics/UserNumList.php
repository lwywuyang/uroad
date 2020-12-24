<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <script src="<?php echo base_url('/asset/plugs/echarts.common.min.js') ?>"></script>
	<style>
        a {cursor: pointer;}
        .m-10{margin-right:10px;}
        .table{margin-bottom: 0;}
        .heading-ul{width: 99%;height: 50px;line-height: 50px;padding: 0;}
        .heading-li{width: 50%;height: 50px;float: left;list-style: none;margin: 0 auto;border-radius: 3px;text-align: center;}
        .li-hover:hover{background-color: #D1DEF0;}
        .li-color{background-color: #428BCA;}
        .li-color a{color: white;}
        .heading-a{font-size: 24px;width: 100% !important;height: 100% !important;display: block;}
	</style>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="SystemLi"><a onclick="statisticsBySystem();" class="heading-a">按运行系统统计</a></li>
                    <li class="heading-li" id="DateLi"><a onclick="statisticsByDate();" class="heading-a">按日期统计</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <!--按运行系统统计-->
            <div class="table-responsive" id="SystemDiv">
                <!-- <div class="form-inline mb10">
                    <label for="SystemStartTime">日期:</label>
                    <input type="text" class="form-control" id="SystemStartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="SystemEndTime">至</label>
                    <input type="text" class="form-control m-10" id="SystemEndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" onclick="statisticsBySystem();" class="btn btn-primary" />
                </div> -->
                <div id="SystemPie" style="height: 500px;width: 99%;"></div>
                <!-- <table class="table table-hover table-bordered dataTable" id="SystemTable">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="firstreleaseperson">部门
                            </th>
                            <th class="title" width="" itemvalue="num">所有事件数
                            </th>
                            <th class="title" width="" itemvalue="num03">待审核事件数
                            </th>
                            <th class="title" width="" itemvalue="num45">发布中事件数
                            </th>
                            <th class="title" width="" itemvalue="num06">已结束事件数
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table> -->
            </div>
            <!--按日期统计-->
            <div class="table-responsive" id="DateDiv">
                <div class="form-inline mb10">
                    <label for="DateStartTime">日期:</label>
                    <input type="text" class="form-control" id="DateStartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="DateEndTime">至</label>
                    <input type="text" class="form-control m-10" id="DateEndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" id="checkWechat" onclick="statisticsByDate();" class="btn btn-primary">
                </div>
                <div id="DateLine" style="height: 500px;width: 98%;"></div>
                <table class="table table-hover table-bordered dataTable" id="DateTable">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="Date">日期
                            </th>
                            <th class="title" width="" itemvalue="AndroidIncrease">Android
                            </th>
                            <th class="title" width="" itemvalue="IOSIncrease">IOS
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" language="javascript">

    $().ready(function(){
        //页面打开时,将App用户趋势的时间设置成七天前到今天
        //将微信用户趋势的时间设置成七天前到昨天
        var now = new Date();
        var nowDay = now.getFullYear()+"-"+((now.getMonth()+1)<10?"0":"")+(now.getMonth()+1)+"-"+(now.getDate()<10?"0":"")+now.getDate();
        //var nowDay2 = now.getFullYear()+"-"+((now.getMonth()+1)<10?"0":"")+(now.getMonth()+1)+"-"+((now.getDate()-1)<10?"0":"")+(now.getDate()-1);

        var lastDate = new Date(now.getTime() - 7 * 24 * 3600 * 1000);
        var year = lastDate.getFullYear();
        var month = ((lastDate.getMonth()+1)<10?"0":"")+(lastDate.getMonth()+1);
        var day = (lastDate.getDate()<10?"0":"")+lastDate.getDate();
        //var day2 = ((lastDate.getDate()+1)<10?"0":"")+(lastDate.getDate()+1);
        lastDay = year + '-' + month + '-' + day;
        //lastDay2 = year + '-' + month + '-' + day2;

        $('#DateStartTime').val(lastDay);
        $('#DateEndTime').val(nowDay);

        statisticsBySystem();
    });


    /***************按运行系统统计***************/
    //获取内容
    function statisticsBySystem(){
        $('#SystemLi').addClass('li-color');
        $('#SystemLi').removeClass('li-hover');

        $('#DateLi').removeClass('li-color');
        $('#DateLi').addClass('li-hover');

        $('#SystemDiv').removeClass('hidden');
        $('#DateDiv').addClass('hidden');


        JAjax("admin/Statistics/UserNumLogic",'onLoadUserNumMsg',{}, function (data) {

            setSystemStatisticsPie(data.data.Android,data.data.IOS);

        }, null);
    }

    //输出按运行系统统计表
    function setSystemStatisticsPie(AndroidNum,IOSNum){

        // 基于准备好的dom，初始化echarts图表
        var SystemPie = echarts.init(document.getElementById('SystemPie'));//,'macarons'
        
        SystemOption = {
            title : {
                text: '用户数统计饼图',
                subtext: '按运行系统统计',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['Android','IOS']
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            series : [
                {
                    name: '管理单位',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        {value:AndroidNum, name:'Android'},
                        {value:IOSNum, name:'IOS'}
                    ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };

        // 为echarts对象加载数据 
        SystemPie.hideLoading();
        SystemPie.setOption(SystemOption);
    }


    /***************Date***************/
    //获取内容
    function statisticsByDate(){
        $('#DateLi').addClass('li-color');
        $('#DateLi').removeClass('li-hover');
        $('#SystemLi').removeClass('li-color');
        $('#SystemLi').addClass('li-hover');
        $('#DateDiv').removeClass('hidden');
        $('#SystemDiv').addClass('hidden');

        var DateStartTime = $('#DateStartTime').val();
        var DateEndTime = $('#DateEndTime').val();

        JAjax("admin/Statistics/UserNumLogic",'onLoadDateStatisticsMsg',{DateStartTime:DateStartTime,DateEndTime:DateEndTime}, function (data) {
            var lineData = data.data.line;
            var tableData = data.data.tableData;

            var dates = lineData.AndroidDate;
            var AndroidIncrease = lineData.AndroidIncrease;
            var IOSIncrease = lineData.IOSIncrease;


            setDateStatisticsLine(dates,AndroidIncrease,IOSIncrease);

            ReloadTb('DateTable', tableData);
        }, null);
    }

    function setDateStatisticsLine(dates,AndroidIncrease,IOSIncrease){
        // 基于准备好的dom，初始化echarts图表
        var DateLine = echarts.init(document.getElementById('DateLine'));//,'macarons'
        
        DateOption = {
            title : {
                text: '用户数统计折线图',
                subtext: '按运行系统统计',
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Android','IOS']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: dates
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'Android',
                    type:'line',
                    data:AndroidIncrease
                },
                {
                    name:'IOS',
                    type:'line',
                    data:IOSIncrease
                }
            ]
        };

        // 为echarts对象加载数据
        DateLine.hideLoading();
        DateLine.setOption(DateOption);
    }

</script>
</html>