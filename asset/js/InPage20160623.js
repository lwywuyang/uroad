var loadingLayerIndex;

function showLoading(str)
{
    if(str==undefined)
        str='加载中…';
      loadingLayerIndex=layer.load('加载中…',1); 
}

function closeLoading()
{
    layer.close(loadingLayerIndex); 
}


//面板控制器，点击隐藏或显示
function panelClick(obj) {
    var t = jQuery(obj);
    var p = t.closest('.panel');
    if (!jQuery(obj).hasClass('maximize')) {
        p.find('.panel-body, .panel-footer').slideUp(200);
        t.addClass('maximize');
        t.html('&plus;');
    } else {
        p.find('.panel-body, .panel-footer').slideDown(200);
        t.removeClass('maximize');
        t.html('&minus;');
    }
    return false;
}
var strPath = "/";
var baseLoadingLayer;
//ajax处理的函数
function JAjax(classname, methodname, paramObj, successFunc, pagerID,isShowLoading) {
    /// <summary>
    ///     JavaScript Ajax方法调用
    /// </summary>
    /// <param name="classname" type="String">
    ///     classname - 业务逻辑类名。
    /// </param>
    /// <param name="methodname" type="String">
    ///     j - 要调用的 Ajax 方法。调用的方法
    /// </param>
    /// <param name="paramObj" type="String">
    ///     paramObj - Ajax 方法参数(键值对)。
    /// </param>
    /// <param name="successFunc" type="Function">
    ///     successFunc - Ajax 回调函数。
    /// </param>
    /// <param name="pagerID" type="String">
    ///     pagerID - 分页控件ID。
    /// </param>

    // JAjax("SysPlatformLogic", "Load", { Key: name, Oper: t }, function (data) {
    //             ReloadTb('dataGrid', data.data);
               
    //         }, "pager");
       
     var strUrls = InpageUrl+ classname + strPath + methodname;
    // var strUrls='http://127.0.0.1/uroad/index.php/admin/post';

    // if (pagerID) {
    //     // pagerobj="{OrderDesc:'asc',OrderField:'Name',PageSize:10}",取出参数
    //     strUrls = SetParastr("PageSize", $("#" + pagerID).attr("PageSize"), strUrls);
        // strUrls = SetParastr("OrderDesc", $("#" + pagerID).attr("OrderDesc"), strUrls);
    //     strUrls = SetParastr("OrderField", $("#" + pagerID).attr("OrderField"), strUrls);
    // }
    // 方法参数的处理


                //此处用setTimeout演示ajax的回调
    if(isShowLoading)
        showLoading();
           // showLoading();     
    $.post(strUrls, paramObj, function (result) {
         // if(isShowLoading)
          // closeLoading();
        if (successFunc) {
            
            var data = parseJsonResult(result);
            successFunc(data);
            if (data.Success && $("#" + pagerID) && data.PagerOrder) {
                GeneratePager(data.PagerOrder, pagerID);
            }
            //AutoPageHeight();
            
        }  

    }); 

}

