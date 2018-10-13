var app = new Vue({
    el: '#app',
    data: {
        userName: '',
        userPhone: '',
        userAddress: '',
        logisticsName: '',
        userOrderNum: '',
        logisticsList: ''
    },
    methods: {
        loadData: function () {
            var self = this;
            $.getJSON(getRootPath()+'/index.php?m=default&c=user&a=get_order_detail_wuliu&order_id='+getParam('order_id'), function (data) {
                console.log(data);
                if(!data.res){
                    mui.toast('获取订单信息失败！');
                }
                if(data.wuliu.status != 200){
                    mui.toast('获取快递物流信息失败！');
                }
                if(data.res && data.wuliu.status == 200){
                    self.userName = data.res.consignee;
                    self.userPhone = data.res.mobile;
                    self.userAddress = data.res.address;
                    self.logisticsName = data.res.shipping_name;
                    self.userOrderNum = data.res.kuaidi_sn;
                    self.logisticsList = data.wuliu.data;
                }
            });
        }
    }
});
app.loadData();

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

/**
 * 获取指定的URL参数值
 * URL:http://www.quwan.com/index?name=tyler
 * 参数：paramName URL参数
 * 调用方法:getParam("name")
 * 返回值:tyler
 */
function getParam(paramName) {
    paramValue = "", isFound = !1;
    if (this.location.search.indexOf("?") == 0 && this.location.search.indexOf("=") > 1) {
        arrSource = unescape(this.location.search).substring(1, this.location.search.length).split("&"), i = 0;
        while (i < arrSource.length && !isFound) arrSource[i].indexOf("=") > 0 && arrSource[i].split("=")[0].toLowerCase() == paramName.toLowerCase() && (paramValue = arrSource[i].split("=")[1], isFound = !0), i++
    }
    return paramValue == "" && (paramValue = null), paramValue
}