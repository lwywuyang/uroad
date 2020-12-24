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
            var startTime1 = $('#startTime1').val();
            var endTime1 = $('#endTime1').val();
            JAjax("admin/WXManage/WXClickLogic", 'onLoadMsg_WXMenuStatistics', {startTime:startTime1,endTime:endTime1}, function (data) {
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
            JAjax("admin/WXManage/WXClickLogic", 'onLoadMsg_HistoryStatistics', {startTime:startTime,endTime:endTime}, function (data) {
                intime = data.data['intime'].split(',');
                ETCProfessional = data.data['ETCProfessional'].split(',');
                TravelServices = data.data['TravelServices'].split(',');
                PrizeActivity = data.data['PrizeActivity'].split(',');
                Tips = data.data['Tips'].split(',');
                AboutOurselves = data.data['AboutOurselves'].split(',');
                HistoryMessage = data.data['HistoryMessage'].split(',');
                SnatchRedPackage = data.data['SnatchRedPackage'].split(',');
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
                            data: ['ETC业务','出行提示','有奖活动','友情提示','关于我们','历史图文消息','抢红包']
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
                        //var intime,ETCProfessional,TravelServices,PrizeActivity,Tips,AboutOurselves,HistoryMessage,SnatchRedPackage,,,
                        series: [
                            {
                                name: 'ETC业务',
                                type: 'line',
                                data: ETCProfessional
                            },
                            {
                                name: '出行提示',
                                type: 'line',
                                data: TravelServices
                            },
                            {
                                name: '有奖活动',
                                type: 'line',
                                data: PrizeActivity
                            },
                            {
                                name: '友情提示',
                                type: 'line',
                                data: Tips
                            },
                            {
                                name: '关于我们',
                                type: 'line',
                                data: AboutOurselves
                            },
                            {
                                name: '历史图文消息',
                                type: 'line',
                                data: HistoryMessage
                            },
                            {
                                name: '抢红包',
                                type: 'line',
                                data: SnatchRedPackage
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
                <div class="form-inline mb10">
                    <label for="startTime1">时间:</label>
                    <input type="text" class="form-control" id="startTime1" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="endTime1">至</label>
                    <input type="text" class="form-control" id="endTime1" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" id="check" onclick="checkWXMenuStatistics();" class="btn btn-primary" >
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="WXMenuStatisticsTable">
                        <thead>
                            <tr>
                                <th class="title" width="15%" itemvalue="title" center="true">菜单名称
                                </th>
                                <th class="title" width="15%" itemvalue="itypeName" center="true">菜单类型
                                </th>
                                <th class="title" width="15%" itemvalue="menuStatusName" center="true">菜单状态
                                </th>
                                <th class="title" width="20%" itemvalue="clickNum" center="true">点击统计数
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
                                <th class="title" width="80px" itemvalue="intime" center="true">日期
                                </th>
                                <th class="title" width="10%" itemvalue="ETCProfessional" center="true">ETC业务
                                </th>
                                <th class="title" width="10%" itemvalue="TravelServices" center="true">出行服务
                                </th>
                                <th class="title" width="10%" itemvalue="PrizeActivity" center="true">有奖活动
                                </th>
                                <th class="title" width="10%" itemvalue="Tips" center="true">友情提示
                                </th>
                                <th class="title" width="10%" itemvalue="AboutOurselves" center="true">关于我们
                                </th>
                                <th class="title" width="10%" itemvalue="HistoryMessage" center="true">历史图文消息
                                </th>
                                <th class="title" width="10%" itemvalue="SnatchRedPackage" center="true">抢红包
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