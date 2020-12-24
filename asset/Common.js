var strPath = "/";

function JAjax(classname, methodname, paramObj, successFunc, pagerID) {
    ///	<summary>
    ///		JavaScript Ajax方法调用
    ///	</summary>
    ///	<param name="classname" type="String">
    ///		classname - 业务逻辑类名。
    ///	</param>
    ///	<param name="methodname" type="String">
    ///		methodname - 要调用的 Ajax 方法。
    ///	</param>
    ///	<param name="paramObj" type="String">
    ///		paramObj - Ajax 方法参数(键值对)。
    ///	</param>
    ///	<param name="successFunc" type="Function">
    ///		successFunc - Ajax 回调函数。
    ///	</param>
    ///	<param name="pagerID" type="String">
    ///		pagerID - 分页控件ID。
    ///	</param>
    var strUrls = strPath + "AjaxHandler.ashx?method=" + methodname + "&classname=" + classname;
    var pager;
    if (pagerID) {
        var aa = document.getElementById("BodyContent_" + pagerID + "_Pager");
        pager = parseJsonResult(document.getElementById("BodyContent_" + pagerID + "_Pager").PagerObj || $("#BodyContent_" + pagerID + "_Pager").attr("PagerObj"));
        strUrls = SetParastr(pager.pagerkey, pager[pager.pagerkey], strUrls);
        strUrls = SetParastr("PageSize", pager.PageSize, strUrls);
        strUrls = SetParastr("OrderDesc", pager.OrderDesc, strUrls);
        strUrls = SetParastr("OrderField", pager.OrderField, strUrls);
    }
    $.post(strUrls, paramObj, function (result) {
        if (successFunc)
        {
            var data = parseJsonResult(result);
            successFunc(data);
            if (data.Success)
            {
                if (pager)
                {
                    GeneratePager(data.PagerOrder, pager, "BodyContent_" + pagerID + "_Pager");
                }
            }
        }
    });
}

function parseJsonResult(json) {
    ///	<summary>
    ///		执行json格式字符串
    ///	</summary>
    ///	<param name="json" type="String">
    ///		json - json 字符串。
    ///	</param>
    ///	<returns type="Object" />
    return json ? eval("(" + json + ")") : null;
}

function GeneratePager(pager, pagerjsObj, pagerID) {
    ///	<summary>
    ///		创建分页html
    ///	</summary>
    ///	<param name="pager" type="String">
    ///		pager - pager 对象。
    ///	</param>
    ///	<param name="pagerjsObj" type="String">
    ///		pagerjsObj - pagerjsObj 对象。
    ///	</param>
    ///	<param name="pagerID" type="String">
    ///		pagerID - 分页控件ID。
    ///	</param>

    if (pager && $("#" + pagerID).length > 0)
    {
        if (pagerjsObj.clientID)
        {
            var currentpage = pager.CurrentPage;
            var totalpage = pager.TotalPage;
            var totlacount = pager.TotalCount;
            var pagerhtml = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
            pagerhtml += "<tr>";
            pagerhtml += "<td height='31' nowrap>";
            pagerhtml += "总共" + totlacount + "条记录 当前 " + currentpage + "/" + totalpage;
            pagerhtml += "</td>";
            pagerhtml += "<td align='center' nowrap>";
            if (currentpage > 1)
            {
                pagerhtml += "<a href=\"javascript:void(0)\" goto=\"1\" title='第一页'><img src='/images/form/first_page.png' border='0' align='absmiddle'></a>";
                pagerhtml += "<a href=\"javascript:void(0)\" goto=\"" + (currentpage - 1) + "\" title='上一页'><img src='/images/form/prev_page.png' border='0' align='absmiddle'></a>";
            }
            else
            {
                pagerhtml += "<img src='/images/form/no_first_page.png' border='0' align='absmiddle'>";
                pagerhtml += "<img src='/images/form/no_prev_page.png' border='0' align='absmiddle'>";
            }
            if (totalpage > currentpage)
            {
                pagerhtml += "<a href=\"javascript:void(0)\" goto=\"" + (currentpage + 1) + "\" title='下一页'><img src='/images/form/next_page.png' border='0' align='absmiddle'></a>";
                pagerhtml += "<a href=\"javascript:void(0)\" goto=\"" + totalpage + "\" title='最后页'><img src='/images/form/last_page.png' border='0' align='absmiddle'></a>";
            }
            else
            {
                pagerhtml += "<img src='/images/form/no_next_page.png' border='0' align='absmiddle'>";
                pagerhtml += "<img src='/images/form/no_last_page.png' border='0' align='absmiddle'>";
            }
            pagerhtml += "</td><td align='right' nowrap>" + pager.PageSize + "条记录/页 转到<input type='text' name='txtpagernum' id='txtpagernum' style='width: 40px;' onkeyup='clearNoNum(this);'>页</td>";
            pagerhtml += "<td width='34' align='right' nowrap>" + (totalpage > 1 ? "<input type='image' gotopage='true' src='/images/form/goto_page.png' />" : "<input type='image' src='/images/form/no_goto_page.png' onclick='return false;' />") + "</td>";
            pagerhtml += "</tr></table>";
            $("#" + pagerID).html(pagerhtml);
            $("#" + pagerjsObj.clientID + "_emptydiv").remove();
            $("#" + pagerID + " a[goto]").click(function () {
                var goto = $(this).attr("goto");
                $("#" + pagerID).attr("PagerObj", "{OrderDesc:'" + pagerjsObj.OrderDesc + "',OrderField:'" + pagerjsObj.OrderField + "',PageSize:" + pagerjsObj.PageSize + "," + pagerjsObj.pagerkey + ":" + goto + ",clientID:'" + pagerjsObj.clientID + "',AutoHeight:'" + pagerjsObj.AutoHeight + "',pagerLoadFunc:'" + pagerjsObj.pagerLoadFunc + "',pagerkey:'" + pagerjsObj.pagerkey + "'}");
                eval(pagerjsObj.pagerLoadFunc);
            });
            $("#" + pagerID + " input[type='image'][gotopage]").click(function () {
                var pagenum = $(this).parent().prev().find('input').val();
                if (pagenum)
                {
                    $("#" + pagerID).attr("PagerObj", "{OrderDesc:'" + pagerjsObj.OrderDesc + "',OrderField:'" + pagerjsObj.OrderField + "',PageSize:" + pagerjsObj.PageSize + "," + pagerjsObj.pagerkey + ":" + pagenum + ",clientID:'" + pagerjsObj.clientID + "',AutoHeight:'" + pagerjsObj.AutoHeight + "',pagerLoadFunc:'" + pagerjsObj.pagerLoadFunc + "',pagerkey:'" + pagerjsObj.pagerkey + "'}");
                    eval(pagerjsObj.pagerLoadFunc);
                }
                return false;
            });
            setTimeout(function () { AutoSetHeight(pagerjsObj, pagerID); }, 1);
            //            if (!haveresize) {
            //                $(window).resize(function () {
            //                    AutoSetHeight(pagerjsObj, pagerID);
            //                });
            //                haveresize = true;
            //            }
            //alert('ok');
        }
    }
}
//创建分页的连接
function PagerLoadFuncLink(pagerjsObj, pagernum, pagerID) {
    var str = "$(\"#" + pagerID + "\").attr(\"PagerObj\", \"{OrderDesc:'" + pagerjsObj.OrderDesc + "',OrderField:'" + pagerjsObj.OrderField + "',PageSize:" + pagerjsObj.PageSize + "," + pagerjsObj.pagerkey + ":" + pagernum + ",clientID:'" + pagerjsObj.clientID + "',AutoHeight:'" + pagerjsObj.AutoHeight + "',pagerLoadFunc:'" + pagerjsObj.pagerLoadFunc + "',pagerkey:'" + pagerjsObj.pagerkey + "'}\");" + pagerjsObj.pagerLoadFunc;
    return "$(\"#" + pagerID + "\").attr(\"PagerObj\", \"{OrderDesc:'" + pagerjsObj.OrderDesc + "',OrderField:'" + pagerjsObj.OrderField + "',PageSize:" + pagerjsObj.PageSize + "," + pagerjsObj.pagerkey + ":" + pagernum + ",clientID:'" + pagerjsObj.clientID + "',AutoHeight:'" + pagerjsObj.AutoHeight + "',pagerLoadFunc:'" + pagerjsObj.pagerLoadFunc + "',pagerkey:'" + pagerjsObj.pagerkey + "'}\");" + pagerjsObj.pagerLoadFunc;
}
//延迟自动设置高度
function AutoSetHeight(pagerjsObj, pagerID) {
    if (pagerjsObj.AutoHeight.toLocaleLowerCase() == "auto")
    {
        var emptyheight = document.documentElement.clientHeight - $('#' + pagerID).offset().top - $('#' + pagerID).height();
        GenerateEmptyRows(emptyheight, pagerjsObj.clientID, true);
    }
    else
    {
        var emptyheight = parseInt(pagerjsObj.AutoHeight, 10);
        emptyheight = emptyheight - $('#' + pagerID).prev().height() - $('#' + pagerID).height();
        if (emptyheight > 0)
        {
            GenerateEmptyRows(emptyheight, pagerjsObj.clientID, true);
        }
    }
}
//创建空行html
function GenerateEmptyRows(emptyheight, clientID, autoaddclass) {
    $("#" + clientID + "_emptydiv").remove();
    //    var perheight = $('#' + clientID + '_Pager').siblings(".form_list").find("tr").eq(1).height();
    //    if (!perheight) perheight = 31;
    //    var genaterows = parseInt((emptyheight) / perheight, 10);
    //    var jslastheight = emptyheight - genaterows * perheight;
    //    var lastrowclassIndex = 1;
    var thetable = $('#' + clientID + '_Pager').parent().children(".form_list");
    if (thetable.length > 0)
    {
        var lastrowclass = thetable[0].rows[thetable[0].rows.length - 1].className;
        if (lastrowclass == "") lastrowclassIndex = 1;
        else
        {
            if (lastrowclass && lastrowclass.indexOf('form_list_row1') >= 0) lastrowclassIndex = 1;
            else lastrowclassIndex = 2;
        }
    }
    var emptyclassname = "emptydiv" + lastrowclassIndex;
    var tablehtml = '<div class="' + emptyclassname + '" id="' + clientID + '_emptydiv" style="height:' + (emptyheight - 1) + 'px">';
    //    tablehtml+='<table width="100%" style="border:0;"  cellspacing="0" cellpadding="0" class="form_list">';
    //    for (var i = 0; i < genaterows; i++) {
    //        var rowclass = "form_list_row" + ((lastrowclassIndex + i) % 2 > 0 ? 2 : 1);
    //        if (autoaddclass) {
    //            tablehtml += "<tr class=\"" + rowclass + "\"><td>&nbsp;</td></tr>";
    //        }
    //        else {
    //            tablehtml += "<tr><td>&nbsp;</td></tr>";
    //        }
    //    }
    //    tablehtml += "</table>";
    tablehtml += "</div>";
    $('#' + clientID + '_Pager').before(tablehtml);
    //    if (genaterows || jslastheight) {
    //        if (genaterows) {
    //            $('#' + clientID + '_Pager').before(tablehtml);
    //        }
    //        var lastheight = emptyheight - $("#" + clientID + "_emptydiv").height();
    //        if (lastheight == emptyheight) lastheight = jslastheight;
    //        if (lastheight > 0) {
    //            $("#" + clientID + "_emptydiv").append("<div style=\"height:" + (lastheight - 2) + "px;margin:0;padding:0;\"></div>");
    //        }
    //    }
}

