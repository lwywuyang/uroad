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
                    <li class="heading-li" id="UnitLi"><a onclick="statisticsByUnit();" class="heading-a">按管理单位统计</a></li>
                    <li class="heading-li" id="TypeLi"><a onclick="statisticsByType();" class="heading-a">按事件性质统计</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <!--按管理单位统计-->
            <div class="table-responsive" id="UnitDiv">
                <div class="form-inline mb10">
                    <label for="UnitStartTime">日期:</label>
                    <input type="text" class="form-control" id="UnitStartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="UnitEndTime">至</label>
                    <input type="text" class="form-control m-10" id="UnitEndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" onclick="statisticsByUnit();" class="btn btn-primary" />
                </div>
                <div id="UnitPie" style="height: 500px;width: 99%;"></div>
                <table class="table table-hover table-bordered dataTable" id="UnitTable">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="firstreleaseperson" center="true">部门
                            </th>
                            <th class="title" width="" itemvalue="num" center="true">所有事件数
                            </th>
                            <th class="title" width="" itemvalue="num03" center="true">待审核事件数
                            </th>
                            <th class="title" width="" itemvalue="num45" center="true">发布中事件数
                            </th>
                            <th class="title" width="" itemvalue="num06" center="true">已结束事件数
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <!--按事件性质统计-->
            <div class="table-responsive" id="TypeDiv">
                <div class="form-inline mb10">
                    <label for="TypeStartTime">日期:</label>
                    <input type="text" class="form-control" id="TypeStartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="TypeEndTime">至</label>
                    <input type="text" class="form-control m-10" id="TypeEndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <input type="button" value="查 询" id="checkWechat" onclick="statisticsByType();" class="btn btn-primary">
                </div>
                <div id="TypePie" style="height: 500px;width: 99%;"></div>
                <table class="table table-hover table-bordered dataTable" id="TypeTable">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="eventcausename" center="true">事件类型
                            </th>
                            <th class="title" width="" itemvalue="num" center="true">事件数
                            </th>
                            <th class="title" width="" itemvalue="num03" center="true">待审核事件数
                            </th>
                            <th class="title" width="" itemvalue="num45" center="true">发布中事件数
                            </th>
                            <th class="title" width="" itemvalue="num06" center="true">已结束事件数
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

        statisticsByUnit();
    });


    /***************按管理单位统计***************/
    //获取内容
    function statisticsByUnit(){
        $('#UnitLi').addClass('li-color');
        $('#UnitLi').removeClass('li-hover');

        $('#TypeLi').removeClass('li-color');
        $('#TypeLi').addClass('li-hover');

        $('#UnitDiv').removeClass('hidden');
        $('#TypeDiv').addClass('hidden');


        var UnitStartTime = $('#UnitStartTime').val();
        var UnitEndTime = $('#UnitEndTime').val();
        JAjax("admin/Statistics/EventStatisticsLogic",'onLoadUnitStatisticsMsg',{UnitStartTime:UnitStartTime,UnitEndTime:UnitEndTime}, function (data) {

            var UnitName = eval('('+data.data.unit+')');
            var UnitData = eval('('+data.data.piedata+')');

            setUnitStatisticsPie(UnitName,UnitData);

            ReloadTb('UnitTable', data.data.table);
        }, null);
    }

    //输出按管理单位统计表
    function setUnitStatisticsPie(UnitName,UnitData){

        // 基于准备好的dom，初始化echarts图表
        var UnitPie = echarts.init(document.getElementById('UnitPie'));//,'macarons'
        
        UnitOption = {
            title : {
                text: '所有事件数据统计饼图',
                subtext: '按管理单位统计',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: UnitName
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
                    data:UnitData,
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
        UnitPie.hideLoading();
        UnitPie.setOption(UnitOption);
    }


    /***************Wechat***************/
    //获取内容
    function statisticsByType(){
        $('#TypeLi').addClass('li-color');
        $('#TypeLi').removeClass('li-hover');
        $('#UnitLi').removeClass('li-color');
        $('#UnitLi').addClass('li-hover');
        $('#TypeDiv').removeClass('hidden');
        $('#UnitDiv').addClass('hidden');

        var TypeStartTime = $('#TypeStartTime').val();
        var TypeEndTime = $('#TypeEndTime').val();

        JAjax("admin/Statistics/EventStatisticsLogic",'onLoadTypeStatisticsMsg',{TypeStartTime:TypeStartTime,TypeEndTime:TypeEndTime}, function (data) {
            var TypeName = eval('('+data.data.type+')');
            var TypeData = eval('('+data.data.piedata+')');

            setTypeStatisticsPie(TypeName,TypeData);

            ReloadTb('TypeTable', data.data.table);
        }, null);
    }

    function setTypeStatisticsPie(TypeName,TypeData){
        
        // 基于准备好的dom，初始化echarts图表
        var TypePie = echarts.init(document.getElementById('TypePie'));//,'macarons'
        
        TypeOption = {
            title : {
                text: '所有事件数据统计饼图',
                subtext: '按事件性质统计',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: TypeName
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
                    name: '事件性质',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data: TypeData,
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
        TypePie.hideLoading();
        TypePie.setOption(TypeOption);
    }

</script>
</html>