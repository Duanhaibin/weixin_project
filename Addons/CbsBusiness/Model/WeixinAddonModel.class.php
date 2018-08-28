<?php
        	
namespace Addons\CbsBusiness\Model;
use Home\Model\WeixinModel;
        	
/**
 * CbsBusiness的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'CbsBusiness' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	