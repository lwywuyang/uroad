document.write(' <script src="../JS/ImgPanZoomHelper.js"> <\/script>')




$(document).bind("contextmenu", function (e) {
    return false;
});


function crossMachinePic(attachDiv, ImgSrcName) {
    this.attachDiv = attachDiv;
    this.pic = ImgSrcName;

    this.device1BackImage = ''; //3灯
    this.device2BackImage = ''; //2灯
    this.device3BackImage = ''; //线圈
    this.device3BackImageOn = ''; //线圈on

    this.redType1Image = '';
    this.redType2Image = '';
    this.redType3Image = '';
    this.redType4Image = '';
    this.redType5Image = '';
    this.redType6Image = '';

    this.yellowType1Image = '';
    this.yellowType2Image = '';
    this.yellowType3Image = '';
    this.yellowType4Image = '';
    this.yellowType5Image = '';


    this.greenType1Image = '';
    this.greenType2Image = '';
    this.greenType3Image = '';
    this.greenType4Image = '';
    this.greenType5Image = '';
    this.greenType6Image = '';

    this.type7Image = '';
    this.transImage = '';
    this.allowControl = false;

}




crossMachinePic.prototype.InitContentCompleted = function () {


}


//color: 'r': 红  'y': 黄 'g':绿
//clickType: '1':左单击   '2': 双击  '3':右单击
crossMachinePic.prototype.deviceClick = function (deviceid, devicetype, color, clickType) {


}



var currentStateCmd = "";
var PanZoomEty = null;
var RedLightArr = null;
var YellowLightArr = null;
var GreenLightArr = null;
var deviceArr = null;
var totalLightArr = null;
var blinkPicArr = null;
var xianQuanImgArr = null;
var xianQuanImgArrOn = null;


crossMachinePic.prototype.Init = function () {
    this.initData();
}



crossMachinePic.prototype.initData = function () {
    RedLightArr = new Array();
    YellowLightArr = new Array();
    GreenLightArr = new Array();
    deviceArr = new Array();
    totalLightArr = new Array();
    blinkPicArr = new Array();
    xianQuanImgArr = new Array();
    xianQuanImgArrOn = new Array();


    var self = this;

    PanZoomEty = new PanZoomClass(this.attachDiv, this.pic);
    PanZoomEty.EnableZoom = false;
    PanZoomEty.InitCompleted = function () {
        self.InitContentCompleted();
    }
    PanZoomEty.Init();
}




crossMachinePic.prototype.addDevicePoint = function (deviceid, deviceType, seqNum, picX, picY, angle) {
    var ety = new Object();
    ety.deviceid = deviceid;
    ety.deviceType = deviceType;
    ety.picX = picX;
    ety.picY = picY;
    ety.angle = angle;
    ety.seqNum = seqNum;


    var elementDiv = document.createElement('div');
    deviceArr[deviceid] = elementDiv;
    this.proccessWithDiv(elementDiv, ety);
    PanZoomEty.AppendChildPixel(elementDiv, picX, picY);
    this.fnRotateScale(elementDiv, (360 - angle), 1);
}










crossMachinePic.prototype.proccessWithDiv = function (dom, ety) {

    var deviceType = ety.deviceType;
    if (deviceType == '1') {
        this.proccessWithDivType1(dom, ety);
    }
    else if (deviceType == '2') {
        this.proccessWithDivType2(dom, ety);
    }
    else if (deviceType == '3') {
        this.proccessWithDivType3(dom, ety);
    }
    else if (deviceType == '4') {
        this.proccessWithDivType4(dom, ety);
    }
    else if (deviceType == '5') {
        this.proccessWithDivType5(dom, ety);
    }
    else if (deviceType == '6') {
        this.proccessWithDivType6(dom, ety);
    }
    else if (deviceType == '7') {
        this.proccessWithDivType7(dom, ety);
    }
}






