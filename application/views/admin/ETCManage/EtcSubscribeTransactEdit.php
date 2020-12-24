<!-- 
    Author：LJK
    Email：lllsuyan@163.com
    Update：2019-08-13
    Name：ETC管理-ETC预约办理-编辑页
 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ETC管理-ETC预约办理-编辑页</title>
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

        .form-item {
            display: flex;
            align-items: center;
        }

        .flex-1 {
            flex: 1;
        }

        .name {
            position: relative;
            width: 66px;
            flex-shrink: 0;
            margin: 0 !important;
            padding: 0 !important;
            font-weight: bold;
            white-space: nowrap;
            text-align-last: justify;
        }

        .point::after {
            content: "湘";
            position: absolute;
            top: 50%;
            left: 80px;
            transform: translateY(-50%);
            z-index: 1;
            font-size: 16px;
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
                <label class="name">姓名</label>
                <input class="form-control" placeholder="请输入姓名" name="name">
            </div>
            <div class="form-item m-t-20">
                <label class="name">手机号</label>
                <input class="form-control" placeholder="请输入手机号" name="phone">
            </div>
            <div class="form-item m-t-20">
                <label class="name point">车牌号</label>
                <input class="form-control" style="padding-left:30px;" placeholder="请输入车牌号" name="plate">
            </div>
            <div class="form-item m-t-20">
                <label class="name">车牌颜色</label>
                <select class="form-control" name="plateColor">
                    <option value="">请选择车牌颜色</option>
                    <?php foreach ($plateColor as $item) : ?>
                    <option value="<?php echo $item['code'] ?>"><?php echo $item['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-item m-t-20">
                <label class="name">银行</label>
                <select class="form-control" name="bank">
                    <option value="">请选择办理银行</option>
                    <?php foreach ($bank as $item) : ?>
                    <option value="<?php echo $item['code'] ?>"><?php echo $item['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-item m-t-20">
                <label class="name">就近地址</label>
                <textarea class="form-control" name="address"></textarea>
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
                JAjax("admin/ETCManage/EtcSubscribeTransact", "getDetails", {
                    id: id, // 订单id
                }, function(res) {
                    // console.log(res);
                    if (res.Success) {
                        $("input[name=name]").val(res.data.name);
                        $("input[name=phone]").val(res.data.mobile);
                        $("input[name=plate]").val(res.data.platenum.substr(1));
                        $("select[name=plateColor]").val(res.data.colorno);
                        $("select[name=bank]").val(res.data.bankno);
                        $("textarea[name=address]").val(res.data.address);
                    }
                }, null, true);
            }
        }

        /**
         * 保存
         */
        function save() {
            JAjax("admin/ETCManage/EtcSubscribeTransact", "save", {
                id: id, // 订单id
                name: $("input[name=name]").val(), // 用户姓名
                mobile: $("input[name=phone]").val(), // 手机号
                platenum: "湘" + $("input[name=plate]").val(), // 车牌号
                colorno: $("select[name=plateColor]").val(), // 车牌颜色编号
                bankno: $("select[name=bank]").val(), // 银行编号
                address: $("textarea[name=address]").val(), // 就近地址
            }, function(res) {
                if (res.Success) {
                    cancel(1);
                } else {
                    ShowMsg("提示:" + res.Message);
                }
            }, null, true);
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