function OpendialogModeWindow(url, target, w, h, mode) {
    ///	<summary>
    ///		弹出（或模态）窗口
    ///	</summary>
    ///	<param name="url" type="String">
    ///		url - URI 字符串。
    ///	</param>
    ///	<param name="target" type="String">
    ///		target - target。
    ///	</param>
    ///	<param name="w" type="Number">
    ///		w - 窗口宽度。
    ///	</param>
    ///	<param name="h" type="Number">
    ///		h - 窗口高度。
    ///	</param>
    ///	<param name="mode" type="String">
    ///		mode - 值modal,模态窗口,其他值时为普通窗口。
    ///	</param>
    if (url.indexOf('?') >= 0)
    {
        url = url + '&' + Math.random();
    }
    else
    {
        url = url + '?' + Math.random();
    }
    var hWin = true;
    if (mode == 'modal')
    {
        hWin = window.showModalDialog(url, target, 'status:no;scrollbars;no;scroll:no;help:no;resizable:no;menubar=no;toolbar=no;location=no;dialogwidth:' + w + 'px;dialogheight:' + h + 'px');
    }
    else
    {
        window.open(url, target, 'width=' + w + 'px,height=' + h +
                                    'px,left=' + (screen.width - w) / 2 + 'px,top=' + (screen.height - h) / 2 +
                                    'px,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no,alwaysRaised=yes');
    }
}


function OpenRoportWindow(url, target, w, h) {
    ///	<summary>
    ///		弹出模态报表窗口
    ///	</summary>
    ///	<param name="url" type="String">
    ///		url - URI 字符串。
    ///	</param>
    ///	<param name="target" type="String">
    ///		target - target。
    ///	</param>
    ///	<param name="w" type="Number">
    ///		w - 窗口宽度。
    ///	</param>
    ///	<param name="h" type="Number">
    ///		h - 窗口高度。
    ///	</param>
    if (url.indexOf('?') >= 0)
    {
        url = url + '&' + Math.random();
    }
    else
    {
        url = url + '?' + Math.random();
    }
    var hWin = true;
    hWin = window.showModalDialog(url, target, 'status:no;scrollbars;no;scroll:yes;help:no;resizable:no;menubar=no;toolbar=no;location=no;dialogwidth:' + w + 'px;dialogheight:' + h + 'px');
}

function getHost() {
    ///	<summary>
    ///		获取网站根地址
    ///	</summary>
    var uri = parseUri(document.location);
    return uri.protocol + "://" + uri.authority;
}

function parseUri(sourceUri) {
    ///	<summary>
    ///		将Url转换为自定义Uri对象，类似.net的Uri对象
    ///	</summary>
    ///	<param name="sourceUri" type="String">
    ///		sourceUri - 源Uri。
    ///	</param>
    ///	<returns type="String" >
    ///		目标Uri。
    ///	</returns>

    var uriPartNames = ["source", "protocol", "authority", "domain", "port", "path", "directoryPath", "fileName", "query", "anchor"];
    var uriParts = new RegExp("^(?:([^:/?#.]+):)?(?://)?(([^:/?#]*)(?::(\\d*))?)?((/(?:[^?#](?![^?#/]*\\.[^?#/.]+(?:[\\?#]|$)))*/?)?([^?#/]*))?(?:\\?([^#]*))?(?:#(.*))?").exec(sourceUri);
    var uri = {};
    for (var i = 0; i < 10; i++)
    {
        uri[uriPartNames[i]] = (uriParts[i] ? uriParts[i] : "");
    }
    if (uri.directoryPath.length > 0)
    {
        uri.directoryPath = uri.directoryPath.replace(/\/?$/, "/");
    }
    return uri;
}

function Search() {
    ///	<summary>
    ///		通用查询页面查询方法
    ///	</summary>
    var searchcondition = location.href;
    $("input[FieldName]").each(function () {
        searchcondition = SetParastr($(this).attr("FieldName"), escape($(this).val()), searchcondition);
    });
    location.href = searchcondition;
}

function AutoSetValue() {
    ///	<summary>
    ///		用于查询页面自动把参数赋值到对应输入框
    ///	</summary>
    var allparmarsArray = location.href.split('?');
    if (allparmarsArray.length > 1)
    {
        var allparmars = allparmarsArray[1].split('&');
        for (var i = 0; i < allparmars.length; i++)
        {
            var keyvalues = allparmars[i].split('=');
            if (keyvalues.length > 1)
            {
                if ($("input[FieldName='" + keyvalues[0] + "']").length > 0)
                {
                    $("input[FieldName='" + keyvalues[0] + "']").val(unescape(keyvalues[1]));
                }
            }
        }
    }
}

function GetParastr(Name, Url) {
    ///	<summary>
    ///		获取参数的数值
    ///	</summary>
    ///	<param name="Name" type="String">
    ///		Name - 查询字符串名称。
    ///	</param>
    ///	<param name="Url" type="String">
    ///		Url - Url 字符串。
    ///	</param>
    ///	<returns type="String">
    ///		查询字符串名对应的值。
    ///	</returns>

    var hrefstr, pos, parastr, para, tempstr;
    hrefstr = Url || window.location.href;
    pos = hrefstr.indexOf("?")
    parastr = hrefstr.substring(pos + 1);
    para = parastr.split("&");
    tempstr = "";
    for (i = 0; i < para.length; i++)
    {
        tempstr = para[i];
        pos = tempstr.indexOf("=");
        if (tempstr.substring(0, pos) == Name)
        {
            return tempstr.substring(pos + 1);
        }
    }
    return null;
}
//设置页面Url参数，有值替换，无值加上
function SetParastr(Name, Value, Url) {
    var oldvalue = GetParastr(Name, Url);
    var oldhref = Url ? Url : location.href;
    if (oldvalue != null)
    {
        var replacevalue = Name + "=" + oldvalue;
        return oldhref.replace(replacevalue, Name + "=" + Value);
    }
    else
    {
        if (oldhref.indexOf('?') >= 0)
        {
            return oldhref + "&" + Name + "=" + Value;
        }
        else
        {
            return oldhref + "?" + Name + "=" + Value;
        }
    }
    return oldhref;
}
//转换时间格式
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
    return format.replace("yyyy", theyear).replace("MM", monthstr).replace("dd", daystr).replace("HH", hstr).replace("mm", mstr).replace("ss", sstr);
}
//将数组转换成可用于ajax传输参数的对象
function ArraytoParmars(beforeName, array, replaceStr) {
    var parmars = {};
    var replacelist = replaceStr.split(';');
    for (var i = 0; i < array.length; i++)
    {
        for (var a in array[i])
        {
            eval("parmars." + beforeName + "_" + getReplaceName(replacelist, a) + "_" + i + "='" + array[i][a] + "';");
        }
    }
    return parmars;
}
function getReplaceName(replacelist, name) {
    for (var i = 0; i < replacelist.length; i++)
    {
        if (replacelist[i].indexOf(name) >= 0)
        {
            return replacelist[i].split('=')[1];
        }
    }
    return name;
}