crossMachinePic.prototype.proccessWithDivType1 = function (dom, ety) {
    var self = this;

    var ImageType1Width = 34;
    var ImageType1Height = 72;
    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');
    img.src = this.device1BackImage; ;
    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;
    elementDiv.id = deviceID;
    elementDiv.onclick = function (id) {

    }


    var lightLen = 12;

    var leftmargin = (ImageType1Width - lightLen) / 2. - 1;


    var idR = deviceID + 'R';
    var idY = deviceID + 'Y';
    var idG = deviceID + 'G';




    var imgRed = document.createElement('img');
    imgRed.width = lightLen;
    imgRed.height = lightLen;
    imgRed.style.top = 7 + 'px';
    imgRed.style.left = leftmargin + 'px';
    imgRed.src = this.redType1Image;
    imgRed.style.position = 'absolute';
    imgRed.style.visibility = 'hidden';
    imgRed.id = idR;
    totalLightArr[imgRed.id] = imgRed;
    imgRed.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '1', 'r', '1');
    };

    imgRed.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '1', 'r', '2');
    };

    imgRed.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '1', 'r', '3');
        }
    };


    var imgYellow = document.createElement('img');
    imgYellow.width = lightLen;
    imgYellow.height = lightLen;
    imgYellow.style.top = 28 + 'px';
    imgYellow.style.left = leftmargin + 'px';
    imgYellow.src = this.yellowType1Image;
    imgYellow.style.position = 'absolute';
    imgYellow.style.visibility = 'hidden';
    imgYellow.id = idY;
    totalLightArr[imgYellow.id] = imgYellow;
    imgYellow.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'y', '1');
        self.deviceClick(deviceID, '1', 'y', '1');
    };
    imgYellow.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'y', '2');
        self.deviceClick(deviceID, '1', 'y', '2');
    };
    imgYellow.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '1', 'y', '3');
        }
    };



    var imgGreen = document.createElement('img');
    imgGreen.width = lightLen;
    imgGreen.height = lightLen;
    imgGreen.style.top = 50 + 'px';
    imgGreen.style.left = leftmargin + 'px';
    imgGreen.src = this.greenType1Image;
    imgGreen.style.position = 'absolute';
    imgGreen.style.visibility = 'hidden';
    imgGreen.id = idG;
    totalLightArr[imgGreen.id] = imgGreen;
    imgGreen.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '1');
        self.deviceClick(deviceID, '1', 'g', '1');
    };
    imgGreen.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '2');
        self.deviceClick(deviceID, '1', 'g', '2');
    };
    imgGreen.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '1', 'g', '3');
        }
    };

    RedLightArr[deviceID] = imgRed;
    YellowLightArr[deviceID] = imgYellow;
    GreenLightArr[deviceID] = imgGreen;



    var imgRedBack = document.createElement('img');
    imgRedBack.width = imgRed.width;
    imgRedBack.height = imgRed.height;
    imgRedBack.src = this.transImage;
    imgRedBack.style.top = imgRed.style.top;
    imgRedBack.style.left = imgRed.style.left;
    imgRedBack.style.position = 'absolute';
    imgRedBack.id = idR + "_back";
    imgRedBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '1', 'r', '1');
    };
    imgRedBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '1', 'r', '2');
    };
    imgRedBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '1', 'r', '3');
        }
    };



    var imgYellowBack = document.createElement('img');
    imgYellowBack.width = imgYellow.width;
    imgYellowBack.height = imgYellow.height;
    imgYellowBack.src = this.transImage;
    imgYellowBack.style.top = imgYellow.style.top;
    imgYellowBack.style.left = imgYellow.style.left;
    imgYellowBack.style.position = 'absolute';
    imgYellowBack.id = idY + "_back";
    imgYellowBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '1', 'y', '1'); };
    imgYellowBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '1', 'y', '2'); };
    imgYellowBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '1', 'y', '3');
        }
    };



    var imgGreenBack = document.createElement('img');
    imgGreenBack.width = imgGreen.width;
    imgGreenBack.height = imgGreen.height;
    imgGreenBack.src = this.transImage;
    imgGreenBack.style.top = imgGreen.style.top;
    imgGreenBack.style.left = imgGreen.style.left;
    imgGreenBack.style.position = 'absolute';
    imgGreenBack.id = idG + "_back";
    imgGreenBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '1');
        self.deviceClick(deviceID, '1', 'g', '1');
    };
    imgGreenBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '2');
        self.deviceClick(deviceID, '1', 'g', '2');
    };
    imgGreenBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '1', 'g', '3');

        }
    };


    elementDiv.appendChild(img);

    elementDiv.appendChild(imgRedBack);
    elementDiv.appendChild(imgYellowBack);
    elementDiv.appendChild(imgGreenBack);

    elementDiv.appendChild(imgRed);
    elementDiv.appendChild(imgYellow);
    elementDiv.appendChild(imgGreen);


}






crossMachinePic.prototype.proccessWithDivType2 = function (dom, ety) {
    var self = this;
    var ImageType1Width = 34;
    var ImageType1Height = 72;
    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');
    img.src = this.device1BackImage;
    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;

    elementDiv.id = deviceID;

    elementDiv.onclick = function (id) {
        // proccessType1Click(this);
    }


    var lightLen = 19;

    var leftmargin = (ImageType1Width - lightLen) / 2.;


    var idR = deviceID + 'R';
    var idY = deviceID + 'Y';
    var idG = deviceID + 'G';




    var imgRed = document.createElement('img');
    imgRed.width = lightLen;
    imgRed.height = lightLen;
    imgRed.style.top = 5 + 'px';
    imgRed.style.left = leftmargin + 'px';
    imgRed.src = this.redType2Image;
    imgRed.style.position = 'absolute';
    imgRed.style.visibility = 'hidden';
    imgRed.id = idR;
    totalLightArr[imgRed.id] = imgRed;
    imgRed.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '2', 'r', '1');
    };
    imgRed.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '2', 'r', '2');
    };
    imgRed.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '2', 'r', '3');
        }
    };


    var imgYellow = document.createElement('img');
    imgYellow.width = lightLen;
    imgYellow.height = lightLen;
    imgYellow.style.top = 26 + 'px';
    imgYellow.style.left = leftmargin + 'px';
    imgYellow.src = this.yellowType2Image;
    imgYellow.style.position = 'absolute';
    imgYellow.style.visibility = 'hidden';
    imgYellow.id = idY;
    totalLightArr[imgYellow.id] = imgYellow;
    imgYellow.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'y', '1');
        self.deviceClick(deviceID, '2', 'y', '1');
    };
    imgYellow.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'y', '2');
        self.deviceClick(deviceID, '2', 'y', '2');
    };
    imgYellow.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '2', 'y', '3');

        }
    };



    var imgGreen = document.createElement('img');
    imgGreen.width = lightLen;
    imgGreen.height = lightLen;
    imgGreen.style.top = 48 + 'px';
    imgGreen.style.left = leftmargin + 'px';
    imgGreen.src = this.greenType2Image;
    imgGreen.style.position = 'absolute';
    imgGreen.style.visibility = 'hidden';
    imgGreen.id = idG;
    totalLightArr[imgGreen.id] = imgGreen;
    imgGreen.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '1');
        self.deviceClick(deviceID, '2', 'g', '1');
    };
    imgGreen.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '2');
        self.deviceClick(deviceID, '2', 'g', '2');
    };
    imgGreen.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '2', 'g', '3');

        }
    };

    RedLightArr[deviceID] = imgRed;
    YellowLightArr[deviceID] = imgYellow;
    GreenLightArr[deviceID] = imgGreen;



    var imgRedBack = document.createElement('img');
    imgRedBack.width = imgRed.width;
    imgRedBack.height = imgRed.height;
    imgRedBack.src = this.transImage;
    imgRedBack.style.top = imgRed.style.top;
    imgRedBack.style.left = imgRed.style.left;
    imgRedBack.style.position = 'absolute';
    imgRedBack.id = idR + "_back";
    imgRedBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '2', 'r', '1');
    };
    imgRedBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '2', 'r', '2');
    };
    imgRedBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '2', 'r', '3');

        }
    };



    //onmousedown

    var imgYellowBack = document.createElement('img');
    imgYellowBack.width = imgYellow.width;
    imgYellowBack.height = imgYellow.height;
    imgYellowBack.src = this.transImage;
    imgYellowBack.style.top = imgYellow.style.top;
    imgYellowBack.style.left = imgYellow.style.left;
    imgYellowBack.style.position = 'absolute';
    imgYellowBack.id = idY + "_back";
    imgYellowBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'y', '1');
        self.deviceClick(deviceID, '2', 'y', '1');
    };
    imgYellowBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'y', '2');
        self.deviceClick(deviceID, '2', 'y', '2');
    };
    imgYellowBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '2', 'y', '3');

        }
    };


    var imgGreenBack = document.createElement('img');
    imgGreenBack.width = imgGreen.width;
    imgGreenBack.height = imgGreen.height;
    imgGreenBack.src = this.transImage;
    imgGreenBack.style.top = imgGreen.style.top;
    imgGreenBack.style.left = imgGreen.style.left;
    imgGreenBack.style.position = 'absolute';
    imgGreenBack.id = idG + "_back";
    imgGreenBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '1');
        self.deviceClick(deviceID, '2', 'g', '1');
    };
    imgGreenBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'g', '2');
        self.deviceClick(deviceID, '2', 'g', '2');
    };
    imgGreenBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '2', 'g', '3');

        }
    };


    elementDiv.appendChild(img);

    elementDiv.appendChild(imgRedBack);
    elementDiv.appendChild(imgYellowBack);
    elementDiv.appendChild(imgGreenBack);

    elementDiv.appendChild(imgRed);
    elementDiv.appendChild(imgYellow);
    elementDiv.appendChild(imgGreen);

}



