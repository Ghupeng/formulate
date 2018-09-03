<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 16:04:24
 * By: Runmode.php
 */

namespace framing\Library;

class Runmode {
	
	public static function get() {
		$runmode = get_cfg_var('run.env');
		if($runmode !== false) {
			return $runmode;
		} else {
			return 'online';
		}
	}
}
