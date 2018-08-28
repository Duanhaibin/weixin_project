<?php

namespace Addons\UserInfoApi\Controller;
use Think\WapBaseController;

class UserInfoApiWapController extends WapBaseController{
    //自助投诉请求域名
    public $url = 'http://m.tousug.12301e.com/';
    //public $url = 'https://mucomplain.12301.cn/';

    public $zizhu_url = 'http://www1.12301e.com/';
    //public $zizhu_url = 'http://m.12301.cn/';

    public $accountId = "gh_6e785e1175b2";
    //public $accountId = "gh_b937df9dcb60";

    public $ajax_url = 'http://service.5212301.com/';
    //public $ajax_url = 'http://service.12301.cn/';

    public function __construct() {
        parent::__construct ();
    }
    /**
     * **************************微信上的操作功能************************************
     */
    function jump(){
        $type = I ( 'type' ) ? I ( 'type' ) : "weixin";   //weixin    wxCity
        $to = I ( 'to' ) ? I ( 'to' ) : "pact";       //pact    my
        $openid = get_openid ();
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $jump_url = $this->url."view/complaintweixin?accountId=".$this->accountId."&openid=".$openid."&type=".$type."#/".$to;
        redirect($jump_url);
    }


    function zizhu(){
        $openid = get_openid ();
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $jump_url = $this->zizhu_url."view/qalist?accountId=".$this->accountId."&openid=".$openid."&type=weixin";
        //echo $jump_url;exit;
        redirect($jump_url);
    }

    //电子合同跳转
    function electronicContractJump(){
        $openid = get_openid ();
        if($openid == -1){
            $this->error ( "请在微信中打开" );
        }
        $jump_url = $this->ajax_url."contract/list?accountId=".$this->accountId."&openid=".$openid;
        redirect($jump_url);
    }

}