crossMachinePic.prototype.proccessWithDivType3 = function (dom, ety) {
    var self = this;

    var ImageType1Width = 34;
    var ImageType1Height = 72;

    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');
    img.src = this.device1BackImage;

    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;

    elementDiv.id = deviceID;

    elementDiv.onclick = function (id) {

    }


    var lightLen = 12;
    var lightH = 9;
    var leftmargin = (ImageType1Width - lightLen) / 2. - 1;


    var idR = deviceID + 'R';
    var idY = deviceID + 'Y';
    var idG = deviceID + 'G';




    var imgRed = document.createElement('img');
    imgRed.width = lightLen;
    imgRed.height = lightH;
    imgRed.style.top = 10 + 'px';
    imgRed.style.left = leftmargin + 'px';
    imgRed.src = this.redType3Image;
    imgRed.style.position = 'absolute';

    imgRed.style.visibility = 'hidden';
    imgRed.id = idR;
    totalLightArr[imgRed.id] = imgRed;
    imgRed.onclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '1'); self.deviceClick(deviceID, '3', 'r', '1'); };
    imgRed.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '2'); self.deviceClick(deviceID, '3', 'r', '2'); };
    imgRed.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '3', 'r', '3');

        }
    };


    var imgYellow = document.createElement('img');
    imgYellow.width = lightLen;
    imgYellow.height = lightH;
    imgYellow.style.top = 31 + 'px';
    imgYellow.style.left = leftmargin + 'px';
    imgYellow.src = this.yellowType3Image;
    imgYellow.style.position = 'absolute';
    imgYellow.style.visibility = 'hidden';
    imgYellow.id = idY;
    totalLightArr[imgYellow.id] = imgYellow;
    imgYellow.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '3', 'y', '1'); };
    imgYellow.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '3', 'y', '2'); };
    imgYellow.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '3', 'y', '3');

        }
    };



    var imgGreen = document.createElement('img');
    imgGreen.width = lightLen;
    imgGreen.height = lightH;
    imgGreen.style.top = 53 + 'px';
    imgGreen.style.left = leftmargin + 'px';
    imgGreen.src = this.greenType3Image;
    imgGreen.style.position = 'absolute';
    imgGreen.style.visibility = 'hidden';
    imgGreen.id = idG;
    totalLightArr[imgGreen.id] = imgGreen;
    imgGreen.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '3', 'g', '1'); };
    imgGreen.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '3', 'g', '2'); };
    imgGreen.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '3', 'g', '3');

        }
    };

    RedLightArr[deviceID] = imgRed;
    YellowLightArr[deviceID] = imgYellow;
    GreenLightArr[deviceID] = imgGreen;



    var imgRedBack = document.createElement('img');
    imgRedBack.width = imgRed.width;
    imgRedBack.height = imgRed.height;
    imgRedBack.src = this.transImage;
    imgRedBack.style.top = imgRed.style.top;
    imgRedBack.style.left = imgRed.style.left;
    imgRedBack.style.position = 'absolute';
    imgRedBack.id = idR + "_back";
    imgRedBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '3', 'r', '1');
    };
    imgRedBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '3', 'r', '2');
    };
    imgRedBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '3', 'r', '3');

        }
    };



    //onmousedown

    var imgYellowBack = document.createElement('img');
    imgYellowBack.width = imgYellow.width;
    imgYellowBack.height = imgYellow.height;
    imgYellowBack.src = this.transImage;
    imgYellowBack.style.top = imgYellow.style.top;
    imgYellowBack.style.left = imgYellow.style.left;
    imgYellowBack.style.position = 'absolute';
    imgYellowBack.id = idY + "_back";
    imgYellowBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '3', 'y', '1'); };
    imgYellowBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '3', 'y', '2'); };
    imgYellowBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '3', 'y', '3');

        }
    };


    var imgGreenBack = document.createElement('img');
    imgGreenBack.width = imgGreen.width;
    imgGreenBack.height = imgGreen.height;
    imgGreenBack.src = this.transImage;
    imgGreenBack.style.top = imgGreen.style.top;
    imgGreenBack.style.left = imgGreen.style.left;
    imgGreenBack.style.position = 'absolute';
    imgGreenBack.id = idG + "_back";
    imgGreenBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '3', 'g', '1'); };
    imgGreenBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '3', 'g', '2'); };
    imgGreenBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '3', 'g', '3');

        }
    };


    elementDiv.appendChild(img);

    elementDiv.appendChild(imgRedBack);
    elementDiv.appendChild(imgYellowBack);
    elementDiv.appendChild(imgGreenBack);

    elementDiv.appendChild(imgRed);
    elementDiv.appendChild(imgYellow);
    elementDiv.appendChild(imgGreen);
}



