<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/05
 * Time: 20:35:38
 * By: BasePageInfo.php
 */
namespace framing\Base;

class BasePageInfo {
	public $requestType = 'GET';
	public $format      = 'json';
	public $formCheck   = true;
	public $login       = true;
	public $runMode     = 'online';
	public $module;
	public $method;
	public $cache = [];
	public $params= [];

}
