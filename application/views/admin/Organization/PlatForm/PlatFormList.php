<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
     <?php $this->load->view('admin/common'); ?>      
    <script type="text/javascript" language="javascript">
        var page = 0; 
        function reLoad() {
            Load(page);
        }
        function Load(t) {
           
            page = t;
            var name = $("#txtKey").val();
            JAjax("admin/Organization/PlatFormLogic", 'onLoad', {key:name, page:t}, function (data) {
              //重新加载列表，table id 
            ReloadTb('dataGrid', data.data);       
            }, "pager");
          

        }
        //新增函数
        function add() {
          //弹出框处理
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/PlatFormLogic/detailPage') ?>/"+0, '系统信息添加', 1000, 210, reLoad);
        }
        function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/PlatFormLogic/detailPage') ?>/"+id, '系统信息维护', 1000, 210, reLoad);
        }
       
       /*删除函数*/
        function deleteInfo() {
          //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
            if (values != "" && values != undefined) {
                ConfirmLayer('提示','您确定要删除吗？',isdel,values);
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }
        function isdel(values){
             JAjax("admin/Organization/PlatFormLogic", "onDelete", { OID: values}, function (data) {
                if (data.Success) {
                    reLoad();
                }
                else {
                    ShowMsg("删除失败：" + data.Message);
                }
               
            }, "pager");
        }
         
    </script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="#" class="minimize" onclick="panelClick(this)">−</a>
            </div>
            
            <h4 class="panel-title">列表-筛选</h4>
        </div>
        <div class="panel-body">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="txtKey">关键字:</label>
                    <input type="email" class="form-control" id="txtKey">
                </div>
                <!-- 查询按钮信息 -->
                <input type="button" value="查 询" onclick="Load(0);" class="btn btn-primary">          
            </div>      
        </div>
        <div class="panel-footer">
            <input type="button" value="新 增" onclick="add();" class="btn btn-info">
            <input type="button" value="删 除" onclick="deleteInfo();" class="btn btn-danger">
        </div>
        <!-- panel-body -->
    </div>
    <div class="table-responsive" id="content_list">
      <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
        <thead>
          <tr>
            <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox' state='{Status}'">
              <input type="checkbox" id="chkall" onclick="checkall('#dataGrid',this, 'rpcheckbox');"
              attr=" state='{Status}'">
            </th>
            <th class="title" itemvalue="Code" center="true">
              系统编码
            </th>
            <th class="title" itemvalue="Name" center="true" showtype="a" attr="onclick= detail('{ID}') href='javascript:void(0)'" itemtext="{itemvalue}">
                                系统名称
            </th>
            <th class="title" itemvalue="IsReg" center="true">
              是否注册
            </th>
            <th class="title" itemvalue="SysType" center="true">
              系统类型
            </th>
          </tr>

        </thead>
        <tbody>
        <!-- 信息 -->

        </tbody>
      </table>
       <div id="pager" fun="Load" class="pager" pagerobj="{OrderDesc:'asc',OrderField:'Name',PageSize:3}">
       </div>
    </div>
    <script type="text/javascript" language="javascript">
         Load(0);
    </script>

</body>
</html>