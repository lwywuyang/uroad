<!DOCTYPE html>
<html id="ng-app" ng-app="app">
<head>
    <title>Simple example</title>
    <?php $this->load->view('admin/common'); ?>
    <!-- <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" /> -->
    <style>
        /* .my-drop-zone { border: dotted 3px lightgray; }
        .nv-file-over { border: dotted 3px red; } Default class applied to drop zones on over
        .another-file-over-class { border: dotted 3px green; }
        
        html, body { height: 100%; } */
        /*#title{height: 80px!important;}*/
        #answer{height: 80px!important;}
        /* #content{height: 300px;} */
        .col-xs-2{padding-right: 0;}
        .col-xs-10{padding-left: 0;}
        .col-xs-2,.col-xs-10,.col-xs-12{/* height: 40px;   */line-height: 40px;margin: 5px 0;}
        .getfile-btn{width: 75px; overflow: hidden;}
        .m-r-10{margin-right: 10px;}
        .word-break{white-space: pre-wrap; word-break: break-all; word-wrap: break-word;}
        .form-control{margin-left: 0; margin-right: 0;}
        #add{cursor: pointer; }
        #dx > label {margin-left: 10px;}
    </style>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-body">
            <div class="form-inline mb10">

                <div class="form-group">
                    <label for="searchTxt" id="templetid" index="">智能机器人回复菜单管理（新增问题)</label>
                </div>
                
            </div>
            
                <table  cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                    <tr>
                        <td  width='15%' nowrap="nowrap" center="true">问题标题：
                        </td width='85%'>
                        <td>
                        <textarea class="form-control" id="title" ><?php if(isset($data[0]['title'])){echo $data[0]['title'];}?></textarea>
                        <!-- <input type="text" value="<?php if(isset($data[0]['title'])){echo $data[0]['title'];}?>" id="title" class="form-control"></td>                     -->
                    </tr>
                    <tr>
                        <td width="100px" itemvalue="" center="true">问题类型：
                        </td>
                        <td>
                            <select class="form-control" id="questiontype">
                                <option value="1">文本</option>
                                <option value="2">图文</option>
                                <option value="3">路况</option>    
                            </select>
                            <script type="text/javascript">
                                $("#questiontype").find("option[value='<?php if(isset($data[0]['questiontype'])){echo $data[0]['questiontype'];}?>']").attr("selected",true);
                                
                            </script>
                        </td>                    
                    </tr>
                     <tr>
                        <td width="100px" itemvalue="" center="true">问题答复：
                        </td>
                        <td id="huifu">
                        <div id="wb">
                            <textarea class="form-control" id="answer" ><?php if(isset($data[0]['answer'])){echo $data[0]['answer'];}?></textarea>
                        </div>
                        <div id="lj" hidden>
                            <textarea style="height:200px; width:100%" id="html" ><?php if(isset($data[0]['answer'])){echo $data[0]['answer'];}?></textarea>
                        </div>
                        <div id="dx" hidden>
                            <?php foreach($lk as $key=>$val) :?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="cbx" id="inlineCheckbox1" value="<?php echo $val['roadoldid']?>"> <?php echo $val['shortname']?>
                                </label>
                           <?php endforeach;?>
                        </div>

                       
                        
                        
                        </td>                    
                    </tr>
                    
                    <tr id="options">
                        <td width="100px" itemvalue="" center="true">关键字：
                        </td>
                        <td><input type="text" style="width:50px;" name="keyword" class="form-control" id="keyword0"><span class="glyphicon glyphicon-plus" aria-hidden="true" id="add"></span></td> <script type="text/javascript">

 
                                var str = "<?php if(isset($data[0]['keyword'])){echo $data[0]['keyword'];} ?>";
                                var arr = str.split('|');

                                for(var i=0;i<arr.length;i++)
                                {

                                    $('#keyword'+i).val(arr[i]);
                                    if(i!=arr.length-1){
                                        $('#keyword'+i).after('<input type="text" style="width:50px;" name="keyword" class="form-control" id="keyword'+(i+1)+'">');
                                    }
                                }
                               
                            </script>                         
                    </tr>
                    
                    <tr> 
                        <td>
                            <input type="button" value="确定" id="new" onclick="addProblem(<?php echo $questionid;?>);" class="btn btn-info m-15" >
                        </td>
                        <td>
                            <input type="button" value="取消" id="new" onclick="dropOut();" class="btn btn-info m-15" >
                        </td>
                    </tr>
                </table>
                <!-- <div id="pager" fun="Load" class="pager" pagerobj="">
                </div> -->
           
        </div>
        <!-- panel-body -->
    </div>
    <script type="text/javascript" language="javascript">
        
    </script>
