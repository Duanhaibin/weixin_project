<?php
        	
namespace Addons\SecurityReminding\Model;
use Home\Model\WeixinModel;
        	
/**
 * CbsBusiness的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'SecurityReminding' ); // 获取后台插件的配置参数
		//dump($config);
	}
}
        	