//判断是否日期格式
function CheckObjIsDate(obj) {
    if ((typeof obj == 'object') && obj.constructor == Date)
    {
        return true;
    }
    return false;
}
//自动显示排序图标和组织带排序参数的Url
function AutoOrderBy() {
    $(".title[dataitem]").click(function () {
        if ($(this).attr("dontCallClick"))
        {
            $(this).removeAttr("dontCallClick");
        }
        else
        {
            var orderfield = GetParastr("OrderField");
            var asc = GetParastr("OrderDesc");
            if (asc == null || asc == "desc") asc = "asc";
            else asc = "desc";
            var src = SetParastr("OrderField", $(this).attr("dataitem"));
            location.href = SetParastr("OrderDesc", asc, src);
        }
    });
    $(".title[dataitem]").each(function () {
        $(this).css("cursor", "pointer");
        if ($(this).attr("dataitem") == GetParastr("OrderField"))
        {
            if (GetParastr("OrderDesc") == "asc")
            {
                $(this).prepend('<img src="/images/icon-table-sort-asc.gif" width="15" height="16" border="0" align="absmiddle" />');
            }
            else
            {
                $(this).prepend('<img src="/images/icon-table-sort-desc.gif" width="15" height="16" border="0" align="absmiddle" />');
            }
        }
        else
        {
            $(this).prepend('<img src="/images/icon-table-sort.gif" width="15" height="16" border="0" align="absmiddle" />');
        }
    });
}
function AutoAjaxOrderBy() {
    if ($(".form_list_foot[id$=_Pager][AjaxLoad]").prev("table").find(".title[dataitem]").length > 0)
    {
        $(".form_list_foot[id$=_Pager][AjaxLoad]").prev("table").find(".title[dataitem]").unbind("click");
        $(".form_list_foot[id$=_Pager][AjaxLoad]").prev("table").find(".title[dataitem]").click(function () {
            if ($(this).attr("dontCallClick"))
            {
                $(this).removeAttr("dontCallClick");
            }
            else
            {
                var orderfield = $(this).attr("dataitem");
                var asc = $(this).attr("itemasc");
                if (asc == null || asc == "desc") asc = "asc";
                else asc = "desc";
                var thepager = $(this).parents("table").nextAll(".form_list_foot[id$=_Pager]").eq(0);
                var pagerjsObj = parseJsonResult(thepager.attr("PagerObj"));
                thepager.attr("PagerObj", "{OrderDesc:'" + asc + "',OrderField:'" + orderfield + "',PageSize:" + pagerjsObj.PageSize + "," + pagerjsObj.pagerkey + ":" + pagerjsObj[pagerjsObj.pagerkey] + ",clientID:'" + pagerjsObj.clientID + "',AutoHeight:'" + pagerjsObj.AutoHeight + "',pagerLoadFunc:'" + pagerjsObj.pagerLoadFunc + "',pagerkey:'" + pagerjsObj.pagerkey + "'}");
                eval(pagerjsObj.pagerLoadFunc);
                $(this).parent().children(".title[dataitem]").find("img").filter("[src$='icon-table-sort-asc.gif'],[src$='icon-table-sort-desc.gif']").replaceWith('<img src="/images/icon-table-sort.gif" width="15" height="16" border="0" align="absmiddle" />');
                var imgsrc = asc == "asc" ? '<img src="/images/icon-table-sort-asc.gif" width="15" height="16" border="0" align="absmiddle" />' : '<img src="/images/icon-table-sort-desc.gif" width="15" height="16" border="0" align="absmiddle" />';
                $(this).children("img").eq(0).remove();
                $(this).prepend(imgsrc);
                $(this).attr("itemasc", asc);
            }
        });
    }
}
//公用显示提示信息的方法
function ShowMsg(msg, sureFunc) {
    alert(msg);
    if (sureFunc)
        if (typeof (sureFunc) == "function")
            sureFunc();
}
//公用显示提示错误的方法
function ShowError(msg, sureFunc) {
    alert(msg);
    if (sureFunc)
        if (typeof (sureFunc) == "function")
            sureFunc();
}
//公共弹出确认的方法
function ShowConfirm(msg, sureFunc) {
    if (confirm(msg))
    {
        if (sureFunc)
            if (typeof (sureFunc) == "function")
                sureFunc();
        return true;
    }
    return false;
}
//公共弹出确认的方法
function ShowDelConfirm(checkboxname, context) {
    var target = context || "";
    if (checkboxname)
    {
        if ($(target + " input[name=" + checkboxname + "]:checked").length == 0)
        {
            ShowMsg("请至少选择一条记录！");
            return false;
        }
    }
    else
    {
        //兼容两种类型
        if ($(target + " input[name=rpcheckbox]").length > 0)
        {
            if ($(target + " input[name=rpcheckbox]:checked").length == 0)
            {
                ShowMsg("请至少选择一条记录！");
                return false;
            }
        } else
        {
            if ($(target + " input[name=checkbox]:checked").length == 0)
            {
                ShowMsg("请至少选择一条记录！");
                return false;
            }
        }
    }
    return ShowConfirm('确定要删除记录吗？');
}
//弹出操作成功提示语，默认提示语是：“操作成功！”
function OPAlert(msg) {
    if (msg && msg != "")
    {
        ShowMsg(msg);
    }
    else
    {
        ShowMsg("操作成功！");
    }
}
var hasShowErrMsg = false;
//注册表单验证事件
function SetValidate() {
    $("input[notNull],textarea[notNull],select[notNull]").parent().prev().prepend("<font style='color:red;'>*</font>");
    $("input[notNull],textarea[notNull],input[mLength],textarea[mLength],input[valueType]").blur(function () {
        var text = $(this).parent().prev()[0].innerText.replace(":", "").replace("：", "").replace("*", "");
        if ($(this).attr("notNull") && $(this).val() == "")
        {
            if (!hasShowErrMsg)
            {
                text += "不能为空！";
                ShowInfo(true, text, $(this)[0]);
                $(this)[0].focus();
                hasShowErrMsg = true;
            }
        }
        else if ($(this).attr("mLength") && $(this).val().replace(/[^\x00-\xff]/g, "**").length > parseInt($(this).attr("mLength"), 10))
        {
            if (!hasShowErrMsg)
            {
                text += "长度不能超过" + $(this).attr("mLength") + "！";
                ShowInfo(true, text, $(this)[0]);
                $(this)[0].focus();
                hasShowErrMsg = true;
            }
        }
        else if ($(this).attr("valueType") && !CheckValueType($(this).attr("valueType"), $(this).val()))
        {
            if (!hasShowErrMsg)
            {
                text = "请输入正确的" + text + "！";
                ShowInfo(true, text, $(this)[0]);
                $(this)[0].focus();
                hasShowErrMsg = true;
            }
        }
        else
        {
            ShowInfo(false);
            hasShowErrMsg = false;
        }
    });
    $("select[notNull]").blur(function () {
        if ($(this).val() == "")
        {
            if (!hasShowErrMsg)
            {
                var text = $(this).parent().prev()[0].innerText.replace(":", "").replace("：", "") + "不能为空！";
                ShowInfo(true, text, $(this)[0]);
                $(this)[0].focus();
                hasShowErrMsg = true;
            }
        }
        else
        {
            ShowInfo(false);
            hasShowErrMsg = false;
        }
    });
    $("input[valueType='date']").click(function () {
        WdatePicker();
    });
    $("input[valueType='num']").keyup(function () {
        clearNoNum($(this)[0]);
    });
}
//提交时一次性验证
function Validate(context) {
    var isvalidata = true;
    var target = context ? context : "";
    var allneedvalidate = $(target + " input[notNull]," + target + " input[mLength]," + target + " input[valueType]," + target + " select[notNull]," + target + " textarea[notNull]," + target + " textarea[mLength]");
    for (var i = 0; i < allneedvalidate.length; i++)
    {
        var text = $(allneedvalidate[i]).parent().prev()[0].innerText.replace(":", "").replace("：", "").replace("*", "");
        if ($(allneedvalidate[i]).attr("notNull"))
        {
            if (!$(allneedvalidate[i]).val())
            {
                text += "不能为空！";
                ShowMsg(text);
                isvalidata = false;
                break;
            }
        }
        if ($(allneedvalidate[i]).attr("mLength"))
        {
            if ($(allneedvalidate[i]).val().replace(/[^\x00-\xff]/g, "**").length > parseInt($(allneedvalidate[i]).attr("mLength"), 10))
            {
                text += "长度不能超过" + $(allneedvalidate[i]).attr("mLength") + "！";
                ShowMsg(text);
                isvalidata = false;
                break;
            }
        }
        if ($(allneedvalidate[i]).attr("valueType"))
        {
            if (!CheckValueType($(allneedvalidate[i]).attr("valueType"), $(allneedvalidate[i]).val()))
            {
                text = "请输入正确的" + text;
                ShowMsg(text);
                isvalidata = false;
                break;
            }
        }
    }
    return isvalidata;
}

function ShowDivAtBottom(sourceDivID, target) {
    if (target)
    {
        if ($("#" + sourceDivID).length > 0)
        {
            $("#" + sourceDivID).css("position", "absolute");
            $("#" + sourceDivID).css("font-size", "12px");
            $("#" + sourceDivID).css("overflow", "hidden");
            //            $("#" + sourceDivID).css("width", $(target).width() + "px");
            $("#" + sourceDivID).css("top", ($(target).offset().top + $(target).height() + 5).toString() + "px");
            $("#" + sourceDivID).css("left", $(target).offset().left + "px");
            $("#" + sourceDivID).show();
        }
    }
}