crossMachinePic.prototype.proccessWithDivType4 = function (dom, ety) {
    var self = this;
    var ImageType1Width = 34;
    var ImageType1Height = 72;

    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');
    img.src = this.device1BackImage;

    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;

    elementDiv.id = deviceID;

    elementDiv.onclick = function (id) {

    }


    var lightLen = 9;
    var lightH = 13;
    var leftmargin = (ImageType1Width - lightLen) / 2.;


    var idR = deviceID + 'R';
    var idY = deviceID + 'Y';
    var idG = deviceID + 'G';




    var imgRed = document.createElement('img');
    imgRed.width = lightLen;
    imgRed.height = lightH;
    imgRed.style.top = 8 + 'px';
    imgRed.style.left = leftmargin + 'px';
    imgRed.src = this.redType4Image;
    imgRed.style.position = 'absolute';
    imgRed.style.visibility = 'hidden';
    imgRed.id = idR;
    totalLightArr[imgRed.id] = imgRed;
    imgRed.onclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '1'); self.deviceClick(deviceID, '4', 'r', '1'); };
    imgRed.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '2'); self.deviceClick(deviceID, '4', 'r', '2'); };
    imgRed.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '4', 'r', '3');

        }
    };


    var imgYellow = document.createElement('img');
    imgYellow.width = lightLen;
    imgYellow.height = lightH;
    imgYellow.style.top = 29 + 'px';
    imgYellow.style.left = leftmargin + 'px';
    imgYellow.src = this.yellowType4Image;
    imgYellow.style.position = 'absolute';
    imgYellow.style.visibility = 'hidden';
    imgYellow.id = idY;
    totalLightArr[imgYellow.id] = imgYellow;
    imgYellow.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '4', 'y', '1'); };
    imgYellow.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '4', 'y', '2'); };
    imgYellow.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '4', 'y', '3');

        }
    };



    var imgGreen = document.createElement('img');
    imgGreen.width = lightLen;
    imgGreen.height = lightH;
    imgGreen.style.top = 51 + 'px';
    imgGreen.style.left = leftmargin + 'px';
    imgGreen.src = this.greenType4Image;
    imgGreen.style.position = 'absolute';
    imgGreen.style.visibility = 'hidden';
    imgGreen.id = idG;
    totalLightArr[imgGreen.id] = imgGreen;
    imgGreen.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '4', 'g', '1'); };
    imgGreen.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '4', 'g', '2'); };
    imgGreen.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '4', 'g', '3');

        }
    };

    RedLightArr[deviceID] = imgRed;
    YellowLightArr[deviceID] = imgYellow;
    GreenLightArr[deviceID] = imgGreen;



    var imgRedBack = document.createElement('img');
    imgRedBack.width = imgRed.width;
    imgRedBack.height = imgRed.height;
    imgRedBack.src = this.transImage;
    imgRedBack.style.top = imgRed.style.top;
    imgRedBack.style.left = imgRed.style.left;
    imgRedBack.style.position = 'absolute';
    imgRedBack.id = idR + "_back";
    imgRedBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '4', 'r', '1');
    };
    imgRedBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '4', 'r', '2');
    };
    imgRedBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '4', 'r', '3');

        }
    };



    //onmousedown

    var imgYellowBack = document.createElement('img');
    imgYellowBack.width = imgYellow.width;
    imgYellowBack.height = imgYellow.height;
    imgYellowBack.src = this.transImage;
    imgYellowBack.style.top = imgYellow.style.top;
    imgYellowBack.style.left = imgYellow.style.left;
    imgYellowBack.style.position = 'absolute';
    imgYellowBack.id = idY + "_back";
    imgYellowBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '4', 'y', '1'); };
    imgYellowBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '4', 'y', '2'); };
    imgYellowBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '4', 'y', '3');

        }
    };


    var imgGreenBack = document.createElement('img');
    imgGreenBack.width = imgGreen.width;
    imgGreenBack.height = imgGreen.height;
    imgGreenBack.src = this.transImage;
    imgGreenBack.style.top = imgGreen.style.top;
    imgGreenBack.style.left = imgGreen.style.left;
    imgGreenBack.style.position = 'absolute';
    imgGreenBack.id = idG + "_back";
    imgGreenBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '4', 'g', '1'); };
    imgGreenBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '4', 'g', '2'); };
    imgGreenBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '4', 'g', '3');
        }
    };


    elementDiv.appendChild(img);

    elementDiv.appendChild(imgRedBack);
    elementDiv.appendChild(imgYellowBack);
    elementDiv.appendChild(imgGreenBack);

    elementDiv.appendChild(imgRed);
    elementDiv.appendChild(imgYellow);
    elementDiv.appendChild(imgGreen);
}




