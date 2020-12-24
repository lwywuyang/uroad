<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>设备维护</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .form-group{margin: 8px 0px;}
        .sn-img{max-width: 120px;max-height: 120px;}
	</style>
    <script type="text/javascript" language="javascript">
        var page = 1;

        $().ready(function(){
            $('#statusSel').find('option[value=1]').attr('selected',true);

            Load(1);
        });

        function reLoad(){
            Load(page);
        }
        
        /**
         * @desc  拉取'设备维护'页面表格数据
         * @param {[string]}    t [页码,默认第一页]
         */
        function Load(t) {
            var road = $('#roadSel').val();
            var status = $('#statusSel').val();
            var search = $('#searchTxt').val();

            JAjax("admin/baseData/BaseDeviceLogic", 'onLoadBaseDevice', {page:t,road:road,status:status,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
                page = t;
            }, "pager");
        }

        function changeStatus(deviceid,status){
            JAjax("admin/baseData/BaseDeviceLogic", 'changeStatus', {deviceid:deviceid,status:status}, function (data) {
                if(data.Success)
                    Load(page);
                else
                    ShowMsg('提示：'+data.Message);
            }, null);
        }

        function checkDetail(deviceid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/BaseDeviceLogic/operateBaseDevice'); ?>?deviceid="+deviceid,'查看',630,350,reLoad);
        }

        function checkPic(url){
            showLayerImageJs(url);
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="roadSel">高速公路:</label>
                    <select class="form-control" id="roadSel" onchange="Load(1)">
                        <option value="">全部</option>
                        <?php foreach($roadold as $item):?>
                            <option value="<?=$item['roadoldid']?>"><?=$item['shortname']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statusSel">状态:</label>
                    <select class="form-control" id="statusSel" onchange="Load(1)">
                        <option value="">全部</option>
                        <option value="0">无效</option>
                        <option value="1">有效</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchTxt">关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="new" onclick="Load(1);" class="btn btn-primary m-15" >
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="devicename" center="true">设备名称
                            </th>
                            <th class="title" width="" itemvalue="sn" center="true">sn码
                            </th>
                            <th class="title" width="" itemvalue="roadname" center="true">所属路段
                            </th>
                            <th class="title" width="" itemvalue="status" center="true">状态
                            </th>
                            <th class="title" width="" itemvalue="pic" center="true">图片
                            </th>
                            <th class="title" width="" itemvalue="coor_x" center="true">经度
                            </th>
                            <th class="title" width="" itemvalue="coor_y" center="true">纬度
                            </th>
                            <th class="title" width="180px" itemvalue="operate" center="true">操作
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj=""></div>
        </div>
    </div>
</body>
</html>