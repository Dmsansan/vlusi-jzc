<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：IndexController.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：ECTouch分销店铺控制器
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class StoreController extends CommonController {

    /**
     * 首页信息
     */
    public function index() {
        $drp_shop = $this->check();
        $this->assign('drp_info', $drp_shop);
        $this->assign('news_goods_num',model('Index')->get_pro_goods('new'));
        $this->assign('promotion_goods_num', count(model('Index')->get_promote_goods()));
        $cat_rec = model('Index')->get_recommend_res(10,4);
        $this->assign('cat_best', $cat_rec[1]);
        $this->assign('cat_new', $cat_rec[2]);
        $this->assign('cat_hot', $cat_rec[3]);
        $this->assign('is_drp', 1);
        $this->display('sale_shop.dwt');
    }

    /**
     * 检测店铺权限
     */
    private function check(){
        if (isset($_GET['drp_id'])) {
            $condition = array('id' => I('drp_id', 0, 'intval'));
        }else{
            $condition = array('user_id'=> I('u', 0, 'intval'));
        }
        $condition['audit'] = 1;
        $condition['open'] = 1;
        $shop_info = $this->model->table('drp_shop')->where($condition)->find();
        if(empty($shop_info)){
            show_message('店铺已关闭或等待审核中', '进入商城', url('index/index'));
        }
        $wechat_user = $this->model->table('wechat_user')->where(array('ect_uid'=>$shop_info['user_id']))->field('headimgurl')->find();
        $shop_info['shop_img'] = empty($shop_info['shop_img']) ? '': __ROOT__ . '/data/attached/drp_logo/'.$shop_info['shop_img'];
        $shop_info['headimgurl'] = empty($wechat_user['headimgurl']) ? __ROOT__ . '/data/attached/images/get_avatar.png':$wechat_user['headimgurl'];
        $_SESSION['drp_shop'] = $shop_info;
        $_SESSION['drp_shop']['drp_id'] = $shop_info['id'];
        return $_SESSION['drp_shop'];
    }

    /**
     * 检测是否拥有自己的小店
     */
    public function check_store(){
        if($_SESSION['user_id']){
            $drp_id = $this->model->table('drp_shop')->where(array('user_id'=>$_SESSION['user_id']))->field('id')->find();
            if(!$drp_id){
                redirect(url('sale/set'));
            }else{
                redirect(url('store/index'));
            }
        }else{
            redirect(url('user/login'));
        }
    }
}