crossMachinePic.prototype.proccessWithDivType5 = function (dom, ety) {
    var self = this;

    var ImageType1Width = 34;
    var ImageType1Height = 72;

    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');
    img.src = this.device1BackImage;

    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;

    elementDiv.id = deviceID;

    elementDiv.onclick = function (id) {

    }


    var lightLen = 12;
    var lightH = 9;
    var leftmargin = (ImageType1Width - lightLen) / 2. + 1;


    var idR = deviceID + 'R';
    var idY = deviceID + 'Y';
    var idG = deviceID + 'G';




    var imgRed = document.createElement('img');
    imgRed.width = lightLen;
    imgRed.height = lightH;
    imgRed.style.top = 10 + 'px';
    imgRed.style.left = leftmargin + 'px';
    imgRed.src = this.redType5Image;
    imgRed.style.position = 'absolute';
    imgRed.style.visibility = 'hidden';
    imgRed.id = idR;
    totalLightArr[imgRed.id] = imgRed;
    imgRed.onclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '1'); self.deviceClick(deviceID, '5', 'r', '1'); };
    imgRed.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '2'); self.deviceClick(deviceID, '5', 'r', '2'); };
    imgRed.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '5', 'r', '3');

        }
    };


    var imgYellow = document.createElement('img');
    imgYellow.width = lightLen;
    imgYellow.height = lightH;
    imgYellow.style.top = 31 + 'px';
    imgYellow.style.left = leftmargin + 'px';
    imgYellow.src = this.yellowType5Image;
    imgYellow.style.position = 'absolute';
    imgYellow.style.visibility = 'hidden';
    imgYellow.id = idY;
    totalLightArr[imgYellow.id] = imgYellow;
    imgYellow.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '5', 'y', '1'); };
    imgYellow.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '5', 'y', '2'); };
    imgYellow.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '5', 'y', '3');

        }
    };



    var imgGreen = document.createElement('img');
    imgGreen.width = lightLen;
    imgGreen.height = lightH;
    imgGreen.style.top = 53 + 'px';
    imgGreen.style.left = leftmargin + 'px';
    imgGreen.src = this.greenType5Image;
    imgGreen.style.position = 'absolute';
    imgGreen.style.visibility = 'hidden';
    imgGreen.id = idG;
    totalLightArr[imgGreen.id] = imgGreen;
    imgGreen.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '5', 'g', '1'); };
    imgGreen.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '5', 'g', '2'); };
    imgGreen.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '5', 'g', '3');

        }
    };

    RedLightArr[deviceID] = imgRed;
    YellowLightArr[deviceID] = imgYellow;
    GreenLightArr[deviceID] = imgGreen;



    var imgRedBack = document.createElement('img');
    imgRedBack.width = imgRed.width;
    imgRedBack.height = imgRed.height;
    imgRedBack.src = this.transImage;
    imgRedBack.style.top = imgRed.style.top;
    imgRedBack.style.left = imgRed.style.left;
    imgRedBack.style.position = 'absolute';
    imgRedBack.id = idR + "_back";
    imgRedBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '5', 'r', '1');
    };
    imgRedBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '5', 'r', '2');
    };
    imgRedBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '5', 'r', '3');

        }
    };



    //onmousedown

    var imgYellowBack = document.createElement('img');
    imgYellowBack.width = imgYellow.width;
    imgYellowBack.height = imgYellow.height;
    imgYellowBack.src = this.transImage;
    imgYellowBack.style.top = imgYellow.style.top;
    imgYellowBack.style.left = imgYellow.style.left;
    imgYellowBack.style.position = 'absolute';
    imgYellowBack.id = idY + "_back";
    imgYellowBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '1'); self.deviceClick(deviceID, '5', 'y', '1'); };
    imgYellowBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'y', '2'); self.deviceClick(deviceID, '5', 'y', '2'); };
    imgYellowBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'y', '3');
            self.deviceClick(deviceID, '5', 'y', '3');

        }
    };


    var imgGreenBack = document.createElement('img');
    imgGreenBack.width = imgGreen.width;
    imgGreenBack.height = imgGreen.height;
    imgGreenBack.src = this.transImage;
    imgGreenBack.style.top = imgGreen.style.top;
    imgGreenBack.style.left = imgGreen.style.left;
    imgGreenBack.style.position = 'absolute';
    imgGreenBack.id = idG + "_back";
    imgGreenBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '5', 'g', '1'); };
    imgGreenBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '5', 'g', '2'); };
    imgGreenBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '5', 'g', '3');

        }
    };


    elementDiv.appendChild(img);

    elementDiv.appendChild(imgRedBack);
    elementDiv.appendChild(imgYellowBack);
    elementDiv.appendChild(imgGreenBack);

    elementDiv.appendChild(imgRed);
    elementDiv.appendChild(imgYellow);
    elementDiv.appendChild(imgGreen);
}



crossMachinePic.prototype.proccessWithDivType6 = function (dom, ety) {
    var self = this;

    var ImageType1Width = 34;
    var ImageType1Height = 51;
    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');
    img.src = this.device2BackImage;
    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;
    elementDiv.id = deviceID;
    elementDiv.onclick = function (id) {

    }

    var lightLen = 11;
    var lightH = 16;
    var leftmargin = (ImageType1Width - lightLen) / 2.;

    var idR = deviceID + 'R';
    var idG = deviceID + 'G';


    var imgRed = document.createElement('img');
    imgRed.width = lightLen;
    imgRed.height = lightH;
    imgRed.style.top = 7 + 'px';
    imgRed.style.left = leftmargin + 'px';
    imgRed.src = this.redType6Image;
    imgRed.style.position = 'absolute';
    imgRed.style.visibility = 'hidden';
    imgRed.id = idR;
    totalLightArr[imgRed.id] = imgRed;
    imgRed.onclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '1'); self.deviceClick(deviceID, '6', 'r', '1'); };
    imgRed.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'r', '2'); self.deviceClick(deviceID, '6', 'r', '2'); };
    imgRed.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '6', 'r', '3');

        }
    };





    var imgGreen = document.createElement('img');
    imgGreen.width = lightLen;
    imgGreen.height = lightH;
    imgGreen.style.top = 29 + 'px';
    imgGreen.style.left = leftmargin + 'px';
    imgGreen.src = this.greenType6Image;
    imgGreen.style.position = 'absolute';
    imgGreen.style.visibility = 'hidden';
    imgGreen.id = idG;
    totalLightArr[imgGreen.id] = imgGreen;
    imgGreen.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '6', 'g', '1'); };
    imgGreen.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '6', 'g', '2'); };
    imgGreen.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '6', 'g', '3');

        }
    };

    RedLightArr[deviceID] = imgRed;

    GreenLightArr[deviceID] = imgGreen;



    var imgRedBack = document.createElement('img');
    imgRedBack.width = imgRed.width;
    imgRedBack.height = imgRed.height;
    imgRedBack.src = this.transImage;
    imgRedBack.style.top = imgRed.style.top;
    imgRedBack.style.left = imgRed.style.left;
    imgRedBack.style.position = 'absolute';
    imgRedBack.id = idR + "_back";
    imgRedBack.onclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '1');
        self.deviceClick(deviceID, '6', 'r', '1');
    };
    imgRedBack.ondblclick = function (e) {
        self.proccessControlClickWith(deviceID, 'r', '2');
        self.deviceClick(deviceID, '6', 'r', '2');
    };
    imgRedBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'r', '3');
            self.deviceClick(deviceID, '6', 'r', '3');

        }
    };



    var imgGreenBack = document.createElement('img');
    imgGreenBack.width = imgGreen.width;
    imgGreenBack.height = imgGreen.height;
    imgGreenBack.src = this.transImage;
    imgGreenBack.style.top = imgGreen.style.top;
    imgGreenBack.style.left = imgGreen.style.left;
    imgGreenBack.style.position = 'absolute';
    imgGreenBack.id = idG + "_back";
    imgGreenBack.onclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '1'); self.deviceClick(deviceID, '6', 'g', '1'); };
    imgGreenBack.ondblclick = function (e) { self.proccessControlClickWith(deviceID, 'g', '2'); self.deviceClick(deviceID, '6', 'g', '2'); };
    imgGreenBack.onmousedown = function (e) {
        var event = window.event;
        if (event.button == 2) {
            self.proccessControlClickWith(deviceID, 'g', '3');
            self.deviceClick(deviceID, '6', 'g', '3');

        }
    };


    elementDiv.appendChild(img);

    elementDiv.appendChild(imgRedBack);

    elementDiv.appendChild(imgGreenBack);

    elementDiv.appendChild(imgRed);

    elementDiv.appendChild(imgGreen);
}



