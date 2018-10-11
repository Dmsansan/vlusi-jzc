<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="format-detection" content="telephone=no">
<title><?php echo $this->_var['page_title']; ?></title>
<link rel="stylesheet" href="__TPL__/css/member.css">
<link rel="stylesheet" href="__PUBLIC__/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="__PUBLIC__/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $this->_var['ecs_css_path']; ?>">
<link rel="stylesheet" href="__TPL__/css/user.css">
<link rel="stylesheet" href="__TPL__/css/photoswipe.css">
<link rel="stylesheet" href="__TPL__/statics/css/search.css" />
<script type="text/javascript" >var tpl = '__TPL__';</script>
</head>

<body>
<div class="con">
<div class="ect-bg">
  <header class="ect-header ect-margin-tb ect-margin-lr text-center ect-bg icon-write"> <a href="<?php if ($this->_var['title'] != '消息中心'): ?> javascript:history.go(-1) <?php else: ?><?php echo url('user/index');?><?php endif; ?>" class="pull-left ect-icon ect-icon1 ect-icon-history"></a> <span><?php echo $this->_var['title']; ?></span> <a href="javascript:;" onClick="openMune()" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
  <nav class="ect-nav ect-nav-list" style="display:none;">
    <ul class="ect-diaplay-box text-center">
      <li class="ect-box-flex"><a href="<?php echo url('index/index');?>"><i class="ect-icon ect-icon-home"></i><?php echo $this->_var['lang']['home']; ?></a></li>
      <li class="ect-box-flex"><a href="<?php echo url('category/top_all');?>"><i class="ect-icon ect-icon-cate"></i><?php echo $this->_var['lang']['category']; ?></a></li>
      <li class="ect-box-flex"><a href="javascript:;" class="j-search-input-new"><i class="ect-icon ect-icon-search"></i><?php echo $this->_var['lang']['search']; ?></a></li>
      <li class="ect-box-flex"><a href="<?php echo url('flow/cart');?>"><i class="ect-icon ect-icon-flow"></i><?php echo $this->_var['lang']['shopping_cart']; ?></a></li>
      <li class="ect-box-flex"><a href="<?php echo url('user/index');?>"><i class="ect-icon ect-icon-user"></i><?php echo $this->_var['lang']['user_center']; ?></a></li>
    </ul>
  </nav>
</div>
