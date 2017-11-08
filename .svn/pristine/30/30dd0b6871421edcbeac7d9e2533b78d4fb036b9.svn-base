var onBridgeReady = function () {
    var appId = '';
    var title = $('#share').text();
    var link = window.location.href;
    var imgUrl = 'http://weixin.chetuobang.com/weixinlukuang/public/images/4.0/icon-spread.jpg';
    var desc = $('#desc').text();

    // 发送给好友;
    WeixinJSBridge.on('menu:share:appmessage', function(argv) {
        if (($('#fix-detail').css('display') !== 'none') && $('#fix-detail').children()[0]) {
            desc = $('#desc').text();
            (desc = desc.slice(0, 32)) && (desc = desc + '...');
            title = $('#share').text();
            imgUrl = $('header .box-img').find('img').attr('src') || 'http://weixin.chetuobang.com/weixinlukuang/public/images/4.0/icon-spread.jpg';
        }
        WeixinJSBridge.invoke('sendAppMessage', {
            "img_url" : imgUrl,
            "img_width" : "60",
            "img_height" : "60",
            "link" : link,
            "desc" : desc,
            "title" : title
        }, function(res) {
        });
    });


    // 分享到朋友圈;
    WeixinJSBridge.on('menu:share:timeline', function(argv){
        if (($('#fix-detail').css('display') !== 'none') && $('#fix-detail').children()[0]) {
            desc = $('#desc').text();
            (desc = desc.slice(0, 32)) && (desc = desc + '...');
            title = $('#share').text();
            imgUrl = $('header .box-img').find('img').attr('src') || 'http://weixin.chetuobang.com/weixinlukuang/public/images/4.0/icon-spread.jpg';
        }
        WeixinJSBridge.invoke('shareTimeline', {
            "img_url" : imgUrl,
            "img_width" : "60",
            "img_height" : "60",
            "link" : link,
            "desc" : desc,
            "title" : desc
        }, function(res) {
        });
    });

    // 分享到微博;
    var weiboContent = '';
    WeixinJSBridge.on('menu:share:weibo', function(argv) {
        WeixinJSBridge.invoke('shareWeibo',{
        "content" : '微信新功能，用您的微信，关注“微信路况”这一公众账号，即可通过微信免费查询实时路况，并定制专属自己的路况报告。' + link,
        "url" : link
        }, function(res) {
        });
    });
    // 隐藏右上角的选项菜单入口;
    //WeixinJSBridge.call('hideOptionMenu');
};


if(document.addEventListener){
    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
} 
else if(document.attachEvent){
    document.attachEvent('WeixinJSBridgeReady' , onBridgeReady);
    document.attachEvent('onWeixinJSBridgeReady' , onBridgeReady);
}



