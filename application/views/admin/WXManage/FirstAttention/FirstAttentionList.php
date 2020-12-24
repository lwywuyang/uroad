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
        .strong{float: left;line-height: 41px;}
        .form-inline select{margin-right: 20px;}
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .photo-img{max-width: 50px;}
        a {cursor: pointer;}
        .picture{max-width: 50px;}
        .btn-xs{margin: 1px 0px;}
	</style>
</head>
<body marginwidth="0" marginheight="0">
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="form-inline mb10">
            <input type="button" value="增 加" id="check" onclick="addMsg();" class="btn btn-primary m-15" >
            <input type="button" value="删 除" id="check" onclick="deleteMsg();" class="btn btn-danger m-15" >
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                <thead>
                    <tr>
                        <th class="title" width="30px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                        </th>
                        <th class="title" width="6%" itemvalue="sort">序号
                        </th>
                        <th class="title" itemvalue="picture" width="70px" showtype="a" attr="onclick= showLayerImage('{imgurl}') href='javascript:void(0)'" itemtext="{itemvalue}">封面
                        </th>
                        <th class="title" nowrap="nowrap" itemvalue="title" showtype="a" attr="onclick= detailMsg('{id}') href='javascript:void(0)'" itemtext="{itemvalue}">标题  
                        </th>
                        <th class="title" width="10%" itemvalue="pubtime" showformat="yyyy-MM-dd hh:mm">发布时间
                        </th>
                        <th class="title" width="70px" itemvalue="statusName" >状态
                        </th>
                        <th class="title" width="8%" itemvalue="viewcount">浏览数
                        </th>
                        <th class="title" width="12%" itemvalue="statuschange">操作
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!-- panel-body -->
    <div class="panel-footer">
        <div id="pager" fun="Load" class="pager" pagerobj="">
        </div>
    </div>
</div>
</body>
<script type="text/javascript" language="javascript">
    var page = 1;
    
    function reLoad() {
        Load(page);
    }

    function subValueStr(str){
        return str.substring(0,(str.length-1));
    }

    function trimStr(str){
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
    
    function Load(t) {
        page=t;
        
        JAjax("admin/WXManage/FirstAttentionLogic", 'onLoadFirstAttentionMsg', {page:page}, function (data) {
            ReloadTb('dataGrid', data.data);
        }, "pager");
    }
    Load(1);

    function detailMsg(id){
        showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/FirstAttentionLogic/detailnew') ?>?id="+id, '查看', 800, 600, reLoad);
    }

    function addMsg(id){
        showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/FirstAttentionLogic/detailnew?id=0') ?>",'新增',800,600,reLoad);
    }


    function deleteMsg() {
        //获取选中选框,属性的name元素,dataGrid上下文对象
        var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
        if (values != "" && values != undefined) {
            ShowConfirm("您确定要删除吗？", function () {
                JAjax("admin/WXManage/FirstAttentionLogic", "delnew", { OID: values}, function (data) {
                    if (data.Success) {
                        reLoad();
                    }
                    else {
                        ShowMsg("删除失败：" + data.Message);
                    }
                }, "pager");
            });
        }
        else {
            ShowMsg("请至少选择一条记录！");
        }
    }  
    function changestatus(newid,type){
        
        //ShowConfirm("您确定要此操作吗？", function () {
        //console.log(newid+','+type+','+id);
            JAjax("admin/WXManage/FirstAttentionLogic", "statuschange", {id:newid,type:type}, function (data) {
                if (data.Success) {
                    reLoad();
                }else {
                    ShowMsg("失败" + data.Message);
                }
            }, null);

        //});

    }

    function showLayerImage(url){
        window.parent.showLayerImage(url);
    }

</script>
</html>