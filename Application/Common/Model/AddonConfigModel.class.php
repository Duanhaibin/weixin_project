<?php

namespace Common\Model;

use Think\Model;

/**
 * 插件配置操作集成
 */
class AddonConfigModel extends Model {
	protected $tableName = 'addons';
	/**
	 * 保存配置
	 */
	function set($addon, $config) {
		$map ['token'] = get_token ();
		if (empty ( $map ['token'] )) {
			return false;
		}
		$info = M ( 'apps' )->where ( $map )->find ();
		if (! $info) {
			$map ['uid'] = session ( 'mid' );
			$addon_config [$addon] = $config;
			$map ['addon_config'] = json_encode ( $addon_config );
			$info ['id'] = M ( 'apps' )->add ( $map );
		} else {
			$addon_config = json_decode ( $info ['addon_config'], true );
			$addon_config [$addon] = isset ( $addon_config [$addon] ) ? $addon_config [$addon] : [ ];
			$addon_config [$addon] = array_merge ( $addon_config [$addon], $config );
			M ( 'apps' )->where ( $map )->setField ( 'addon_config', json_encode ( $addon_config ) );
		}
		
		D ( 'Common/Apps' )->clear ( $info ['id'] );
		
		return $info ['id'];
	}
	function sett($addon, $config, $id) {
		$map ['token'] = get_token ();
		$map ['id'] = $id;
		if (empty ( $map ['token'] )) {
			return false;
		}
		$info = M ( 'weisite_category' )->where ( $map )->find ();
		if (! $info) {
			// $map['id']=$id;
			$map ['uid'] = session ( 'mid' );
			$addon_config [$addon] = $config;
			$map ['addon_config'] = json_encode ( $addon_config );
			
			$aaa = M ( 'weisite_category' )->add ( $map );
		} else {
			$addon_config = json_decode ( $info ['addon_config'], true );
			$addon_config [$addon] = $config;
			$aaa = M ( 'weisite_category' )->where ( $map )->setField ( 'addon_config', json_encode ( $addon_config ) );
		}
		
		return $aaa;
	}
	function setlist($addon, $config, $id) {
		$map ['token'] = get_token ();
		$map ['id'] = $id;
		if (empty ( $map ['token'] )) {
			return false;
		}
		$info = M ( 'weisite_category' )->where ( $map )->find ();
		if (! $info) {
			// $map['id']=$id;
			$map ['uid'] = session ( 'mid' );
			$addon_config [$addon] = $config;
			$map ['listts'] = json_encode ( $addon_config );
			
			$aaa = M ( 'weisite_category' )->add ( $map );
		} else {
			$addon_config = json_decode ( $info ['listts'], true );
			$addon_config [$addon] = $config;
			$aaa = M ( 'weisite_category' )->where ( $map )->setField ( 'listts', json_encode ( $addon_config ) );
		}
		
		return $aaa;
	}
	function setdetail($addon, $config, $id) {
		$map ['token'] = get_token ();
		$map ['id'] = $id;
		if (empty ( $map ['token'] )) {
			return false;
		}
		$info = M ( 'weisite_category' )->where ( $map )->find ();
		if (! $info) {
			// $map['id']=$id;
			$map ['uid'] = session ( 'mid' );
			$addon_config [$addon] = $config;
			$map ['content'] = json_encode ( $addon_config );
			
			$aaa = M ( 'weisite_category' )->add ( $map );
		} else {
			$addon_config = json_decode ( $info ['content'], true );
			$addon_config [$addon] = $config;
			$aaa = M ( 'weisite_category' )->where ( $map )->setField ( 'content', json_encode ( $addon_config ) );
		}
		
		return $aaa;
	}
	function setfooter($addon, $config, $id) {
		$map ['token'] = get_token ();
		$map ['id'] = $id;
		if (empty ( $map ['token'] )) {
			return false;
		}
		$info = M ( 'weisite_category' )->where ( $map )->find ();
		if (! $info) {
			// $map['id']=$id;
			$map ['uid'] = session ( 'mid' );
			$addon_config [$addon] = $config;
			$map ['footer'] = json_encode ( $addon_config );
			
			$aaa = M ( 'weisite_category' )->add ( $map );
		} else {
			$addon_config = json_decode ( $info ['footer'], true );
			$addon_config [$addon] = $config;
			$aaa = M ( 'weisite_category' )->where ( $map )->setField ( 'footer', json_encode ( $addon_config ) );
		}
		
		return $aaa;
	}
	
	/**
	 * 获取插件配置
	 * 获取的优先级：当前公众号设置》后台默认配置》安装文件上的配置
	 */
	function get($addon) {
		// 当前公众号的设置
		$token = get_token ();
		$token_config = D ( 'Common/Apps' )->getInfoByToken ( $token, 'addon_config' );
		// dump ( $token_config );
		$token_config = json_decode ( $token_config, true );
		$token_config = isset ( $token_config [$addon] ) ? $token_config [$addon] : [ ];
		// dump ( $token_config );
		
		// 后台默认的配置
		$addonArr = D ( 'Home/Addons' )->getInfoByName ( $addon );
		$addon_config = ( array ) json_decode ( $addonArr ['config'], true );
		// dump ( $addon_config );
		
		// 安装文件上的配置
		$file_config = array ();
		$file = ONETHINK_ADDON_PATH . $addon . '/config.php';
		$file_config = [ ];
		if (file_exists ( $file )) {
			$arr = include $file;
			foreach ( $arr as $f => $v ) {
				$file_config [$f] = isset ( $v ['value'] ) ? $v ['value'] : '';
			}
		}
		
		return array_merge ( $file_config, $addon_config, $token_config );
	}
}
?>
