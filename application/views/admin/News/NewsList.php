<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <META HTTP-EQUIV="pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
    <META HTTP-EQUIV="expires" CONTENT="0">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript" language="javascript">
    var page = 1;
    var newstype='<?php echo $newstype ?>';

    var newstypeArray = ['1006008','1011018','1011013','1011015','1011016','1011017'];

    var error = '<?php echo isset($error)?$error:'' ?>';

    $().ready(function(){
        if (error != '') {
            ShowMsg(error);
        }
        if (newstype == '1011003' || newstype == '1011008') {
            $('#subtypelabel').removeClass('dis-none');
            $('#subtypeSel').removeClass('dis-none');
        }

        /*if (jQuery.inArray(newstype,newstypeArray) != '-1') {
            $('#seqTh').removeClass('hidden');
            $('#seqTh').attr('hide','false');
        }*/
    });
    
    function reLoad() {
        Load(page);
    }

    function Load(t) {
        page = t;
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        var keyword = $('#keyword').val();
        var typeSel = $('#typeSel').val();
        var subtypeSel = $('#subtypeSel').val();

        JAjax("admin/News/NewsLogic", 'onLoadNews', {newstype:newstype,page:t,startTime:startTime,endTime:endTime,keyword:keyword,typeSel:typeSel,subtypeSel:subtypeSel}, function (data) {
            //if (data.Success)
                ReloadTb('dataGrid', data.data);
            //else
                //ShowMsg(data.Message);
        }, "pager");
    }

    function detail(id) {
        showLayerPageJs("<?php echo base_url('/index.php/admin/News/NewsLogic/detail') ?>?id="+id+"&newstype="+newstype, '详细信息', 900, 600, reLoad);
    }

    /*删除函数*/
    function deleteInfo() {
        //获取选中选框,属性的name元素,dataGrid上下文对象
        var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
        var titles = '';
        var values_arr = values.split(',');
        var values_arr_length = values_arr.length;
        for(var i = 0;i<values_arr_length;i++){
            var dataid = values_arr[i].replace(/\'/g,"");
            titles+=','+$('#news'+dataid).data('title');

        }
        titles = titles.substring(1);


        if (values != "" && values != undefined) {
            ShowConfirm("您确定要删除吗？", function () {
                JAjax("admin/News/NewsLogic", "delNews", { OID: values,titles:titles}, function (data) {
                    if (data.Success)
                        reLoad();
                    else
                        ShowMsg("提示:" + data.Message);
                }, null);
            });
        }else{
            ShowMsg("请至少选择一条记录！");
        }
    }



    function showLayerImage(url){
        window.parent.showLayerImage(url);
    }

    function statuschange(id,status,title){
        console.log(title);
        JAjax("admin/News/NewsLogic", "statuschange", { id: id,newstype:newstype,status:status,title:title}, function (data) {
            if (data.Success)
                reLoad();
            else
                ShowMsg("提示:" + data.Message);
        }, null);
    }

    //预览
    function read(id,url){
        if (url == '') {
            showLayerPageJs("http://hunangstapi.u-road.com/HuNanGSTAppAPIServer/index.php?/news/getnewsdetail?newsid="+id, '信息', 400, 600, reLoad);
        }else{
            showLayerPageJs(url, '信息', 600, 900, reLoad);
        }
        
    }

    function pushTop(id,title){
        JAjax("admin/News/NewsLogic", "pushNewsToTop", { id: id,title:title}, function (data) {
            if (data.Success)
                reLoad();
            else
                ShowMsg("提示:" + data.Message);
        }, null);
    }

    function putUpOrDown(id,up){
        JAjax("admin/News/NewsLogic", "putUpOrDown", {id:id,up:up}, function (data) {
            if (data.Success)
                reLoad();
            else
                ShowMsg("提示:" + data.Message);
        }, null);
    }


    </script>
    <style>
    .m-5{margin-right: 5px;}
    .m-10{margin-right: 10px;}
    .dis-none{display: none!important;}
    .red-font,.green-font,.blue-font{margin: 0px 5px;font-weight: bolder;}
    .red-font{color: #DD4F43;}
    .green-font{color: #19A15F;}
    .blue-font{color: #008AD4;}
    .btn-xs{margin: 5px;}
    </style>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <label for="startTime">时间:</label>
                <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="endTime">至</label>
                <input type="text" class="form-control m-10" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="keyword">关键字:</label>
                <input type="text" class="form-control m-10" id="keyword" placeholder="请输入关键字">
                <label for="typeSel">状态:</label>
                <select class="form-control m-10" id="typeSel" onchange="Load(1);">
                    <option value="">全部</option>
                    <option value="1012001">登记</option>
                    <option value="1012004">发布</option>
                </select>
                <label for="subtypeSel" id="subtypelabel" class="dis-none">细分类型:</label>
                <select class="dis-none form-control m-10" id="subtypeSel" onchange="Load(1);">
                    <option value="">全部</option>
                    <?php foreach($subnewstype as $item): ?>
                        <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                    <?php endforeach;?>
                </select>
                <input type="button" value="查 询" id="new" onclick="Load(1);" class="btn btn-primary m-10" >
                <input type="button" value="新 增" id="new" onclick="detail(0);" class="btn btn-info m-10" >
                <input type="button" value="撤 销" id="del" onclick="deleteInfo();" class="btn btn-danger" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="3%" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' id='news{id}' data-title='{title}' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title"  width="10%" itemvalue="jpgurl" center="true" maxlength='6000'>封面图 
                            </th>
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
                            <th class="title"  width="6%" itemvalue="seq" center="true" >排序<!-- id="seqTh" hide="true" -->
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
        Load(1);
    </script>
</body>
</html>