<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript" language="javascript">
    var page = 1;
    var thisPage = 1;

    function reLoad() {
        Load(page);
    }

    function Load(t) {
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();

        JAjax("admin/baseData/WeatherReportLogic", 'onLoadWeatherReport', {page:t,startTime:startTime,endTime:endTime}, function (data) {
            ReloadTb('dataGrid', data.data);
            page = t;
        }, "pager");
    }

    function detail(id) {
        showLayerPageJs("<?php echo base_url('/index.php/admin/News/NewsLogic/detail') ?>?id="+id+"&newstype="+newstype, '新闻信息', 800, 600, reLoad);
    }

    /*删除函数*/
    function deleteInfo() {
        //获取选中选框,属性的name元素,dataGrid上下文对象
        var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
        if (values != "" && values != undefined) {
            ShowConfirm("您确定要删除吗？", function () {
                JAjax("admin/News/NewsLogic", "delNews", { OID: values}, function (data) {
                    if (data.Success)
                        reLoad();
                    else
                        ShowMsg("error:" + data.Message);
                }, null);
            });
        }else{
            ShowMsg("请至少选择一条记录！");
        }
    }

    function showLayerImage(url){
        window.parent.showLayerImage(url);
    }

    function statuschange(id,status){
        JAjax("admin/News/NewsLogic", "statuschange", { id: id,newstype:newstype,status:status}, function (data) {
            if (data.Success)
                reLoad();
            else
                ShowMsg("error:" + data.Message);
        }, null);
    }

    //预览
    function read(id){
        //var id=e.id;
        showLayerPageJs("http://test.u-road.com/HuNanGSTAppAPIServer/index.php?/news/getnewsdetail?newsid="+id, '信息', 400, 600, reLoad);
    }

    function pushTop(id){
        JAjax("admin/News/NewsLogic", "pushNewsToTop", { id: id}, function (data) {
            if (data.Success)
                reLoad();
            else
                ShowMsg("error:" + data.Message);
        }, null);
    }

    </script>
    <style>
    .m-5{margin-right: 5px;}
    .m-15{margin-right: 10px;}
    .dis-none{display: none!important;}
    .red-font,.green-font,.blue-font{margin: 0px 5px;font-weight: bolder;}
    .red-font{color: #DD4F43;}
    .green-font{color: #19A15F;}
    .blue-font{color: #008AD4;}
    </style>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="startTime">时间:</label>
                    <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <label for="endTime">至</label>
                    <input type="text" class="form-control m-15" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="new" onclick="Load(1);" class="btn btn-primary m-15" >
                </div>
                <div class="form-group">
                    <input type="button" value="新 增" id="new" onclick="detail(0);" class="btn btn-info m-15" >
                </div>
                <!-- <input type="button" value="删 除" id="del" onclick="deleteWeatherReport();" class="btn btn-danger" > -->
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="30px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title"  width="10%" itemvalue="jpgurl" center="true" maxlength='6000'>封面图 
                            </th>
                            <!-- <th class="title"  nowrap="nowrap" itemvalue="title" center="true" showtype="a" attr="onclick= detail('{id}') href='javascript:void(0)'" itemtext="{itemvalue}">标题
                            </th> -->
                            <th class="title" itemvalue="title" center="true">标题
                            </th>
                            <th class="title" width="10%" itemvalue="intime" center="true" showformat="yyyy-MM-dd hh:mm">创建时间
                            </th>
                            <th class="title" width="5%" itemvalue="statusname" center="true">状态
                            </th>
                            <!-- <th class="title"  width="6%" itemvalue="commentcount" center="true">评论数 
                            </th> -->
                            <th class="title"  width="6%" itemvalue="viewcount" center="true">浏览数
                            </th>
                            <th class="title"  width="15%" itemvalue="statuschange" center="true" maxlength='10000'>操作
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
                <div id="pager" fun="Load" class="pager" pagerobj="">
                </div>
            </div>
        </div>
        <!-- panel-body -->
    </div>
    <script type="text/javascript" language="javascript">
        Load(0);
    </script>
</body>
</html>