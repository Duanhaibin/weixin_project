<?php

namespace Addons\CbsBusiness\Controller;
use Think\WapBaseController;

class CbsBusinessWapController extends WapBaseController{
    //智旅通请求域名
    public $ajax_url = 'http://tousuc.12301e.com/';
    //public $ajax_url = 'http://ecomplain.12301.cn/';

    //自助投诉请求域名
    public $mcomplain_url = 'http://m.tousug.12301e.com/';
    //public $mcomplain_url = 'http://mucomplain.12301.cn/';

    public function __construct() {
        parent::__construct ();
    }
    /**
     * **************************微信上的操作功能************************************
     */

    function getsignPackageUrl(){
        $url = I ( 'url' );
        if(!$url){
            $res['result_code'] = '0001';
            $res['result_msg'] = 'URL不能为空';
            $res['result_data'] = '';
            echo json_encode($res);
            exit;
        }
        $token = get_token ();
        $info = $GLOBALS ['public_info'] = get_token_appinfo ( $token );
        Vendor ( 'jssdk.jssdk' );
        $jssdk = new \JSSDK ( $info ['appid'], $info ['secret'] );
        //$url = 'http://weixin.12301e.com/index.php?s=/w18/CbsBusiness/CbsBusinessWap/detail&from=singlemessage#/s/cbs123456';
        $jsapiParams = $jssdk->getsignPackageUrl ($url);
        $res['result_code'] = '0000';
        $res['result_msg'] = '获取成功';
        $res['result_data'] = $jsapiParams;
        echo json_encode($res);
        exit;
    }

    //评星首页
    function index() {
        $openid = get_openid ();
        $openinfo = getWeixinUserInfo($openid);
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $token = get_token ();
        $page_title = '首页';
        $this->assign ( 'openid', $openid );
        $this->assign ( 'token', $token );
        $this->assign ( 'page_title', $page_title );
        $this->assign ( 'ajax_url', $this->ajax_url );
        $this->assign ( 'mcomplain_url', $this->mcomplain_url );
        $this->assign ( 'nickname', $openinfo['nickname'] );//昵称
        $this->assign ( 'headimgurl', $openinfo['headimgurl'] );//头像
        $this->display ();
    }

    //商户搜索页面
    function search() {
        $openid = get_openid ();
        $openinfo = getWeixinUserInfo($openid);
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $token = get_token ();
        $page_title = '搜索页面';
        $this->assign ( 'openid', $openid );
        $this->assign ( 'token', $token );
        $this->assign ( 'page_title', $page_title );
        $this->assign ( 'ajax_url', $this->ajax_url );
        $this->assign ( 'mcomplain_url', $this->mcomplain_url );
        $this->assign ( 'nickname', $openinfo['nickname'] );//昵称
        $this->assign ( 'headimgurl', $openinfo['headimgurl'] );//头像
        $this->display ('index');
    }

    //商户详情页面
    function detail() {
        $openid = get_openid ();
        $openinfo = getWeixinUserInfo($openid);
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $token = get_token ();
        $page_title = '商户详情页面';
        $this->assign ( 'openid', $openid );
        $this->assign ( 'token', $token );
        $this->assign ( 'page_title', $page_title );
        $this->assign ( 'ajax_url', $this->ajax_url );
        $this->assign ( 'mcomplain_url', $this->mcomplain_url );
        $this->assign ( 'nickname', $openinfo['nickname'] );//昵称
        $this->assign ( 'headimgurl', $openinfo['headimgurl'] );//头像
        $this->display ('index');
    }

    //用户评价页面
    function evaluate() {
        $openid = get_openid ();
        $openinfo = getWeixinUserInfo($openid);
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $token = get_token ();
        $page_title = '用户评价页面';
        $this->assign ( 'openid', $openid );
        $this->assign ( 'token', $token );
        $this->assign ( 'page_title', $page_title );
        $this->assign ( 'ajax_url', $this->ajax_url );
        $this->assign ( 'mcomplain_url', $this->mcomplain_url );
        $this->assign ( 'nickname', $openinfo['nickname'] );//昵称
        $this->assign ( 'headimgurl', $openinfo['headimgurl'] );//头像
        $this->display ('index');
    }

}
