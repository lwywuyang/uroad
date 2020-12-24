<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>广告图</title>
    <?php $this->load->view('admin/common') ?>
    <style type="text/css">
        .m-5{margin-right: 5px; margin-bottom: 5px;}
        .m-10{margin-right: 10px;}
        .ad-image{max-width: 80px; max-height: 80px;}
    </style>
</head>
<body marginwidth="0" marginheight="0">
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="form-inline">
            <div class="form-group">
                <label for="StartTime">修改时间:</label>
                <input type="text" class="form-control" id="StartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="EndTime">至</label>
                <input type="text" class="form-control m-10" id="EndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="stateSel">状态:</label>
                <select id="stateSel" class="form-control m-10" onchange="Load(1);">
                    <option value="">全部</option>
                    <option value="1">发布中</option>
                    <option value="0">未发布</option>
                </select>
                <input type="button" value="查 询" onclick="Load(0);" class="btn btn-primary m-10">
                <input type="button" value="新 增" onclick="detail(0);" class="btn btn-info m-10">
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered dataTable" id="dataGrid">
                <thead>               
                    <tr>
                        <th class="title" width="" itemvalue="imageurl">广告图
                        </th>
                        <th class="title" width="" itemvalue="redirecturl">链接
                        </th>
                        <th class="title" width="" itemvalue="state">状态
                        </th>
                        <th class="title" width="" itemvalue="seq">排序
                        </th>
                        <th class="title" width="" itemvalue="created" showformat="yyyy-MM-dd hh:mm:ss">创建时间
                        </th>
                        <th class="title" width="" itemvalue="modified" showformat="yyyy-MM-dd hh:mm:ss">修改时间
                        </th>
                        <th class="title" width="150px" itemvalue="operate">操作
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" language="javascript">
    var page = 0;
    function reLoad() {
        Load(page);
    }
    function Load(t) {
        page = t;
        var StartTime = $("#StartTime").val();
        var EndTime = $("#EndTime").val();
        var stateSel = $("#stateSel").val();

        JAjax("admin/baseData/AdPicLogic", 'onLoadAdPic', {page:t,StartTime:StartTime,EndTime:EndTime,stateSel:stateSel}, function (data) {
            ReloadTb('dataGrid', data.data);
        }, "pager");
    }
    Load(0);

   
     function detail(id) {
        showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/AdPicLogic/detailAdPic') ?>?id="+id, '网点信息', 750, 500, reLoad);
    }


    function changeState(id,state) {

        JAjax("admin/baseData/AdPicLogic", 'setNewState', {id:id,state:state}, function (data) {
            if(data.Success)
                Load(page);
            else
                ShowMsg('Tips:'+data.Message);
        }, null);
    }

</script>
</html>