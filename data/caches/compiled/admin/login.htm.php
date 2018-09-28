<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $this->_var['lang']['cp_home']; ?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="ECTouch Team">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Bootstrap core CSS -->
<link href="__TPL__/css/bootstrap.min.css" rel="stylesheet">
<link href="__TPL__/css/bootstrap-reset.css" rel="stylesheet">
<link href="__PUBLIC__/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="__TPL__/css/style.css" rel="stylesheet">
<link href="__TPL__/css/style-responsive.css" rel="stylesheet" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
<!--[if lt IE 9]>
<script src="__TPL__/js/html5shiv.js"></script>
<script src="__TPL__/js/respond.min.js"></script>
<![endif]-->
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,validator.js')); ?>
<script language="JavaScript">
<!--
// 这里把JS用到的所有语言都赋值到这里
<?php $_from = $this->_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

if (window.parent != window)
{
  window.top.location.href = location.href;
}

//-->
</script>
</head>

<body>
<div class="login-bg">
  <header>
    <div class="login-header"> <?php echo $this->_var['lang']['cp_home']; ?> </div>
  </header>
  <section>
    <form method="post" action="privilege.php" name='theForm' onsubmit="return validate()">
      <div class="login-con">
        <input name="username" type="text" placeholder="<?php echo $this->_var['lang']['label_username']; ?>">
        <input name="password" type="password" placeholder="<?php echo $this->_var['lang']['label_password']; ?>" class="login_password">
        <?php if ($this->_var['gd_version'] > 0): ?>
        <div class="login-con-code">
          <input name="captcha" type="text" placeholder="<?php echo $this->_var['lang']['label_captcha']; ?>">
          <img src="index.php?act=captcha&<?php echo $this->_var['random']; ?>" onclick="this.src='index.php?act=captcha&'+Math.random();" title="<?php echo $this->_var['lang']['click_for_another']; ?>" class="cursor" />
        </div>
        <?php endif; ?>
        <div style="overflow:hidden;">
          <div class="checkboxes pull-left">
            <label class="label_check" for="checkbox-06">
              <input name="remember" id="checkbox-06" value="1" type="checkbox"> <?php echo $this->_var['lang']['remember']; ?>
            </label>
          </div>
          <span class="pull-right"><a href="get_password.php?act=forget_pwd"><?php echo $this->_var['lang']['forget_pwd']; ?></a></span>
        </div>
        <button class="btn ect-btn-login ect-clear" onClick="this.submit"><?php echo $this->_var['lang']['signin_now']; ?></button>
      </div>
      <input type="hidden" name="act" value="signin" />
    </form>
  </section>
</div>
<footer>
  <div class="login-footer"><?php echo $this->_var['lang']['copyright']; ?></div>
</footer>
<div class="passport-bg"></div>
<script language="JavaScript">
<!--
  document.forms['theForm'].elements['username'].focus();
  
  /**
   * 检查表单输入的内容
   */
  function validate()
  {
    var validator = new Validator('theForm');
    validator.required('username', user_name_empty);
    //validator.required('password', password_empty);
    if (document.forms['theForm'].elements['captcha'])
    {
      validator.required('captcha', captcha_empty);
    }
    return validator.passed();
  }
  
//-->
</script>
</body>
</html>