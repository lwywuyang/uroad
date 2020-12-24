<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .publishmap-image{max-width: 100px;max-height: 100px;}
	</style>
    <script type="text/javascript" language="javascript">
        var page = 1;
        
        function reLoad() {
            Load(page);
        }

        /**
         * [Load 加载基础数据]
         * @param {[type]} t [description]
         */
        function Load(t) {
            page=t;
            var search = $('#searchTxt').val();
            JAjax("admin/baseData/FileVerLogic", 'onLoadFileVer', {page:page,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        /**
         * @desc   '数据版本控制'页面->点击更新,弹出更新数据版本子窗口
         * @data   2015-9-30 10:52:55
         * @param  {[type]}    fileid [description]
         */
        function changeMsg(fileid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/FileVerLogic/changeMsgLogic?fileid='); ?>"+fileid,'更新',500,295,reLoad);
        }

        function changeMsg2(fileid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/FileVerLogic/changeMsgLogic?isMap=1&fileid='); ?>"+fileid,'更新',500,295,reLoad);
        }

</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="searchTxt">  关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary m-20" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <!-- <th class="title"  width="3%" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th> -->
                            <th class="title" width="7%" itemvalue="name" center="true">版本名称
                            </th>
                            <!-- <th class="title" width="7%" itemvalue="filename" center="true">版本编号
                            </th>
                            <th class="title" width="5%" itemvalue="dataType" center="true">数据类型
                            </th> -->
                            <th class="title" width="5%" itemvalue="verno" center="true">版本号
                            </th>
                            <th class="title" width="5%" itemvalue="updatetime" center="true" showformat="yyyy-MM-dd hh:mm">更新时间
                            </th>
                            <th class="title" width="15%" itemvalue="remark" center="true">备注
                            </th>
                            <!-- <th class="title" width="5%" center="true" showtype="a" attr="onclick= changeMsg('{fileid}') href='javascript:void(0)' " itemtext="更新">操作
                            </th> -->
                            <th class="title" width="30px" itemvalue="operate" center="true">操作
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
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>