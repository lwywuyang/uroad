<!DOCTYPE html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>高德路况</title>
    <?php $this->load->view('admin/common') ?>
    <style>
    a:hover, a:focus {
    color: #2a6496;
    text-decoration: none;
}
    .m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
        .m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .form-inline select{margin-right: 20px;}
        .photo-img{max-width: 50px;}
        a {cursor: pointer;text-decoration: none;}
        .heading-ul{width: 100%;height: 50px;line-height: 50px;padding: 0;}
        .heading-li{width: 50%;height: 50px;float: left;list-style: none;margin: 0 auto;border-radius: 3px;text-align: center;}
        .li-hover:hover{background-color: #D1DEF0;}
        .li-color{background-color: #428BCA;}
        .li-color a{color: white;}
        .heading-a{font-size: 24px;width: 100% !important;height: 100% !important;display: block;color: #428bca;}
        .dis-none{display: none;}
        .allnum{font-size: 18px;}
        #eventSpan,#serviceSpan,#trafficSpan,#etcSpan{color: #E2131C; margin-right: 20px;}
        .strong{float: left;line-height: 41px;}
        .form-inline select{margin-right: 20px;}
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .newcode-img{width: 18%}
        .autoheight{height: auto}
        #typeSel_chosen{width: 200px !important;}
        .font-red{color: red;}
        .modal-backdrop {
            opacity: 0 !important;
            filter: alpha(opacity=0) !important;
        }
        #fileList{
            float: left;
        }
        #filePicker{
            float: left;
            line-height: 19px;
            margin-right:3px;
        }
        .webuploader-pick {
            background: #4CADFF none repeat scroll 0 0;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            /*display: inline-block;*/
            overflow: hidden;
            padding: 0.23rem 16px;
            text-align: center;
        }
        .col-xs-6{height: 98px;padding-top: 0 !important;float: left;}
        .col-xs-6 > div{width: 100%;height: 95px;color: #696363;padding:10px 15px 20px 15px;border-radius: 2px;}
        .statistics-title{height: 30px;line-height: 30px;margin: 0;font-size: 16px;font-family: 'Microsoft YaHei';}
        .statistics-todaynum{height: 25px;line-height: 50px;font-size: 30px;margin: 0 auto;text-align: center;}
        .statistics-contrast{}
        .yesterday-num{font-size: 16px;float: right;}
        .yesterday-left{font-size: 16px;float: left;}
        .statistics-contrast{font-family: 'Microsoft YaHei';}
        .btnbule{
            background-color: #4CADFF;width:100px;
        }
        .label-warning{background-color: #ce1e1e;}
        .label-danger{background-color: #961b18;}
        #pref_mat{width: 580px;}
        #pref_mat dd a {color:#333}
        #dataGrid thead tr th,.font-style{
            color:rgb(74, 121, 206);font-weight: bold;
        }
        .nav-tabs {background: #AEADAD;}
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus { background-color: #fff; border: 0;border-radius: 0;}
        .nav-tabs > li > a {color: #fff;}
    </style>
    <script type="text/javascript" language="javascript">
        var page = 0;//当日页码
        var historypage = 0;//历史页码
        var ids = "<?php echo empty($ids)?'':$ids?>";
        var cztype = 0;
        var hasCheckcg = "<?php echo $hasCheckcg?>";
        // var nowpage = "<?php echo empty($nowpage)?'':$nowpage?>";
        // var cztypes = "<?php echo empty($selecttype)?0:$selecttype?>";
        $(function(){
            if(hasCheckcg==1){
                $("#dcgd").show();
                $("#historydcgd").show();
            }
            // cztype = cztypes;

        })
        function fye(t){
            if(cztype==1){
                page=t;
                Load(page,cztype);
            }else{
                historypage=t;
                Load(historypage,cztype);
            }
        }
        function reLoad(type) {
            if(type!=undefined){//点击按钮切换
                cztype = type;
            }else{//关闭弹窗刷新时
                type = cztype;
            }
            Load(page,type);
        }
        //当天高德提醒数据
        function Load(t,type) {
            var loadingLayerIndex=layer.load('加载中…'); 
            var status = "";//事件状态
            var ppstatus = "";//匹配状态
            var starttime = $("#txtstarttime").val();
            var endtime = $("#txtendtime").val();
            //判断是否为查看当天高德提醒数据 1-当天 2-历史
            var tablename = "";
            var pagename = "";
            var keyword = "";
            if(type==1){
                status = $('#tstatus').val();//事件状态
                ppstatus = $('#ppstatus').val();//匹配状态
                keyword = $('#keyword').val();//关键字
                $('#event').addClass('li-color');
                $('#event').removeClass('li-hover');
                $('#service').removeClass('li-color');
                $('#service').addClass('li-hover');

                $('#eventTable').removeClass('dis-none');
                $('#serviceTable').addClass('dis-none');
                $('#check1').removeClass('dis-none');
                $('#check2').addClass('dis-none');
                tablename = "dataGrid";
                pagename = "pager";
            }else{
                status = $('#Historytatus').val();//事件状态
                ppstatus = $('#lsppstatus').val();//匹配状态
                keyword = $('#keywordhistory').val();//关键字
                $('#event').removeClass('li-color');
                $('#event').addClass('li-hover');
                $('#service').addClass('li-color');
                $('#service').removeClass('li-hover');

                $('#eventTable').addClass('dis-none');
                $('#serviceTable').removeClass('dis-none');
                $('#check1').addClass('dis-none');
                $('#check2').removeClass('dis-none');
                tablename = "dataGridHistory";
                pagename = "Historypager";
                ids = "";
            }
            JAjax("admin/GaoDeLIst/GaoDeListLogic", 'onLoadGaoDeList', {page:t,starttime:starttime,endtime:endtime,status:status,ids:ids,type:type,ppstatus:ppstatus,keyword:keyword}, function (data) {
                    layer.close(loadingLayerIndex);
                    ReloadTb(tablename, data.data);
            }, pagename);
        }



        function refesh() {
            page = 1;
            JAjax("admin/UnifiedRelease/CollectTrafficListLogic", 'onLoadGaoDeList', {page:page}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "");
        }
         //操作
        function checkInfo(caozuoid,selecttype){
            cztype = selecttype;
            // var src = "<?php echo base_url('index.php/admin/UnifiedRelease/CollectTrafficLogic/getCommentById') ?>?id="+caozuoid;
            // $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
            showLayerPageJs("<?php echo base_url('index.php/admin/GaoDeLIst/GaoDeListLogic/getCommentById') ?>?id="+caozuoid+"&selecttype="+selecttype, '地图详细', 1250, 800, reLoad);
        }

        /**
         * 处理或查看事件
         * @Author   RaK
         * @DateTime 2017-05-05T12:54:44+0800
         * @param    {[type]}                 eventid     [事件ID]
         * @param    {[type]}                 type        [操作类型]
         * @param    {[type]}                 selecttype [当天还是历史数据]
         * @return   {[type]}                             [description]
         */
        function chuli(eventid,type,selecttype){
            var nowpage = historypage;
            if(selecttype==1){
                nowpage=page;
            }
            if(type==1){
                var src = "<?php echo base_url('index.php/admin/GaoDeLIst/GaoDeListLogic/gaoDeHandleList') ?>?eventid="+eventid+"&selecttype="+selecttype+"&type="+type+"&nowpage="+nowpage;
                $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
            }else{
                JAjax("admin/GaoDeLIst/GaoDeListLogic", 'fillInOperator', {eventid:eventid,selecttype:selecttype}, function (data) {
                    if(data.Success){
                        var src = "<?php echo base_url('index.php/admin/GaoDeLIst/GaoDeListLogic/gaoDeHandleList') ?>?eventid="+eventid+"&selecttype="+selecttype+"&type="+type+"&nowpage="+nowpage;
                        $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
                    }else{
                        ShowMsg(data.Message);
                    }
                }, null,false);
            }
        }

        function updatetsstatus(tsid,tsstatus){
            JAjax("admin/UnifiedRelease/InfoReleaseLogic", 'updatetsstatus', {tsid:tsid,tsstatus:tsstatus}, function (data) {

            }, null);
        }

        function getExcel(type){
            var status = "";//事件状态
            var ppstatus = "";//匹配状态
            var starttime = $("#txtstarttime").val();
            var endtime = $("#txtendtime").val();
            //判断是否为查看当天高德提醒数据 1-当天 2-历史
            var tablename = "";
            var pagename = "";
            var keyword = "";
            if(type==1){
                status = $('#tstatus').val();//事件状态
                ppstatus = $('#ppstatus').val();//匹配状态
                keyword = $('#keyword').val();//关键字
            }else{
                status = $('#Historytatus').val();//事件状态
                ppstatus = $('#lsppstatus').val();//匹配状态
                keyword = $('#keywordhistory').val();//关键字
                ids = "";
            }
            JAjax("admin/GaoDeLIst/GaoDeListLogic", 'getTrafficdataExcel', {starttime:starttime,endtime:endtime,status:status,ids:ids,type:type,ppstatus:ppstatus,keyword:keyword}, function (data) {
                if(data.Success){
                    var name=data.data['name']
                    location.href="<?php echo base_url('excel/"+name+"') ?>";
                    $("#daochu").val("导出订单");
                    $("#tixing").hide();
                }else{
                    ShowMsg(data.Message);
                }
            }, null,true);
        }


</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="event"><a onclick="reLoad(1);" class="heading-a">当天提醒</a></li>
                    <li class="heading-li" id="service"><a onclick="reLoad(2);" class="heading-a">历史提醒</a></li>
                </ul>
            </div>
            <!--当天提醒-->
            <div class="table-responsive" id="eventTable">
                <div class="panel-body">
                    <div class="form-inline mb10">
                        <strong class="strong">事件状态:</strong>
                        <select class="form-control" id="tstatus"  style="float: left;width: 10%;min-width: 0;" onchange="reLoad(1)">
                            <option value="">全部</option>
                            <?php foreach($tsstatus as $item): ?>
                                <option value="<?php echo $item['code'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?>
                           <!--  <option value="1">未处理</option>
                            <option value="2">已处理</option>
                            <option value="3">无效</option> -->
                        </select>
                        <strong class="strong">匹配状态:</strong>
                        <select class="form-control" id="ppstatus"  style="float: left;width: 10%;min-width: 0;" onchange="reLoad(1)">
                            <option value="">全部</option>
                            <option value="0">未匹配</option>
                            <option value="1">匹配成功</option>
                            <option value="2">匹配失败</option>
                        </select>
                        <strong class="strong">关键字:</strong>
                        <input type="text" class="form-control" id="keyword" >
                        <input type="button" value="查 询" id="load1" class="btn btn-info m-10 m-l-10 btnbule" onclick="reLoad(1);" >
                        <input type="button" id="dcgd" style="display:none" value="导出列表" class="btn btn-info m-10 m-l-10 btnbule" onclick="getExcel(1);" >
                    </div>
                </div>
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="15%" itemvalue="roadname" center="true" >高德名称
                            </th>
                            <th class="title"  width="15%" itemvalue="shortname" center="true" >道路名称
                            </th>
                            <th class="title"  width="20%" itemvalue="ydqj" center="true" >拥堵区间
                            </th>
                            <th class="title"  width="10%" itemvalue="jamdist" center="true" >拥堵距离
                            </th>
                            <th class="title"  width="6%" itemvalue="jamspeed" center="true" >时速
                            </th>
                            <th class="title" width="15%" itemvalue="createtime" center="true" showformat="yyyy-MM-dd hh:mm">创建时间
                            </th>
                            <th class="title" width="10%" itemvalue="pubRunStatus" center="true">拥堵状态
                            </th>
                            <th class="title"  width="10%" itemvalue="operatorname" center="true">处理人
                            </th>
                            <th class="title"  width="10%" itemvalue="eventstatusname" center="true">事件状态
                            </th>
                            <th class="title"  width="10%" itemvalue="sctime" showformat="yyyy-MM-dd hh:mm" center="true">首次提醒时间
                            </th>
                            <th class="title"  width="10%" itemvalue="operatortime" center="true" showformat="yyyy-MM-dd hh:mm">处理时间
                            </th>
                            <th class="title"  width="15%" itemvalue="caozuo" center="true" maxlength='6000'>操作
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
                <div id="pager" fun="fye" class="pager" pagerobj="">
                </div>
            </div>
            <!--历史提醒-->
            <div class="table-responsive dis-none" id="serviceTable">
                <div class="panel-body">
                    <div class="form-inline mb10">
                        <label for="txtstarttime">创建时间(始):</label>
                        <input type="text" class="form-control" id="txtstarttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                        <label for="txtendtime">创建时间(终):</label>
                        <input type="text" class="form-control" id="txtendtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                        <strong class="strong">事件状态:</strong>
                        <select class="form-control" id="Historytatus"  style="float: left;width: 10%;min-width: 0;" onchange="reLoad(2)">
                            <option value="">全部</option>
                            <?php foreach($tsstatus as $item): ?>
                                <option value="<?php echo $item['code'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?>
                           <!--  <option value="1">未处理</option>
                            <option value="2">已处理</option>
                            <option value="3">无效</option> -->
                        </select>
                        <strong class="strong">匹配状态:</strong>
                        <select class="form-control" id="lsppstatus"  style="float: left;width: 10%;min-width: 0;" onchange="reLoad(2)">
                            <option value="">全部</option>
                            <option value="0">未匹配</option>
                            <option value="1">匹配成功</option>
                            <option value="2">匹配失败</option>
                        </select>
                    </div>
                    <div class="form-inline mb10">
                        <strong class="strong">关键字:</strong>
                        <input type="text" class="form-control" id="keywordhistory" >
                        <input type="button" value="查 询" id="load1" class="btn btn-info m-10 m-l-10 btnbule" onclick="reLoad(2);" >
                        <input type="button" id="historydcgd" style="display:none" value="导出列表" class="btn btn-info m-10 m-l-10 btnbule" onclick="getExcel(2);" >
                    </div>
                </div>
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGridHistory">
                    <thead>
                        <tr>
                            <th class="title"  width="15%" itemvalue="roadname" center="true" >高德名称
                            </th>
                             <th class="title"  width="15%" itemvalue="shortname" center="true" >道路名称
                            </th>
                            <th class="title"  width="20%" itemvalue="ydqj" center="true" >拥堵区间
                            </th>
                            <th class="title"  width="10%" itemvalue="jamdist" center="true" >拥堵距离
                            </th>
                            <th class="title"  width="6%" itemvalue="jamspeed" center="true" >时速
                            </th>
                            <th class="title" width="15%" itemvalue="createtime" center="true" showformat="yyyy-MM-dd hh:mm">创建时间
                            </th>
                            <th class="title" width="10%" itemvalue="pubRunStatus" center="true">拥堵状态
                            </th>
                             <th class="title"  width="10%" itemvalue="operatorname" center="true">处理人
                            </th>
                            <th class="title"  width="10%" itemvalue="eventstatusname" center="true">事件状态
                            </th>
                            <!--  <th class="title"  width="10%" itemvalue="sctime" showformat="yyyy-MM-dd hh:mm" center="true">首次处理时间
                            </th> -->
                            <th class="title"  width="10%" itemvalue="operatortime" center="true" showformat="yyyy-MM-dd hh:mm">处理时间
                            </th>
                            <th class="title"  width="15%" itemvalue="caozuo" center="true" maxlength='6000'>操作
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
                <div id="Historypager" fun="fye" class="pager" pagerobj="">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        reLoad(1);
        // $(function(){ 
        // setInterval("refesh();",120000); //每隔一秒执行一次 
        // });
    </script>
</body>
</html>