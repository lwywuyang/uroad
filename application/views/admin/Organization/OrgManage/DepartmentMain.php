<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>部门页面</title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var EmployeePage = 0;
        // 顶级部门id
         var DepartmentID = '<?php echo $ID; ?>';
         //上级公司的Id
         var companyid="<?php echo $depdata[0]['CompanyID']; ?>";
          // alert(companyid);
        function reLoadEmployee() {
            LoadEmployee(EmployeePage);
        }

        //取出属于该部门的员工
        function LoadEmployee(t) {
            EmployeePage = t;
            var departmentID=DepartmentID;
            var name = $("#txtKey").val(); 
           
 
            JAjax("admin/Organization/OrgManageLogic", "getEmployee", { key:name, DepartmentID: departmentID, page: t }, function (data) {
                ReloadTb('EmployeeDataGrid', data.data);
            }, "EmployeePager");
        }
        function reLoadPage() {
            location.reload();
        }
        $(document).ready(function () {
            // ReSizeiFrameByPage2();
            LoadEmployee(0);
            LoadDepartment(0);
        });
        //编辑主部门信息
        function editThisDepartment() {
            //location.reload();
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageDep') ?>/"+DepartmentID, '部门信息', 1000, 260, reLoadPage);

        }
        //增加员工信息
        function addEmployee() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailEmployee') ?>/"+0+'/'+companyid+'/'+DepartmentID, '员工信息', 1000, 280, reLoadEmployee);
        }
        function detailEmployee(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailEmployee') ?>/"+id+'/'+companyid+'/'+DepartmentID, '员工信息', 1000, 280, reLoadEmployee);
        }
        function deleteEmployeeInfo() {
            var values = getCheckedValues("rpcheckbox", "#EmployeeDataGrid", 'string');
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Organization/OrgManageLogic", "deleteEmp", { OID: values }, function (data) {
                        if (data.Success)
                            reLoadEmployee();
                        else
                            ShowMsg("删除失败：" + data.Message);
                    }, "EmployeePager");
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }

        //用户离职后禁用用户
        function disableEmployeeInfo() {
            var values = getCheckedValues("rpcheckbox", "#EmployeeDataGrid", 'string');
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要禁用吗？", function () {
                    JAjax("admin/Organization/OrgManageLogic", "disableEmp", { OID: values }, function (data) {
                        if (data.Success)
                            reLoadEmployee();
                        else
                            ShowMsg("禁用失败：" + data.Message);
                    }, null);
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }

        var DepartmentPage = 0;
        function reLoadDepartment() {
            LoadDepartment(DepartmentPage);
        }
        //显示子部门
        function LoadDepartment(t) {
            DepartmentPage = t;
            var  key= $("#Email1").val();
           
            JAjax("admin/Organization/OrgManageLogic", "getChildDepartment", { key: key, PID: DepartmentID, page: t }, function (data) {
                ReloadTb('DepartmentDataGrid', data.data);
               
            }, "DepartmentPager");
        }
        function addDepartment() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageDep') ?>/"+0+'/'+companyid+'/'+DepartmentID, '部门信息', 1000, 310, reLoadDepartment);
        }
        function detailDepartment(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageDep') ?>/" + id, '部门信息', 1000, 310, reLoadDepartment);
        }
        function deleteDepartmentInfo() {
            var values = getCheckedValues("rpcheckbox", "#DepartmentDataGrid", 'string');
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Organization/OrgManageLogic", "deleteDep", { OID: values }, function (data) {
                        if (data.Success) {
                            reLoadDepartment();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "DepartmentPager");
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        
    </script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="#" class="minimize" onclick="panelClick(this)" >&minus;</a>
            </div>
            <h4 class="panel-title">部门详细信息<input type="button" value="修改" class="btn btn-primary ml10" onclick="editThisDepartment();" /></h4>
        </div>
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-xs-1 control-label">部门编码:</label>
                <div class="col-xs-3">
                    <input name="DepaCode" type="text" value="<?php echo $depdata[0]['DepaCode']; ?>" maxlength="10" readonly="readonly" id="BodyContent_conDepaCode" notnull="true" class="form-control">
                </div>
                <label class="col-xs-1 control-label">部门名称:</label>
                <div class="col-xs-3">
                    <input name="DepaName" type="text" value="<?php echo $depdata[0]['DepaName']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conDepaName" notnull="true" class="form-control">
                </div>
                <label class="col-xs-1 control-label">排序:</label>
                <div class="col-xs-3">
                    <input name="DepaSerial" type="text" value="<?php echo $depdata[0]['DepaSerial']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conDepaSerial" notnull="true" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">负责人:</label>
                <div class="col-xs-3">
                    <input name="Depamanager" type="text" maxlength="10" readonly="readonly" id="BodyContent_conDepamanager" notnull="true" class="form-control" value="<?php echo $depdata[0]['Depamanager']; ?>">
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#department" data-toggle="tab">
                <strong>子部门</strong>
            </a>
        </li>
        <li>
            <a href="#employee" data-toggle="tab">
                <strong>部门员工</strong>
            </a>
        </li>
    </ul>
    <div class="tab-content mb30">
        <div class="tab-pane active " id="department">
            <div class="form-inline mb10">
                    <input type="button" value="新 增" onclick="addDepartment();" class="btn btn-info">
                    <input type="button" value="删 除" onclick="deleteDepartmentInfo();" class="btn btn-danger">
                    <div class="form-group">
                        <label for="txtKey">关键字:</label>
                        <input type="email" class="form-control" id="Email1">
                    </div>
                    <input type="button" value="查 询" onclick="LoadDepartment(0);" class="btn btn-primary">
                </div>
                <div class="table-responsive">
                    <table class="table mb30 table-hover table-bordered dataTable" id="DepartmentDataGrid">
                        <thead>
                            <tr><th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                                <input type="checkbox" id="Checkbox1" onclick="checkall('#DepartmentDataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" itemvalue="DepaCode" center="true">部门编码
                            </th>
                            <th class="title" itemvalue="DepaName" center="true" showtype="a" attr="onclick= detailDepartment('{ID}') href='javascript:void(0)'" itemtext="{itemvalue}">部门名称
                            </th>
                            <th class="title" itemvalue="DepaSerial" center="true">排序
                            </th>
                             <th class="title" itemvalue="Depamanager">
                                负责人
                            </th>
                        </tr></thead>
                    <tbody>
                    </tbody>
                </table>
                    <div id="DepartmentPager" class="pager" fun="LoadDepartment" pagerobj="{OrderDesc:'asc',OrderField:'DepaCode',PageSize:10}">
                    </div>
                </div>
        </div>
        <div class=" tab-pane " id="employee">
              <div class="form-inline mb10">
                    <input type="button" value="新 增" onclick="addEmployee();" class="btn btn-info">
                    <input type="button" value="删 除" onclick="deleteEmployeeInfo();" class="btn btn-danger">
                    <input type="button" value="禁 用" onclick="disableEmployeeInfo();" class="btn btn-primary">
                    <div class="form-group">
                        <label for="txtKey">关键字:</label>
                        <input type="email" class="form-control" id="txtKey">
                    </div>
                    <input type="button" value="查 询" onclick="LoadEmployee(0);" class="btn btn-primary">
                </div>
                <div class="table-responsive">
                    <table class="table mb30 table-hover table-bordered dataTable" id="EmployeeDataGrid">
                        <thead>
                            <tr>
                                <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                                <input type="checkbox" id="chkall" onclick="checkall('#EmployeeDataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" itemvalue="EmplCode" center="true">编码(工号) 
                            </th>
                            <th class="title" itemvalue="EmplName" center="true" showtype="a" attr="onclick= detailEmployee('{ID}') href='javascript:void(0)'" itemtext="{itemvalue}">员工名称
                            </th>
                             <th class="title" itemvalue="statusName" center="true">状态 
                            </th>

                            </tr>
                        </thead>
                    <tbody>
                    </tbody>
                </table>
                    <div id="EmployeePager" class="pager" fun="LoadEmployee" pagerobj="{OrderDesc:'asc',OrderField:'EmplCode',PageSize:10}">
                    </div>
                </div>         
        </div>
    </div>
</body>
</html>