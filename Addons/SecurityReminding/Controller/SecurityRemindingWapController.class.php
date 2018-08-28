<?php

namespace Addons\SecurityReminding\Controller;
use Think\WapBaseController;

class SecurityRemindingWapController extends WapBaseController{
    public $ajax_url = 'http://service.5212301.com';
    //public $ajax_url = 'http://service.12301.cn';

    public $accountId = "gh_6e785e1175b2";
    //public $accountId = "gh_b937df9dcb60";

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

    //首页
    function index() {
        $openid = get_openid ();
        $openinfo = getWeixinUserInfo($openid);
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $token = get_token ();
        $this->assign ( 'accountId', $this->accountId );
        $this->assign ( 'openid', $openid );
        $this->assign ( 'token', $token );
        $this->assign ( 'ajax_url', $this->ajax_url );
        $this->assign ( 'nickname', $openinfo['nickname'] );//昵称
        $this->assign ( 'headimgurl', $openinfo['headimgurl'] );//头像
        $this->display ();
    }

}
