<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
		.m-15{margin-right:15px;}
        .table{margin-bottom: 0}
        .picture{max-height: 150px;}
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
            JAjax("admin/baseData/DevicePoiLogic", 'onLoadDevicePoiMsg', {page:page}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        function uploadPicture(deviceid){
            console.log(deviceid);
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/DevicePoiLogic/showUploadList?deviceid=') ?>"+deviceid,'图片',550,420,reLoad);
        }
    </script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <!-- <label for="start_time">时间:</label>
                <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="end_time">至:</label>
                <input type="text" class="form-control" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary m-15" > -->
                <!-- <input type="button" value="新 增" id="new" onclick="addDevicePoi();" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deleteDevicePoi();" class="btn btn-danger m-15" > -->
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="30px" itemvalue="deviceid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="" itemvalue="name" >监控快拍设备
                            </th>
                            <th class="title" width="" itemvalue="roadname" >所在路段
                            </th>
                            <th class="title" width="" itemvalue="devicetype" >设备类型
                            </th>
                            <th class="title" width="" itemvalue="coor_x" >经度
                            </th>
                            <th class="title" width="" itemvalue="coor_y" >纬度
                            </th>
                            <th class="title" width="250px" itemvalue="picturefile" >图片
                            </th>
                            <th class="title" width="100px" itemvalue="operate" >操作 
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
            <div id="pager" fun="Load" class="pager" pagerobj=""></div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>