//用来显示输入错误提示框，在对象的右边
function ShowInfo(isshow, message, targetobj) {
    theinfodiv = document.getElementById("divinfomessage");
    if (isshow)
    {
        if (!theinfodiv)
        {
            theinfodiv = document.createElement("div");
            theinfodiv.id = "divinfomessage";
            theinfodiv.className = "input_tips";
            document.body.appendChild(theinfodiv);
        }
        theinfodiv.innerHTML = message;
        theinfodiv.style.top = ($(targetobj).offset().top + $(targetobj).height() + 10).toString() + "px";
        theinfodiv.style.left = $(targetobj).offset().left + "px";
        theinfodiv.style.visibility = 'visible';

    }
    else
    {
        if (theinfodiv)
        {
            theinfodiv.style.visibility = 'hidden';
        }
    }
}
function LoadingMask(targetObj, msg) {
    this.maskdiv;
    this.loadingdiv;
    this.msg = msg;
    this.targetObj = targetObj || document.body;
    if (document.getElementById("zybmask"))
    {
        this.maskdiv = document.getElementById("zybmask");
    }
    else
    {
        this.maskdiv = document.createElement("div");
        this.maskdiv.className = "mask";
        this.maskdiv.id = "zybmask";
        this.maskdiv.style.display = "none";
        //        document.body.appendChild(this.maskdiv);
        $(targetObj).append(this.maskdiv);
    }
    if (document.getElementById("zybloading"))
    {
        this.loadingdiv = document.getElementById("zybloading");
        var thechilddiv = this.loadingdiv.getElementsByTagName("div");
        if (thechilddiv) thechilddiv[0].innerHTML = this.msg;
    }
    else
    {
        this.loadingdiv = document.createElement("div");
        this.loadingdiv.className = "maskmsg";
        this.loadingdiv.id = "zybloading";
        this.loadingdiv.style.display = "none";
        var infodiv = document.createElement("div");
        infodiv.className = "loadingDiv";
        infodiv.innerHTML = this.msg;
        this.loadingdiv.appendChild(infodiv);
        //        document.body.appendChild(this.loadingdiv);
        $(targetObj).append(this.loadingdiv);
    }
    this.show = function () {
        $(this.maskdiv).show();
        $(this.loadingdiv).show();
        if (this.targetObj)
        {
            var thetop = $(this.targetObj).offset().top;
            var theleft = $(this.targetObj).offset().left;
            this.loadingdiv.style.top = (thetop + this.targetObj.offsetHeight / 2 - this.loadingdiv.offsetHeight / 2).toString() + "px";
            this.loadingdiv.style.left = (theleft + this.targetObj.offsetWidth / 2 - this.loadingdiv.offsetWidth / 2).toString() + "px";
            this.maskdiv.style.top = thetop.toString() + "px";
            this.maskdiv.style.left = theleft.toString() + "px";
            this.maskdiv.style.width = this.targetObj.offsetWidth.toString() + "px";
            this.maskdiv.style.height = this.targetObj.offsetHeight.toString() + "px";
        }
    }
    this.hide = function () {
        $(this.maskdiv).hide();
        $(this.loadingdiv).hide();
    }
}
function clearMask() {
    $("#zybmask").hide();
    $("#zybloading").hide();
}
// 清除非数据的字符
function clearNoNum(obj) {
    //先把非数字的都替换掉，除了数字和.
    obj.value = obj.value.replace(/[^\d.]/g, "");
    //必须保证第一个为数字而不是.
    obj.value = obj.value.replace(/^\./g, "");
    //保证只有出现一个.而没有多个.
    obj.value = obj.value.replace(/\.{2,}/g, ".");
    //保证.只出现一次，而不能出现两次以上
    obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
}
//验证数据格式
function CheckValueType(flag, value) {
    if (value == '') return true;
    var re;
    if (flag == "mobile")
    {
        re = /^1[3|4|5|8][0-9]\d{8}$/;
    }
    else if (flag == "email")
    {
        re = /\w@\w*\.\w/;
    }
    else if (flag == "zipcode")
    {
        re = /^[1-9][0-9]{5}$/;
    }
    else if (flag == "num")
    {
        re = /^(\-?)(\d+)$/;
    }
    else if (flag == "float")
    {
        re = /^\d+\.?\d*$/;
    }
    else if (flag == "stakeno")
    {
        re = /^\d+\.?\d{3}$|\d+\.?0$|^\d*$/;
    }
    else if (flag == "ip")
    {
        re = /((2[0-4]\d|25[0-5]|[01]?\d\d?)\.){3}(2[0-4]\d|25[0-5]|[01]?\d\d?)/;
    }
    else if (flag == "xy")
    {
        re = /^([1-2]\d{2}|3[0-5]\d|[1-9]\d|\d|360)(\.\d*|$)$/;
    }
    else if (flag == "date")
    {
        re = /^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))(\s(([01]\d{1})|(2[0123])):([0-5]\d):([0-5]\d))?$/;
    }
    return re.test(value);
}
function clearHTML(html) {
    return html ? html.replace(/<[^>]*>/g, "").replace(/&nbsp;/g, " ") : "";
}
var moveObj = '';
//设置dom元素可被移动
function SetDomCanMove(mousedownobj, moveobj) {
    $(mousedownobj).mousedown(function (e) {
        moveObj = $(moveobj).attr("id");
        document.all(moveObj).setCapture();
        pX = e.pageX - document.all(moveObj).style.pixelLeft;
        pY = e.pageY - document.all(moveObj).style.pixelTop;
    });
    $(document).mousemove(function (e) {
        if (moveObj != '')
        {
            if ((e.pageX - pX) >= 0 && (e.pageX - pX) <= (document.documentElement.clientWidth - $(moveobj).width()))
            {
                document.all(moveObj).style.left = (e.pageX - pX).toString() + "px";
            }
            else
            {
                if ((e.pageX - pX) < 0) document.all(moveObj).style.left = 0;
                else
                    document.all(moveObj).style.left = (document.documentElement.clientWidth - $(moveobj).width()) + "px";
            }
            if ((e.pageY - pY) >= 0 && (e.pageY - pY) <= (document.documentElement.clientHeight - $(moveobj).height()))
            {
                document.all(moveObj).style.top = (e.pageY - pY).toString() + "px";
            }
            else
            {
                if ((e.pageY - pY) < 0) document.all(moveObj).style.top = 0;
                else
                    document.all(moveObj).style.top = (document.documentElement.clientHeight - $(moveobj).height()) + "px";
            }
        }
    });
    $(document).mouseup(function () {
        if (moveObj != '')
        {
            document.all(moveObj).releaseCapture();
            moveObj = '';
        }
    });
}

//弹出div
function myWindow(divid, title, buttons) {
    this.show = function () {
        var maskdiv;
        if ($("#" + divid).length > 0)
        {
            if ($("#" + divid).attr("haveShow"))
            {
                maskdiv = document.getElementById("zybmask");
            }
            else
            {
                $("#" + divid).attr("haveShow", "true");
                $("#" + divid).wrap("<div class=\"edit_table\" style=\"width:" + $("#" + divid).width() + "px;\"></div>");
                $("#" + divid).parent().wrap("<div id='parent_" + divid + "' style='position:absolute;z-index: 10;background:#fff;width:" + ($("#" + divid).parent().width() + 3) + "px; border-bottom:#333 6px solid;border-right:#333 6px solid;'></div>");
                $("#" + divid).before("<div style='position:relative;cursor:move;' class='edit_title'><div style='position:absolute;top:10px;left:10px;' class='windowtitlecontent'>" + title + "</div><a style='position:absolute;right:5px;' href='javascript:void(0)' onclick='$(this).parent().parent().parent().hide();if(document.getElementById(\"zybmask\"))document.getElementById(\"zybmask\").style.display = \"none\";ShowInfo(false);'><img src=\"/images/divclose.gif\" alt=\"关闭\" name=\"divclose\" border=\"0\" id=\"divclose\" onmouseover=\"ChangeImage(this,'/images/divclose.gif','/images/divclose_f2.gif',true);\" onmouseout=\"ChangeImage(this,'/images/divclose.gif','/images/divclose_f2.gif');\" /></a></div>");
                var btnhtml = "";
                if (buttons)
                {
                    for (var btn in buttons)
                    {
                        btnhtml += "<input type='button' value='" + btn + "' class='btn_out' />";
                    }
                }
                if (btnhtml != "")
                {
                    $("#" + divid).after("<div>" + btnhtml + "</div>");
                }
                $("#" + divid).next().find("input[type='button']").each(function (index) {
                    $(this).click(function () {
                        if (buttons)
                        {
                            for (var btn in buttons)
                            {
                                if (btn == $(this).val())
                                {
                                    var clickfunc = buttons[btn];
                                    clickfunc();
                                }
                            }
                        }
                    });
                });
                if (document.getElementById("zybmask"))
                {
                    maskdiv = document.getElementById("zybmask");
                }
                else
                {
                    maskdiv = document.createElement("div");
                    maskdiv.className = "mask";
                    maskdiv.id = "zybmask";
                    document.body.appendChild(maskdiv);
                }
                $("#" + divid).show();
                $("#" + divid).parent().parent().css("top", ((document.documentElement.clientHeight - $("#" + divid).parent().parent().height()) / 2).toString() + "px");
                $("#" + divid).parent().parent().css("left", ((document.documentElement.clientWidth - $("#" + divid).parent().parent().width()) / 2).toString() + "px");
                SetDomCanMove($("#" + divid).prev(), $("#" + divid).parent().parent());
            }
            maskdiv.style.top = "0";
            maskdiv.style.left = "0";
            maskdiv.style.width = document.documentElement.scrollWidth + "px";
            maskdiv.style.height = document.documentElement.scrollHeight + "px";
            maskdiv.style.display = "block";
            $("#" + divid).parent().parent().show();
        }
    }
    this.hide = function () {
        if (document.getElementById("zybmask"))
        {
            document.getElementById("zybmask").style.display = "none";
        }
        ShowInfo(false);
        $("#" + divid).parent().parent().hide();
    }
    this.setTitle = function (thetitle) {
        $("#" + divid).prev().find(".windowtitlecontent").text(thetitle);
    }
}
function ChangeImage(imgobj, outimg, overimg, isOver) {
    if (imgobj)
    {
        if (isOver) imgobj.src = overimg;
        else imgobj.src = outimg;
    }
}
//创建Frame弹出div，包含一个页面,divid可以没有，但必须唯一
function FrameWindow(divid, url, title, width, height, buttons) {
    var thediv = document.getElementById(divid);
    if (thediv)
    {

    }
    else
    {
        thediv = document.createElement("div");
        thediv.id = divid;
        document.body.appendChild(thediv);
    }
    if (thediv.attributes["haveShow"])
    {

    }
    else
    {
        $("#" + divid).width(width);
        $("#" + divid).height(height);
        var frame = document.createElement("iframe");
        frame.id = "frame_" + divid;
        frame.width = "100%";
        frame.height = height;
        frame.frameborder = "0";
        frame.scrolling = "no";
        frame.src = url;
        thediv.appendChild(frame);
    }
    this.theWindow = new myWindow(divid, title, buttons);
    this.show = function () { this.theWindow.show(); }
    this.hide = function () { this.theWindow.hide(); }
}
//重新加载页面
function Reload() {
    $("#btnReload").click();
}

