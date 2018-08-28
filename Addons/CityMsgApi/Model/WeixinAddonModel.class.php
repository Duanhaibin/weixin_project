<?php
        	
namespace Addons\UserInfoApi\Model;
use Home\Model\WeixinModel;
        	
/**
 * UserInfoApi的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'CityMsgApi' ); // 获取后台插件的配置参数
		//dump($config);
	}
}
        	