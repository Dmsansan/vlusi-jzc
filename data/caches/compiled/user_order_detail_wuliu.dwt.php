<!DOCTYPE html>
<html lang="en">
<head>
<meta name="Generator" content="ECTouch 2.11.30" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>物流信息</title>
    <link rel="stylesheet" href="__TPL__/bind/assets/mui/css/mui.min.css">
    <link rel="stylesheet" href="__TPL__/bind/css/logistics-info.css">
    <link rel="stylesheet" href="__TPL__/bind/css/common.css"/>
</head>
<body>
<div class="mui-content" id="app">
    <header class="mui-bar mui-bar-nav">
        <a href="javascript:;" onclick="history.go(-1);" class="mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">物流信息</h1>
    </header>
    <section class="main-content">
        <!--
         收货人信息
        -->
        <table class="address">
            <tr>
                <td class="position-img" rowspan="2">
                    <img src="__TPL__/bind/img/position.png"/>
                </td>
                <td nowrap>收货人</td>
                <td nowrap>{{userName}}</td>
                <td nowrap>{{userPhone}}</td>
            </tr>
            <tr>
                <td class="address-title" nowrap>收货地址</td>
                <td class="address-info" colspan="2">
                    {{userAddress}}
                </td>
            </tr>
        </table>
        <!--
            物流名称信息，运单号
        -->
        <div class="logistics">
            <img src="__TPL__/bind/img/position.png"/>
            <div>
                <p class="logistics-name">{{logisticsName}}</p>
                <p class="logistics-number">运单号：{{userOrderNum}}</p>
            </div>
        </div>
        <!--
            物流信息
        -->
        <ul>
            <li v-for="item in logisticsList">
                <p class="logistics-date">
                    <span class="logistics-date-date">{{item.ftime}}</span>
                    <span class="logistics-date-time">{{item.time}}</span>
                </p>
                <p>
                <template>
                    <i class="logistics-dot logistics-dot-success"></i>
                    <p class="logistics-detail">

                        <span class="logistics-detail-content">
                        {{item.context}}
                    </span>
                    </p>
                </template>
                </p>
            </li>
        </ul>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="__TPL__/bind/assets/mui/js/mui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17-beta.0/vue.min.js"></script>
<script src="__TPL__/bind/js/logistics-info.js" type="text/javascript"></script>
</body>
</html>