//从页面控件中构造实体对象，根据Id与字段的规则
function CreateObjectFromDom(context) {
    var result = {};
    var target = context ? context : "";
    $(target + " input[id^=BodyContent_con]," + target + " input[id^=con]," + target + " select[id^=BodyContent_con]," + target + " select[id^=con]," + target + " textarea[id^=BodyContent_con]," + target + " textarea[id^=con]").each(function () {
        var at = "";
        if ($(this).attr("id").indexOf("BodyContent_con") == 0) at = $(this).attr("id").replace("BodyContent_con", "");
        else at = $(this).attr("id").replace("con", "");
        eval("result." + at + "='" + StringConvert($(this).val()) + "';");
    });
    return result;
}
function StringConvert(value) {
    return value ? value.replace(/\n/gi, "\\n").replace(/\r/gi, "\\r") : "";
}
//把实体对象值绑定到页面控件中，根据Id与字段的规则
function SetDomValueFromData(data, context) {
    var target = context ? context : "";
    for (var d in data)
    {
        var allcanedit = $(target + " #con" + d + "," + target + " #BodyContent_con" + d);
        if (allcanedit.length > 0)
        {
            SetDomValue(allcanedit[0], data[d]);
        }
    }
}
function SetDomValue(dom, value) {
    if (dom.tagName.toLocaleLowerCase() == "input")
    {
        if (dom.type.toLocaleLowerCase() != "checkbox" && dom.type.toLocaleLowerCase() != "radio")
        {
            if (CheckObjIsDate(value))
            {
                $(dom).val(DateTimeToString(value, "yyyy-MM-dd"));
            }
            else
            {
                $(dom).val(value);
            }
        }
        else
        {
            if (value == "1" || value == true || value.toLocaleLowerCase() == "true")
            {
                $(dom).attr("checked", "checked");
            }
            else
            {
                $(dom).removeAttr("checked");
            }
        }
    }
    else if (dom.tagName.toLocaleLowerCase() == "select")
    {
        $(dom).val(value);
    }
    else if (dom.tagName.toLocaleLowerCase() == "textarea")
    {
        if (CheckObjIsDate(value))
        {
            $(dom).val(DateTimeToString(value, "yyyy-MM-dd"));
        }
        else
        {
            $(dom).val(value);
        }
    }
    else
    {
        if (CheckObjIsDate(value))
        {
            $(dom).html(DateTimeToString(value, "yyyy-MM-dd"));
        }
        else
        {
            $(dom).html(value);
        }
    }
}
function clearDomValue(context) {
    $(context + " input[type!=button][type!=submit]," + context + " textarea").val("");
    $(context + " select").each(function () {
        $(this)[0].selectedIndex = 0;
    });
}
function checkall(checkallobj, targetname) {
    $("input[name=" + targetname + "]").each(function () {
        if ($(checkallobj).attr("checked"))
        {
            $(this).attr("checked", true);
        }
        else
        {
            $(this).attr("checked", false);
        }
    });
}

function getCheckedValues(name, context, type) {
    var target = context ? context : "";
    var result = "";
    $(target + " input[name='" + name + "']:checked").each(function () {
        result += $(this).val() + ",";
    });
    if (type)
        result = result.substring(0, result.length - 1);
    return result;
}

/*
页面CheckBox全选或不全选
调用方法：Repeater 中 
头部：<input type="checkbox" id="chkall" onclick="CheckBoxSelectAll(this.checked)" />
中间：<input type="checkbox" name="rpcheckbox" value='<%#Eval("OID") %>' /> id必须唯一统一为：rpcheckbox
*/
function CheckBoxSelectAll(boolvalue) {
    $("input[name=rpcheckbox]").each(function () {
        $(this).attr("checked", boolvalue);
    });
}
function TreeNode(value, text, ischecked, jsnode) {
    this.value = value;
    this.text = text;
    this.jsnode = jsnode;
    this.attr = function (name, thevalue) {
        if (thevalue)
            this.jsnode.attr(name, thevalue);
        else
            return this.jsnode.attr(name);
    }
    this.ischecked = ischecked || false;

}
//divid:包含树html的div id
//selectedFunc：选择树节点的触发函数
function myJstree(divid, showcheckbox, selectedFunc, target, openID, selectedIDs, LoadOpenLevel) {
    var obj = this;
    this.currentNode;
    this.IsShowCheckBox = showcheckbox;
    this.show = function () {
        var initopen = [];
        if (openID)
        {
            initopen[0] = "tree_" + openID.replace(/-/g, "");
        }
        else
        {
            var openlevel = LoadOpenLevel ? LoadOpenLevel : 1;
            initopen = GetOpenNodeArrayByLevel(divid, openlevel);
        }
        if (this.IsShowCheckBox)
        {
            $("#" + divid).jstree({
                "plugins": ["themes", "html_data", "checkbox", "ui", "crrm"],
                "themes": { "theme": "classic" },
                "core": { animation: 0, initially_open: initopen },
                "ui": { initially_select: ["tree_" + (selectedIDs ? selectedIDs.replace(/-/g, "") : "")] },
                "checkbox": { real_checkboxes: true }
            }).bind("select_node.jstree", function (event, data) {
                //            obj.currentNode = $("#" + divid).jstree("get_selected");
                $("#" + divid).jstree("check_node", data.rslt.obj);
                obj.currentNode = new TreeNode(data.rslt.obj[0].attributes["nodevalue"].value, data.rslt.obj.children("a")[0].innerText, $("#" + divid).jstree("is_checked", data.rslt.obj), data.rslt.obj);
                if (selectedFunc)
                {
                    selectedFunc(obj.currentNode);
                }
            });
        }
        else
        {
            $("#" + divid).jstree({
                "plugins": ["themes", "html_data", "ui", "crrm"],
                "themes": { "theme": "classic" },
                "core": { animation: 0, initially_open: initopen },
                "ui": { initially_select: ["tree_" + (selectedIDs ? selectedIDs.replace(/-/g, "") : "")] }
            }).bind("select_node.jstree", function (event, data) {
                //            obj.currentNode = $("#" + divid).jstree("get_selected");
                obj.currentNode = new TreeNode(data.rslt.obj[0].attributes["nodevalue"].value, data.rslt.obj.children("a")[0].innerText, false, data.rslt.obj);
                if (selectedFunc)
                {
                    selectedFunc(obj.currentNode);
                }
            });
        }
    }
    this.deleteNode = function () {
        if (this.currentNode == null)
        {
            ShowMsg("请先选择您要删除的节点！");
            return;
        }
        $("#" + divid).jstree("delete_node");
    }
    this.createNode = function (value, text, attrs) {
        if ($("#" + divid + " > ul > li").length == 0)
        {
            this.createRoot(value, text, attrs);
        }
        else
        {
            if (attrs) attrs.nodevalue = value;
            else attrs = { "nodevalue": value };
            $("#" + divid).jstree("create", null, "last", { "attr": attrs, "data": text }, null, true);
        }
    }
    this.createRoot = function (value, text, attrs) {
        if (attrs)
        {
            attrs.nodevalue = value;
        }
        else attrs = { "nodevalue": value };
        $("#" + divid).jstree("create", -1, "last", { "attr": attrs, "data": text }, null, true);
    }
    this.updateNode = function (value, text, attrs) {
        this.currentNode.attr("nodevalue", value);
        for (var r in attrs)
        {
            this.currentNode.attr(r, attrs[r]);
        }
        $("#" + divid).jstree("rename_node", this.currentNode.jsnode, text);
    }
    this.selectNode = function (value) {
        $("#" + divid).jstree("select_node", "#tree_" + value.replace(/-/g, ""));
    }
    var prevnode;
    this.startMove = function () {
        prevnode = this.currentNode;
        prevnode.jsnode.children("a").css("border", "1px dashed red");
        if (!prevnode)
        {
            ShowMsg("请先选择一个节点!");
        }
        return prevnode;
    }
    this.moveTo = function () {
        if (this.currentNode && prevnode && this.currentNode.value != prevnode.value)
        {
            prevnode.jsnode.children("a").css("border", "");
            $("#" + divid).jstree("move_node", prevnode.jsnode, this.currentNode.jsnode, "last");
            prevnode = null;
            return true;
        }
        else
        {
            ShowMsg("请选择一个目标节点!");
            return false;
        }
    }
    this.cancelMove = function () {
        prevnode.jsnode.children("a").css("border", "");
        prevnode = null;
    }
}
function GetOpenNodeArrayByLevel(divid, level) {
    if (level == 0) return [];
    var result = [];
    var openlevel = level || "";
    if (typeof (openlevel) == "string" && openlevel.toLocaleLowerCase() == "full")
    {
        result = $("#" + divid + " li").filter(function () {
            return $('ul', this).length > 0;
        }).map(function () {
            return $(this).attr("id");
        }).get();
        return result;
    }
    else
    {
        var intlevel = level || 0;
        if (intlevel != 0)
        {
            var allparents = $("#" + divid + " > ul > li").filter(function () {
                return $('ul', this).length > 0;
            });
            result = allparents.map(function () {
                return $(this).attr("id");
            }).get();
            intlevel--;
            if (intlevel > 0)
            {
                for (var i = 0; i < allparents.length; i++)
                {
                    result = result.concat(GetOpenNodeArrayByLevel($(allparents[i]).attr("id"), intlevel));
                }
            }
        }
    }
    return result;
}

