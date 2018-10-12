<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<div class="con">
	<header class="dis-box header-menu b-color color-whie"  style="background-color:#4f743b"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
		<h3 class="box-flex">收货人信息</h3>
	</header>
	<div class="j-nav-content">

	</div>
	<section class="flow-consignee-list j-get-consignee-one select-three" id="J_ItemList">
		<li class="flow-checkout-adr m-top08 single_item "> </li>
		<a href="javascript:;" style="text-align:center" class="get_more"></a>
	</section>
	<div class="filter-btn dis-box">
		<a href="<?php echo url('user/add_address');?>" type="button" class="btn-submit box-flex n-iphone5-top1 j-goods-attr j-show-div"  style="background-color:#4f743b"><?php echo $this->_var['lang']['add_address']; ?></a>
	</div>
</div>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript" src="__PUBLIC__/js/jquery.more.js"></script>
<script type="text/javascript">
	get_asynclist('<?php echo url("user/address_list");?>', '__TPL__/images/loader.gif');
</script>
<script>
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