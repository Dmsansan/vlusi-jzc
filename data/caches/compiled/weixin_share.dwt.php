<!DOCTYPE html> 
<html lang="en"> 
    <head>
<meta name="Generator" content="ECTouch 2.11.30" /> 
        <meta charset="UTF-8"> 
        <title>js微信自定义分享标题、链接和图标</title> 
         <meta name="keywords" content="js微信分享,php微信分享" /> 
        <meta name="description" content="PHP自定义微信分享内容，包括标题、图标、链接等，分享成功和取消有js回调函数。" /> 
    </head> 
    <body> 
 
    </body> 
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
    <script> 
        /* 
         * 注意： 
         * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
         * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。 
         * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html 
         * 
         * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈： 
         * 邮箱地址：weixin-open@qq.com 
         * 邮件主题：【微信JS-SDK反馈】具体问题 
         * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。 
         */ 
        wx.config({ 
            debug: true, 
            appId: '<?php echo $this->_var['signPackage']['appId']; ?>', 
            timestamp: '<?php echo $this->_var['signPackage']['timestamp']; ?>', 
            nonceStr: '<?php echo $this->_var['signPackage']['nonceStr']; ?>', 
            signature: '<?php echo $this->_var['signPackage']['signature']; ?>', 
            jsApiList: [ 
               'onMenuShareTimeline' 
            ] 
        }); 
        wx.ready(function() { 
            wx.onMenuShareTimeline({ 
                title: '二当家的', // 分享标题 
                link: 'http://www.erdangjiade.com/', // 分享链接 
                imgUrl: '', // 分享图标 
                success: function() { 
                    // 用户确认分享后执行的回调函数 
                }, 
                cancel: function() { 
                    // 用户取消分享后执行的回调函数 
                } 
            }); 
        }); 
    </script> 
    <p style="text-align: center;color:red;font-size:20px;margin-top: 120px">请用微信浏览器打开，并打开右上方按钮。分享到朋友圈试试。<?php echo $this->_var['signPackage']['appId']; ?></p> 
</html>