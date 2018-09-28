$(function(){
	$("#dpg1_list1 a").click(function(){ 
		$(this).next(".index_nav").slideToggle("slow").siblings(".div3:visible").slideUp("slow");
	});
});

/**
 * 我的商品
 * @param id
 */
function select_cate(id){
    if($("#cate_"+id).hasClass("sp_box1")){
        $("#input_"+id).prop("checked",true);
        $("#cate_"+id).removeClass("sp_box1").addClass("sp_box2");
    }else{
        $("#input_"+id).prop("checked",false);
        $("#cate_"+id).removeClass("sp_box2").addClass("sp_box1");
    }

}
function set_my_goods(){
    var id_arr = 0;
    $('input[name="cate[]"]:checked').each(function(){
        id_arr++;
    });
    if(id_arr > 0){
        $("#my_goods").submit();
    }else{
        alert('请选择分销分类');
    }
}
function check_all(){
    if($("#checkAll").is(":checked")==true){
        $(".elecheck").prop("checked",true);
        $(".check").removeClass("sp_box1").addClass("sp_box2");

    }else{
        $(".elecheck").prop("checked",false);
        $(".check").removeClass("sp_box2").addClass("sp_box1");

    }

}

// 关闭指定class
function close_class(id){
    $("."+id).hide();
}

/**
 * 申请开店
 * @returns {Boolean}
 */
function submit_saleSet() {

    var shop_name	=	$("#shop_name").val();
    var real_name	=	$("#real_name").val();
    var shop_mobile	=	$("#shop_mobile").val();
    var msg			=	'';
    regPartton		=	/1[3-8]+\d{9}/;
	if(!shop_mobile || shop_mobile==null){
		msg += "手机号码不能为空！\n";
	}else if(!regPartton.test(shop_mobile) || shop_mobile.length != 11){
		msg += "手机号码格式不正确！\n";
	}
    if(!shop_name || shop_name==null){
        msg += "店铺名称不能为空！\n";
    }
    if(!real_name || real_name==null){
        msg += "真实姓名不能为空！\n";
    }
    if (msg.length > 0) {
        alert(msg);
        return false;
    }else{
        return true;
    }
}