crossMachinePic.prototype.proccessWithDivType7 = function (dom, ety) {
    var self = this;

    var ImageType1Width = 22;
    var ImageType1Height = 22;

    var deviceID = ety.deviceid;
    var elementDiv = dom;
    var img = document.createElement('img');






    xianQuanImgArr[deviceID + "XianQuan"] = img;

    img.src = this.type7Image;


    elementDiv.width = ImageType1Width;
    elementDiv.height = ImageType1Height;

    elementDiv.id = deviceID;


    elementDiv.onclick = function (id) {
        self.proccessXianQuanWith(ety);
    }

    var seqNum = ety.seqNum;

    var lb = document.createElement('h');
    lb.innerHTML = seqNum;
    lb.style.position = 'absolute';
    lb.style.width = ImageType1Width + 'px';
    lb.style.height = ImageType1Height + "px";
    lb.style.left = '2px';
    lb.style.top = '2px';
    lb.style.color = 'white';

    // elementDiv.style.background = 'red';

    elementDiv.appendChild(img);
    elementDiv.appendChild(lb);
}















































crossMachinePic.prototype.fnRotateScale = function (dom, angle, scale) {
    if (dom && dom.nodeType === 1) {
        angle = parseFloat(angle) || 0;
        scale = parseFloat(scale) || 1;
        if (typeof (angle) === "number") {
            //IE 
            var rad = angle * (Math.PI / 180);
            var m11 = Math.cos(rad) * scale, m12 = -1 * Math.sin(rad) * scale, m21 = Math.sin(rad) * scale, m22 = m11;
            if (!dom.style.Transform) {
                dom.style.filter = "progid:DXImageTransform.Microsoft.Matrix(M11=" + m11 + ",M12=" + m12 + ",M21=" + m21 + ",M22=" + m22 + ",SizingMethod='auto expand')";
            }
            //Modern 
            dom.style.mozTransform = "rotate(" + angle + "deg) scale(" + scale + ")";
            dom.style.webkitTransform = "rotate(" + angle + "deg) scale(" + scale + ")";
            dom.style.oTransform = "rotate(" + angle + "deg) scale(" + scale + ")";
            dom.style.transform = "rotate(" + angle + "deg) scale(" + scale + ")";
            return;


            var w = dom.width;
            var h = dom.height;

            var x1 = -w / 2.0;
            var y1 = h / 2.0;

            var x2 = w / 2.0;
            var y2 = h / 2.0;

            var x3 = -w / 2.0;
            var y3 = -h / 2.0;

            var x4 = w / 2.0;
            var y4 = -h / 2.0;


            var x1r = this.proccessWithXCoor(x1, y1, rad);
            var y1r = this.proccessWithYCoor(x1, y1, rad);

            var x2r = this.proccessWithXCoor(x2, y2, rad);
            var y2r = this.proccessWithYCoor(x2, y2, rad);


            var x3r = this.proccessWithXCoor(x3, y3, rad);
            var y3r = this.proccessWithYCoor(x3, y3, rad);

            var x4r = this.proccessWithXCoor(x4, y4, rad);
            var y4r = this.proccessWithYCoor(x4, y4, rad);


            var leftMax = x1r;
            if (x2r < leftMax) {
                leftMax = x2r;
            }
            if (x3r < leftMax) {
                leftMax = x3r;
            }
            if (x4r < leftMax) {
                leftMax = x4r;
            }

            var topMax = y1r;
            if (y2r > topMax) {
                topMax = y2r;
            }
            if (y3r > topMax) {
                topMax = y3r;
            }
            if (y4r > topMax) {
                topMax = y4r;
            }


            var modifyX = 0.0;
            var modifyY = 0.0;


            modifyX = x1 - leftMax;
            modifyY = topMax - y1;


            var leftOrgin = dom.style.left;
            var topOrgin = dom.style.top;

            leftOrgin = parseInt(leftOrgin);
            topOrgin = parseInt(topOrgin);

            var targetX = leftOrgin - modifyX;
            var targetY = topOrgin - modifyY;

            targetX = parseInt(targetX);
            targetY = parseInt(targetY);

            targetX = targetX + "px";
            targetY = targetY + "px";


            dom.style.left = targetX;
            dom.style.top = targetY;


        }
    }
};






crossMachinePic.prototype.proccessWithXCoor = function (x, y, diffAngel) {
    var rad = Math.atan2(y, x);
    var radResult = rad - diffAngel;
    var s = Math.sqrt(x * x + y * y)
    var resultX = s * Math.cos(radResult);
    return resultX;

}

crossMachinePic.prototype.proccessWithYCoor = function (x, y, diffAngel) {
    var rad = Math.atan2(y, x);
    var radResult = rad - diffAngel;
    var s = Math.sqrt(x * x + y * y)
    var resultY = s * Math.sin(radResult);
    return resultY;

}



