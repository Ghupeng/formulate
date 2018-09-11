<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/04
 * Time: 10:10:29
 * By: BaseException.php
 */
namespace framing\Base;

class BaseException extends \Exception {
    const SUCCESS           = 1000;
    const FROM_CHECK_FAIL   = 2000;
    const BAN_LOGIN         = 3000;
    const DATA_IS_EMPTY     = 4000;
    const INTER_ERROR       = 5000;
    const CONF_FILE_ERROR   = 5001;

    public static $messages = [
        self::SUCCESS           => '成功',
        self::FROM_CHECK_FAIL   => '参数错误',
        self::BAN_LOGIN         => '账号被锁定',
        self::DATA_IS_EMPTY     => '未找到数据',
        self::INTER_ERROR       => '服务器开小差',
        self::CONF_FILE_ERROR   => '内部错误',
    ];
    public function __construct( $code = 0,$param='') {
        $this->code     = $code;
        $this->message  = self::getErrorMessage($code);
        if($param !== '') {
            $this->message = $param;
        }
    }
    public static function getErrorMessage($code = '') {
        if(isset(self::$messages[$code])){
            return self::$messages[$code];
        }
    }
}