</body>

<script type="text/javascript">

    $(function(){
        UE.getEditor('html');
        var sta = $('#questiontype').val();
        if(sta == 3)
        {
            $('#wb').attr('hidden',true);
           $('#lj').attr('hidden',true);
           $('#dx').attr('hidden',false);

            var str = '<?php if(isset($data[0]["answer"])){echo str_replace(array("\r\n", "\r", "\n"), "", $data[0]["answer"]);} ?>';

            var arr = str.split(',');

            for(var i=0;i<arr.length;i++)
            {
                
                $("#dx").find("input[value='"+arr[i]+"']").prop("checked",true);

               
            }
           
        }else if(sta == 2){
            $('#wb').attr('hidden',true);
            $('#lj').attr('hidden',false);
            $('#dx').attr('hidden',true);
        }else{
           $('#wb').attr('hidden',false);
            $('#lj').attr('hidden',true);
           $('#dx').attr('hidden',true);
        }
    })


   $('#add').click(function(){  

        var list;

        list = $('input[name="keyword"]');
        var i = list.length-1;

        if(list.eq(i).val() == ''){
            ShowMsg('请先填写');
        }else{
            $('#keyword'+i).after('<input type="text" style="width:50px;" name="keyword" class="form-control" id="keyword'+(i+1)+'">');
        }
        
        
   });
    function addProblem(id){
        

        var questionid = id;
        var title = $("#title").val().replace(/[\r\n]/g,"");

        var questiontype = $("#questiontype").val();
        var answer = '';
        var content = $('input[name="keyword"]');;
        var keyword='';

       
        

        if(questiontype == 3){
            var id_array=new Array();  
            $('input[name="cbx"]:checked').each(function(){  
                id_array.push($(this).val());//向数组中添加元素  
            });  
            answer = id_array.join(',');//将数组元素连接起来以构建一个字符串  
              
            
        }else if(questiontype == 2){
            answer = UE.getEditor('html').getContent();
            // answer = $('#answer').val();
            // if(questiontype == 2){

            //     var reg=/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/;
            //     if(!reg.test(answer)){
            //         alert("这网址不是以http://https://开头，或者不是网址！");
            //         return false;
            //     }
            // }
        }else{
            answer = $('#answer').val();
        }

       

        for(var i=0;i<content.length;i++){
            keyword += content.eq(i).val() + '|';
        }
        // console.log(keyword);
        // console.log(answer);
        // return false;
        if(keyword == '|'){
            ShowMsg('关键字不能为空！');return false;
        }
        JAjax('admin/Robot/RobotLogic','addProblem',{questionid:questionid,title:title,questiontype:questiontype,answer:answer,keyword:keyword},function (data){

            if(data.Success){
                closeLayerPageJs();
                ShowMsg('success：创建成功！');
            }else{
                ShowMsg('Tips：' + data.Message);
            }
        },null);
    
    }

    function dropOut() {
            closeLayerPageJs();
        }

    $('#questiontype').change(function(){
        var sta = $('#questiontype').val();
        if(sta == 3)
        {
           $('#wb').attr('hidden',true);
           $('#lj').attr('hidden',true);
           $('#dx').attr('hidden',false);
           
        }else if(sta == 2){
           $('#wb').attr('hidden',true);
           $('#lj').attr('hidden',false);
           $('#dx').attr('hidden',true);
        }else{
           $('#wb').attr('hidden',false);
           $('#lj').attr('hidden',true);
           $('#dx').attr('hidden',true);
        }
     });

</script>
</html>
