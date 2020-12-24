<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>左边公司列表</title>
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

        var zNodes =<?php echo $com; ?>;
        function onClick(e, treeId, treeNode) {
            if (treeNode.uri && treeNode.uri != "") {
                setOrgRightUrl("Chmain", treeNode.uri);
            }
            else {
                var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                zTree.expandNode(treeNode);
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
