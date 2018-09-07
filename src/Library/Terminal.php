<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/03
 * Time: 21:02:13
 * By: Terminal.php
 */
namespace framing\Library;

class Terminal {
	const UNKNOWN 	= 0;
	const PC	= 1;
	const WAP	= 2;
	const IOS	= 3;
	const ANDROID	= 4;
	/**
	 * @param string $terminal
	 * return int
	 */
	public static function getTerminalType(string $terminal = '') {
		if(!empty($terminal)) {
			return self::_reverseTerminal($terminal);
		}

		$agent = $_SERVER['HTTP_USER_AGENT'];

		if (preg_match('/win/i', $agent) ) {
			return self::PC;
		} else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
			return self::PC;
		} else if (strpos($agent, 'Android') !== false) {
			return self::ANDROID;
		} elseif (strpos($agent, 'iPhone') !== false) {
			return self::IOS;
		} elseif (strpos($agent, 'iPad') !== false) {
			return self::IOS;
		} else {
			return self::UNKNOWN;
		}
	}
	/**
	 * @param int $terminal_type
	 * return string
	 */
	public static function getReverseTerminal(int $terminal_type = 0){
		if(intval($terminal_type) === 0) {
			return 'unknown';
		} else if(intval($terminal_type) === 1) {
			return 'pc';
		} else if(intval($terminal_type) === 2) {
			return 'wap';
		} else if(intval($terminal_type) === 3) {
			return 'ios';
		} else if(intval($terminal_type) === 4) {
			return 'android';
		}
	}
	/**
	 * @param string $termain
	 * return int
	 */
	private static function _reverseTerminal(string $terminal = '') {
		if(empty($terminal) || $terminal === 'unknown') {
			return self::UNKNOWN;
		}
		if($terminal === 'pc') {
			return self::PC;
		}
		if($terminal === 'wap') {
			return self::WAP;
		}
		if($terminal === 'ios') {
			return self::IOS;
		}
		if($terminal === 'android') {
			return self::ANDROID;
		}
	}
}
