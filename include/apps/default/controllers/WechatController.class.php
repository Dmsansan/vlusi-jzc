<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：WechatControoller.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：微信公众平台API
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class WechatController extends CommonController
{

    private $weObj = '';

    private $orgid = '';

    private $wechat_id = '';

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();

        // 获取公众号配置
        $this->orgid = I('get.orgid');
        if (! empty($this->orgid)) {
            $wxinfo = $this->get_config($this->orgid);

            $config['token'] = $wxinfo['token'];
            $config['appid'] = $wxinfo['appid'];
            $config['appsecret'] = $wxinfo['appsecret'];
            $this->weObj = new Wechat($config);
            $this->weObj->valid();
            $this->wechat_id = $wxinfo['id'];
        }
    }

    /**
     * 执行方法
     */
    public function index()
    {
        // 事件类型
        $type = $this->weObj->getRev()->getRevType();
        $wedata = $this->weObj->getRev()->getRevData();
        $keywords = '';
        if ($type == Wechat::MSGTYPE_TEXT) {
            $keywords = $wedata['Content'];
        } elseif ($type == Wechat::MSGTYPE_EVENT) {
            if ('subscribe' == $wedata['Event']) {
                // 用户扫描带参数二维码(未关注)
                if (isset($wedata['Ticket']) && ! empty($wedata['Ticket'])) {
                    $scene_id = $this->weObj->getRevSceneId();
                    $flag = true;
                    // 关注
                    $this->subscribe($wedata['FromUserName'], $scene_id);
                }
                else{
                    // 关注
                    $this->subscribe($wedata['FromUserName']);
                    // 关注时回复信息
                    $this->msg_reply('subscribe');
                    exit;
                }
            } elseif ('unsubscribe' == $wedata['Event']) {
                // 取消关注
                $this->unsubscribe($wedata['FromUserName']);
                exit();
            } elseif ('MASSSENDJOBFINISH' == $wedata['Event']) {
                // 群发结果
                $data['status'] = $wedata['Status'];
                $data['totalcount'] = $wedata['TotalCount'];
                $data['filtercount'] = $wedata['FilterCount'];
                $data['sentcount'] = $wedata['SentCount'];
                $data['errorcount'] = $wedata['ErrorCount'];
                // 更新群发结果
                $this->model->table('wechat_mass_history')
                    ->data($data)
                    ->where('msg_id = "' . $wedata['MsgID'] . '"')
                    ->update();
                exit();
            } elseif ('CLICK' == $wedata['Event']) {
                // 点击菜单
                $keywords = $wedata['EventKey'];
            } elseif ('VIEW' == $wedata['Event']) {
                $this->redirect($wedata['EventKey']);
            } elseif ('SCAN' == $wedata['Event']) {
                $scene_id = $this->weObj->getRevSceneId();
            }
        } else {
            // $this->msg_reply('msg');
            exit();
        }
        //扫描二维码
        if(!empty($scene_id)){
            $qrcode_fun = $this->model->table('wechat_qrcode')->field('function')->where('scene_id = "'.$scene_id.'"')->getOne();
            //扫码引荐
            if(!empty($qrcode_fun) && isset($flag)){
                //增加扫描量
                $this->model->table('wechat_qrcode')->data('scan_num = scan_num + 1')->where('scene_id = "'.$scene_id.'"')->update();
            }
            $keywords = $qrcode_fun;
        }
        // 回复
        if (! empty($keywords)) {
            $keywords = html_in($keywords);
            //记录用户操作信息
            $this->record_msg($wedata['FromUserName'], $keywords);
            // 多客服
            $rs = $this->customer_service($wedata['FromUserName'], $keywords);
            if (empty($rs) && $keywords != 'kefu') {
                // 功能插件
                $rs1 = $this->get_function($wedata['FromUserName'], $keywords);
                if (empty($rs1)) {
                    // 关键词回复
                    $rs2 = $this->keywords_reply($keywords);
                    if (empty($rs2)) {
                      // 消息自动回复
                      // $this->msg_reply('msg');
                      //推荐商品
                      // $rs_rec = $this->recommend_goods($wedata['FromUserName'], $keywords);
                    }
                }
            }
        }
    }

    /**
     * 关注处理
     *
     * @param array $info
     */
    private function subscribe($openid = '', $scene_id = 0)
    {
        if(empty($openid)){
            exit('null');
        }

        // 获取微信用户信息
        $info = $this->weObj->getUserInfo($openid);
        if (empty($info)) {
            $this->weObj->resetAuth();
            exit('null');
        } else {
            $data = array(
                'subscribe' => $info['subscribe'],
                'openid' => $info['openid'],
                'nickname' => $info['nickname'],
                'sex' => $info['sex'],
                'city' => $info['city'],
                'country' => $info['country'],
                'province' => $info['province'],
                'language' => $info['country'],
                'headimgurl' => $info['headimgurl'],
                'subscribe_time' => $info['subscribe_time'],
                'unionid' => isset($info['unionid']) ? $info['unionid'] : '',
                'remark' => $info['remark'],
                'group_id' => isset($info['groupid']) ? $info['groupid'] : $this->weObj->getUserGroup($openid),
                'wechat_id' => $this->wechat_id
            );
        }

        // 判断是否注册为微信粉丝（如果是，更新资料；如果不是，新增粉丝）
        $condition = array('openid'=>$data['openid']);
        $userinfo = $this->model->table('wechat_user')->where($condition)->find();
        $pc_userinfo = array();
        // 是否为微信新用户
        if (empty($userinfo)) {
            // 是否申请开放平台，判断是否有pc和app端的用户信息
            if(!empty($data['unionid'])){
                $pc_condition = array('aite_id'=>'wechat_'.$data['unionid']);
                $pc_userinfo = $this->model->table('users')->where($pc_condition)->find();
            }
            // 是否已注册pc或app账户
            if (empty($pc_userinfo)){
                // 设置的用户注册信息
                $reg_condition = array(
                    'enable' => 1,
                    'command' => 'register_remind',
                    'register_remind' => $this->wechat_id
                );
                $reg_config = $this->model->table('wechat_extend')->field('config')->where($this->wechat_id)->find();
                if (! empty($reg_config)) {
                    $reg_config = unserialize($reg_config['config']);
                    $username = msubstr($reg_config['user_pre'], 3, 0, 'utf-8', false) . time() . mt_rand(100, 999);
                    $pwd_rand = array();
                    $arr_rand = range(0, 9);
                    $reg_config['pwd_rand'] = $reg_config['pwd_rand'] ? $reg_config['pwd_rand'] : 6;
                    for ($i = 0; $i < $reg_config['pwd_rand']; $i ++) {
                        $pwd_rand[] = array_rand($arr_rand);
                    }
                    $pwd_rand = implode('', $pwd_rand);
                    $password = $reg_config['pwd_pre'] . $pwd_rand;
                    // 通知模版
                    $template = str_replace(array('[$username]', '[$password]'), array($username, $password), $reg_config['template']);
                } else {
                    $username = time() . mt_rand(1000, 9999);
                    $password = '123456aA';
                    // 通知模版
                    $template = '默认用户名：' . $username . "\r\n" . '默认密码：' . $password;
                }
                // 用户注册
                $scene_id = empty($scene_id) ? 0 : $scene_id;
                $scene_user_id = $this->model->table("users")->field('user_id')->where(array('user_id'=>$scene_id))->getOne();
                $scene_user_id = empty($scene_user_id) ? 0 : $scene_user_id;
                $domain = get_top_domain();
                if (model('Users')->register($username, $password, $username . '@' . $domain, array('parent_id'=>$scene_user_id, 'aite_id'=>'wechat_'.$data['unionid'])) !== false) {
                    model('Users')->update_user_info();
                } else {
                    exit('null');
                }
                $data['ect_uid'] = $_SESSION['user_id'];
            } else {
                $data['ect_uid'] = $pc_userinfo['user_id'];
            }
            // 新增微信粉丝
            $this->model->table('wechat_user')->data($data)->insert();
            // 关注送红包
            $bonus_msg = $this->send_message($openid, 'bonus', $this->weObj, 1);
            if (! empty($bonus_msg)) {
                $template = $template . "\r\n" . $bonus_msg['content'];
            }
            // 微信端发送消息
            if(!empty($template)){
                $msg = array(
                    'touser' => $openid,
                    'msgtype' => 'text',
                    'text' => array(
                        'content' => $template
                    )
                );
                $this->weObj->sendCustomMessage($msg);
                //记录用户操作信息
                $this->record_msg($openid, $template, 1);
            }
            /*DRP_START*/
            /* 下线扫码关注成功，给上级发送消息提示*/
            if($scene_user_id > 0){
                $time = date("Y-m-d H:i:s");
                $openid = $this->model->table("wechat_user")->field('openid')->where(array('ect_uid'=>$scene_user_id))->getOne();
                $template = '您有新下线加入'. "\r\n" . '微信昵称：' . $data['nickname'] . "\r\n" . '加入时间：' .$time ;
                // 微信端发送消息
                $msg = array(
                    'touser' => $openid,
                    'msgtype' => 'text',
                    'text' => array(
                        'content' => $template 
                    )
                );
                $this->weObj->sendCustomMessage($msg);
                //记录用户操作信息
                $this->record_msg($openid, $template , 1);
            }
            /*DRP_END*/
        } else {
            $template = $data['nickname'] .  '，欢迎您再次回来';
            // 更新微信粉丝
            $this->model->table('wechat_user')->data($data)->where($condition)->update();
            // 是否申请开放平台
            if(!empty($data['unionid'])){
                $pc_data = array('aite_id'=>'wechat_'.$data['unionid']);
                $pc_condition = array('user_id' => $userinfo['ect_uid']);
                $this->model->table('users')->data($pc_data)->where($pc_condition)->update();
            }
            // 先授权登录后再关注送红包
            $bonus_num = model('Base')->model->table('user_bonus')->where('user_id = "'.$userinfo['ect_uid'].'"')->count();
            if($bonus_num <= 0){
                $bonus_msg = $this->send_message($openid, 'bonus', $this->weObj, 1);
                if (! empty($bonus_msg)) {
                    $template = $template . "\r\n" . $bonus_msg['content'];
                }
            }
            // 微信端发送消息
            $msg = array(
                'touser' => $openid,
                'msgtype' => 'text',
                'text' => array(
                    'content' => $template
                )
            );
            $this->weObj->sendCustomMessage($msg);
            //记录用户操作信息
            $this->record_msg($openid, $template, 1);
        }
    }

    /**
     * 取消关注
     *
     * @param string $openid
     */
    public function unsubscribe($openid = '')
    {
        // 未关注
        $where['openid'] = $openid;
        $rs = $this->model->table('wechat_user')
            ->where($where)
            ->count();
        // 修改关注状态
        if ($rs > 0) {
            $data['subscribe'] = 0;
            $this->model->table('wechat_user')
                ->data($data)
                ->where($where)
                ->update();
        }
    }

    /**
     * 被动关注，消息回复
     *
     * @param string $type
     * @param string $return
     */
    private function msg_reply($type, $return = 0)
    {
        $replyInfo = $this->model->table('wechat_reply')
            ->field('content, media_id')
            ->where('type = "' . $type . '" and wechat_id = ' . $this->wechat_id)
            ->find();
        if (! empty($replyInfo)) {
            if (! empty($replyInfo['media_id'])) {
                $replyInfo['media'] = $this->model->table('wechat_media')
                    ->field('title, content, file, type, file_name')
                    ->where('id = ' . $replyInfo['media_id'])
                    ->find();
                if ($replyInfo['media']['type'] == 'news') {
                    $replyInfo['media']['type'] = 'image';
                }
                // 上传多媒体文件
                $rs = $this->weObj->uploadMedia(array(
                    'media' => '@' . ROOT_PATH . $replyInfo['media']['file']
                ), $replyInfo['media']['type']);

                // 回复数据重组
                if ($rs['type'] == 'image' || $rs['type'] == 'voice') {
                    $replyData = array(
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => array(
                            'MediaId' => $rs['media_id']
                        )
                    );
                } elseif ('video' == $rs['type']) {
                    $replyData = array(
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => array(
                            'MediaId' => $rs['media_id'],
                            'Title' => $replyInfo['media']['title'],
                            'Description' => strip_tags($replyInfo['media']['content'])
                        )
                    );
                }
                $this->weObj->reply($replyData);
                //记录用户操作信息
                $this->record_msg($this->weObj->getRev()->getRevTo(), '图文信息', 1);
            } else {
                // 文本回复
                $replyInfo['content'] = html_out($replyInfo['content']);
                if($replyInfo['content']){
                    $this->weObj->text($replyInfo['content'])->reply();
                    //记录用户操作信息
                    $this->record_msg($this->weObj->getRev()->getRevTo(), $replyInfo['content'], 1);
                }
            }
        }
    }

    /**
     * 关键词回复
     *
     * @param string $keywords
     * @return boolean
     */
    private function keywords_reply($keywords)
    {
        $endrs = false;
        $sql = 'SELECT r.content, r.media_id, r.reply_type FROM ' . $this->model->pre . 'wechat_reply r LEFT JOIN ' . $this->model->pre . 'wechat_rule_keywords k ON r.id = k.rid WHERE k.rule_keywords = "' . $keywords . '" and r.wechat_id = ' . $this->wechat_id . ' order by r.add_time desc LIMIT 1';
        $result = $this->model->query($sql);
        if (! empty($result)) {
            // 素材回复
            if (! empty($result[0]['media_id'])) {
                $mediaInfo = $this->model->table('wechat_media')
                    ->field('id, title, content, digest, file, type, file_name, article_id, link')
                    ->where('id = ' . $result[0]['media_id'])
                    ->find();

                // 回复数据重组
                if ($result[0]['reply_type'] == 'image' || $result[0]['reply_type'] == 'voice') {
                    // 上传多媒体文件
                    $rs = $this->weObj->uploadMedia(array(
                        'media' => '@' . ROOT_PATH . $mediaInfo['file']
                    ), $result[0]['reply_type']);

                    $replyData = array(
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => array(
                            'MediaId' => $rs['media_id']
                        )
                    );
                    // 回复
                    $this->weObj->reply($replyData);
                    $endrs = true;
                } elseif ('video' == $result[0]['reply_type']) {
                    // 上传多媒体文件
                    $rs = $this->weObj->uploadMedia(array(
                        'media' => '@' . ROOT_PATH . $mediaInfo['file']
                    ), $result[0]['reply_type']);

                    $replyData = array(
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => array(
                            'MediaId' => $rs['media_id'],
                            'Title' => $replyInfo['media']['title'],
                            'Description' => strip_tags($replyInfo['media']['content'])
                        )
                    );
                    // 回复
                    $this->weObj->reply($replyData);
                    $endrs = true;
                } elseif ('news' == $result[0]['reply_type']) {
                    // 图文素材
                    $articles = array();
                    if (! empty($mediaInfo['article_id'])) {
                        $artids = explode(',', $mediaInfo['article_id']);
                        foreach ($artids as $key => $val) {
                            $artinfo = $this->model->table('wechat_media')
                                ->field('id, title, digest, file, content, link')
                                ->where('id = ' . $val)
                                ->find();
                            //$artinfo['content'] = strip_tags(html_out($artinfo['content']));
                            $articles[$key]['Title'] = $artinfo['title'];
                            $articles[$key]['Description'] = $artinfo['digest'];
                            $articles[$key]['PicUrl'] = __URL__ . '/' . $artinfo['file'];
                            $articles[$key]['Url'] = empty($artinfo['link']) ? __HOST__ . url('article/wechat_news_info', array('id'=>$artinfo['id'])) : strip_tags(html_out($artinfo['link']));
                        }
                    } else {
                        $articles[0]['Title'] = $mediaInfo['title'];
                        //$articles[0]['Description'] = strip_tags(html_out($mediaInfo['content']));
                        $articles[0]['Description'] = $mediaInfo['digest'];
                        $articles[0]['PicUrl'] = __URL__ . '/' . $mediaInfo['file'];
                        $articles[0]['Url'] = empty($mediaInfo['link']) ? __HOST__ . url('article/wechat_news_info', array('id'=>$mediaInfo['id'])) : strip_tags(html_out($mediaInfo['link']));
                    }
                    // 回复
                    $this->weObj->news($articles)->reply();
                    //记录用户操作信息
                    $this->record_msg($this->weObj->getRev()->getRevTo(), '图文信息', 1);
                    $endrs = true;
                }
            } else {
                // 文本回复
                $result[0]['content'] = html_out($result[0]['content']);
                $this->weObj->text($result[0]['content'])->reply();
                //记录用户操作信息
                $this->record_msg($this->weObj->getRev()->getRevTo(), $result[0]['content'], 1);
                $endrs = true;
            }
        }
        return $endrs;
    }

    /**
     * 功能变量查询
     *
     * @param unknown $tousername
     * @param unknown $fromusername
     * @param unknown $keywords
     * @return boolean
     */
    public function get_function($fromusername, $keywords)
    {
        $return = false;
        $rs = $this->model->table('wechat_extend')
            ->field('name, command, config')
            ->where('keywords like "%' . $keywords . '%" and enable = 1 and wechat_id = ' . $this->wechat_id)
            ->order('id asc')
            ->find();
        $file = ROOT_PATH . 'plugins/wechat/' . $rs['command'] . '/' . $rs['command'] . '.class.php';
        if (file_exists($file)) {
            require_once ($file);
            $wechat = new $rs['command']();
            $data = $wechat->show($fromusername, $rs);
            if (! empty($data)) {
                // 数据回复类型
                if ($data['type'] == 'text') {
                    $this->weObj->text($data['content'])->reply();
                    //记录用户操作信息
                    $this->record_msg($fromusername, $data['content'], 1);
                } elseif ($data['type'] == 'news') {
                    $this->weObj->news($data['content'])->reply();
                    //记录用户操作信息
                    $this->record_msg($fromusername, '图文消息', 1);
                }
                $return = true;
            }
        }
        return $return;
    }

    /**
     * 商品推荐查询
     *
     * @param unknown $tousername
     * @param unknown $fromusername
     * @param unknown $keywords
     * @return boolean
     */
    public function recommend_goods($fromusername, $keywords)
    {
        $return = false;
        $rs = $this->model->table('wechat_extend')
            ->field('name, keywords, command, config')
            ->where('command = "recommend" and enable = 1 and wechat_id = ' . $this->wechat_id)
            ->order('id asc')
            ->find();

        $file = ROOT_PATH . 'plugins/wechat/' . $rs['command'] . '/' . $rs['command'] . '.class.php';
        if (file_exists($file)) {
            require_once ($file);
            $wechat = new $rs['command']();
            $rs['user_keywords'] = $keywords;
            $data = $wechat->show($fromusername, $rs);
            if (! empty($data)) {
                // 数据回复类型
                if ($data['type'] == 'text') {
                    $this->weObj->text($data['content'])->reply();
                    //记录用户操作信息
                    $this->record_msg($fromusername, $data['content'], 1);
                } elseif ($data['type'] == 'news') {
                    $this->weObj->news($data['content'])->reply();
                    //记录用户操作信息
                    $this->record_msg($fromusername, '图文消息', 1);
                }
                $return = true;
            }
        }
        return $return;
    }

    /**
     * 主动发送信息
     *
     * @param unknown $tousername
     * @param unknown $fromusername
     * @param unknown $keywords
     * @param unknown $weObj
     * @param unknown $return
     * @return boolean
     */
    public function send_message($fromusername, $keywords, $weObj, $return = 0)
    {
        $result = false;
        $rs = $this->model->table('wechat_extend')
            ->field('name, command, config')
            ->where('keywords like "%' . $keywords . '%" and enable = 1 and wechat_id = ' . $this->wechat_id)
            ->order('id asc')
            ->find();
        $file = ROOT_PATH . 'plugins/wechat/' . $rs['command'] . '/' . $rs['command'] . '.class.php';
        if (file_exists($file)) {
            require_once ($file);
            $wechat = new $rs['command']();
            $data = $wechat->show($fromusername, $rs);
            if (! empty($data)) {
                if ($return) {
                    $result = $data;
                } else {
                    $weObj->sendCustomMessage($data['content']);
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * 多客服
     *
     * @param unknown $fromusername
     * @param unknown $keywords
     */
    public function customer_service($fromusername, $keywords)
    {
        /*$kfevent = $this->weObj->getRevKFClose();
        logResult(var_export($kfevent, true));*/
        $result = false;
        //是否超时
        $timeout = false;
        //查找用户
        $uid = $this->model->table('wechat_user')->field('uid')->where(array('openid'=>$fromusername))->getOne();
        if($uid){
            $time_list = $this->model->table('wechat_custom_message')->field('send_time')->where(array('uid'=>$uid))->order('send_time desc')->limit(2)->select();
            if($time_list[0]['send_time'] - $time_list[1]['send_time'] > 3600 * 2){
                $timeout = true;
            }

        }

        // 是否处在多客服流程
        $kefu = $this->model->table('wechat_user')
            ->field('openid')
            ->where('openid = "' . $fromusername . '"')
            ->getOne();
        if($kefu){
            if ($keywords == 'kefu') {
                $rs = $this->model->table('wechat_extend')
                    ->field('config')
                    ->where('command = "kefu" and enable = 1 and wechat_id = ' . $this->wechat_id)
                    ->getOne();
                if (! empty($rs)) {
                    $config = unserialize($rs);
                    $msg = array(
                        'touser' => $fromusername,
                        'msgtype' => 'text',
                        'text' => array(
                            'content' => '欢迎进入多客服系统'
                        )
                    );
                    $this->weObj->sendCustomMessage($msg);
                    //记录用户操作信息
                    $this->record_msg($fromusername, $msg['text']['content'], 1);

                    // 在线客服列表
                    $online_list = $this->weObj->getCustomServiceOnlineKFlist();
                    if ($online_list['kf_online_list']) {
                        foreach ($online_list['kf_online_list'] as $key => $val) {
                            if ($config['customer'] == $val['kf_account'] && $val['status'] > 0 && $val['accepted_case'] < $val['auto_accept']) {
                                $customer = $config['customer'];
                            } else {
                                $customer = '';
                            }
                        }
                    }
                    // 转发客服消息
                    $this->weObj->transfer_customer_service($customer)->reply();
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * 获取用户昵称，头像
     *
     * @param unknown $user_id
     * @return multitype:
     */
    static function get_avatar($user_id)
    {
        $u_row = model('Base')->model->table('wechat_user')
            ->field('nickname, headimgurl')
            ->where('ect_uid = ' . $user_id)
            ->find();
        if (empty($u_row)) {
            $u_row = array();
        }
        return $u_row;
    }

    public static function snsapi_base(){
        $wxinfo = model('Base')->model->table('wechat')->field('token, appid, appsecret, status')->find();
        if(!empty($wxinfo['appid']) && is_wechat_browser() && ($_SESSION['user_id'] === 0 || empty($_SESSION['openid']))){
            $_SESSION['openid'] = isset($_COOKIE['openid']) ? addslashes($_COOKIE['openid']) : '';
            if($wxinfo['status']){
                self::snsapi_userinfo();
            }else{
                $config = model('Base')->model->table('wechat')->field('token, appid, appsecret, status')->find();
                if($config['status']){
                    $obj = new Wechat($config);
                    // 用code换token
                    if(isset($_GET['code']) && $_GET['state'] == 'repeat'){
                        $token = $obj->getOauthAccessToken();
                        $_SESSION['openid'] = $token['openid'];
                        setcookie('openid', $token['openid'], gmtime() + 86400 * 7);
                    }
                    // 生成请求链接
                    if (! empty($wxinfo['oauth_redirecturi'])) {
                        $callback = rtrim($wxinfo['oauth_redirecturi'], '/')  .'/'. $_SERVER['REQUEST_URI'];
                    }
                    if (! isset($callback)) {
                        $callback = __HOST__ . $_SERVER['REQUEST_URI'];
                    }
                    $obj->getOauthRedirect($callback, 'repeat', 'snsapi_base');
                }
            }
        }
    }

    /**
     * 跳转到第三方登录
     */
    public static function snsapi_userinfo(){
        if(is_wechat_browser() && ($_SESSION['user_id'] === 0 || empty($_SESSION['openid'])) && ACTION_NAME != 'third_login'){
            $wxinfo   = model('Base')->model->table('wechat')->field('token, appid, appsecret, status')->find();
            if(!$wxinfo['status']){return false;}
            if (! empty($wxinfo['oauth_redirecturi'])) {
                $callback = rtrim($wxinfo['oauth_redirecturi'], '/')  .'/'. $_SERVER['REQUEST_URI'];
            }
            if (! isset($_SESSION['redirect_url'])) {
                $callback = __HOST__ . $_SERVER['REQUEST_URI'];
            }
            $url = url('user/third_login', array('type'=>'weixin', 'backurl'=> $callback));
            header("Location: ".$url);
            exit;
        }
    }

    /**
     * 记录用户操作信息
     */
     public function record_msg($fromusername, $keywords, $iswechat = 0){
        $uid = $this->model->table('wechat_user')->field('uid')->where(array('openid'=>$fromusername))->getOne();
        if($uid){
            $data['uid'] = $uid;
            $data['msg'] = $keywords;
            $data['send_time'] = time();
            //是公众号回复
            if($iswechat){
                $data['iswechat'] = 1;
            }
            $this->model->table('wechat_custom_message')
                ->data($data)
                ->insert();
        }
     }

    /**
     * 检查是否是微信浏览器访问
     */
    static function is_wechat_browser()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 插件页面显示方法
     *
     * @param string $plugin
     */
    public function plugin_show()
    {
        $plugin = I('get.name');
        $file = ADDONS_PATH . 'wechat/' . $plugin . '/' . $plugin . '.class.php';
        if (file_exists($file)) {
            include_once ($file);
            $wechat = new $plugin();
            $wechat->html_show();
        }
    }

    /**
     * 插件处理方法
     *
     * @param string $plugin
     */
    public function plugin_action()
    {
        $plugin = I('get.name');
        $file = ADDONS_PATH . 'wechat/' . $plugin . '/' . $plugin . '.class.php';
        if (file_exists($file)) {
            include_once ($file);
            $wechat = new $plugin();
            $wechat->action();
        }
    }

    /**
     * 获取公众号配置
     *
     * @param string $orgid
     * @return array
     */
    private function get_config($orgid)
    {
        $config = $this->model->table('wechat')
            ->field('id, token, appid, appsecret')
            ->where('orgid = "' . $orgid . '" and status = 1')
            ->find();
        if (empty($config)) {
            $config = array();
        }
        return $config;
    }

    /**
     * 获取access_token的接口
     * @return [type] [description]
     */
    public function check_auth(){
        $appid = I('get.appid');
        $appsecret = I('get.appsecret');
        if(empty($appid) || empty($appsecret)){
            echo json_encode(array('errmsg'=>'信息不完整，请提供完整信息', 'errcode'=>1));
            exit;
        }
        $config = $this->model->table('wechat')
            ->field('token, appid, appsecret')
            ->where('appid = "' . $appid . '" and appsecret = "'.$appsecret.'" and status = 1')
            ->find();
        if(empty($config)){
            echo json_encode(array('errmsg'=>'信息错误，请检查提供的信息', 'errcode'=>1));
            exit;
        }

        $obj = new Wechat($config);
        $access_token = $obj->checkAuth();
        if($access_token){
          echo json_encode(array('access_token'=>$access_token, 'errcode'=>0));
          exit;
        }
        else{
          echo json_encode(array('errmsg'=>$obj->errmsg, 'errcode'=>$obj->errcode));
          exit;
        }
    }

     /**
     * 推荐分成二维码
     * @param  string  $user_name [description]
     * @param  integer $user_id   [description]
     * @param  integer $time      [description]
     * @param  string  $fun       [description]
     * @return [type]             [description]
     */
    static function rec_qrcode($user_name = '', $user_id = 0, $expire_seconds = 0, $fun = '', $force = false){
        if(empty($user_id)){
            return false;
        }
        // 默认公众号信息
        $wxinfo = model('Base')->model->table('wechat')->field('id, token, appid, appsecret, oauth_redirecturi, type, oauth_status')->where('default_wx = 1 and status = 1')->find();

        if (! empty($wxinfo) && $wxinfo['type'] == 2) {
            $config['token'] = $wxinfo['token'];
            $config['appid'] = $wxinfo['appid'];
            $config['appsecret'] = $wxinfo['appsecret'];
            // 微信通验证
            $weObj = new Wechat($config);
            if($force){
                $weObj->clearCache();
                model('Base')->model->table('wechat_qrcode')->where(array('scene_id'=>$user_id, 'wechat_id'=>$wxinfo['id']))->delete();
            }

            $qrcode = model('Base')->model->table('wechat_qrcode')->field('id, scene_id, type, expire_seconds, qrcode_url')->where(array('scene_id'=>$user_id, 'wechat_id'=>$wxinfo['id']))->find();
            if($qrcode['id'] && !empty($qrcode['qrcode_url'])){
                return $qrcode['qrcode_url'];
            }
            elseif($qrcode['id'] && empty($qrcode['qrcode_url'])){
                $ticket = $weObj->getQRCode((int)$qrcode['scene_id'], $qrcode['type'], $qrcode['expire_seconds']);
                if (empty($ticket)) {
                    $weObj->resetAuth();
                    //$weObj->errCode, $weObj->errMsg
                    return false;
                }
                $data['ticket'] = $ticket['ticket'];
                $data['expire_seconds'] = $ticket['expire_seconds'];
                $data['endtime'] = time() + $ticket['expire_seconds'];
                // 二维码地址
                $data['qrcode_url'] = $weObj->getQRUrl($ticket['ticket']);
                M()->table('wechat_qrcode')->data($data)->where(array('id'=>$qrcode['id']))->update();
                return $data['qrcode_url'];
            }
            else{
                $data['function'] = $fun;
                $data['scene_id'] = $user_id;
                $data['username'] = $user_name;
                $data['type'] = empty($expire_seconds) ? 1 : 0;
                $data['wechat_id'] = $wxinfo['id'];
                $data['status'] = 1;
                //生成二维码
                $ticket = $weObj->getQRCode((int)$data['scene_id'], $data['type'], $expire_seconds);
                if (empty($ticket)) {
                    $weObj->resetAuth();
                    //$weObj->errCode, $weObj->errMsg
                    return false;
                }
                $data['ticket'] = $ticket['ticket'];
                $data['expire_seconds'] = $ticket['expire_seconds'];
                $data['endtime'] = time() + $ticket['expire_seconds'];
                // 二维码地址
                $data['qrcode_url'] = $weObj->getQRUrl($ticket['ticket']);

                M()->table('wechat_qrcode')->data($data)->insert();
                return $data['qrcode_url'];
            }
        }
        return false;
    }
}
