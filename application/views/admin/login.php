<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo base_url('/asset/images/favicon.png') ?>" type="image/png">
    <title><?php  echo $this->config->item('project_name');?></title>
    <script type="text/javascript">
        var InpageUrl = "<?php $this->load->helper('url');echo base_url('') ?>";
        InpageUrl = InpageUrl+"index.php/";
    </script>
    <link href="<?php echo base_url('/asset/css/style.default.css') ?>" rel="stylesheet">
    <script src="<?php echo base_url('/asset/js/jquery-2.2.3.min.js') ?>"></script>
    <script src="<?php echo base_url('/asset/js/jquery-migrate-1.2.1.min.js') ?>"></script>
    <script src="<?php echo base_url('/asset/js/modernizr.min.js') ?>"></script>
    <script src="<?php echo base_url('/asset/js/retina.min.js') ?>"></script>
    <script src="<?php echo base_url('/asset/js/custom.js') ?>"></script>
    <script src="<?php echo base_url('/asset/js/InPage.js') ?>"></script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/layer/layer.min.js') ?>"></script>
    <link type="text/css" rel="stylesheet" href="<?php $this->load->helper('url');echo base_url('/asset/layer/skin/layer.css') ?>">
    <script src="<?php $this->load->helper('url');echo base_url('/asset/layer/extend/layer.ext.js') ?>"> </script>
    <script type="text/javascript">

        var username = '<?php echo isset($username)?$username:'' ?>';
        var password = '<?php echo isset($password)?$password:'' ?>';

        $().ready(function(){
            if (username != '' && password != '') {//有cookie
                $('input:checkbox[name=remember][value=1]').attr('checked',true);

                $('#txtUserName').attr('value',username);
                $('#txtPwd').attr('value',password);
            }
        });

        document.onkeydown = function(event){
            var e = event || window.event || arguments.callee.caller.arguments[0];
          
            if(e && e.keyCode==13){
             // enter 键
                login();
            }
        };

        function login(){
            var name=$("#txtUserName").val();
            var pwd=$("#txtPwd").val();
            JAjax('admin/Login','login_in',{username:name,password:pwd},function (data){
                if(data.Success){
                    location.href="<?php echo base_url('index.php/admin/Meun') ?>";
                }
                else
                {
                    layer.alert(data.Message,0);
                }
            },null);
        }

        function changeType () {
          $pwd = $('#txtPwd');
          $pwd.attr('type', $pwd.attr('type') == 'password' ? 'text' : 'password')
        }
     

    </script>
    <style>
      html, body {
        height: 100%;
      }
      body {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: url(<?php echo base_url('/asset/images/login/login-bg.png') ?>) no-repeat center center;
        background-size: 100% 100%;
      }
      .login__box {
        width: 380px;
        padding: 30px;
        margin-top: 80px;
        margin-bottom: 20px;
        background: #fff;
        color: #5D5D5D;
      }
      .login__header {
        display: flex;
        margin: 0;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #000;
        line-height: 56px;
        font-weight: 600;
      }
      .login__header img {
        width: 24px;
        height: 24px;
        margin-right: 10px;
      }
      .login__form {
        padding-top: 10px;
      }
      .login__form-item {
        display: flex;
        flex-direction: column;
        padding: 5px;
      }
      .box-shadow {
        box-shadow: 0px 0px 6px 0px rgba(0,0,0,0.09);
      }
      .login__form-item:not(:last-child) {
        margin-top: 10px;
      }
      .login__form-item .icon-user {
        width: 13px;
        height: 14px;
      }
      .login__form-item .icon-lock {
        width: 11.5px;
        height: 13.5px;
      }
      .login__form-item .icon-eye {
        width: 14.5px;
        height: 11px;
        cursor: pointer;
      }
      .login__form-content {
        display: flex;
        padding: 6px 12px;
        align-items: center;
      }
      .login__form-content input[type="text"], .login__form-content input[type="password"] {
        border: none;
        outline: none;
        flex: 1;
        height: 30px;
        line-height: 30px;
        margin-left: 8px;
      }
      .checkbox_label {
        display: flex;
        align-items: center;
      }
      .checkbox_label input {
        margin: 0;
        margin-right: 5px;
      }

      .login__form-item input[type="button"] {
        border: none;
        outline: none;
        height: 40px;
        line-height: 40px;
        color: #fff;
        background:rgba(67,132,255,1);
        box-shadow:0px 0px 6px 0px rgba(0,0,0,0.09);
        border-radius: 4px;
      }
      footer {
        color: #4384FF;
      }
      
    </style>
</head>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<section class="login__box">
  <h1 class="login__header">
    <img src="<?php echo base_url('/asset/images/login/icon-logo.png') ?>" alt="">
    湖南高速通管理后台
  </h1>
  <div class="login__form">
      <div class="login__form-item">
        <label for="txtUserName">用户名</label>
        <div class="login__form-content box-shadow">
          <img class="icon-user" src="<?php echo base_url('/asset/images/login/icon-user.png') ?>" alt="">
          <input type="text" id="txtUserName" name="txtUserName" placeholder="请输入用户名" />
        </div>
      </div>
      <div class="login__form-item">
        <label for="txtPwd">密码</label>
        <div class="login__form-content box-shadow">
          <img class="icon-lock" src="<?php echo base_url('/asset/images/login/icon-lock.png') ?>" alt="">
          <input type="password" id="txtPwd" name="txtPwd" placeholder="请输入密码">
          <img class="icon-eye" onclick="changeType()" src="<?php echo base_url('/asset/images/login/icon-eye.png') ?>" alt="">
        </div>
      </div>
      <div class="login__form-item">
        <label class="checkbox_label"><input type="checkbox" name="remember" value="1">记住密码</input></label>
      </div>
      <div class="login__form-item">
      <input type="button" name="btnLogic" value="登录" id="btnLogic" onclick="login()" />
      </div>
  </div>
</section>
<footer>创建者：广州优路加信息科技有限公司</footer>
<section style="display: none">
    <div class="signinpanel">
        <div class="row">
            <div class="col-md-12">
                <h4 class="nomargin"><?php echo $this->config->item('project_name');?></h4>
                <p class="mt5 mb20">用户登录</p>
                <input name="txtUserName" type="text" id="txtUserName" class="form-control uname" placeholder="请输入用户名" />
                <input name="txtPwd" type="password" id="txtPwd" class="form-control pword" placeholder="请输入密码" />
                <label><input type="checkbox" name="remember" value="1">记住密码</input></label>
                <input type="button" name="btnLogic" value="登录" id="btnLogic" onclick="login()" class="btn btn-success btn-block" />
            </div><!-- col-sm-5 -->
        </div><!-- row -->
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2016. All Rights Reserved.
            </div>
            <div class="pull-right">
                Created By: <a href="http://www.u-road.com" target="_blank">广州优路加信息科技有限公司</a>
            </div>
        </div>
    </div>
</section>
</body>
</html>