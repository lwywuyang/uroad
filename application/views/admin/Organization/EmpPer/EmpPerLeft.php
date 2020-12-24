<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <?php $this->load->view('admin/common'); ?>
    
    <script type="text/javascript">
        var setting = {

            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                onClick: onClick
            }
        };

        var zNodes = <?php echo $getEmpPerOrg ?>;

        function onClick(e, treeId, treeNode) {

            if (treeNode.uri) {
                setOrgRightUrl("Chmain", treeNode.uri);
            }
        }

        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);

        });

    </script>
    <style>
        body {           
            overflow:auto;
        }
    </style>
</head>
<body>


        
    
    <ul id="treeDemo" class="ztree" ></ul>   
</body>
</html>
