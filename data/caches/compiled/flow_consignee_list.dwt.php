<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">收货人信息</h3>
	<p><i class="iconfont icon-pailie j-nav-box"></i></p>
</header>
<div class="j-nav-content">
	<ul class="dis-box new-footer-box">
		<li class="box-flex">
			<a href="<?php echo url('index/index');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/home.png"></i><span>首页</span></a>
		</li>
		<li class="box-flex">
			<a href="<?php echo url('category/top_all');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/cate.png"></i><span>分类</span></a>
			<li class="box-flex"><a href="javascript:;" class="nav-cont j-search-input"><i class="nav-box"><img src="__TPL__/statics/img/search.png"></i><span>搜索</span></a></li>
			<li class="box-flex"><a href="<?php echo url('flow/cart');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/flow.png"></i><span>购物车</span></a></li>
			<li class="box-flex"><a href="<?php echo url('user/index');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/user.png"></i><span>用户中心</span></a></li>
	</ul>
</div>
<section class="flow-consignee-list j-get-consignee-one select-three" id="J_ItemList">
	<li class="flow-checkout-adr m-top08 single_item "> </li>
	<a href="javascript:;" style="text-align:center" class="get_more"></a>
</section>
<div class="filter-btn dis-box">
	<a href="<?php echo url('flow/consignee');?>" type="button" class="btn-submit box-flex n-iphone5-top1 j-goods-attr j-show-div"><?php echo $this->_var['lang']['add_address']; ?></a>
</div>

</div>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript" src="__PUBLIC__/js/jquery.more.js"></script>
<script type="text/javascript">
	get_asynclist('index.php?m=default&c=flow&a=consignee_list', '__TPL__/images/loader.gif');
</script>
<script>
	
	/*设置默认收货地址*/
	function flow_edit_address_info(address_id) {
		var url = 'index.php?m=default&c=flow&a=edit_address_info';
		$.get(url, {'id':address_id}, function(data){
			if (1 == data.status) {							
				window.location.href = 'index.php?m=default&c=flow&a=checkout';							
			}
		}, 'json');
		return false;

	}
	
    function getLocation(){
	    var options={
		    enableHighAccuracy:true, 
		    maximumAge:1000
	    }
	    if(navigator.geolocation){
		    navigator.geolocation.getCurrentPosition(onSuccess,onError,options);		   
	    }else{
		    onError();
	    }
   }
    //成功时
    function onSuccess(position){
	    //返回用户位置	   
	    var longitude =position.coords.longitude; //经度	    
	    var latitude = position.coords.latitude;//纬度
		
		$.post('<?php echo url("user/positions");?>', {
            lng: longitude,
            lat: latitude
        }, function (data) {
            if (data.error == 0) {
                window.location.href = data.url;
            } else {
                alert(data.message);
            }
        }, 'json');
	   }
    //失败时
    function onError(error){
	   switch(error.code){
		   case 1:
		   alert("位置服务被拒绝");
		   break;


		   case 2:
		   alert("暂时获取不到位置信息");
		   break;


		   case 3:
		   alert("获取信息超时");
		   break;


		   case 4:
			alert("未知错误");
		   break;
	   }


    }

	getLocation();
 </script>
</body>

</html>