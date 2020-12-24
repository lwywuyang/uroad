<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>部门页面</title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var id = "<?php echo $ID ?>";
        // 顶级部门id
         var DepartmentID = '<?php echo $empdata[0]["DepartmentID"]; ?>';
         //上级公司的Id
         var companyid="<?php echo $empdata[0]['CompanyID']; ?>";
          // alert(companyid);
       
        //编辑主部门信息
        function editThisDepartment() {
            //location.reload();
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailEmployee') ?>/"+id+'/'+companyid+'/'+DepartmentID, '员工信息', 1000, 610, reLoadPage);

        }
         function reLoadPage() {
            location.reload();
        }
        
    </script>
    <style>
    .panel-body{
        height: 500px;
    }
    </style>
</head>
<body marginwidth="0" marginheight="0" style="">

        
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="#" class="minimize" onclick="panelClick(this)" >&minus;</a>
            </div>
            <h4 class="panel-title">员工信息<input type="button" value="修改" class="btn btn-primary ml10" onclick="editThisDepartment();" /></h4>
        </div>
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-xs-1 control-label">员工编码:</label>
                <div class="col-xs-3">
                    <input name="EmplCode" type="text" value="<?php echo $empdata[0]['EmplCode']; ?>" maxlength="10" readonly="readonly" id="BodyContent_conDepaCode" notnull="true" class="form-control">
                </div>
                <label class="col-xs-1 control-label">员工名称:</label>
                <div class="col-xs-3">
                    <input name="EmplName" type="text" value="<?php echo $empdata[0]['EmplName']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conDepaName" notnull="true" class="form-control">
                </div>
                <label class="col-xs-1 control-label">联系电话:</label>
                <div class="col-xs-3">
                    <input name="Mobile" type="text" value="<?php echo $empdata[0]['Mobile']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conDepaSerial" notnull="true" class="form-control">
                </div>
            </div>
          

        </div>
    </div>
    
</body>
</html>