<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <base target="_self" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
    <title><?php echo $this->config->item('project_name');?></title>
    <?php $this->load->view('admin/index_common') ?>
    <style>
        #main-content{top:50px;padding: 10px; display: block; background: rgb(228, 231, 234);}
        ul{padding: 0;}
        .glyphicon{margin-right: 5px;}
          .chakanbtn{
            float: right;
            height: 30px;
            width: 40px;
            background-color: #28ea6b;
            border-radius: 5px;
            text-align: center;
            line-height: 30px;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body class="dark-theme" style="overflow: hidden">
    <div class="header navbar navbar-inverse box-shadow navbar-fixed-top">
        <div class="navbar-inner">
            <div class="header-seperation">
                <ul class="nav navbar-nav">
                    <li class="sidebar-toggle-box">
                        <a href='javascript:void(0)'>
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                    <li>
                        <h3 style="color: white; padding-left: 15px;line-height: 30px;margin-top: 10px;">
                            <?php echo $this->config->item('project_name');?>
                        </h3>
                    </li>
                    <li class="hidden-xs" style="display: none"></li>
                    <li class="hidden-xs">
                        <a class="">2020 &copy; U-Road+ </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="page-container">
        <div class="nav-collapse top-margin fixed box-shadow2 hidden-xs" id="sidebar">
            <div class="leftside-navigation" style="overflow: hidden; outline: none;" tabindex="5000">
                <div class="sidebar-section sidebar-user clearfix">
                    <div class="sidebar-user-avatar">
                        <a href='javascript:void(0)'>
                            <img alt="avatar" src="<?php $this->load->helper('url');echo base_url('/asset/images/photos/loggeduser.png') ?>"/>
                        </a>
                    </div>
                    <a>
                        <div id="username" class="sidebar-user-name">
                            <?php echo $EmplName; ?>--<?php echo $DepaName; ?>
                        </div>
                    </a>
                    <div class="sidebar-user-links">
                        <!-- 修改密码 -->
                        <a href="javascript:;" id="A1" data-placement="bottom" onclick="showEditPass()" title="修改密码">
                            <span class="glyphicon glyphicon-user"></span>
                        </a>
                        <a id="logout" data-placement="bottom" data-toggle="" style="cursor :pointer " data-original-title="Logout" onclick="logout()" title="退出管理系统">
                            <span class="glyphicon glyphicon-log-out"></span>
                        </a>
                    </div>
                </div>
<!-- <<<向左缩进<<< -->
<!-- 一级菜单 -->
<ul id="nav-accordion" class="sidebar-menu" style="margin-bottom:100px;">
    <?php foreach ($fundata as $top): ?>
        <li isroot="true" class="sub-menu dcjq-parent-li">
            <a href="javascript:void(0);" onclick="gotoURL('','parent_T201601131126185510000194','',1,1)" class="dcjq-parent">
                <span class="glyphicon glyphicon-home"></span>
                <span><?php echo $top['FuncName'] ?></span>
            </a>
            <!-- 二级菜单 -->
            <ul class="sub" style="display: block;">
                <?php if(!$top['subfun'] == ''): ?><!-- 有子菜单 -->
                    <?php foreach ($top['subfun'] as $secondLevel): ?>
                        <?php if(!$secondLevel['subfun'] == ''): ?><!-- 有子菜单 -->
                            <li class="sub-menu dcjq-parent-li">
                                <a href="javascript:void(0);" onclick="gotoURL('','parent_T201501131126185510000194','',1,1)" class="dcjq-parent">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                    <span><?php echo $secondLevel['FuncName'] ?></span>
                                </a>
                                <!-- 三级菜单 -->
                                <ul class="sub sub-s" id="sub-menu-ul" style="display: block;">
                                    <?php foreach ($secondLevel['subfun'] as $thirdLevel): ?>
                                        <li id="sub_T201601131128152410000274">
                                            <a href="<?php echo base_url($thirdLevel['URI']) ?>" target="iframeContent">
                                                <span class="glyphicon glyphicon-list-alt"></span>
                                                <span><?php echo $thirdLevel['FuncName'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li id="sub_T201601131128152410000274">
                                <a href="<?php echo base_url($secondLevel['URI']) ?>" target="iframeContent">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    <span><?php echo $secondLevel['FuncName'] ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach ?>
                <?php endif; ?>
            </ul>
        </li>
    <?php endforeach ?>
</ul>
<!-- <<<向左缩进<<< -->
            </div>
        </div>
        <div id="main-content">
            <iframe id="iframeContent" name="iframeContent" frameborder="0" width="100%" marginheight="0" marginwidth="0" src="<?php //echo base_url('index.php/admin/login/aa'); ?>">
            </iframe>
        </div>
        <div id="jp_container_1">

    </div>
    </div>
</body>
<script type="text/javascript">
    //验证是否登陆
    var empName = '<?php echo $EmplName; ?>';
    var empid = '<?php echo $EmpID ?>'; 
    var nbsphtml = "<?php echo str_repeat('&nbsp;',1)?>";
     var tcid;
    if(empName == ''){
        location.href = "<?php  $this->load->helper('url'); echo base_url('index.php/admin/login'); ?>";
    }

    $(document).ready(function() {
        var h = $(window).height()-70;
        var iframe = document.getElementById("iframeContent");
        iframe.height = h;


        if(empid!='T20170418101709974439773786'){
            setTimeout('getTopTipsMsgNew()',1000);

            setInterval('getTopTipsMsgNew()',120000);
        }

    });

    function closeAll(){
        layer.closeAll();
    }

    function logout(){
        ConfirmTopLayer(250,140,'是否退出','您确认退出？','退出','取消',tuichu);
    }

    //退出登录
    function tuichu(){
        JAjax('admin/Login','login_out',{},function (data){
            if(data.Success){
                location.href = "<?php echo base_url('index.php/admin/login') ?>";
            }else{
                alert(data.Message);
            }
        },null);
    }

    function goTcInfoReleaseNew(e){
          var totalnum = $(e).attr("totalnum");
          var eventids = $(e).attr("eventids");
          var src = "";
          if(totalnum==1){
            var roadid = $("#gaodedata"+eventids).attr('roadid');
            var intime = $("#gaodedata"+eventids).attr('intime');
              src = "<?php echo base_url('index.php/admin/MsgPublish/RoadEventLogic/indexPage?eventtype=1006001') ?>&ids="+eventids;
          }else{
              src = "<?php echo base_url('index.php/admin/GaoDeLIst/GaoDeListLogic/gaoDeIndexPage') ?>?ids="+eventids;
          }
          layer.close(tcid);
          $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
      }
    function showmap1(longitude,latitude){
        var w = document.body.scrollWidth;
        var h = document.body.scrollHeight;

        $(".panel-body").css("height",h);
            var w1 = w*0.85;
            var h1 = h*0.85;
            $.layer({
            type: 2,
            shade: [0],
            fix: false,
            title: '选择经纬度',
            maxmin: true,
            iframe: { src: "<?php echo base_url('/index.php/admin/MapLogic/selectXYPage1/') ?>?x="+longitude+"&y="+latitude},
            area: [w1, h1],
            close: function (index) {}
        });
    }

    function getTopTipsMsgNew(){
            JAjax("admin/GaoDeLIst/GaoDeListLogic", 'getEventNumToTipNew', {}, function (data) {
              console.log(data);
                if(data.Success){
                    layer.close(tcid);
                    //是否存在提醒
                    if(data.data.gdlkdata.totalnum>0 || data.data.eventnum[0].num>0){
                        var content = "<div class='tcdiv'>";
                        //高德提醒
                        if(data.data.gdlkdata.totalnum>0){
                          btnhtml = "<div>";
                          if(data.data.gdlkdata['totalnum']==1){
                            btnhtml+="<div id='gaodedata"+data.data.gdlkdata['eventid']+"' intime='"+data.data.gdlkdata['intime']+"' roadid='"+data.data.gdlkdata['roadid']+"' style='float:left;width: 250px;line-height: 30px;margin-bottom: 10px;'>"+data.data.gdlkdata['title']+"</div>";
                          }else{
                            btnhtml+="<div style='float:left;width: 250px;line-height: 30px;margin-bottom: 10px;'><div style='float:left;width:150px;'>高德路况</div><div style='float:left'>高德路况("+data.data.gdlkdata['totalnum']+")</div></div>";
                          }
                          btnhtml+="<div style='float:left;line-height: 30px;'>"+nbsphtml+data.data.gdlkdata['intime']+"</div>";
                          btnhtml+="<div class='chakanbtn'><span totalnum='"+data.data.gdlkdata['totalnum']+"'  eventids='"+data.data.gdlkdata['eventid']+"'  onclick='goTcInfoReleaseNew(this)'>查看</span></div>";
                          btnhtml+="</div>";
                          btnhtml+="<div style='clear:both'></div>";
                          content+=btnhtml;
                          istc=true;
                          logeventids = data.data.gdlkdata['eventid'];
                        }

                        if(data.data.eventnum[0].num>0){
                          btnhtml = "<div>";
                          btnhtml+="<div style='float:left;width: 250px;line-height: 30px;margin-bottom: 10px;'>"+data.data.eventnum[0]['shortname']+"</div>";
                          btnhtml+="<div style='float:left;line-height: 30px;'>事件"+data.data.eventnum[0]['type1']+"&nbsp;&nbsp;施工"+data.data.eventnum[0]['type2']+"&nbsp;&nbsp;出行"+data.data.eventnum[0]['type3']+"</div>";
                          btnhtml+="<div class='chakanbtn'><span onclick='setsecondeventtipreaded()'>已阅</span></div>";
                          btnhtml+="</div>";
                          btnhtml+="<div style='clear:both'></div>";
                          content+=btnhtml;
                          istc=true;
                        }
                        content+='</div>';
                        tc(content);
                    }
                }

            }, null);
        }
         //弹出窗口
      function tc(content){
          $("#jp_container_1").html('');
         // var mpshtml = '<audio autoplay="true" controls="controls" src="<?php echo base_url('/asset/js/5818.mp3') ?>"> </audio>';
         //      $("#jp_container_1").html(mpshtml);
          tcid = $.layer({
              type: 1,
              title:"消息提醒",
              area: ['480px', '120px'], //宽高
              offset: ['', ''],
              scrollbar: true,
              shade: 0,
              shift: 'right-bottom',
              page: {
                  html:content
              }
          });
          // recordlog();

      }
</script>
</html>
