var app = new Vue({
    el: '#app',
    data: {
        userName: '张益达',
        userPhone: 13524789511,
        userAddress: '下水道',
        logisticsName: '顺丰',
        userOrderNum: 2135478962342,
        logisticsList: [
            {
                date: '06-15',
                time: '15:22',
                status: '已发货',
                description: '订单已发货',
                code:1
            },
            {
                date: '06-15',
                time: '15:22',
                status: '包裹异常',
                description: '包裹异常，正在处理',
                code:0
            },
            {
                date: '06-15',
                time: '15:22',
                status: '已发货',
                description: '订单已发货',
                code:1
            },
            {
                date: '06-15',
                time: '15:22',
                status: '已发货',
                description: '订单已发货哒哒哒哒哒哒多多多多多多多多多多多多多多多多多多多多多多多多',
                code:1
            },
            {
                date: '06-15',
                time: '15:22',
                status: '已发货',
                description: '订单已发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊',
                code:1
            },
            {
                date: '06-15',
                time: '15:22',
                status: '派送中',
                description: '派送中对方考虑的积分离开对方考虑对方的立刻分开了的纠纷',
                code:1
            },
            {
                date: '06-15',
                time: '15:22',
                status: '已收货',
                description: '已收货，自提柜提货',
                code:2
            }
        ]
    },
    methods: {}
});
/**
 * 数组逆向排序
 */
app.logisticsList.reverse();