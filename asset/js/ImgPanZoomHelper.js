


function PanZoomClass(AttachDivName, ImgSrcName) {
    this.AttachDivName = AttachDivName;
    this.SrcName = ImgSrcName;
    this.InnerPanDivName = 'InnerPicDiv';
    this.OuterPanDivName = 'OutPicDiv';
    this.EnableZoom = true;

    //     <img  id="Picimg"   src="/高速变形图--2012-03-28.png"  style="height: 100%; width: 100%; display:none; position:absolute; z-index:0"  />
   // this.AttachDiv.innerHTML = '<img  id="Picimg"   src="' + ImgSrcName + '"  style="height: 100%; width: 100%;position:absolute; z-index:0"  />';



    var innerPicW;
    var innerPicH;


    this.mouseDownFlag = false;



    this.downX = 0;
    this.downY = 0;

    this.OffSetXpic = 0;
    this.OffSetYpic = 0;

    this.ImgDivMaxLen = 8000;
    this.ImgDivMinLen = 2000;
//    this.ImgDivMaxLen = 500;
 //   this.ImgDivMinLen = 500;

  //  this.MouseDown = function (event) {
    this.BeenLoad = true;

   
     }



     PanZoomClass.prototype.InitCompleted = function () {
     
     
      }


      PanZoomClass.prototype.Init = function () {
          var self = this;

          this.AttachDiv = document.getElementById(this.AttachDivName);
          this.AttachDiv.style.overflow = 'hidden';
        //  this.AttachDiv.style.position = 'relative';


          //         this.AttachDiv.innerHTML =
          //     '<div id=' + this.OuterPanDivName + ' style="height:100%; width:100%;cursor:default;">' +
          //     '<div id="' + this.InnerPanDivName + '"   style="height: 1000px; width: 1000px; position:absolute ;top:0px; overflow:hidden" >' +
          //     '<img  id="Picimg"   src="' + this.SrcName + '"  style="height: 100%; width: 100%;  position:absolute; z-index:0"  />  ' +
          //     '</div> </div> ';



          //         var outDiv = document.getElementById(this.OuterPanDivName);

          var Ety = this;
          var outDiv = document.createElement('div');
          //outDiv.style.position = 'absolute';
          outDiv.innerHTML =
                '<div id=' + this.OuterPanDivName + ' style="height:100%; width:100%;cursor:default;position:absolute">' +
              '<div id="' + this.InnerPanDivName + '"   style="height: 100%; width: 100%; position:absolute ;top:0px;  " >' +
              '<img  id="Picimg"   src="' + this.SrcName + '"  style="  position:absolute; z-index:0"  />  ' +
              '</div> </div> ';


          outDiv.onmousedown = function (event) {
              event = window.event;
              var btn = event.button;
              if (btn == 1) {
                  if (!Ety.BeenLoad) { return; }
                  var Pic = document.getElementById(Ety.InnerPanDivName);
                  var PicDiv = document.getElementById(Ety.OuterPanDivName);
                  PicDiv.setCapture();
                  Ety.mouseDownFlag = true;
                  Ety.downX = event.clientX;
                  Ety.downY = event.clientY;
                  Ety.OffSetXpic = Pic.offsetLeft;
                  Ety.OffSetYpic = Pic.offsetTop;
              }
          }

          outDiv.onmousemove = function (event) {
              event = window.event;
              if (Ety.mouseDownFlag) {
                  var Pic = document.getElementById(Ety.InnerPanDivName);
                  var offsetX = event.clientX - Ety.downX;
                  var offsetY = event.clientY - Ety.downY;

                  var targetLeft = (offsetX + Ety.OffSetXpic);
                  var targetTop = (offsetY + Ety.OffSetYpic);

                  if (targetLeft > 0) {
                      targetLeft = 0;
                  }
                  else {
                      var domContainer = document.getElementById(self.OuterPanDivName);
                      var domContainerW = domContainer.offsetWidth;

                      var limitLeft = domContainerW - innerPicW;
                      if (limitLeft > 0) {
                          limitLeft = 0;
                      }
                      if (targetLeft < limitLeft) {
                          targetLeft = limitLeft;
                      }



                  }


                  if (targetTop > 0) {
                      targetTop = 0;
                  } else {
                      var domContainer = document.getElementById(self.OuterPanDivName);
                      var domContainerH = domContainer.offsetHeight;
                      var limitTop = domContainerH - innerPicH;
                      if (limitTop > 0) {
                          limitTop = 0;
                      }
                      if (targetTop < limitTop) {
                          targetTop = limitTop;
                      }
                  }





                  Pic.style.left = targetLeft + 'px';
                  Pic.style.top = targetTop + 'px';
              }
          }


          outDiv.onmouseup = function (event) {
              event = window.event;
              Ety.mouseDownFlag = false;
              PicDiv = document.getElementById(Ety.OuterPanDivName);
              PicDiv.releaseCapture();
          }

          outDiv.onmousewheel = function (event) {
              if (Ety.EnableZoom == true) {
                  event = window.event;
                  if (event.wheelDelta > 0) {
                      Ety.ZoomIn(0.8);
                  }
                  else {
                      Ety.ZoomOut(0.8);
                  }


              }

          }



          this.AttachDiv.appendChild(outDiv);

          this.InitCompleted();


          var picDom = document.getElementById("Picimg");
          var width = picDom.width;
          var height = picDom.height;

          innerPicW = width;
          innerPicH = height;


          var outPicDom = document.getElementById(this.InnerPanDivName);
          outPicDom.style.width = innerPicW+'px';
          outPicDom.style.height = innerPicH+'px';

      }



 
     PanZoomClass.prototype.ZoomIn = function (Factor) {
         var Pic = document.getElementById("Picimg");
         var PicDiv = document.getElementById(this.OuterPanDivName);
         var PicWidth = Pic.offsetWidth;
         var PicHeight = Pic.offsetHeight;

         if (PicWidth >= this.ImgDivMaxLen) {
             return;
         }
         var Outwidth = PicDiv.offsetWidth;
         var Outheight = PicDiv.offsetHeight;
         var OSX = Pic.offsetLeft;
         var OSY = Pic.offsetTop;
         var PicCenterXOffSet = Outwidth / 2.0 - OSX;
         var PicCenterYOffSet = Outheight / 2.0 - OSY;
         var PicCenterXOffSetZoomIn = PicCenterXOffSet / Factor;
         var PicCenterYOffSetZoomIn = PicCenterYOffSet / Factor;
         var NewOSX = Outwidth / 2.0 - PicCenterXOffSetZoomIn;
         var NewOSY = Outheight / 2.0 - PicCenterYOffSetZoomIn;
         Pic.style.left = (NewOSX) + 'px';
         Pic.style.top = (NewOSY) + 'px';
         Pic.style.width = (PicWidth / Factor) + 'px';
         Pic.style.height = (PicHeight / Factor) + 'px';
     }
     PanZoomClass.prototype.ZoomOut = function (Factor) {
         var Pic = document.getElementById("Picimg");
         var PicDiv = document.getElementById(this.OuterPanDivName);
         var PicWidth = Pic.offsetWidth;
         var PicHeight = Pic.offsetHeight;
         if (PicWidth <= this.ImgDivMinLen) {
             return;
         }
         var Outwidth = PicDiv.offsetWidth;
         var Outheight = PicDiv.offsetHeight;
         var OSX = Pic.offsetLeft;
         var OSY = Pic.offsetTop;
         var PicCenterXOffSet = Outwidth / 2.0 - OSX;
         var PicCenterYOffSet = Outheight / 2.0 - OSY;
         var PicCenterXOffSetZoomIn = PicCenterXOffSet * Factor;
         var PicCenterYOffSetZoomIn = PicCenterYOffSet * Factor;
         var NewOSX = Outwidth / 2.0 - PicCenterXOffSetZoomIn;
         var NewOSY = Outheight / 2.0 - PicCenterYOffSetZoomIn;
         Pic.style.left = (NewOSX) + 'px';
         Pic.style.top = (NewOSY) + 'px';
         Pic.style.width = (PicWidth * Factor) + 'px';
         Pic.style.height = (PicHeight * Factor) + 'px';
     }






     PanZoomClass.prototype.AppendChild = function (Child, X, Y, Len, Height) {

         var x = ((X - Len / 2.0)*100) + '%';
         var y = ((Y - Height / 2.0)*100) + '%';

         var width = (Len*100) + '%';
         var height = (Height*100) + '%';

         Child.style.position = 'absolute';
         Child.style.left = x;
         Child.style.top = y;
         Child.style.width = width;
         Child.style.height = height;


         var Picdiv = document.getElementById(this.InnerPanDivName);
         Picdiv.appendChild(Child);

     }

    



     PanZoomClass.prototype.AppendChildPixel = function (Child, CenterPixelX, CenterPixelY) {
         var width = Child.width;
         var height = Child.height;
         var x = (CenterPixelX - width / 2.0) + 'px';
         var y = (CenterPixelY - height / 2.0) + 'px';
         Child.style.position = 'absolute';

         width = width + 'px';
         height = height + 'px';
         Child.style.width = width;
         Child.style.height = height;
         Child.style.left = x;
         Child.style.top = y;
         var Picdiv = document.getElementById(this.InnerPanDivName);
       //  var Picdiv = document.getElementById("TestDiv");
         Picdiv.appendChild(Child);
     }


