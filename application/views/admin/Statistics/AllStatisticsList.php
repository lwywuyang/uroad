<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/echarts-2.2.7/build/dist/echarts.js') ?>"></script>
    <script type="text/javascript" language="javascript">
        var interactive,APPdownload,roadCheck,congestionIndex,myNeighborhood,report,service,checkIllegal,community,personalCenter,mall;
        var nowDay,lastDay;

        function subValueStr(str){
            return str.substring(0,(str.length-1));
        }

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
        
        function Load() {
            //checkAllStatistics();
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



        //获取内容
        function checkAllStatistics(){


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
<style>
    .m-10{margin-right:10px;}
    .table{margin-bottom: 0;}
    .form-inline select{margin-right: 20px;}
    a {cursor: pointer;}
    .allstatistics-table{width: 100%;height: 600px;}
    /* .allstatistics-table tr td{padding: 10px;} */
    .allstatistics-table tr td div{width: 220px;height: 150px;color: white;margin: 10px;float: left;border-radius: 2px;}
    .panel-heading{color: #FF634D !important;font-size: 18px;}
    .today-publish-amount{background-color: #3DA3EF;}
    .today-multiservice{background-color: #FF634D;}
    .td-title{height: 40px;line-height: 30px;font-size: 22px;padding: 5px 10px;font-family: 'Microsoft YaHei';}
    .today-num{height: 100px;line-height: 85px;font-size: 45px;margin: 0 auto;text-align: center;}
    .yesterday-num{font-size: 18px;}
    #publishAmountLine,#multiServiceLine{border:1px red solid;}
</style>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">系统汇总统计</div>
        <div class="panel-body">
            <table cellspacing="0" cellpadding="0" class="vc_table allstatistics-table">
                <tr style="height: 170px;">
                    <td width='100%' colspan="2" style="padding-top: 0 !important;">
                        <div class="today-publish-amount">
                            <p class="td-title">今日发布量</p>
                            <p class="today-num">99999      <span class="yesterday-num">(昨日666)</span></p>
                        </div>
                        <div class="today-multiservice">
                            <p class="td-title">今日多客服</p>
                            <p class="today-num">8888       <span class="yesterday-num">(昨日7777)</span></p>
                        </div>
                    </td>
                </tr>
                <tr style="height: 450px;">
                    <td width='50%'>
                        <div id="publishAmountLine" style="height:400px;width: 95%;"></div>
                    </td>
                    <td width='50%'>
                        <div id="multiServiceLine" style="height:400px;width: 95%;"></div>
                    </td>
                </tr>
            </table> 
            <!-- panel-body -->
        </div>
        
    </div>
    <script type="text/javascript" language="javascript">
        Load();
    </script>
</body>
</html>