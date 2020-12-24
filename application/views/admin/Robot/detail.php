<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title><?php  echo isset($title)?$title:"" ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
  content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>

  <link href="https://cdn.bootcss.com/amazeui/2.7.2/css/amazeui.min.css" rel="stylesheet">
  <style>
    @media only screen and (min-width: 1200px) {
      .blog-g-fixed {
        max-width: 1200px;
      }
    }

    @media only screen and (min-width: 641px) {
      .blog-sidebar {
        font-size: 1.4rem;
      }
    }

    .blog-main {
      padding: 15px 0 20px 0; 
    }

    .blog-title {
      margin: 0;
      font-size:2.2rem;
      font-weight: 600;
      color: #000;
    }

    .blog-meta {
      font-size: 14px;
      margin: 10px 0 ;
      color: gray;
    }

    .blog-meta a {
      color: #27ae60;
    }

    .blog-pagination a {
      font-size: 1.4rem;
    }

    .blog-team li {
      padding: 4px;
    }

    .blog-team img {
      margin-bottom: 0;
    }

    .blog-content img,
    .blog-team img {
      max-width: 100%;
      height: auto;
    }

    .blog-footer {
      padding: 10px 0;
      text-align: center; 
    }
    #author{color: #07518d;}
  </style>
</head>
<body>

  <header class="am-topbar" style="display:none">

  </header>

  <div class="am-g am-g-fixed blog-g-fixed">
    <div class="am-u-md-8">
      <article class="blog-main">
        <h3 class="blog-title" id="title">
       <?php  echo isset($title)?$title:"" ?>
        </h3>
       
       <br>
        <div class="blog-content" id="content">
      <?php  echo isset($answer)?$answer:"" ?>

        </div>

      </article>


    </div>



  </div>

  <footer class="blog-footer" style="display:none">
    <p><br/>
      <small></small>
    </p>
  </footer>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/polyfill/rem.min.js"></script>
<script src="assets/js/polyfill/respond.min.js"></script>
<script src="assets/js/amazeui.legacy.js"></script>
<![endif]-->


</body>
</html>
