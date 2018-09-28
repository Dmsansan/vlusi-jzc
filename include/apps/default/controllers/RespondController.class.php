<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：RespondController.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：ECTOUCH 支付应答控制器
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */

/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class RespondController extends CommonController
{

    private $data;

    public function __construct()
    {
        parent::__construct();
        // 获取参数
        $this->data = array(
            'code' => I('get.code'),
            'type' => I('get.type')
        );
	}

    // 发送
    public function index()
    {
        /* 判断是否启用 */
        $condition['pay_code'] = $this->data['code'];
        $condition['enabled'] = 1;
        $enabled = $this->model->table('payment')->where($condition)->count();
        if ($enabled == 0) {
            $msg = L('pay_disabled');
        } else {
            $plugin_file = ADDONS_PATH.'payment/' . $this->data['code'] . '.php';
            if (file_exists($plugin_file)) {
                include_once($plugin_file);
                $payobj = new $this->data['code']();
                // 处理异步请求
                if($this->data['type'] == 'notify'){
                    @$payobj->notify($this->data);
                }
                $msg = (@$payobj->callback($this->data)) ? L('pay_success') : L('pay_fail');
            } else {
                $msg = L('pay_not_exist');
            }
        }
        //显示页面
        $this->assign('message', $msg);
        $this->assign('shop_url', __URL__);
        $this->display('respond.dwt');
    }
}