<?php

namespace Addons\CityMsgApi;
use Common\Controller\Addon;

/**
 * 微信城市服务
 * @author 蒋凯
 */

    class CityMsgApiAddon extends Addon{

        public $info = array(
            'name'=>'CityMsgApi',
            'title'=>'微信城市服务',
            'description'=>'微信城市服务',
            'status'=>1,
            'author'=>'蒋凯',
            'version'=>'1.0',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/CityMsgApi/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/CityMsgApi/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }