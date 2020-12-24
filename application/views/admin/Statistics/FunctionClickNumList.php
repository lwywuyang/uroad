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
                    <li class="heading-li" id="AllNumLi"><a onclick="statisticsByAllNum();" class="heading-a">APP菜单点击量统计</a></li>
                    <li class="heading-li" id="HistoryLi"><a onclick="statisticsByHistory();" class="heading-a">点击量历史统计</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <!--按运行系统统计-->
            <div class="table-responsive" id="AllNumDiv">
                <!-- <div id="AllNumPie" style="height: 500px;width: 99%;"></div> -->
                <table class="table table-hover table-bordered dataTable" id="AllNumTable">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="functionname">菜单名称
                            </th>
                            <th class="title" width="" itemvalue="clicknum">菜单点击总量
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <!--按日期统计-->
            <div class="table-responsive" id="HistoryNumDiv">
                <div class="form-inline mb10">
                    <label for="HistoryNumStartTime">日期:</label>
                    <input type="text" class="form-control" id="HistoryNumStartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="HistoryNumStartEndTime">至</label>
                    <input type="text" class="form-control m-10" id="HistoryNumStartEndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" id="checkWechat" onclick="statisticsByHistory();" class="btn btn-primary">
                </div>
                <div id="HistoryLine" style="height: 500px;width: 98%;"></div>
                <table class="table table-hover table-bordered dataTable" id="HistoryNumTable">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="date">日期
                            </th>
                            <th class="title" width="" itemvalue="gslw">高速路网
                            </th>
                            <th class="title" width="" itemvalue="lkxx">路况信息
                            </th>
                            <th class="title" width="" itemvalue="lkdh">路况导航
                            </th>
                            <th class="title" width="" itemvalue="cxgb">出行广播
                            </th>
                            <th class="title" width="" itemvalue="gsff">高速服务
                            </th>
                            <th class="title" width="" itemvalue="cljy">车辆救援
                            </th>
                            <th class="title" width="" itemvalue="etcff">ETC服务
                            </th>
                            <th class="title" width="" itemvalue="lxcx">路线查询
                            </th>
                            <th class="title" width="" itemvalue="jd">景点
                            </th>
                            <th class="title" width="" itemvalue="bl">报料
                            </th>
                            <th class="title" width="" itemvalue="lj">链接
                            </th>
                            <th class="title" width="" itemvalue="wd">我的
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
    var date = '',gslw = '',lkxx = '',lkdh = '',cxgb = '',gsff = '',cljy = '',etcff = '',lxcx = '',jd = '',bl = '',lj = '',wd = '',reverseData = '';

    $().ready(function(){
        //页面打开时,将App用户趋势的时间设置成七天前到今天
        //将APP用户趋势的时间设置成七天前到昨天
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

        $('#HistoryNumStartTime').val(lastDay);
        $('#HistoryNumStartEndTime').val(nowDay);

        statisticsByAllNum();
    });


    /***************按运行系统统计***************/
    //获取内容
    function statisticsByAllNum(){
        $('#AllNumLi').addClass('li-color');
        $('#AllNumLi').removeClass('li-hover');

        $('#HistoryLi').removeClass('li-color');
        $('#HistoryLi').addClass('li-hover');

        $('#AllNumDiv').removeClass('hidden');
        $('#HistoryNumDiv').addClass('hidden');

        JAjax("admin/Statistics/FunctionClickNumLogic",'onLoadAllNumMsg',{}, function (data) {
            ReloadTb('AllNumTable', data.data);
        }, null);
    }

    /***************History***************/
    //获取内容
    function statisticsByHistory(){
        $('#HistoryLi').addClass('li-color');
        $('#HistoryLi').removeClass('li-hover');
        $('#AllNumLi').removeClass('li-color');
        $('#AllNumLi').addClass('li-hover');
        $('#HistoryNumDiv').removeClass('hidden');
        $('#AllNumDiv').addClass('hidden');

        var HistoryNumStartTime = $('#HistoryNumStartTime').val();
        var HistoryNumStartEndTime = $('#HistoryNumStartEndTime').val();

        JAjax("admin/Statistics/FunctionClickNumLogic",'onLoadHistoryStatisticsMsg',{HistoryNumStartTime:HistoryNumStartTime,HistoryNumStartEndTime:HistoryNumStartEndTime}, function (data) {
            
            date = data.data['date'].split(',');
            gslw = data.data['gslw'].split(',');
            lkxx = data.data['lkxx'].split(',');
            lkdh = data.data['lkdh'].split(',');
            cxgb = data.data['cxgb'].split(',');
            gsff = data.data['gsff'].split(',');
            cljy = data.data['cljy'].split(',');
            etcff = data.data['etcff'].split(',');
            lxcx = data.data['lxcx'].split(',');
            jd = data.data['jd'].split(',');
            bl = data.data['bl'].split(',');
            lj = data.data['lj'].split(',');
            wd = data.data['wd'].split(',');
            reverseData = data.data['reverseData'];

            setHistoryStatisticsLine();

            ReloadTb('HistoryNumTable', reverseData);
        }, null);
    }

    function setHistoryStatisticsLine(){
        // 基于准备好的dom，初始化echarts图表
        var HistoryLine = echarts.init(document.getElementById('HistoryLine'));//,'macarons'
        
        HistoryOption = {
            title : {
                text: 'APP菜单点击量统计折线图',
                subtext: '按菜单每天点击量统计',
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                left:225,
                data: ['高速路网','路况信息','路况导航','出行广播','高速服务','车辆救援','ETC服务','路线查询','景点','报料','链接','我的']
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
                data: date
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'高速路网',
                    type:'line',
                    data:gslw
                },
                {
                    name:'路况信息',
                    type:'line',
                    data:lkxx
                },
                {
                    name:'路况导航',
                    type:'line',
                    data:lkdh
                },
                {
                    name:'出行广播',
                    type:'line',
                    data:cxgb
                },
                {
                    name:'高速服务',
                    type:'line',
                    data:gsff
                },
                {
                    name:'车辆救援',
                    type:'line',
                    data:cljy
                },
                {
                    name:'ETC服务',
                    type:'line',
                    data:etcff
                },
                {
                    name:'路线查询',
                    type:'line',
                    data:lxcx
                },
                {
                    name:'景点',
                    type:'line',
                    data:jd
                },
                {
                    name:'报料',
                    type:'line',
                    data:bl
                },
                {
                    name:'链接',
                    type:'line',
                    data:lj
                },
                {
                    name:'我的',
                    type:'line',
                    data:wd
                }
            ]
        };

        // 为echarts对象加载数据
        HistoryLine.hideLoading();
        HistoryLine.setOption(HistoryOption);
    }

</script>
</html>