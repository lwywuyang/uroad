<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
    <script type="text/javascript">
        var eventid = "<?php echo $data['eventid'] ?>";
        //var reply = 
        
        $().ready(function(){
            checkReply();
        });


        function checkReply(){
            JAjax("admin/WXManage/ReportMessageLogic", 'getReplyDetail', {eventid:eventid}, function (data) {
                if (data.Success) {
                    ReloadTb('dataReply', data.data);
                }else{
                    ShowMsg("error:" + data.Message);
                }
            }, null);
        }

        //userid,被评论者id,如果是报料投稿者,为0
        function replyThis(id,touserid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/ReportMessageLogic/showReplyList') ?>?eventid="+eventid+'&id='+id+'&touserid='+touserid, '评论', 500, 350, checkReply);
        }

        

        /**
         * @desc   关闭窗口
         */
        function comeback(){
            var src = "<?php echo base_url('/index.php/admin/WXManage/ReportMessageLogic/indexPage') ?>";
            $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
        }



    </script>
    <style type="text/css">
        table{border-collapse: collapse;}
        .vc_table .name{width: auto !important;}
        .form-control{width: 70%;float: left;}
        .imgDiv{height: 200px;float: left;margin: 10px;}
        .panel-heading{color: #FF634D !important;font-size: 22px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">评论报料</div>
    <div class="panel-body ">
        <table cellspacing="1" cellpadding="4" class="vc_table" style="width:100%">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        报料人:
                    </td>
                    <td class="content">
                        <?php echo $data['username'] ?>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        报料时间:
                    </td>
                    <td class="content">
                        <?php echo $data['occtime'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        报料内容:
                    </td>
                    <td class="content" colspan="3">
                        <?php echo $data['remark'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        报料图片:
                    </td>
                    <td class="content" colspan="3">
                        <img class="reportimg" src="<?php echo $data['filename'] ?>" onclick="showLayerImageJs(this.src)" />
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="table-responsive">
            <div class="form-inline mb10">
                <input type="button" value="评论" id="reply" onclick="replyThis(0,0);" class="btn btn-primary" >
            </div>
            <table class="table mb30 table-hover table-bordered dataTable" id="dataReply">
                <thead>
                    <tr>
                        <th class="title" width="120px" itemvalue="user">评论用户</th>
                        <th class="title" width="" itemvalue="usercomment">评论内容</th>
                        <th class="title" width="95px" itemvalue="intime" showformat="yyyy-MM-dd hh:mm:ss">评论时间</th>
                        <th class="title" width="70px" itemvalue="statusName">状态</th>
                        <th class="title" width="70px" itemvalue="operate">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据 -->
                </tbody>
            </table>
        </div>
    </div>
 </div>
</body>
</html>