//pagerJson取出的数据，pagerId页数
function GeneratePager(pagerJson, pagerId) {
    var loadFun = $("#" + pagerId).attr("Fun");
    $("#" + pagerId).addClass("pager");
    var pagerHtml = "<div class=\"dataTables_info\" >当前显示第 " + pagerJson.CurrentPage + " 页，共 " + pagerJson.TotalPage + " 页，" + pagerJson.TotalCount + " 条记录</div>";
    pagerHtml += "<div class=\"dataTables_paginate paging_full_numbers\" >";
    if (pagerJson.CurrentPage > 1) {
        pagerHtml += "<a tabindex=\"0\" class=\"first paginate_button \" onclick=\"" + loadFun + "(1)\"  >首页</a>";
        pagerHtml += "<a tabindex=\"0\" class=\"previous paginate_button \" onclick=\"" + loadFun + "(" + (pagerJson.CurrentPage-1) + ")\" >上一页</a>";
    }
    else {
        pagerHtml += "<a tabindex=\"0\" class=\"first paginate_button paginate_button_disabled \"   >首页</a>";
        pagerHtml += "<a tabindex=\"0\" class=\"previous paginate_button paginate_button_disabled \"   >上一页</a>";
    }
    pagerHtml += "<select class=\"form-control\" style=\"float: left; width: 100px; margin-right: 5px;\" onchange=\"" + loadFun + "(this.value)\">";
    for (var i = 1; i <= pagerJson.TotalPage; i++) {
        if (i == pagerJson.CurrentPage) {
            pagerHtml += "<option value="+i+" selected>"+i+"</option>";
        }
        else {
            pagerHtml += "<option value=" + i + " >" + i + "</option>";
        }
    }
    pagerHtml += "</select>";
    if (pagerJson.CurrentPage < pagerJson.TotalPage) {
        pagerHtml += "<a tabindex=\"0\" class=\"next paginate_button \" onclick=\"" + loadFun + "(" + (pagerJson.CurrentPage + 1) + ")\"  >下一页</a>";
        pagerHtml += "<a tabindex=\"0\" class=\"last paginate_button \" onclick=\"" + loadFun + "(" + (pagerJson.TotalPage) + ")\" >末页</a>";
    }
    else {
        pagerHtml += "<a tabindex=\"0\" class=\"next paginate_button  paginate_button_disabled\"  >下一页</a>";
        pagerHtml += "<a tabindex=\"0\" class=\"last paginate_button  paginate_button_disabled\"  >末页</a>";
    }
    $("#" + pagerId).attr("PagerObj", "{OrderDesc:'" + pagerJson.OrderDesc + "',OrderField:'" + pagerJson.OrderField + "',PageSize:" + pagerJson.PageSize + "}");
    $("#" + pagerId).html(pagerHtml);
}
function parseJsonResult(json) {
    /// <summary>
    ///     执行json格式字符串
    /// </summary>
    /// <param name="json" type="String">
    ///     json - json 字符串。
    /// </param>
    /// <returns type="Object" />
    return json ? eval("(" + json + ")") : null;
}
// 设置页面Url参数，有值替换，无值加上
function SetParastr(Name, Value, Url) {
    /// <summary>
    ///     设置页面Url参数，有值替换，无值加上
    /// </summary>
    /// <param name="Name" type="String">
    ///     查询字符串名。
    /// </param>
    /// <param name="Value" type="String">
    ///     查询字符串值。
    /// </param>
    /// <param name="Url" type="String">
    ///     Url 字符串。
    /// </param>

        // 获取参数的数值？name=1,获取1
    var oldvalue = GetParastr(Name, Url);
    var oldhref = Url ? Url : location.href;
    if (oldvalue != null) {
        var replacevalue = Name + "=" + oldvalue;
        // 字符串中用一些字符替换另一些字符，或替换一个与正则表达式匹配的子串后面换成前面。
        return oldhref.replace(replacevalue, Name + "=" + (Value ? Value : ""));
    }
    else {
        if (oldhref.indexOf('?') >= 0) {
            return oldhref + "&" + Name + "=" + (Value?Value:"");
        }
        else {
            return oldhref + "?" + Name + "=" + (Value ? Value : "");
        }
    }
    return oldhref;
}