function mySyncTree(divid, className, methodName, selectedFunc, parmarsFunc, openID, selectedID, parmarsMethod) {
    $("#" + divid).jstree("clean_node", -1);
    var obj = this;
    this.currentNode;
    this.show = function () {
        $("#" + divid).jstree({
            "plugins": ["themes", "json_data", "ui", "crrm"],
            "json_data": {
                "ajax": {
                    "url": "/JsonHandler.ashx?method=" + methodName + "&classname=" + className + "&" + (parmarsMethod||""),
                    "data": function (n) {
                        return parmarsFunc(n);
                    },
                    "cache": false
                }
            },
            "themes": { "theme": "classic" },
            "core": { initially_open: openID },
            "ui": { initially_select: [selectedID] }
        }).bind("select_node.jstree", function (event, data) {
            obj.currentNode = data.rslt.obj;
            if (selectedFunc)
            {
                selectedFunc(obj.currentNode);
            }
        });
    }
}

//从新加载列表
function ReloadTb(sourceTbID, datasource, notclearrows, titlerows) {
    var tb = document.getElementById(sourceTbID);
    if (tb)
    {
        if ($("#" + sourceTbID + " tr").length == 2 && $("#" + sourceTbID + " tr:eq(1) td").eq(0).attr("colspan") == "99")
        {
            ClearTbRows(sourceTbID);
        }
        if (!notclearrows) ClearTbRows(sourceTbID, titlerows);
        var data = datasource || [];
        for (var i = 0; i < data.length; i++)
        {
            var itemclass;
            //            if ($(tb).attr("ItemClass")) itemclass = $(tb).attr("ItemClass");
            if (i % 2 == 0)
            {
                itemclass = "form_list_row1";
            }
            if (i % 2 > 0)
            {
                itemclass = "form_list_row2";
            }
            AddNewRow(data[i], tb, itemclass);
        }
    }
}
//清空列表，只留下表头
function ClearTbRows(sourceTbID, titlerows) {
    var thetitlerowslength = titlerows || 1;
    thetitlerowslength--;
    var tb = document.getElementById(sourceTbID);
    if (tb)
    {
        for (var i = tb.rows.length - 1; i > thetitlerowslength; i--)
        {
            $(tb.rows[i]).remove();
        }
    }
}
//添加新行
function AddNewRow(obj, tb, rowclass) {
    if (tb.rows.length > 0)
    {
        var newrow = tb.insertRow();
        $(newrow).mouseover(function () {
            oldclor = this.style.backgroundColor;
            if (this.style.backgroundColor == "")
            {
                this.style.backgroundColor = '#FFFBCC';
            }
        });
        $(newrow).mouseout(function () {
            if (this.style.backgroundColor == "#d4ff8b" || this.style.backgroundColor == "rgb(212, 255, 139)") return;
            this.style.backgroundColor = oldclor;
        });
        if ($(tb).attr("onrowclick"))
        {
            $(newrow).click(function () {
                for (var i = 0; i < $(tb).find("tr:gt(0)").length; i++)
                {
                    if ($(tb).find("tr:gt(0)").eq(i)[0].style.backgroundColor == "#d4ff8b" || $(tb).find("tr:gt(0)").eq(i)[0].style.backgroundColor == "rgb(212, 255, 139)")
                    {
                        $(tb).find("tr:gt(0)").eq(i).css("background-color", "");
                    }
                }
                $(newrow).css("background-color", "#d4ff8b");
                //原来的
                eval($(tb).attr("onrowclick"));
                // $(tb).trigger("onrowclick");
            });
        }
        if (rowclass) newrow.className = rowclass;
        for (var i = 0; i < tb.rows[0].cells.length; i++)
        {
            try
            {
                if (IsView && $(tb.rows[0].cells[i]).attr("hiddenwhenview"))
                {
                    continue;
                }
            }
            catch (e) { }
            var cell = newrow.insertCell();
            if ($(tb.rows[0].cells[i]).attr("itemclass"))
                cell.className = $(tb.rows[0].cells[i]).attr("itemclass");
            var value = obj[$(tb.rows[0].cells[i]).attr("itemvalue")] ? obj[$(tb.rows[0].cells[i]).attr("itemvalue")] : "";
            var type = "";
            if ($(tb.rows[0].cells[i]).attr("showtype"))
            {
                try
                {
                    eval("type=" + ReplaceDataItem(obj, $(tb.rows[0].cells[i]).attr("showtype").replace(new RegExp("{itemvalue}", "gm"), value)));
                }
                catch (e)
                {
                    type = $(tb.rows[0].cells[i]).attr("showtype");
                }
            }
            var addattr = $(tb.rows[0].cells[i]).attr("attr") ? $(tb.rows[0].cells[i]).attr("attr") : "";
            var thetext = $(tb.rows[0].cells[i]).attr("itemtext") ? ReplaceDataItem(obj, $(tb.rows[0].cells[i]).attr("itemtext").replace(new RegExp("{itemvalue}", "gm"), value)) : "";
            var valuelist = $(tb.rows[0].cells[i]).attr("valuelist") ? ReplaceDataItem(obj, $(tb.rows[0].cells[i]).attr("valuelist").replace(new RegExp("{itemvalue}", "gm"), value)) : "";
            var showFormat = $(tb.rows[0].cells[i]).attr("showformat") ? $(tb.rows[0].cells[i]).attr("showformat") : "";
            if (type == "" && addattr != "")
            {

            }
            var maxthlength = parseInt($(tb.rows[0].cells[i]).attr("maxlength"), 10) || 100;
            $(cell).html(ShowItem(type, value, ReplaceDataItem(obj, addattr.replace(new RegExp("{itemvalue}", "gm"), value)), thetext, valuelist, maxthlength, showFormat));
        }
    }
}
//显示每个html选项
function ShowItem(type, value, attrArray, text, valuelist, maxlength, showFormat) {
    var valuetype = typeof (value);
    if (CheckObjIsDate(value))
    {
        showFormat = (showFormat == "") ? "yyyy-MM-dd" : showFormat;
        value = DateTimeToString(value, showFormat);
    }
    var attrtohtml = attrArray || "";
    var typelist = type.split('|');
    var attrlist = attrtohtml.split('|');
    var textlist = text.split('|');
    var result = "";
    for (var i = 0; i < typelist.length; i++)
    {
        var theattr = attrlist.length > i ? attrlist[i] : "";
        var thetext = textlist.length > i ? textlist[i] : "";
        if (typelist[i] == "select")
        {
            result += "<select " + theattr + ">" + itemHTML(typelist[i], value, theattr, textlist, valuelist) + "</select>";
            continue;
        }
        result += itemHTML(typelist[i], value, theattr, thetext, valuelist, maxlength);
        if (typelist[i] == "a" && i != typelist.length - 1)
        {
            result += "&nbsp;|&nbsp;";
        }
    }
    return result;
}