crossMachinePic.prototype.getStateData = function (list) {

    var markList = new Array();
    var markTotalIndx = 0;

    var markBlinkList = new Array();
    var markBlinkTotalIndx = 0;
    if (list == undefined )
        return;
    var arr = list.split(';');
    var len = arr.length;

    var markXianQuanOnList = new Array();


    for (var i = 1; i <= len; i++) {
        var subString = arr[i - 1];
        var subArr = subString.split(':');
        var subLen = subArr.length;
        if (subLen == 2) {
            var id = subArr[0];
            var state = subArr[1];
            var idString = '';
            var operateType = 0;
            if (state == 'r') {
                idString = id + 'R';
                operateType = 1;
            }
            else if (state == 'y') {
                idString = id + 'Y';
                operateType = 1;
            }
            else if (state == 'g') {
                idString = id + 'G';
                operateType = 1;
            }
            else if (state == 'rb') {
                idString = id + 'R';
                operateType = 2;
            }
            else if (state == 'yb') {
                idString = id + 'Y';
                operateType = 2;
            }
            else if (state == 'gb') {
                idString = id + 'G';
                operateType = 2;
            }
            else if (state == 'o') {
                idString = id + "XianQuan";
                operateType = 3;
            }


            if (operateType == 1) {
                markList[markTotalIndx] = idString;
                markTotalIndx += 1;
            }
            else if (operateType == 2) {
                markBlinkList[markBlinkTotalIndx] = idString;
                markBlinkTotalIndx += 1;
            }
            else if (operateType == 3) {
                markXianQuanOnList[idString] = idString;
            }
        }

    }

    var newXianQuanOnList = new Array();


    for (var key in xianQuanImgArr) {
        var find = false;
        for (var showKey in markXianQuanOnList) {
            if (showKey == key) {
                find = true;
                break;
            }
        }
        var dom = xianQuanImgArr[key];
        if (find) {
            dom.src = this.device3BackImageOn;
            newXianQuanOnList[key] = dom;
        } else {
            dom.src = this.device3BackImage;
        }
    }


    xianQuanImgArrOn = newXianQuanOnList;



    var markBlinkAddList = new Array();
    var markBlinkAddListTotalIndx = 0;


    for (var i = 1; i <= markBlinkList.length; i++) {
        var comparedKey = markBlinkList[i - 1];
        blinkPicArr[comparedKey] = comparedKey;
        markBlinkAddList[markBlinkAddListTotalIndx] = comparedKey;
        markBlinkAddListTotalIndx += 1;
    }


    var markBlinkRemoveList = new Array();
    var markBlinkRemoveListTotalIndx = 0;


    for (var key in blinkPicArr) {

        var markBlinkAddListLen = markBlinkAddList.length;
        var find = false;

        for (var i = 1; i <= markBlinkAddListLen; i++) {
            var comparedKey = markBlinkAddList[i - 1];
            if (comparedKey == key) {
                find = true;
                break;
            }

        }

        if (!find) {
            markBlinkRemoveList[markBlinkRemoveListTotalIndx] = key;
            markBlinkRemoveListTotalIndx += 1;
        }
    }

    for (var i = 1; i <= markBlinkRemoveList.length; i++) {
        var key = markBlinkRemoveList[i - 1];
        delete blinkPicArr[key];
    }

    for (var key in totalLightArr) {

        var markListLen = markList.length;
        var find = false;

        for (var i = 1; i <= markListLen; i++) {
            var comparedKey = markList[i - 1];
            if (comparedKey == key) {
                find = true;
                break;
            }

        }

        if (!find) {
            var img = totalLightArr[key];
            if (img != null) {
                var findblink = false;

                for (var blinkkey in blinkPicArr) {
                    if (key == blinkkey) {
                        findblink = true;
                        break;
                    }
                }

                if (!findblink) {
                    img.style.visibility = 'hidden';
                }


            }

        }
        else {
            var img = totalLightArr[key];
            if (img != null) {
                img.style.visibility = 'visible';
            }

        }

    }




    for (var key in deviceArr) {
        var div = deviceArr[key];
        if (div != null) {
            div.style.visibility = 'visible';
        }

    }

    currentStateCmd = list;
}




crossMachinePic.prototype.CurrentData = function () {
    var convert = this.convertCmd(currentStateCmd);
    return convert;
}



