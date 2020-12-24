<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var eventid = '<?php echo isset($eventid)?$eventid:'0' ?>';
        var roadoldid = '<?php echo isset($data[0]['roadoldid'])?$data[0]['roadoldid']:'1' ?>';

        $().ready(function(){
            $('#roadSel').find('option[value='+roadoldid+']').attr('selected',true);
        });

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function closeqw(){
            closeLayerPageJs();
        }

        function submit(){

            var roadSel = $('#roadSel').val();
            var occtime = $('#occtime').val();
            var tipContent = $('#tipContent').val();

            if (trimStr(occtime) == '') {ShowMsg('请输入发生时间!');return;}
            if (trimStr(tipContent) == '') {ShowMsg('请输入易堵预报内容!');return;}

            JAjax("admin/MsgPublish/EventTipsLogic", 'saveEventTips', {eventid:eventid,roadSel:roadSel,occtime:occtime,tipContent:tipContent}, function (data) {
                if (data.Success){
                    closeLayerPageJs();
                }else{
                    ShowMsg('error:'+data.Message);
                }
            }, null);
        }

    </script>
    <style type="text/css">
        .panel-body{padding: 0;}
        .m-10{margin-right: 10px;}
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
                        易堵路段:
                    </td>
                    <td class="content">
                        <select class="form-control" id="roadSel">
                            <?php foreach ($road as $item): ?>
                                <option value="<?=$item['roadoldid']?>"><?=$item['roadName']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        发生时间:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="occtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php if(isset($data[0]['occtime'])){echo $data[0]['occtime'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        发布内容:
                    </td>
                    <td class="content">
                        <textarea cols="60" rows="10" id="tipContent"><?php if(isset($data[0]['reportout'])){echo $data[0]['reportout'];}?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
     </div>
    <div class="panel-footer">
        <input type="button" value="确定" class="btn btn-primary m-10" onclick="submit();" />
        <input type="button" value="返回" class="btn btn-danger m-10" onclick="closeqw();" />           
    </div>
 </div>
</body>
</html>