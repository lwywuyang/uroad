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
        var interactive,APPdownload,roadCheck,congestionIndex,myNeighborhood,report,service,checkIllegal,community,personalCenter,mall;
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
            checkWXMenuStatistics();
        }

        $().ready(function(){
            var now = new Date();
            nowDay = now.getFullYear()+"-"+((now.getMonth()+1)<10?"0":"")+(now.getMonth()+1)+"-"+(now.getDate()<10?"0":"")+now.getDate();

            var lastDate = new Date(now.getTime() - 7 * 24 * 3600 * 1000);
            var year = lastDate.getFullYear();
            var month = lastDate.getMonth() + 1;
            var day = lastDate.getDate();
            lastDay = year + '-' + month + '-' + day;

            $('#startTime').val(lastDay);
            $('#endTime').val(nowDay);
        });

        /*function Load2(){
            checkRemark(1);
        }*/

        /***************微信菜单点击总数***************/
        //读取内容
        function checkWXMenuStatistics(){
            $('#WXMenuStatistics').addClass('li-color');
            $('#WXMenuStatistics').removeClass('li-hover');
            $('#historyStatistics').removeClass('li-color');
            $('#historyStatistics').addClass('li-hover');

            $('#WXMenuStatisticsDiv').removeClass('dis-none');
            $('#historyStatisticsDiv').addClass('dis-none');

            /*var keyword = $('#keyword_road').val();*/
            JAjax("admin/Statistics/WXClickLogic", 'onLoadMsg_WXMenuStatistics', {}, function (data) {
                ReloadTb('WXMenuStatisticsTable', data.data);
            }, "pagerRoad");
        }


        /***************点击历史统计***************/
        //获取内容
        function checkHistoryStatistics(){
            /*alert(nowDay);
            alert(lastDay);*/
            $('#WXMenuStatistics').removeClass('li-color');
            $('#WXMenuStatistics').addClass('li-hover');
            $('#historyStatistics').addClass('li-color');
            $('#historyStatistics').removeClass('li-hover');

            $('#WXMenuStatisticsDiv').addClass('dis-none');
            $('#historyStatisticsDiv').removeClass('dis-none');

           /* $('#WXMenuStatistics').addClass('li-color');
            $('#WXMenuStatistics').removeClass('li-hover');
            $('#historyStatistics').removeClass('li-color');
            $('#historyStatistics').addClass('li-hover');

            $('#WXMenuStatisticsDiv').removeClass('dis-none');
            $('#historyStatisticsDiv').addClass('dis-none');*/

            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            JAjax("admin/Statistics/WXClickLogic", 'onLoadMsg_HistoryStatistics', {startTime:startTime,endTime:endTime}, function (data) {
                
                intime = data.data['intime'].split(',');
                interactive = data.data['interactive'].split(',');
                APPdownload = data.data['APPdownload'].split(',');
                roadCheck = data.data['roadCheck'].split(',');
                congestionIndex = data.data['congestionIndex'].split(',');
                myNeighborhood = data.data['myNeighborhood'].split(',');
                report = data.data['report'].split(',');
                service = data.data['service'].split(',');
                checkIllegal = data.data['checkIllegal'].split(',');
                community = data.data['community'].split(',');
                personalCenter = data.data['personalCenter'].split(',');
                mall = data.data['mall'].split(',');

                setLineChart();
                ///
                var reverseData = data.data['reverseData'];
                ReloadTb('historyStatisticsTable', reverseData);
            }, "pagerRemark");
        }

        //画折线图
        function setLineChart(){
            // 路径配置
            require.config({
                paths: {
                    echarts: "<?php $this->load->helper('url');echo base_url('/asset/js/echarts-2.2.7/build/dist') ?>"
                }
            });

             // 使用
            require(
                [
                    'echarts',
                    'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
                ],
                function (ec) {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('line')); 
                    
                    var option = {
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['微互动','APP下载','道路速查','拥堵指数','我的附近','我要报料','人工客服','违法查询','微社区','个人中心','微商城']
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                magicType: { show: true, type: ['line'] },
                                restore: { show: true },
                                saveAsImage: { show: true }
                            },

                        },

                        xAxis: [
                            {
                                type: 'category',
                                boundaryGap: false,
                                data: intime
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',

                            }
                        ],
                        
                        series: [
                            {
                                name: '微互动',
                                type: 'line',
                                data: interactive
                            },
                            {
                                name: 'APP下载',
                                type: 'line',
                                data: APPdownload
                            },
                            {
                                name: '道路速查',
                                type: 'line',
                                data: roadCheck
                            },
                            {
                                name: '拥堵指数',
                                type: 'line',
                                data: congestionIndex
                            },
                            {
                                name: '我的附近',
                                type: 'line',
                                data: myNeighborhood
                            },
                            {
                                name: '我要报料',
                                type: 'line',
                                data: report
                            },
                            {
                                name: '人工客服',
                                type: 'line',
                                data: service
                            },
                            {
                                name: '违法查询',
                                type: 'line',
                                data: checkIllegal
                            },
                            {
                                name: '微社区',
                                type: 'line',
                                data: community
                            },
                            {
                                name: '个人中心',
                                type: 'line',
                                data: personalCenter
                            },
                            {
                                name: '微商城',
                                type: 'line',
                                data: mall
                            }
                        ]
                    };


                    // 为echarts对象加载数据 
                    myChart.hideLoading();
                    myChart.setOption(option);

                }
            );
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="WXMenuStatistics">
                        <a onclick="checkWXMenuStatistics();" class="heading-a">
                            微信菜单点击总数<span class="small-font">(数据从2015-01-23起统计)</span>
                        </a>
                    </li>
                    <li class="heading-li" id="historyStatistics">
                        <a onclick="checkHistoryStatistics();" class="heading-a">点击历史统计</a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="WXMenuStatisticsDiv">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="WXMenuStatisticsTable">
                        <thead>
                            <tr>
                                <th class="title" width="15%" itemvalue="title" center="true">菜单名称
                                </th>
                                <!-- <th class="title" width="15%" itemvalue="itypeName" center="true">菜单类型
                                </th>
                                <th class="title" width="15%" itemvalue="menuStatusName" center="true">菜单状态
                                </th> -->
                                <th class="title" width="20%" itemvalue="clickcount" center="true">点击统计数
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 数据 -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <!-- <div id="pagerRoad" fun="checkRoad" class="pager" pagerobj="">
                </div> -->
            </div>
        </div>
        <div id="historyStatisticsDiv" class="dis-none">
            <div class="panel-body">
                <div class="form-inline mb10">
                    <label for="startTime">时间:</label>
                    <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="endTime">至</label>
                    <input type="text" class="form-control" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" id="check" onclick="checkHistoryStatistics();" class="btn btn-primary m-15" >
                </div>
                <div id="line" style="height:400px;width: 100%;"></div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="historyStatisticsTable">
                        <thead>
                            <tr>
                                <th class="title" width="95px" itemvalue="intime" center="true">日期
                                </th>
                                <th class="title" width="" itemvalue="interactive" center="true">微互动
                                </th>
                                <th class="title" width="" itemvalue="APPdownload" center="true">APP下载
                                </th>
                                <th class="title" width="" itemvalue="roadCheck" center="true">道路速查
                                </th>
                                <th class="title" width="" itemvalue="congestionIndex" center="true">拥堵指数
                                </th>
                                <th class="title" width="" itemvalue="myNeighborhood" center="true">我的附近
                                </th>
                                <th class="title" width="" itemvalue="report" center="true">我要报料
                                </th>
                                <th class="title" width="" itemvalue="service" center="true">人工客服
                                </th>
                                <th class="title" width="" itemvalue="checkIllegal" center="true">违法查询
                                </th>
                                <th class="title" width="" itemvalue="community" center="true">微社区
                                </th>
                                <th class="title" width="" itemvalue="personalCenter" center="true">个人中心
                                </th>
                                <th class="title" width="" itemvalue="mall" center="true">微商城
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
            <div class="panel-footer">
                <!-- <div id="pagerRemark" fun="checkRemark" class="pager" pagerobj="">
                </div> -->
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load();
    </script>
</body>
</html>