function itemHTML(type, value, attrtohtml, text, valuelist, maxlength) {
    value = value ? value : "";
    switch (type)
    {
        case 'a':
            return "<a " + attrtohtml + ">" + text + "</a>";
        case 'checkbox':
            return "<input type='checkbox' value='" + value + "' " + attrtohtml + " />";
        case 'radio':
            return "<input type='radio' value='" + value + "' " + attrtohtml + " />";
        case 'text':
            return "<input type='text' value='" + value + "' " + attrtohtml + " />";
        case 'img':
            return "<img value='" + value + "' " + attrtohtml + " alt='" + text + "' />";
        case 'select':
            {
                var result = "";
                var values = valuelist.split('|');
                var selected = "";
                for (var i = 0; i < values.length; i++)
                {
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

function ReplaceDataItem(datasource, str) {
    if (str)
    {
        var data = datasource || {};
        for (var p in data)
        {
            str = str.replace(new RegExp("{" + p + "}", "gm"), data[p]);
        }
    }
    return str;
}

function TbRowsToParmars(sourceTbID, beforeName, replaceStr, notchecked, appendobject) {
    var subparmars = appendobject || {};
    var replacelist = replaceStr.split(';');
    var allfirstrowtd = $("#" + sourceTbID + " tr").eq(0).find("td");
    for (var i = 0; i < allfirstrowtd.length; i++)
    {
        if ($(allfirstrowtd[i]).attr("itemvalue"))
        {
            for (var j = 0; j < $("#" + sourceTbID + " tr:gt(0)").length; j++)
            {
                if (notchecked)
                {

                }
                else if ($("#" + sourceTbID + " tr:gt(0)").eq(j).find("td").eq(0).find(":checked").length <= 0)
                {
                    continue;
                }
                var value = "";
                if ($(allfirstrowtd[i]).attr("showtype"))
                {
                    if ($(allfirstrowtd[i]).attr("showtype") == "checkbox") value = $("#" + sourceTbID + " tr:gt(0)").eq(j).find("td").eq(i).find("input[type=checkbox]").val();
                    if ($(allfirstrowtd[i]).attr("showtype") == "text") value = $("#" + sourceTbID + " tr:gt(0)").eq(j).find("td").eq(i).find("input").val();
                }
                else
                {
                    value = $("#" + sourceTbID + " tr:gt(0)").eq(j).find("td").eq(i).html();
                }
                eval("subparmars." + beforeName + "_" + getReplaceName(replacelist, $(allfirstrowtd[i]).attr("itemvalue")) + "_" + j + "='" + value + "'");
            }
        }
    }
    return subparmars;
}

//选项卡对象
function Tag(targetdiv, tagClass) {
    var ulclass = tagClass ? tagClass : "tagclass";
    this.mask = new LoadingMask(document.getElementById(targetdiv), "请稍后...");
    this.show = function (index) {
        //        $(document).ready(function () {
        var showIndex = index || 0;
        GenerateTag(targetdiv, showIndex, tagClass);
        //        });
    }
    this.hideMask = function () {
        this.mask.hide();
    }
    this.showMask = function () {
        this.mask.show();
    }
    this.setActive = function (index) {
        if (index != undefined && index != null)
        {
            if ($("#" + targetdiv + " > table." + ulclass + " li").length > index)
            {
                $("#" + targetdiv + " > table." + ulclass + " li").eq(index).find("a")[0].click();
            }
        }
    }
    this.setCanActive = function (index) {
        if (index)
        {
            if ($("#" + targetdiv + " > table." + ulclass + " li").length > index)
            {
                $("#" + targetdiv + " > div[title]").eq(index).removeAttr("notActive");
                $("#" + targetdiv + " > table." + ulclass + " li").eq(index).removeClass("off");
            }
        }
        else
        {
            $("#" + targetdiv + " > div[title]").removeAttr("notActive");
            $("#" + targetdiv + " > table." + ulclass + " li").removeClass("off");
        }
    }
    this.setClickReLoad = function (index) {
        if (index)
        {
            if ($("#" + targetdiv + " > table." + ulclass + " li").length > index)
            {
                $("#" + targetdiv + " > table." + ulclass + " li").eq(index).removeAttr("haveload");
            }
        }
        else
        {
            $("#" + targetdiv + " > table." + ulclass + " li").removeAttr("haveload");
        }
        var currentindex = $("#" + targetdiv + " > table." + ulclass + " li.selected").eq(0).index();
        if (index)
        {
            if (currentindex == index)
            {
                $("#" + targetdiv + " > table." + ulclass + " li").eq(index).find("a")[0].click();
            }
        }
        else
        {
            $("#" + targetdiv + " > table." + ulclass + " li").eq(currentindex).find("a")[0].click();
        }
    }

    this.setDisplay = function (index) {
        if (index)
        {
            if ($("#" + targetdiv + " > table." + ulclass + " li").length > index)
            {
                $("#" + targetdiv + " > table." + ulclass + " li").eq(index).show();
            }
        }
    }
    this.setUnDisplay = function (index) {
        if (index)
        {
            if ($("#" + targetdiv + " > table." + ulclass + " li").length > index)
            {
                $("#" + targetdiv + " > table." + ulclass + " li").eq(index).hide();
            }
        }
    }

    this.gettagHeight = function () {
        return $("#" + targetdiv + " > table." + ulclass).height();
    }
    this.getActiveIndex = function () {
        return $("#" + targetdiv + " > table." + ulclass + " li.selected").index();
    }
    this.getActiveContainer = function () {
        return $("#" + targetdiv + " > div:eq(" + this.getActiveIndex() + ")")[0];
    }
}
//产生一个选项卡列表
function GenerateTag(targetdiv, showIndex, tagClass) {
    var ulclass = tagClass ? tagClass : "tagclass";
    if ($("#" + targetdiv).length > 0)
    {
        var firstshow = showIndex || 0;
        var allchlddiv = $("#" + targetdiv).children("div[title]");
        var taghtml = "<table class=\"" + ulclass + "\"  cellspacing=\"0\" cellpadding=\"0\">";
        taghtml += "<tr><td class=\"tableftline\"></td>";
        taghtml += "<td>";
        taghtml += "<ul>";
        for (var i = 0; i < allchlddiv.length; i++)
        {
            taghtml += "<li";
            if (i == showIndex)
            {
                taghtml += " class=\"selected\"";
            }
            else if ($(allchlddiv[i]).attr("notActive"))
            {
                taghtml += " class=\"off\"";
            }
            taghtml += "><b></b>";
            taghtml += "<a href=\"javascript:void(0)\" onclick=\"LoadTag($(this).parent());\">";
            taghtml += $(allchlddiv[i]).attr("title");
            taghtml += "</a><i></i></li>";
        }
        taghtml += "</ul>";
        taghtml += "</td>";
        taghtml += "<td class=\"rightline\"></td>";
        taghtml += "</tr></table>";
        $("#" + targetdiv).prepend(taghtml);
        LoadTag($("#" + targetdiv).children("." + ulclass).find("ul").eq(0).children("li").eq(showIndex));
    }
}
//加载并显示选项卡
function LoadTag(tagli) {
    var iframeheight = $(tagli).parents("div").height() - $(tagli).parents("table").height();
    var tagdiv = $(tagli).parents("div").children("div[title]").eq($(tagli).index());
    if ($(tagdiv).attr("notActive"))
    {
        if ($(tagdiv).attr("notActiveMsg")) ShowMsg($(tagdiv).attr("notActiveMsg"));
        return;
    }
    $(tagli).parent().children("li").removeClass("selected");
    $(tagli).addClass("selected");
    $(tagdiv).parent().children("div[title]").hide();

    if ($(tagli).attr("haveload"))
    {

    }
    else
    {
        if ($(tagdiv).attr("loadFunc"))
        {
            eval($(tagdiv).attr("loadFunc"));
        }
        if ($(tagdiv).attr("url"))
        {
            new LoadingMask($(tagdiv).parent()[0], "请稍后...").show();
            if ($(tagdiv).find(" > iframe").length == 0)
            {
                $(tagdiv).append('<iframe width="100%" frameborder="0" style="margin:0;padding:0;" scrolling="no" height="' + iframeheight + 'px"></iframe>');
            }
            $(tagdiv).find(" > iframe").eq(0).attr("src", $(tagdiv).attr("url"));
        }
        $(tagli).attr("haveload", "true");
    }
    $(tagdiv).fadeIn();
}
function RegisteBtnFunc() {
    $("input.btn_out").mouseover(function () {
        $(this).removeClass().addClass("btn_over");
    });
    $("input.btn_out").mouseout(function () {
        $(this).removeClass().addClass("btn_out");
    });
    $("input.btn_out").mousedown(function () {
        $(this).removeClass().addClass("btn_down");
    });
    $("input.btn_out").mouseup(function () {
        $(this).removeClass().addClass("btn_over");
    });
}
function AutoSetOnlyHeight(sourcedivID, frametop) {
    var emptyheight = frametop;
    if (emptyheight == "" || emptyheight == undefined)
    {
        emptyheight = document.documentElement.clientHeight - $('#' + sourcedivID).offset().top;
        if (frametop) emptyheight -= frametop;
    }
    $('#' + sourcedivID).height(emptyheight + "px");
    //    if (!haveresize) {
    //        $(window).resize(function () {
    //            AutoSetOnlyHeight(sourcedivID);
    //        });
    //        haveresize = true;
    //    }
}
function AutoSetTreeHeight(sourcedivID, height) {
    $('#' + sourcedivID).height(height + "px");
}
//获取当前用户点击的一级菜单
function GetCurrentTopMenuID() {
    if (parent.parent.topFrame && parent.parent.topFrame.currentmenuID)
    {
        return parent.parent.topFrame.currentmenuID;
    }
    return "";
}
//获取当前用户点击的二级左边菜单
function GetCurrentSecendMenuID() {
    if (parent.parent.leftFrame && parent.parent.leftFrame.currentmenuID)
    {
        return parent.parent.leftFrame.currentmenuID;
    }
    return "";
}
/*******限制输入框只数据数字 开始*******/
/*用法：    <input type="text" name="txtBeginStakeNO" id="txtBeginStakeNO" class="kbox" runat="server"  onkeypress="onlyNumber();" runat="server" onpaste="return   false "
Style="ime-mode: disabled"/>*/
//function onlyNumber() {
//    if ((event.keyCode < 48 || event.keyCode > 57))
//        event.returnValue = false;
//}

//function numberAndPoint() {
//    if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 46)
//        event.returnValue = false;
//}
/*******限制输入框只数据数字 结束*******/


//注册调整列宽事件
function RegisteResizeColumnFunc() {
    $(".form_list .title").mousedown(function (event) {
        var th = $(this);
        if (th.nextAll().length < 1)
        {
            return;
        }
        var left = th.offset().left;
        if ((th.width() - (event.clientX - left)) < 4)
        {
            th.attr("isMouseDown", true);
            th.attr("oldWidth", th.width());
        }
    });
    //    $(".form_list .title").mouseup(function (event) {
    //        var th = $(this);
    //        event.preventDefault();
    //    });
    $(document).mouseup(function () {
        $(".form_list .title[isMouseDown]").removeAttr("isMouseDown").attr("dontCallClick", true);
    });
    $(".form_list .title").mousemove(function (event) {
        var th = $(this);
        if (th.nextAll().length < 1)
        {
            return;
        }
        var left = th.offset().left;
        if ((th.width() - (event.clientX - left)) < 4)
        {
            th.css("cursor", "e-resize");
        }
        else
        {
            th.css("cursor", "default");
        }
        if (th.attr("isMouseDown"))
        {
            var oldwidth = parseInt(th.attr("oldWidth"), 10) || 0;
            var newsize = event.clientX - left;
            if (newsize > 0)
            {
                th.width(newsize);
            }
        }
    });
}

function MakeSpanCanBeEdit(context, filter) {
    IsNowEditingEvent = true;
    var target = context ? context : "";
    var domlist = $(target + " span");
    if (filter) domlist = domlist.filter(filter);
    domlist.each(function () {
        var type = $(this).attr("showtype") ? $(this).attr("showtype") : "text";
        var valuetype = $(this).attr("valuetype") ? $(this).attr("valuetype") : "";
        var attr = "";
        var areanotnull = "";
        if (valuetype == "date")
        {
            attr = "onclick=\"WdatePicker();\" valuetype=\"date\"";
        }
        else if (valuetype == "num")
        {
            attr = "onkeyup=\"clearNoNum(this);\" valuetype=\"num\"";
        }
        if ($(this).attr("notNull"))
        {
            attr += "notNull=\"true\"";
            areanotnull = " notNull=\"true\"";
        }
        if (type == "textarea")
        {
            $(this).replaceWith("<textarea rows='5' cols='45' style='width:99%;' id='" + $(this).attr("id") + "'" + areanotnull + ">" + $(this).html() + "</textarea>");
        }
        else if (type != "hidden")
        {
            $(this).replaceWith("<input type='" + type + "' value='" + $(this).html() + "' id='" + $(this).attr("id") + "'" + attr + " />");
        }
    });
}
function MakeInputCanNotBeEdit(context, filter) {
    IsNowEditingEvent = false;
    var target = context ? context : "";
    var domlist = $(target + " input[type!=hidden][type!=button]," + target + " select," + target + " textarea");
    if (filter) domlist = domlist.filter(filter);
    domlist.each(function () {
        var type = $(this).attr("type") ? $(this).attr("type") : "textarea";
        var valuetype = $(this).attr("valuetype") ? $(this).attr("valuetype") : "";
        var attr = " showtype=\"" + type + "\"";
        if (valuetype == "date")
        {
            attr += " valuetype=\"date\"";
        }
        else if (valuetype == "num")
        {
            attr += " valuetype=\"num\"";
        }
        if ($(this).attr("notNull"))
        {
            attr += " notNull=\"true\"";
        }
        $(this).replaceWith("<span id='" + $(this).attr("id") + "'" + attr + ">" + ($(this).val() ? $(this).val() : "&nbsp;") + "</span>");
    });
}

//通用页面加载完毕事件，只有绝大多数的页面都有的方法才写在这里
$(document).ready(function () {
    try
    {
        if (IsView) $("[hiddenwhenview]").hide();
    }
    catch (e)
    { }
    $(".form_list tr:odd").addClass("form_list_row1");
    $(".form_list tr:even:gt(0)").addClass("form_list_row2");
    $(".form_list[showemptyrow!=true]").each(function () {
        if ($(this).find("tr").length == 1)
        {
            $(this).find("tr").after("<tr><td colspan='99'>没有记录!</td></tr>");
        }
    });
    RegisteBtnFunc();

    AutoSetValue();
    AutoOrderBy();
    AutoAjaxOrderBy();
    SetValidate();
    RegisteResizeColumnFunc();
});


//*******************************************************************************************
//*********************************    新增JS   *****************************************
//*******************************************************************************************
//从新加载列表
function ReloadinnerTb(sourceTbID, datasource, notclearrows) {
    var tb = document.getElementById(sourceTbID);
    if (tb)
    {
        if ($("#" + sourceTbID + " tr").length == 2 && $("#" + sourceTbID + " tr:eq(1) td").eq(0).attr("colspan") == "99")
        {
            ClearTbRows(sourceTbID);
        }
        if (!notclearrows) ClearTbRows(sourceTbID);
        var data = datasource || [];
        for (var i = 0; i < data.length; i++)
        {
            var itemclass;
            //            if ($(tb).attr("ItemClass")) itemclass = $(tb).attr("ItemClass");
            if (i % 2 == 0)
            {
                itemclass = "form_list_row1";
            }
            if (i % 2 > 0)
            {
                itemclass = "form_list_row2";
            }
            AddNewDRow(data[i], tb, itemclass);
        }
    }
}

//设置指定对象obj下所有控件为只读。
function SetPageReadonly(obj) {

    $(obj + " input," + obj + " textarea").each(function () {
        $(this).attr('readonly', 'readonly');
        $(this).attr('disabled', 'disabled');
    });
    $(obj + " select").each(function () {
        $(this).attr('disabled', 'disabled');
    });
}

//移除原设定所有控件为只读,恢复可编辑状态
function RemovePageReadonly(obj) {

    $(obj + " input," + obj + " textarea").each(function () {
        $(this).removeAttr('readonly');
        $(this).removeAttr('disabled');
    });
    $(obj + " select").each(function () {
        $(this).removeAttr('disabled');
    });
}

//添加新行
function AddNewDRow(obj, tb, rowclass) {
    if (tb.rows.length > 0)
    {
        var newrow = tb.insertRow();
        $(newrow).mouseover(function () {
            oldclor = this.style.backgroundColor;
            if (this.style.backgroundColor == "")
            {
                this.style.backgroundColor = '#FFFBCC';
            }
        });
        $(newrow).mouseout(function () {
            if (this.style.backgroundColor == "#d4ff8b") return;
            this.style.backgroundColor = oldclor;
        });
        if ($(tb).attr("onrowclick"))
        {
            $(newrow).click(function () {
                for (var i = 0; i < $(tb).find("tr:gt(0)").length; i++)
                {
                    if ($(tb).find("tr:gt(0)").eq(i)[0].style.backgroundColor == "#d4ff8b")
                    {
                        $(tb).find("tr:gt(0)").eq(i).css("background-color", "");
                    }
                }
                $(newrow).css("background-color", "#d4ff8b");
                eval($(tb).attr("onrowclick"));
            });
        }
        if (rowclass) newrow.className = rowclass;
        for (var i = 0; i < tb.rows[1].cells.length; i++)
        {
            try
            {
                if (IsView && $(tb.rows[1].cells[i]).attr("hiddenwhenview"))
                {
                    continue;
                }
            }
            catch (e) { }
            //td内嵌入table


            var cell = newrow.insertCell();
            if ($(tb.rows[1].cells[i]).attr("itemclass"))
                cell.className = $(tb.rows[0].cells[i]).attr("itemclass");
            var value = obj[$(tb.rows[1].cells[i]).attr("itemvalue")] ? obj[$(tb.rows[1].cells[i]).attr("itemvalue")] : "";
            var type = "";
            if ($(tb.rows[1].cells[i]).attr("showtype"))
            {
                try
                {
                    eval("type=" + ReplaceDataItem(obj, $(tb.rows[1].cells[i]).attr("showtype").replace(new RegExp("{itemvalue}", "gm"), value)));
                }
                catch (e)
                {
                    type = $(tb.rows[1].cells[i]).attr("showtype");
                }
            }
            var addattr = $(tb.rows[1].cells[i]).attr("attr") ? $(tb.rows[0].cells[i]).attr("attr") : "";
            var thetext = $(tb.rows[1].cells[i]).attr("itemtext") ? ReplaceDataItem(obj, $(tb.rows[1].cells[i]).attr("itemtext").replace(new RegExp("{itemvalue}", "gm"), value)) : "";
            var valuelist = $(tb.rows[1].cells[i]).attr("valuelist") ? ReplaceDataItem(obj, $(tb.rows[1].cells[i]).attr("valuelist").replace(new RegExp("{itemvalue}", "gm"), value)) : "";
            var showFormat = $(tb.rows[0].cells[i]).attr("showformat") ? $(tb.rows[0].cells[i]).attr("showformat") : "";
            if (type == "" && addattr != "")
            {

            }
            var maxthlength = parseInt($(tb.rows[1].cells[i]).attr("maxlength"), 10) || 100;
            $(cell).html(ShowItem(type, value, ReplaceDataItem(obj, addattr.replace(new RegExp("{itemvalue}", "gm"), value)), thetext, valuelist, maxthlength, showFormat));
        }
    }
}
