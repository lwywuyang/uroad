<!-- 
    Author：LJK
    Email：lllsuyan@163.com
    Update：2019-08-06
    Name：ETC管理-ETC常见问题-编辑页
 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ETC管理-ETC常见问题-编辑页</title>
    <?php $this->load->view('admin/common') ?>
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }

        .m-r-10 {
            margin-right: 10px;
        }

        .m-t-20 {
            margin-top: 20px;
        }

        textarea {
            height: 100% !important;
            resize: none;
        }

        .wrap {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            padding: 15px;
            background-color: #ffffff;
        }

        .form {
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        .form-item {
            display: flex;
            align-items: flex-start;
        }

        .flex-1 {
            flex: 1;
        }

        .name {
            margin: 0 !important;
            padding: 0 !important;
            font-weight: bold;
            white-space: nowrap;
        }

        .buttons {
            display: flex;
            flex-shrink: 0;
            justify-content: center;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="form">
            <div class="form-item">
                <label class="name">问题：</label>
                <input class="form-control" type="text" name="question">
            </div>
            <div class="form-item flex-1 m-t-20">
                <label class="name">答案：</label>
                <textarea class="form-control" name="answer"></textarea>
            </div>
        </div>
        <div class="buttons">
            <input type="button" value="保存" class="btn btn-info m-r-10" onclick="save()">
            <input type="button" value="取消" class="btn btn-danger m-r-10" onclick="cancel(0)">
        </div>
    </div>

    <script>
        let id = "<?php echo $id ?>";

        $(function() {
            getDetails();
        });

        /**
         * 获取问题详情
         */
        function getDetails() {
            if (id != 0) {
                JAjax("admin/News/EtcQuestion", "getDetails", {
                    id: id, // 问题id
                }, function(res) {
                    if (res.Success) {
                        $("input[name=question]").val(res.data.question);
                        $("textarea[name=answer]").text(res.data.answer);
                    }
                }, null, true);
            }
        }

        /**
         * 保存
         */
        function save() {
            JAjax("admin/News/EtcQuestion", "save", {
                id: id, // 问题id
                question: $("input[name=question]").val(), // 问题
                answer: $("textarea[name=answer]").val(), // 答案
            }, function(res) {
                if (res.Success) {
                    cancel(1);
                } else {
                    ShowMsg("提示:" + res.Message);
                }
            });
        }

        /**
         * 取消
         */
        function cancel(v) {
            window.sessionStorage.setItem("cancelReload", v);
            closeLayerPageJs();
        }
    </script>
</body>

</html>