var app = new Vue({
    el: '#app',
    data: {
        sendCodeText: '发送验证码',
        code: '',
        phone: '',
        time: 60
    },
    methods: {
        sendCode: function () {
            //验证手机号
            if (!/^(13[0-9]|14[0-9]|15[0-9]|166|17[0-9]|18[0-9]|19[8|9])\d{8}$/.test(app.phone)) {
                mui.toast('请输入正确的手机号');
            }
            else {
                //是否已发送验证码时间未到
                if (app.time <= 0 || app.time == 60) {
                    //发送验证码
                    $.getJSON(getRootPath()+'/index.php?m=default&c=user&a=send_code', {phone: app.phone}, function (data) {
                       
                        if (data.code == 1) {
                            mui.toast('验证码发送成功!');
                            app.time = 60;
                            app.sendCodeText = app.time + 's重新发送';
                            var interval = setInterval(function () {
                                if (app.time == 1) {
                                    app.sendCodeText = '重新发送';
                                    clearInterval(interval);
                                }
                                else {
                                    app.sendCodeText = (--app.time) + 's重新发送';
                                }
                            }, 1000);
                        }
                        else {
                            mui.toast('验证码发送失败，请重试!');
                        }
                    });
                }
            }
        }
    }
});

//提交表单
function submitForm() {
    //验证验证码是否为6位
    if (app.code.trim().length != 4) {
        mui.toast('验证码输入错误');
    }
    else {
        //验证手机号和验证码是否匹配
        $.getJSON(getRootPath()+'/index.php?m=default&c=user&a=verify_code', {phone: app.phone, code: app.code}, function (data) {
            if (data.code == 1) {
                //通过
                 mui.toast('手机号码绑定成功');
                 window.location.reload();
            }
            else {
                //不通过
                mui.toast('验证码错误');
            }
        });
    }
}


//js获取项目根路径，如： http://localhost:8083/uimcardprj
function getRootPath(){
    //获取当前网址，如： http://localhost:8083/uimcardprj/share/meun.jsp
    var curWwwPath=window.document.location.href;
    //获取主机地址之后的目录，如： /uimcardprj/share/meun.jsp
    var pathName=window.document.location.pathname;
    var pos=curWwwPath.indexOf(pathName);
    //获取主机地址，如： http://localhost:8083
    var localhostPaht=curWwwPath.substring(0,pos);
    //获取带"/"的项目名，如：/uimcardprj
    var projectName=pathName.substring(0,pathName.substr(1).indexOf('/')+1);
    return(localhostPaht+projectName);
}

window.onload = function () {
}