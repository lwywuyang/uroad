function initUpload(uploadbtn,showimg,imgval) {
    var $ = jQuery,
        $list = $('#fileList'),
        // 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,

        // 缩略图大小
        thumbnailWidth = 100 * ratio,
        thumbnailHeight = 100 * ratio,

        // Web Uploader实例
        uploader;

    // 初始化Web Uploader
    uploader = WebUploader.create({

        // 自动上传。
        auto: true,

        // swf文件路径
        swf: '/lib/webuploader/Uploader.swf',

        // 文件接收服务端。
        server: '/lib/webuploader/UploadImageHandler.ashx',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: uploadbtn,

        // 只允许选择文件，可选。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });
    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file, response) {
        var data = response._raw ? response._raw : response;
        $(showimg).attr("src", data.split(',')[1]);
        $(imgval).val(data.split(',')[1]);
    });

    // 文件上传失败，现实上传出错。
    uploader.on('uploadError', function (file, reason) {
        ShowMsg("上传失败：" + reson);
    });
    return uploader;
}


jQuery(function () {
    


});