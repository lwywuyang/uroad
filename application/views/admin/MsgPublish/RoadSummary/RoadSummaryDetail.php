<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>路况综述详情</title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var eventid = '<?php echo isset($eventid)?$eventid:'0' ?>';
        var eventstatus = '<?php echo isset($data['eventstatus'])?$data['eventstatus']:'' ?>';
        var jpgurl = '<?php echo isset($data['imgurl'])?$data['imgurl']:'http://hunangstapi.u-road.com/HuNanGSTAppAPIServer/images/roadsummarize.png' ?>';

        $().ready(function(){
            if (eventid == '0') {
                $('#publish').removeClass('hidden');
            }else{
                if (eventstatus == '1012004') {
                    $('#save').removeClass('hidden');
                    $('#finish').removeClass('hidden');
                }
            }

            if(jpgurl != ''){
                $("#imgupload").html("<img src="+jpgurl+"  width='150px' onclick='showLayerImage(this.src)'/>");
            }
        });

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        function submit(status){
            var title = $('#title').val();
            var intime = $('#intime').val();
            var reportinfo = $('#reportinfo').val();

            /*if (reportinfo.indexOf('（不包含施工路段）') < 0) {
                reportinfo += '（不包含施工路段）';
            }*/
            
            if (trimStr(title) == '') {ShowMsg('请输入路况综述标题!');return;}
            if (trimStr(reportinfo) == '') {ShowMsg('请输入路况综述内容!');return;}

            JAjax("admin/MsgPublish/RoadSummaryLogic", 'saveRoadSummary', {eventid:eventid,title:title,reportinfo:reportinfo,jpgurl:jpgurl,status:status,intime:intime}, function (data) {
                if (data.Success)
                    closeLayerPageJs();
                else
                    ShowMsg('操作失败!');
            }, null);
        }

    </script>
    <style type="text/css">
        .panel-body{padding: 0;}
        .content{color:#0000FF;}
        #roadSel,#occtime{max-width: 200px;}
        .m-15{margin-right: 15px;}
        .vc_table .content{width: auto!important;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading"></div>
    <div class="panel-body">
        <table cellspacing="1" cellpadding="4" class="vc_table" style="width:98%">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap">
                        标题:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="title" value="<?php echo isset($data['title'])?$data['title']:'' ?>" />
                    </td>
                </tr>
                <tr height="150px">
                    <td class="name" nowrap="nowrap">
                        封面图片:
                        <div id="uploader-demo">
                            <div id="fileList" class="uploader-list"></div>
                            <div id="filePicker">选择</div>
                        </div>
                    </td>
                    <td class="content">
                        <div id="imgupload" width="100%"></div>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        发布时间:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="intime" value="<?php echo isset($data['intime'])?$data['intime']:'' ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        发布内容:
                    </td>
                    <td class="content">
                        <textarea style="width: 100%;height: 200px;" id="reportinfo"><?php echo isset($data['reportinfo'])?$data['reportinfo']:'' ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
     </div>
    <div class="panel-footer">
        <input type="button" value="发布" class="btn btn-info m-15 hidden" id="publish" onclick="submit(1012004);" />
        <input type="button" value="保存" class="btn btn-primary m-15 hidden" id="save" onclick="submit(0);" />
        <input type="button" value="结束" class="btn btn-danger m-15 hidden" id="finish" onclick="submit(1012005);" />
        <input type="button" value="取消" class="btn btn-success" onclick="closeLayerPageJs();" />
    </div>
 </div>
</body>
</html>