function GetParastr(Name, Url) {
    /// <summary>
    ///     获取参数的数值
    /// </summary>
    /// <param name="Name" type="String">
    ///     Name - 查询字符串名称。
    /// </param>
    /// <param name="Url" type="String">
    ///     Url - Url 字符串。
    /// </param>
    /// <returns type="String">
    ///     查询字符串名对应的值。
    /// </returns>

    var hrefstr, pos, parastr, para, tempstr;
    // window.location.href 获取页面链接， 语句可以实现一个框架的页面在执行服务器端代码后刷新另一个框架的页面
    hrefstr = Url || window.location.href;
    // 查找字串中指定字符或字串首次出现的位置,返首索引值
    pos = hrefstr.indexOf("?")
    parastr = hrefstr.substring(pos + 1);
    para = parastr.split("&");
    tempstr = "";
    for (i = 0; i < para.length; i++) {
        tempstr = para[i];
        pos = tempstr.indexOf("=");
        if (tempstr.substring(0, pos) == Name) {
            return tempstr.substring(pos + 1);
        }
    }
    return null;
}
function ReloadTb(sourceTbID, datasource, titlerows) {
    /// <summary>
    ///     重新加载列表
    /// </summary>
    //table 的id sourceTbID
    //pager分页下标签的id
    var tb = document.getElementById(sourceTbID);
    //    var itemname = "";
    //    var name = $(tb).find(" td[NotShow='true'] ").each(function () {
    //        itemname += $(this).attr("itemvalue") + "@"; 
    //    });
    var colors = { "红": "red", "橙": "orange", "黄": "yellow", "绿": "green", "青": "cyan", "蓝": "blue", "紫": "purple" };
    if (tb) {
        if (titlerows)
            ClearTbRows(sourceTbID, titlerows);
        else
            ClearTbRows(sourceTbID);
        var data = datasource || [];
        $(tb).append("<tbody></tbody>");
        for (var i = 0; i < data.length; i++) {
            var itemclass;
            itemclass = "gradeA list";
            AddNewRow(data[i], tb, itemclass);
        }
       
    }
}
function ClearTbRows(sourceTbID, titlerows) {
    /// <summary>
    ///     清空列表，只留下表头
    /// </summary>
    var thetitlerowslength = titlerows || 1;
    thetitlerowslength--;
    var tb = document.getElementById(sourceTbID);
    if (tb) {
        $("#" + sourceTbID + " tbody").remove();
        
    }
}
function AddNewRow(obj, tb, rowclass) {
    /// <summary>
    ///  添加新行
    /// </summary>
    /// <param name="obj" type="Object">
    ///  要赋给新行的数据对象。
    /// </param>
    /// <param name="tb" type="Object">
    ///  表格对象。
    /// </param>
    /// <param name="rowclass" type="String">
    ///  表格新行className。
    /// </param>

    if (tb.rows.length > 0) {

        var newrow = $("<tr></tr>");
        // alert(newrow);
       
        if (rowclass)
            newrow.addClass(rowclass);
        //        alert($(tb).children().eq(0).find("th").length);
        //        alert(tb.rows[1].cells[i]);

        var cellCount = $(tb).children().eq(0).find("th").length;
        for (var i = 0; i < cellCount; i++) {
            var hide = $(tb.rows[0].cells[i]).attr("hide");
            var ishide = $(tb.rows[0].cells[i]).attr("NotShow");
            var itemvalue = $(tb.rows[0].cells[i]).attr("itemvalue");
            var center = $(tb.rows[0].cells[i]).attr("center");
            var wordbreak = $(tb.rows[0].cells[i]).attr("wordbreak");
            if (ishide != "true") {
                if (hide != 'true') {
                    var cell = $("<td style='word-break: break-all;'></td>");
                }else{
                    //隐藏但是可以被js遍历到内容的td
                    var cell = $("<td style='display:none;'></td>");
                }
                

                if ($(tb.rows[0].cells[i]).attr("itemclass"))
                    cell.addClass((tb.rows[0].cells[i]).attr("itemclass"));

                if (center == "true")
                    $(cell).addClass("center");
                if (wordbreak == "true") {
                    $(cell).addClass("wordbreak");
                    if ($(tb.rows[0].cells[i]).attr("width")) {
                        $(cell).attr("width", $(tb.rows[0].cells[i]).attr("width"));
                    }
                    else {
                        $(cell).attr("width", "120px");
                    }
                }
                // var value = obj[$(tb.rows[0].cells[i]).attr("itemvalue")] || obj[$(tb.rows[0].cells[i]).attr("itemvalue")]=='0' ? obj[$(tb.rows[0].cells[i]).attr("itemvalue")] : "";
                // var value = obj[$(tb.rows[0].cells[i]).attr("itemvalue")];
                var value = obj[$(tb.rows[0].cells[i]).attr("itemvalue")] != undefined ? obj[$(tb.rows[0].cells[i]).attr("itemvalue")] : "";
                var type = "";

                if ($(tb.rows[0].cells[i]).attr("showtype")) {
                    try {
                        eval("type=" + ReplaceDataItem(obj, $(tb.rows[0].cells[i]).attr("showtype").replace(new RegExp("{itemvalue}", "gm"), value)));
                    }
                    catch (e) {
                        type = $(tb.rows[0].cells[i]).attr("showtype");
                    }
                }
                var addattr = $(tb.rows[0].cells[i]).attr("attr") ? $(tb.rows[0].cells[i]).attr("attr") : "";
                var thetext = $(tb.rows[0].cells[i]).attr("itemtext") ? ReplaceDataItem(obj, $(tb.rows[0].cells[i]).attr("itemtext").replace(new RegExp("{itemvalue}", "gm"), value)) : "";
                var valuelist = $(tb.rows[0].cells[i]).attr("valuelist") ? ReplaceDataItem(obj, $(tb.rows[0].cells[i]).attr("valuelist").replace(new RegExp("{itemvalue}", "gm"), value)) : "";
                var showFormat = $(tb.rows[0].cells[i]).attr("showformat") ? $(tb.rows[0].cells[i]).attr("showformat") : "";
                if (type == "" && addattr != "") {

                }
                var maxthlength = parseInt($(tb.rows[0].cells[i]).attr("maxlength"), 10) || 70000;
                cell.html(ShowItem(type, value, ReplaceDataItem(obj, addattr.replace(new RegExp("{itemvalue}", "gm"), value)), thetext, valuelist, maxthlength, showFormat));
                newrow.append(cell);
            }
        }
        $(tb).find("tbody").append(newrow);
        
    }
}

function ShowItem(type, value, attrArray, text, valuelist, maxlength, showFormat) {
    /// <summary>
    ///     显示每个html选项
    /// </summary>

    var valuetype = typeof (value);
    if (CheckObjIsDate(value)) {
        showFormat = (showFormat == "") ? "yyyy-MM-dd" : showFormat;
        try {
            if (value.constructor == Date) {
                value = DateTimeToString(value, showFormat);
            }
            else {
                var date_value = StringToDateTime(value);
                value = DateTimeToString(date_value, showFormat);
            }
        }
        catch (E) {
        }

    }
    var attrtohtml = attrArray || "";
    var typelist = type.split('|');
    var attrlist = attrtohtml.split('|');
    var textlist = text.split('|');
    var result = "";
    for (var i = 0; i < typelist.length; i++) {
        var theattr = attrlist.length > i ? attrlist[i] : "";
        var thetext = textlist.length > i ? textlist[i] : "";
        if (typelist[i] == "select") {
            result += "<select " + theattr + ">" + itemHTML(typelist[i], value, theattr, textlist, valuelist) + "</select>";
            continue;
        }
        result += itemHTML(typelist[i], value, theattr, thetext, valuelist, maxlength);
        if (typelist[i] == "a" && i != typelist.length - 1) {
            result += "&nbsp;|&nbsp;";
        }
    }
    return result;
}

function itemHTML(type, value, attrtohtml, text, valuelist, maxlength) {
    /// <summary>
    ///     生成表格行内容(返回 table中单元格的 html字符串)
    /// </summary>
    /// <param name="type" type="String">
    ///     列类型(a:超链接,checkbox:多选框,radio:单选框,text:文本框,img:图像,hidden:显示一个值,隐藏另一个值,select:下拉框)。
    /// </param>
    value = value ? value : "";
    switch (type) {
        case 'a':
            text = text == "" ? "&nbsp;" : text.length > maxlength ? text.substr(0, maxlength) + "..." : text;
            return "<a " + attrtohtml + ">" + text + "</a>";
        case 'checkbox':
            return "<input type='checkbox' value='" + value + "' " + attrtohtml + " />";
        case 'radio':
            return "<input type='radio' value='" + value + "' " + attrtohtml + " />";
        case 'text':
            return "<input type='text' value='" + value + "' " + attrtohtml + " />";
        case 'img':
            return "<img class='item-img' value='" + value + "' " + attrtohtml + " alt='" + text + "' src='"+value+"' />";
        case 'span':
            return "<span " + attrtohtml + "  >" + value + "</span>";
        case 'hidden':
            return "<span>" + value + "</span><input type='hidden' value='" + value + "' " + attrtohtml + " />";
        case 'select':
            {
                var result = "";
                var values = valuelist.split('|');
                var selected = "";
                for (var i = 0; i < values.length; i++) {
                    if (value == values[i]) selected = " selected='selected'";
                    var thetext = text.length > i ? text[i] : "";
                    result += "<option value='" + values[i] + "'" + selected + ">" + thetext + "</option>";
                }
                return result;
            }
        default:
            return value == "" ? "&nbsp;" : value.length > maxlength ? "<span title=\"" + clearHTML(value) + "\">" + clearHTML(value).substr(0, maxlength) + "...</span>" : value;
    }
}
function clearHTML(html) {
    return html ? html.replace(/<[^>]*>/g, "").replace(/&nbsp;/g, " ") : "";
}
function ReplaceDataItem(datasource, str) {
    /// <summary>
    ///     替换数据项
    /// </summary>
    if (str) {
        var data = datasource || {};
        for (var p in data) {
            str = str.replace(new RegExp("{" + p + "}", "gm"), data[p]);
        }
    }
    return str;
}
function CheckObjIsDate(obj) {
    /// <summary>
    ///     判断是否日期格式
    /// </summary>
    /// <param name="obj" type="Object">
    ///     要检查的对象。
    /// </param>
    /// <returns type="Boolean">如果该参数为日期，则为 true；否则为 false。</returns>
    if ((typeof obj == 'object') && obj.constructor == Date) {
        return true;
    }else {
        try {
            return IsDate(obj);
        }
        catch (E) {
            return false;
        }
    }
}
function IsDate(DateString, Dilimeter) {
    if (DateString == null) return false;
    if (Dilimeter == '' || Dilimeter == null)
        Dilimeter = '-';
    var tempy = '';
    var tempm = '';
    var tempd = '';
    var tempArray;
    if (DateString.length < 8 || DateString.length > 19)
        return false;
    tempArray = DateString.split(Dilimeter);
    if (tempArray.length != 3)
        return false;
    if (tempArray[0].length == 4) {
        tempy = tempArray[0];
        tempd = tempArray[2].split(' ')[0];
    }
    else {
        tempy = tempArray[2];
        tempd = tempArray[1];
    }
    tempm = tempArray[1];
    var tDateString = tempy + '/' + tempm + '/' + tempd + ''; //加八小时是因为我们处于东八区
    var tempDate = new Date(tDateString);
    if (isNaN(tempDate))
        return false;
    if (((tempDate.getUTCFullYear()).toString() == tempy) && (tempDate.getMonth() == parseInt(tempm, 10) - 1) && (tempDate.getDate() == parseInt(tempd, 10))) {
        return true;
    }
    else {
        return false;
    }
}

function checkall(target, checkallobj, targetname) {
    /// <summary>
    ///     全选
    /// </summary>
    $(target+" input[name=" + targetname + "]").each(function () {
        if (checkallobj.checked) {
            $(this).prop("checked", true);
        }
        else {
            $(this).prop("checked", false);
        }
    });
}

function AutoPageHeight() {
    if (window.parent.ReSizePage)
        window.parent.ReSizePage();
}
function showLayerPageJs(url, title, w, h, func,arg) {
    if(window.parent.showLayerPage)
        window.parent.showLayerPage(url, title, w, h, func, arg);
    else if (window.parent.parent.showLayerPage)
    {
        window.parent.parent.showLayerPage(url, title, w, h, func, arg);
    }
}
// function showLayerPageJs(url, title, w, h, func, arg) {
//     if (window.parent.showLayerPage)
//         window.parent.showLayerPage(this.location, url, url, title, w, h, func, arg);
//     else if (window.parent.parent.showLayerPage) {
//         window.parent.parent.showLayerPage(this.location, url, title, w, h, func, arg);
//     }
// }
// 
function showLayerFullPageJs(url) {
    if (window.parent.showLayerFullPage){
          window.parent.showLayerFullPage(this.location, url);
    }else if (window.parent.parent.showLayerFullPage){
        window.parent.parent.showLayerFullPage(this.location, url);
    }
}
//跳转返回
function showLayerFullPagecomebackJs(){
    if (window.parent.showLayerFullPagecomeback)
        window.parent.showLayerFullPagecomeback();
    else if (window.parent.parent.showLayerFullPagecomeback) {
        window.parent.parent.showLayerFullPagecomeback();
    }
}
function showLayerPageJsCallBack(url, title, w, h, id, func) {
    if (window.parent.showLayerPageCallBack)
        window.parent.showLayerPageCallBack(url, title, w, h, id, func);
    else if (window.parent.parent.showLayerPageCallBack) {
        window.parent.parent.showLayerPageCallBack(url, title, w, h, id, func);
    }
}
function showLayerImageJs(url) {
    if (window.parent.showLayerImage)
        window.parent.showLayerImage(url);
    else if (window.parent.parent.showLayerImage) {
        window.parent.parent.showLayerImage(url);
    }
}
function showNoModelLayerPage(url, title, w, h, id, func) {
    if (window.parent.showNoModelLayerPage)
        window.parent.showNoModelLayerPage(url, title, w, h, id, func);
    else if (window.parent.parent.showNoModelLayerPage) {
        window.parent.parent.showNoModelLayerPage(url, title, w, h, id, func);
    }
}
function closeLayerPageJs() {
    if(window.parent.closeLayerPage)
        window.parent.closeLayerPage();
    else if (window.parent.parent.closeLayerPage)
        window.parent.parent.closeLayerPage();
}
function closeLayerPageJs(id, func) {
    if (window.parent.closeLayerPage)
        window.parent.closeLayerPage(id, func);
    else if (window.parent.parent.closeLayerPage)
        window.parent.parent.closeLayerPage(id, func);
}
function ShowConfirm(msg, sureFunc) {
    /// <summary>
    ///     公共弹出确认的方法
    /// </summary>
    if (confirm(msg)) {
        if (sureFunc)
            if (typeof (sureFunc) == "function")
                sureFunc();
        return true;
    }
    return false;
}
function ShowMsg(str) {
    layer.alert(str,0);
}
function getCheckedValues(name, context, type) {
    /// <summary>
    ///     获取选中的多选框或单选框值
    /// </summary>
    /// <param name="name" type="String">
    ///  name属性为name的元素。
    /// </param>
    /// <param name="context" type="String">
    ///  上下文对象。
    /// </param>
    /// <param name="type" type="String">
    ///  非空时去除最后逗号。
    /// </param>
    var target = context ? context : "";
    var result = "";
    $(target + " input[name='" + name + "']:checked").each(function () {
        if(type)
            result += "'"+$(this).val() + "',";
        else
            result += $(this).val() + ",";
    });
    result = result.substring(0, result.length - 1);//去掉最后一个逗号
    return result;
}


function AutoPageHeight100() {
    var ChleftFrame = document.getElementById("ChleftFrame");
    var Chmain = document.getElementById("Chmain");
    if (window.parent) {
        ChleftFrame.height=$(window).height() - 40;
        Chmain.height = $(window).height() - 30;

    }
    else {
        ChleftFrame.height=500;
        Chmain.height = 500;
    }
}
function setOrgRightUrl(obj, url) {
    window.parent.setFrameURL(obj, url);
}
(function ($) {
    var oldHTML = $.fn.html;
    $.fn.formhtml = function () {
        if (arguments.length) return oldHTML.apply(this, arguments);
        $("input,textarea,button", this).each(function () {
            this.setAttribute('value', this.value);
        });
        $(":radio,:checkbox", this).each(function () {
            // im not really even sure you need to do this for "checked"
            // but what the heck, better safe than sorry
            if (this.checked) this.setAttribute('checked', 'checked');
            else this.removeAttribute('checked');
        });
        $("option", this).each(function () {
            // also not sure, but, better safe...
            if (this.selected) this.setAttribute('selected', 'selected');
            else this.removeAttribute('selected');
        });
        return oldHTML.apply(this);
    };

    //optional to override real .html() if you want
    // $.fn.html = $.fn.formhtml;
})(jQuery);

function convertTime(hour, min) {
    var str = "";
    if (hour < 10)
        str += "0" + hour;
    else
        str += hour;
    str += ":";
    if (min < 10)
        str += "0" + min;
    else
        str += min;
    return str;

}
function convertDate(mon, day) {
    var str = "";
    if (mon < 10)
        str += "0" + mon;
    else
        str += mon;
    str += "-";
    if (day < 10)
        str += "0" + day;
    else
        str += day;
    return str;

}

function Loading(str) {
    baseLoadingLayer=layer.load(str, 60)
}
function closeLoad() {
    layer.close(baseLoadingLayer);
}
var TimeSycMode =new Array("");
function GetTimeSycMode(i) {
}
function showMsgAutoClose(str, time) {
    layer.msg(str, time, closeLayerPageJs);
}
function StringToDateTime(strings) {
    try {
        var arr = strings.split(" ");

        var arr1 = arr[0].split("-");

        var arr2 = arr[1].split(":");

        var year = arr1[0];

        var month = arr1[1] - 1;

        var day = arr1[2];

        var hour = arr2[0];

        var min = arr2[1];

        var ses = arr2[2];

        var date = new Date(year, month, day, hour, min, ses);

        return date;
    }
    catch (E) {
        return strings;
    }
    
}
function DateTimeToString(theday, format) {
    var theyear = theday.getFullYear();
    var thedate = theday.getDate();
    var themonth = theday.getMonth();
    themonth++;
    var thehours = theday.getHours();
    var themi = theday.getMinutes();
    var thes = theday.getSeconds();
    var monthstr = themonth.toString();
    if (themonth < 10) monthstr = "0" + monthstr;
    var daystr = thedate.toString();
    if (thedate < 10) daystr = "0" + daystr;
    var hstr = thehours.toString();
    if (thehours < 10) hstr = "0" + hstr;
    var mstr = themi.toString();
    if (themi < 10) mstr = "0" + mstr;
    var sstr = thes.toString();
    if (thes < 10) sstr = "0" + sstr;
    return format.replace("yyyy", theyear).replace("MM", monthstr).replace("dd", daystr).replace("hh", hstr).replace("mm", mstr).replace("ss", sstr);
}
function Validate(context) {
    /// <summary>
    ///     提交时一次性验证
    /// </summary>
    var isvalidata = true;
    var target = context ? context : "";
    var allneedvalidate = $(target + " input[notNull]," + target + " input[mLength]," + target + " input[valueType]," + target + " select[notNull]," + target + " textarea[notNull]," + target + " textarea[mLength]");
    for (var i = 0; i < allneedvalidate.length; i++) {

        var text = $(allneedvalidate[i]).parent().prev()[0].innerText.replace(":", "").replace("：", "").replace("*", "");
        if ($(allneedvalidate[i]).attr("notNull")) {
            if (!$(allneedvalidate[i]).val()) {
                text += "不能为空！";
                ShowMsg(text);
                isvalidata = false;
                break;
            }
        }
        if ($(allneedvalidate[i]).attr("mLength")) {
            if ($(allneedvalidate[i]).val().replace(/[^\x00-\xff]/g, "**").length > parseInt($(allneedvalidate[i]).attr("mLength"), 10)) {
                text += "长度不能超过" + $(allneedvalidate[i]).attr("mLength") + "！";
                ShowMsg(text);
                isvalidata = false;
                break;
            }
        }
        if ($(allneedvalidate[i]).attr("valueType")) {
            if (!CheckValueType($(allneedvalidate[i]).attr("valueType"), $(allneedvalidate[i]).val())) {
                text = "请输入正确的" + text;
                ShowMsg(text);
                isvalidata = false;
                break;
            }
        }
    }
    return isvalidata;
}



//查看图片
function showLayerImage(url){
    if (window.parent.showLayerTopImage)
        window.parent.showLayerTopImage(url);
    else if (window.parent.parent.showLayerTopImage)
        window.parent.parent.showLayerTopImage(url);
}

//查看图片2 类型2
function showimgpage(url){
    // window.parent.showTopimgpage(url);

    if (window.parent.showTopimgpage)
        window.parent.showTopimgpage(url);
    else if (window.parent.parent.showTopimgpage)
        window.parent.parent.showTopimgpage(url);
        
}
// window.parent.showimgpage(url);
//查看图片2 类型2
function ConfirmLayer(title,msg,func,arg,w,h,yesmsg,nomsg){
    if(!w){
        w=250;
    }
    if(!h){
        h=140;
    }
    if(!yesmsg){
        yesmsg='确认';
    }
    if(!nomsg){
        nomsg='取消';
    }
     if (window.parent.ConfirmTopLayer)
        window.parent.ConfirmTopLayer(w,h,title,msg,yesmsg,nomsg,func,arg);
    else if (window.parent.parent.ConfirmTopLayer)
        window.parent.parent.ConfirmTopLayer(w,h,title,msg,yesmsg,nomsg,func,arg);
    // window.parent.ConfirmTopLayer(w,h,title,msg,yesmsg,nomsg,func,arg);
}