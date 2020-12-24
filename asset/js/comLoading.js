var comLoading= {};
comLoading.loadingHtml = '<div class="loading">'
    +'<span></span>'
    +'<span></span>'
    +'<span></span>'
    +'<span></span>'
    +'<span></span>'
    +'</div>';
comLoading.showLoading = function () {
    if ($(".ui-loadingk-screen").length == 0) {
        $(document.body).append(comLoading.loadingHtml);
    }
    var screen = $(".ui-loadingk-screen"), loading = $(".loadingcontainer");
    if (window.screen.height > ($(document.body).height() + $(window).scrollTop())) {
        screen.height(window.screen.height);
    } else {
        screen.height($(document.body).height() + $(window).scrollTop());
    }
    screen.show();
    loading.show();
    loading.css("left", $(document.body).width() * 0.3);
    loading.css("top", $(window).scrollTop() + $(window).height() / 2 - loading.height() / 2);

}

comLoading.hideLoading = function () {
    $(".ui-loadingk-screen").hide();
    $(".loadingcontainer").hide();
}




