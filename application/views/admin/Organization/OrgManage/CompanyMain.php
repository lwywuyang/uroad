
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>公司信息页面</title>
     <?php $this->load->view('admin/common') ?> 
    <script type="text/javascript">
    //自己的id,就是后面添加子公司里面的顶级公司的id，为pid
     var CompanyID ='<?php echo $ID; ?>';
        var CompanyPage = 0;
        function reLoadCompany() {
            LoadCompany(CompanyPage);
        }
        function LoadCompany(t) {
            CompanyPage = t;
            var comname = $("#txtKey").val();
            var ID =CompanyID;
            // ID查找下属的子公司
            JAjax("admin/Organization/OrgManageLogic", "onloadChildCom", { key:comname, PID:ID, page: t }, function (data) {
                ReloadTb('CompanyDataGrid', data.data);
            }, "CompanyPager");
        }
        function reLoadPage() {
            location.reload();
        }
        $(document).ready(function () {
            LoadCompany(0);
            LoadDepartment(0);
        });
        function editThisCompany() {         
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageCom') ?>/"+CompanyID, '公司信息', 1000, 210, reLoadPage);
        }

       
        function addCompany() {
            //传入顶级公司id
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageCom') ?>/"+0+'/'+CompanyID+'/'+0, '公司信息', 1000, 210, reLoadCompany);

        }
        function detailCompany(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageCom') ?>/"+id, '公司信息', 1000, 210, reLoadCompany);
        }

        
        /*删子公司除函数*/
        function deleteCompanyInfo() {
            var values = getCheckedValues("rpcheckbox", "#CompanyDataGrid", 'string');
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Organization/OrgManageLogic", "onDelCom", { OID: values }, function (data) {
                        if (data.Success) {
                            reLoadCompany();
                        }else{
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "CompanyPager");
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }
        var DepartmentPage = 1;
        function reLoadDepartment() {
            LoadDepartment(DepartmentPage);
        }
        function LoadDepartment(t) {
            DepartmentPage = t;
            var depname = $("#Email1").val();
            var companyid=CompanyID;
            JAjax("admin/Organization/OrgManageLogic", "onLoadDepartment", { key: depname,CompanyID: companyid, page: t }, function (data) {
                ReloadTb('DepartmentDataGrid', data.data);

            }, "DepartmentPager");
        }
        /*增加公司下的部门*/
        function addDepartment() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageDep') ?>/"+0+'/'+CompanyID , '部门信息', 1000, 270, reLoadDepartment);
        }
        function detailDepartment(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageDep') ?>/"+id,'部门信息', 1000, 270, reLoadDepartment);
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
            <h4 class="panel-title">公司详细信息<input type="button" value="修改" class="btn btn-primary ml10" onclick="editThisCompany();" /></h4>
        </div>
        <div class="panel-body">
            <div class="form-horizontal form-group ">
                <label class="col-xs-1 control-label">公司编码:</label>
                <div class="col-xs-3">
                    <input name="CompCode" type="text" value="<?php echo $comdata[0]['CompCode']; ?>" maxlength="10" readonly="readonly" id="BodyContent_conCompCode" notnull="true" class="form-control">
                </div>
                <label class="col-xs-1 control-label">公司简称:</label>
                <div class="col-xs-3">
                    <input name="CompShortName" type="text" value="<?php echo $comdata[0]['CompShortName']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conCompShortName" notnull="true" class="form-control">
                </div>
                <label class="col-xs-1 control-label">公司全称:</label>
                <div class="col-xs-3">
                    <input name="CompName" type="text" value="<?php echo $comdata[0]['CompName']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conCompName" notnull="true" class="form-control">
                </div>
            </div>

        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#company" data-toggle="tab">
                <strong>子公司</strong>
            </a>
        </li>
        <li>
            <a href="#department" data-toggle="tab">
            <strong>部门</strong>
            </a>
        </li>
    </ul>
    <div class="tab-content mb30">
        <div class=" tab-pane active" id="company">

              <div class="form-inline mb10">
                    <input type="button" value="新 增" onclick="addCompany();" class="btn btn-info">
                    <input type="button" value="删 除" onclick="deleteCompanyInfo();" class="btn btn-danger">
                    <div class="form-group">
                        <label for="txtKey">关键字:</label>
                        <input type="email" class="form-control" id="txtKey">
                    </div>
                    <input type="button" value="查 询" onclick="LoadCompany(0);" class="btn btn-primary">

                </div>
                <div class="table-responsive">
                    <table class="table mb30 table-hover table-bordered dataTable" id="CompanyDataGrid">
                        <thead>
                            <tr>
                                <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                                <input type="checkbox" id="chkall" onclick="checkall('#CompanyDataGrid',this, 'rpcheckbox');">
                            </th>
                            <th class="title" itemvalue="CompCode" center="true">公司编码
                            </th>
                            <th class="title" itemvalue="CompName" center="true" showtype="a" attr="onclick= detailCompany('{ID}') href='javascript:void(0)'" itemtext="{itemvalue}">公司名称
                            </th>
                            <th class="title" itemvalue="CompShortName" center="true">简称
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                    <div id="CompanyPager" class="pager" fun="LoadCompany" pagerobj="{OrderDesc:'asc',OrderField:'CompCode',PageSize:10}">
                    </div>
                </div>      
        </div>
        <div class="tab-pane  " id="department">
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
                            <tr>
                                <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                                <input type="checkbox" id="Checkbox1" onclick="checkall('#DepartmentDataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" itemvalue="DepaCode" center="true">部门编码
                            </th>
                            <th class="title" itemvalue="DepaName" center="true" showtype="a" attr="onclick= detailDepartment('{ID}') href='javascript:void(0)'" itemtext="{itemvalue}">部门名称
                            </th>
                            <th class="title" itemvalue="DepaSerial" center="true">排序
                            </th>
                             <th class="title" itemvalue="Depamanager">负责人</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                    <div id="DepartmentPager" class="pager" fun="LoadDepartment" pagerobj="{OrderDesc:'asc',OrderField:'DepaCode',PageSize:10}">
                    </div>
                </div>
        </div>
    </div>

</body></html>