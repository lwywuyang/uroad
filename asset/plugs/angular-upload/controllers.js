'use strict';

angular

    .module('app', ['angularFileUpload'])

    .controller('AppController', ['$scope', 'FileUploader', function($scope, FileUploader) {
        var uploader = $scope.uploader = new FileUploader({
            //url: 'upload.php'
            url: 'http://hunangstapi.u-road.com/GSTHuNanAdmin/index.php/admin/Uploadimg/angularFileUpload'
        });

        // FILTERS

        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 10;
            }
        });

        // CALLBACKS

        uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
            //console.info('onWhenAddingFileFailed', item, filter, options);
        };
        uploader.onAfterAddingFile = function(fileItem) {
            //console.info('onAfterAddingFile', fileItem);
        };
        uploader.onAfterAddingAll = function(addedFileItems) {
            //console.info('onAfterAddingAll', addedFileItems);
        };
        uploader.onBeforeUploadItem = function(item) {
            //console.info('onBeforeUploadItem', item);
        };
        uploader.onProgressItem = function(fileItem, progress) {
            //console.log('fileItem=>>'+fileItem);
            //console.info('onProgressItem', fileItem, progress);
        };
        uploader.onProgressAll = function(progress) {
            //console.info('onProgressAll', progress);
        };
        //添加thisRowNum,标记上传的文件在表格中的行数
        uploader.onSuccessItem = function(fileItem, response, status, headers,thisRowNum) {

            var $trObj = $('#tbody tr');
            var $tdObj = $trObj.eq(thisRowNum).find("td");
            $tdObj.eq(1).text(response);
            console.log(response);
            localStorage.setItem('weatherWord',response);

            /*$.post('http://hunangstapi.u-road.com/GSTHuNanAdmin/index.php/admin/Uploadimg/getHtml',{url:response},function(data){

                var dataArr = eval('('+data+')');

                if (dataArr.status == 'Success') {

                    UE.getEditor('weatherHtml').setContent(dataArr.data);
                }else{
                    ShowMsg("未能找到转换后的HTML文件，请使用Microsoft Office保存的word文件重试!");
                }

            });*/

            //console.info('onSuccessItem', fileItem, response, status, headers);
        };
        uploader.onErrorItem = function(fileItem, response, status, headers) {
            //console.info('onErrorItem', fileItem, response, status, headers);
        };
        uploader.onCancelItem = function(fileItem, response, status, headers) {
            //console.info('onCancelItem', fileItem, response, status, headers);
        };
        uploader.onCompleteItem = function(fileItem, response, status, headers) {
            //console.info('onCompleteItem', fileItem, response, status, headers);
        };
        uploader.onCompleteAll = function() {
            //console.info('onCompleteAll');
        };

        //console.info('uploader', uploader);

    }]);
