<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>管制事件</title>
    <link href="<?php $this->load->helper('url');echo base_url('/asset/css/bootstrap-select.min.css?333333') ?>" rel="stylesheet">
    <link href="<?php $this->load->helper('url');echo base_url('/asset/css/bootstrap-select.css?333333') ?>" rel="stylesheet">
  <?php $this->load->view('admin/common'); ?>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/bootstrap-select.js?as12') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/bootstrap-select.min.js?as12') ?>">
    </script>
  <script type="text/javascript">
    var eventid = "<?php echo isset($eventid)?$eventid:''?>";
    var gaodeeventid = "<?php echo isset($gaodeeventid)?$gaodeeventid:''?>";

    //qszdid
        function save() {
            var reportout = $("#reportout").val();//对外消息内容
            var isnewreportout = $("#isnewreportout").is(":checked");
            var isnew = "";
            if(isnewreportout){
                isnew=1;
            }
            if(reportout==""){
                ShowMsg('请输入对外信息');
                return false;

            }

              JAjax("admin/GaoDeLIst/GaoDeListLogic", 'savereportout', {isnew:isnew,eventid:eventid,gaodeeventid:gaodeeventid,reportout:reportout},function(data) {
                if (data.Success) {
                    ShowMsg('保存成功');
                  closeLayerPageJs();
                } else {
                  ShowMsg("失败：" + data.Message);
                }
              },
              "",true);
              
          }
  function showLayerImage1(url){
           // window.parent.parent.showLayerImage(e);
            window.parent.showimgpage(url);
      }

      
       
</script>
    <style>
      
    </style>
      </head>
      <body>
        <div class="panel panel-default form-horizontal ">
          <div class="panel-body ">
            <table cellspacing="1" cellpadding="4" style="width:99%">
              <tbody>
               <tr>
                    <td class="name" nowrap="nowrap">
                        原对外公告:
                    </td>
                    <td class="content ">
                        <textarea class="form-control" id="oldreportout" style="height: 15rem;" disabled="disabled"><?php echo isset($oldreportout)?$oldreportout:'' ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        新对外公告:
                    </td>
                    <td class="content " style="padding-top: 10px;">
                        <textarea class="form-control" id="reportout" style="height: 15rem;"><?php echo isset($reportout)?$reportout:'' ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td class="name" nowrap="nowrap">
                        是否使用新的对外公告:&nbsp;&nbsp;
                        <input type="checkbox" id="isnewreportout"/>
                    </td>
                </tr>
              </tbody>
            </table>
            <!-- panel-body -->
          </div>
          <!-- panel-body -->
          <div class="panel-footer"  style="text-align: center">
            <input type="button"  id="btnSave" value="保存"  class="btn btn-primary" onclick="save();" />
            <input type="button"  id="btnCancel"  value="关闭" class="btn btn-primary" onclick="closeLayerPageJs();" />
          </div>
        </div>
      </body>
      </html>