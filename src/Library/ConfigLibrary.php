<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 14:02:41
 * By: ConfigLibrary.php
 */
namespace framing/Library;
class ConfigLibraray {
	/**
	 * @$param string $filename 文件名
	 * @$param string $module   模块
	 *
	 */
	public static function init(string $filename,string $module) {
		echo $filename . $module; 	
	}
}
