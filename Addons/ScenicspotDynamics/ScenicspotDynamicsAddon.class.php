<?php

namespace Addons\ScenicspotDynamics;
use Common\Controller\Addon;

/**
 * 长白山商家诚信星评系统插件
 * @author 蒋凯
 */

    class ScenicspotDynamicsAddon extends Addon{

        public $info = array(
            'name'=>'ScenicspotDynamics',
            'title'=>'景区动态',
            'description'=>'景区动态',
            'status'=>1,
            'author'=>'蒋凯',
            'version'=>'1.0',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/ScenicspotDynamics/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/ScenicspotDynamics/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }