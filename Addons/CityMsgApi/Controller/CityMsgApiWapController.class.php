<?php

namespace Addons\CityMsgApi\Controller;
use Think\WapBaseController;

class CityMsgApiWapController extends WapBaseController{
    public function __construct() {
        parent::__construct ();
    }

    function index(){
        $request = file_get_contents('php://input');
        $request = json_decode($request);
        $openid = $request->openid;
        $order_no = $request->order_no;
        $name = $request->name;
        $tel = $request->tel;
        $ts_fact = $request->ts_fact;
        if($openid && $order_no && $name && $tel && $ts_fact ){
            $url = 'https://api.weixin.qq.com/cityservice/sendmsgdata?access_token=' . get_access_token ();
            //data数据
            $data['first']['value'] = '投诉提交成功';
            $data['first']['color'] = '#173177';

            $data['keyword1']['value'] = $name;
            $data['keyword1']['color'] = '#173177';

            $data['keyword2']['value'] =  $tel;
            $data['keyword2']['color'] = '#173177';

            $data['keyword3']['value'] =  $ts_fact;
            $data['keyword3']['color'] = '#173177';

            $data['keyword4']['value'] =  $order_no;
            $data['keyword4']['color'] = '#173177';

            $data['remark']['value'] = '您的投诉已经提交成功，请记录您的投诉订单编号，后续可以通过 投诉查询 关注投诉进度，感谢您的关注！';
            $data['remark']['color'] = '#173177';
            $post_data = array(
                "openid" => $openid,
                "biz_template_id" => "7wZizRfKfrvFkqSK9KsrrSCXV1DgXNvV3wraMOWx1OQ",
                "result_page_style_id"=>'-bC75BmCf6SDTrlkbUNbancJBR7fVUWG9fGsAlphfgo',
                "deal_msg_style_id"=>'-bC75BmCf6SDTrlkbUNbahNbbhhH2SuVk6KVRtmGzO4',
                "order_no"=>$order_no,
                "data"=>$data
            );
            $res = $this->http_post_data($url, json_encode($post_data));
            echo $res;exit;
        }else{
            $res['errcode'] = 40001;
            $res['errmsg'] = 'error';
            echo json_encode($res);exit;
        }
    }

    function http_post_data($url, $data_string) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return  $return_content;
    }


}
