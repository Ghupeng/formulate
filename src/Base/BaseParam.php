<?php
/**
 * Created by vim.
 * User: huguopeng
 * Date: 2018/09/06
 * Time: 23:42:17
 * By: BaseParam.php
 */
namespace framing\Base;

use framing\Library\Reflection;
use Phalcon\Filter;
class BaseParam {

	public function vaild($basePageInfo,$request) {
		$className  = get_called_class();
		$reflection = new Reflection();
		$comments = $reflection->getAttribute($className);
		if( $basePageInfo->requestType === 'POST' ) {
			$params =  $request->getPost();
		} else if( $basePageInfo->requestType === 'GET' ) {
			$params = $request->get();
		}
		$param = $this->_getParamValue($params,$comments);
		return $param;
	}

	private function _getParamValue($params,$comments) {
		unset($params['_url']);
		if(empty($params)) {
			echo "无请求参数";die;
		}

		$filter = new Filter();
		$param = [];
		foreach ($comments as $comment) {
			$message = $comment['message'];
			$default = $comment['default'];
			$length = [];
			if( !empty($comment['length']) ) {
				$length = explode(',',$comment['length']);
			}
			$min_max = [];
			if( !empty($comment['min_max']) ) {
				$min_max = explode(',',$comment['min_max']);
			}
			
			$key   = $comment['name'];
			$value = $params[$key];
			// Custom type regex filter from input
			if( !empty($comment['regex']) ) {
				if(!preg_match('/'.$comment["regex"].'/',$value)) {
					echo $key."数据有误";die;
				}
				// regex Sanitizing filter from input
				if( isset($comment['filter']) ) {
					$param[$key] = $filter->sanitize($value, $comment['filter']);
				} else {
					$param[$key] = $filter->sanitize($value, 'string');
				}
			}
			if( empty($value) && isset($default) ) {
				$value = $default;
			}
			// Custom type string filter from input
			if( strtolower($comment['type']) === 'string' ) {
				if( !is_string($value) || (!isset($default) && empty($value)) ) {
					echo $key.'输入的必须为字符串';die;
				}
				$temp_length = mb_strlen($value,'utf-8');
				if( ($temp_length < $length[0] || ($temp_length > $length[1] && intval($length[1]) !== 0)) && !empty($length) ) {
					echo $key.'输入的字符串长度限制';die;
				}
				// string Sanitizing filter from input
				if( isset($comment['filter']) ) {
					$param[$key] = $filter->sanitize($value, $comment['filter']);
				} else {
					$param[$key] = $filter->sanitize($value, 'string');
				}
			}
			// Custom type int filter from input
			if( strtolower($comment['type']) === 'int' ) {
				if( !is_numeric($value) || (!isset($default) && empty($min_max) && empty($value)) ) {
					echo $key."输入的必须是数字类型";die;
				}
				if( !empty($min_max) && ($value < $min_max[0] || ($value >$min_max[1] && intval($min_max[1]) !== 0)) ) {
					echo $key."输入的数字限制";die;
				}
				// int Sanitizing filter from input
				if( isset($comment['filter']) ) {
					$param[$key] = $filter->sanitize($value, $comment['filter']);
				} else {
					$param[$key] = $filter->sanitize($value, 'int');
				}
			}
			// Custom type float filter from input
			if(strtolower($comment['type']) === 'float') {
				if( !is_numeric($value) || (!isset($default) && empty($min_max) && empty($value)) ) {
					echo $key."输入的必须是float类型";die;
				}
				$value=(float)$value;
				if( !is_float($value) ) {
					echo $key."输入的必须是float类型";die;
				}
				if( !empty($min_max) && ($value < $min_max[0] || ($value >$min_max[1] && intval($min_max[1]) !== 0)) ) {
					echo $key."输入的float限制";die;
				}
				// float Sanitizing filter from input
				if( isset($comment['filter']) ) {
					$param[$key] = $filter->sanitize($value, $comment['filter']);
				} else {
					$param[$key] = $filter->sanitize($value, 'float');
				}
			}
		}
		return $param;
	}
}
