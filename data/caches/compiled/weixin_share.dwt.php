<!DOCTYPE html>
<html lang="en">
<head>
<meta name="Generator" content="ECTouch 2.11.30" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>分享商品信息</title>   
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    分享商品详情
</body>
<script>
$(function(){
    console.log('<?php echo $this->_var['signPackage']['appId']; ?>');
    wx.config({
                    debug: false,
                    appId: '<?php echo $this->_var['signPackage']['appId']; ?>',
                    timestamp: '<?php echo $this->_var['signPackage']['timestamp']; ?>',
                    nonceStr: '<?php echo $this->_var['signPackage']['nonceStr']; ?>',
                    signature: '<?php echo $this->_var['signPackage']['signature']; ?>',
                    jsApiList: [
                    // 所有要调用的 API 都要加到这个列表中
                         'onMenuShareTimeline',
                         'onMenuShareAppMessage'
                  ]
                });
                 
                 
            wx.ready(function(){
                     wx.onMenuShareTimeline({
                        title:'看了我的购物车，马云都给跪了！', // 分享标题
                        link: '', // 分享链接
                        imgUrl: 'http://www.flyinjoy.com/wxcallback/imges/LOGO.jpg', // 分享图标
                        desc:'扫扫二维码，微信关注“飞享购”，你也可以晒出属于你的购物车！',
                        success: function () {
                            // 用户确认分享后执行的回调函数
                            alert("分享成功");
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                     wx.onMenuShareAppMessage({
                        title: '看了我的购物车，马云都给跪了！', // 分享标题
                        desc: '扫扫二维码，微信关注“飞享购”，你也可以晒出属于你的购物车！', // 分享描述
                        link: '', // 分享链接
                        imgUrl: 'http://www.flyinjoy.com/wxcallback/imges/LOGO.jpg', // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            // 用户确认分享后执行的回调函数
                            alert("分享成功");
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                     
         
    })

})
</script>

</html>