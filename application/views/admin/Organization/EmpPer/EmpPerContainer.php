<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>组织机构</title>
    <script src="<?php echo base_url('/asset/js/jquery-1.10.2.min.js') ?>"></script>
    <script src="<?php echo base_url('/asset/js/InPage.js') ?>"></script>
    <script type="text/javascript">
        function setHeight(h) {
            if ($(window).height() < h) {
                $("#ChleftFrame").height(h);
                $("#Chmain").height(h);
                window.parent.setiFramePage1Heigth($("#ChleftFrame").height());
            }
        }
        $(document).ready(function () {
            AutoPageHeight100();       
            AutoPageHeight();
        });
        function setFrameURL(obj, url) {
            $("#" + obj).attr("src", url);
        }
    </script>
</head>
 <body>

     <table border="0" width="100%">
         <tr>
             <td width="250">
                 <iframe id="ChleftFrame" name="ChleftFrame"  frameborder="0" width="100%"   marginheight="0"  marginwidth="0" scrolling="yes" src="<?php echo base_url('/index.php/admin/Organization/EmpPerLogic/EmpPerLeft') ?>"  ></iframe>
             </td>
             <td>

                 <iframe id="Chmain" name="Chmain"  frameborder="0" width="100%"   marginheight="0"   marginwidth="0" scrolling="yes" src="<?php echo base_url('/index.php/admin/Organization/EmpPerLogic/Default1') ?>" ></iframe>
             </td>

         </tr>

     </table>
 </body>

  

</html>