crossMachinePic.prototype.proccessControlClickWith = function (deviceid, color, clickType) {
    var allowChange = this.allowControl;
    if (!allowChange) {
        return;
    }

    var nowCmd = currentStateCmd;
    if (clickType == '3') {//删除
        var resultCmd = "";

        var arr = nowCmd.split(';');
        var len = arr.length;

        for (var i = 1; i <= len; i++) {
            var subString = arr[i - 1];
            var subArr = subString.split(':');
            var subLen = subArr.length;
            var shouldAdd = true;

            if (subLen == 2) {
                var id = subArr[0];
                var state = subArr[1];

                var matchId = false;
                var matchColor = false;

                if (id == deviceid) {
                    matchId = true;
                }



                if (state == 'r') {
                    if (color == 'r') {
                        matchColor = true;
                    }
                }
                else if (state == 'y') {
                    if (color == 'y') {
                        matchColor = true;
                    }
                }
                else if (state == 'g') {
                    if (color == 'g') {
                        matchColor = true;
                    }
                }
                else if (state == 'rb') {
                    if (color == 'r') {
                        matchColor = true;
                    }
                }
                else if (state == 'yb') {
                    if (color == 'y') {
                        matchColor = true;
                    }
                }
                else if (state == 'gb') {
                    if (color == 'g') {
                        matchColor = true;
                    }
                }

                if (matchId && matchColor) {
                    shouldAdd = false;
                }

                if (shouldAdd) {
                    resultCmd += subString;
                    resultCmd += ";";
                }
            }


        }

        this.getStateData(resultCmd);
    }






    else if (clickType == '1') {//亮
        var resultCmd = "";

        var arr = nowCmd.split(';');
        var len = arr.length;
        var shouldInsert = true;


        for (var i = 1; i <= len; i++) {
            var subString = arr[i - 1];
            var subArr = subString.split(':');
            var subLen = subArr.length;
            var shouldAdd = true;

            if (subLen == 2) {
                var id = subArr[0];
                var state = subArr[1];

                var matchId = false;


                if (id == deviceid) {
                    matchId = true;
                }



                if (state == 'r') {
                    if (color == 'r') {
                        if (matchId) {
                            shouldInsert = false;
                        }

                    }
                }
                else if (state == 'y') {
                    if (color == 'y') {
                        if (matchId) {
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'g') {
                    if (color == 'g') {
                        if (matchId) {
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'rb') {
                    if (color == 'r') {
                        if (matchId) {
                            resultCmd += id + ":" + color;
                            resultCmd += ";";
                            shouldAdd = false;
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'yb') {
                    if (color == 'y') {
                        if (matchId) {
                            resultCmd += id + ":" + color;
                            resultCmd += ";";
                            shouldAdd = false;
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'gb') {
                    if (color == 'g') {
                        if (matchId) {
                            resultCmd += id + ":" + color;
                            resultCmd += ";";
                            shouldAdd = false;
                            shouldInsert = false;
                        }
                    }
                }
                if (shouldAdd) {
                    resultCmd += subString;
                    resultCmd += ";";
                }
            }


        }




        if (shouldInsert) {
            resultCmd += deviceid + ":" + color;
            resultCmd += ";";
            resultCmd = this.convertCmd(resultCmd);
        }




        this.getStateData(resultCmd);

    }













    else if (clickType == '2') {//闪
        var resultCmd = "";

        var arr = nowCmd.split(';');
        var len = arr.length;
        var shouldInsert = true;


        for (var i = 1; i <= len; i++) {
            var subString = arr[i - 1];
            var subArr = subString.split(':');
            var subLen = subArr.length;
            var shouldAdd = true;

            if (subLen == 2) {
                var id = subArr[0];
                var state = subArr[1];

                var matchId = false;


                if (id == deviceid) {
                    matchId = true;
                }



                if (state == 'r') {
                    if (color == 'r') {
                        if (matchId) {
                            resultCmd += id + ":" + color + 'b';
                            resultCmd += ";";
                            shouldAdd = false;
                            shouldInsert = false;
                        }

                    }
                }
                else if (state == 'y') {
                    if (color == 'y') {
                        if (matchId) {
                            resultCmd += id + ":" + color + 'b';
                            resultCmd += ";";
                            shouldAdd = false;
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'g') {
                    if (color == 'g') {
                        if (matchId) {
                            resultCmd += id + ":" + color + 'b';
                            resultCmd += ";";
                            shouldAdd = false;
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'rb') {
                    if (color == 'r') {
                        if (matchId) {
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'yb') {
                    if (color == 'y') {
                        if (matchId) {
                            shouldInsert = false;
                        }
                    }
                }
                else if (state == 'gb') {
                    if (color == 'g') {
                        if (matchId) {

                            shouldInsert = false;
                        }
                    }
                }
                if (shouldAdd) {
                    resultCmd += subString;
                    resultCmd += ";";
                }
            }


        }




        if (shouldInsert) {
            resultCmd += deviceid + ":" + color + 'b';
            resultCmd += ";";
            resultCmd = this.convertCmd(resultCmd);
        }




        this.getStateData(resultCmd);


    }

    // var dataString = '1:r;1:g;2:rb;2:y;2:gb;3:r;3:yb;3:g;4:y;4:gb;5:r;5:yb;5:g;6:rb;6:g;12:r;12:gb';
}



crossMachinePic.prototype.proccessXianQuanWith = function (ety) {
    if (!this.allowControl) {
        return;
    }
    var deviceid = ety.deviceid;
    var nowCmd = currentStateCmd;

    var resultCmd = "";

    var arr = nowCmd.split(';');
    var len = arr.length;
    var find = false;

    for (var i = 1; i <= len; i++) {
        var subString = arr[i - 1];
        var subArr = subString.split(':');
        var subLen = subArr.length;
        var shouldAdd = true;


        if (subLen == 2) {
            var id = subArr[0];
            var state = subArr[1];
            if (id == deviceid) {
                if (state == 'o') {
                    shouldAdd = false;
                    find = true;
                }
            }

            if (shouldAdd) {
                resultCmd += subString;
                resultCmd += ';';
            }
        }






    }

    if (!find) {
        resultCmd += deviceid + ':o;';
    }

    this.getStateData(resultCmd);


    //     var deviceid = ety.deviceid;
    //     var dom = xianQuanImgArrOn[deviceid + 'XianQuan'];
    //     if (dom != null) {

    //     } else {
    //     
    //      }
}




crossMachinePic.prototype.convertCmd = function (cmd) {

    var result = '';

    var arr = cmd.split(';');
    var len = arr.length;

    var tempArrR = new Array();
    var tempArrY = new Array();
    var tempArrG = new Array();
    var tempArrOn = new Array();


    for (var i = 1; i <= len; i++) {
        var subString = arr[i - 1];
        var subArr = subString.split(':');
        var subLen = subArr.length;
        var subStringMerge = subString + ';';
        if (subLen == 2) {
            var id = subArr[0];
            var state = subArr[1];
            if (state == 'r' || state == 'rb') {
                tempArrR[id] = subStringMerge;
            }
            else if (state == 'y' || state == 'yb') {
                tempArrY[id] = subStringMerge;
            } else if (state == 'g' || state == 'gb') {
                tempArrG[id] = subStringMerge;
            }
            if (state == 'o') {
                tempArrOn[id] = subStringMerge;
            }
        }
    }



    for (var deviceKey in deviceArr) {
        var RString = tempArrR[deviceKey];
        var YString = tempArrY[deviceKey];
        var GString = tempArrG[deviceKey];
        var OString = tempArrOn[deviceKey];
        if (RString != undefined) {
            result += RString;

        }
        if (YString != undefined) {
            result += YString;

        }
        if (GString != undefined) {
            result += GString;

        }
        if (OString != undefined) {
            result += OString;

        }
    }

    return result;
}



var markBlick = false;
timerID = setInterval("tick()", 500);
function tick() {
    for (var key in blinkPicArr) {
        var dom = document.getElementById(key);
        if (dom != null) {
            if (markBlick) {
                dom.style.visibility = 'visible';
            } else {
                dom.style.visibility = 'hidden';
            }

        }

    }
    markBlick = !markBlick;
}