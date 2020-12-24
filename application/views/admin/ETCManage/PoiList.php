<!DOCTYPE html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>网点列表</title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript" language="javascript">
        var page = 0;
        function reLoad() {
            Load(page);
        }
        function Load(t) {
            page = t;
            var name = $("#txtKey").val();
            var city=$("#city").val();
            var businesstypeid=$("#businesstypeid").val();
            var businessstatusid=$("#businessstatusid").val();

            JAjax("admin/ETCManage/PoiLogic", 'onLoadPoi', {key:name,page:t,city:city,businesstypeid:businesstypeid,businessstatusid:businessstatusid}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

       
         function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/ETCManage/PoiLogic/detailPoi') ?>?id="+id, '网点信息', 800, 530, reLoad);
        }
        /*删除函数*/
        function deleteInfo() {
          //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/ETCManage/PoiLogic", "delPoi", { OID: values}, function (data) {
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
    </script>
    <style type="text/css">
        .m-10{margin-right: 10px;}
    </style>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-body">
            <div class="form-inline mb10">
                <div class="form-group">
                     <label for="city">城市:</label>
                        <select name="city" id="city" class="form-control" onchange="Load(1);">
                            <option  value="">全部</option>
                            <?php foreach ($city as $k): ?>
                                <option value="<?php echo $k['id'] ?>"><?php echo $k['city'] ?></option>
                            <?php endforeach ?>
                        </select>   
                    <label for="businesstypeid">类型:</label>
                        <select name="businesstypeid" id="businesstypeid" class="form-control" onchange="Load(1);">
                            <option selected="businesstypeid" value="">全部</option>
                            <?php foreach ($businesstypeid as $k): ?>
                            <option  value="<?php echo $k['dictcode'] ?>"><?php echo $k['name'] ?></option>
                            <?php endforeach ?>
                        </select> 

                    <label for="businessstatusid">状态:</label>
                        <select name="businessstatusid" id="businessstatusid" class="form-control" onchange="Load(1);">
                            <option value="">全部</option>
                            <?php foreach ($status as $k): ?>
                                <option  value="<?php echo $k['dictcode'] ?>"><?php echo $k['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    <label for="txtKey">关键字:</label>
                    <input type="text" class="form-control" id="txtKey">
                </div>
                <input type="button" value="查 询" onclick="Load(0);" class="btn btn-primary">
            </div>
             <div class="form-inline mb10">
                <input type="button" value="新 增" onclick="detail(0);" class="btn btn-info m-10">
                <input type="button" value="删 除" onclick="deleteInfo();" class="btn btn-danger">
            </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title" width="30px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="80px" itemvalue="id" center="true">网点编号
                            </th>
                            <th class="title" itemvalue="title" center="true" showtype="a" attr="onclick= detail('{id}') href='javascript:void(0)'" itemtext="{itemvalue}">名称
                            </th>
                            <th class="title" width="80px" itemvalue="cityname" center="true">城市
                            </th>
                            <th class="title" width="80px" itemvalue="zonename" center="true">地区
                            </th>
                            <th class="title" itemvalue="address" center="true">地址
                            </th>
                            <th class="title" width="100px" itemvalue="longitude" center="true">经度
                            </th>
                            <th class="title" width="100px" itemvalue="latitude" center="true">纬度
                            </th>
                            <th class="title" itemvalue="phone" center="true">联系电话
                            </th>
                            <th class="title" width="95px" itemvalue="businesstime" center="true" showformat="yyyy-MM-dd hh:mm:ss">营业时间
                            </th>
                            <th class="title" width="90px" itemvalue="typename" center="true">类型
                            </th>
                            <th class="title" width="90px" itemvalue="statusname" center="true">状态
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
