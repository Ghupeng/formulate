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
            throw new BaseException(BaseException::FROM_CHECK_FAIL);
        }
        $param = [];
        foreach ($comments as $comment) {
            $key   = $comment['name'];
            $value = $this->_getFilterCommentValue($key,$comment,$params);
            if($value !== false){
                $param[$key] = $value[$key];
            }
        }
        return $param;
    }

    private function _getFilterCommentValue($key,$comment,$params){
        if(!isset($params[$key])) {
            return false;
        }
        $filter = new Filter();
        $option = $comment['option'];
        $message= $comment['message'];
        if(empty($option)) {
            $param = $filter->sanitize($params[$key],'string');
            return $param;
        }
        $length = [];
        if( isset($comment['length']) ) {
            $length = explode(',',$comment['length']);
        }

        $value = $params[$key];
        // Custom type regex filter from input
        if( !empty($comment['regex']) && isset($comment['regex'])) {
            if(!preg_match('/'.$comment["regex"].'/',$value)) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
            }
            // regex Sanitizing filter from input
            if( isset($comment['filter']) ) {
                $param[$key] = $filter->sanitize($value, $comment['filter']);
            } else {
                $param[$key] = $filter->sanitize($value, 'string');
            }
        }
        // Custom type default filter from input
        if( empty($value) && isset($comment['default']) ) {
            $value = $comment['default'];
        }
        // Custom type string filter from input
        if( strtolower($comment['type']) === 'string' ) {
            if( !is_string($value) || empty($value)) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
            }
            $temp_length = mb_strlen($value,'utf-8');
            if( ($temp_length < $length[0] || ($temp_length > $length[1] && intval($length[1]) !== 0)) && !empty($length) ) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
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
            if( !is_numeric($value) || $value === '') {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
            }
            if( !empty($length) && ($value < $length[0] || ($value >$length[1] && intval($length[1]) !== 0)) ) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
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
            if( !is_numeric($value) || $value === '' ) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
            }
            $value=(float)$value;
            if( !is_float($value) ) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
            }
            if( !empty($length) && ($value < $length[0] || ($value >$length[1] && intval($length[1]) !== 0)) ) {
                throw new BaseException(BaseException::FROM_CHECK_FAIL,$message);
            }
            // float Sanitizing filter from input
            if( isset($comment['filter']) ) {
                $param[$key] = $filter->sanitize($value, $comment['filter']);
            } else {
                $param[$key] = $filter->sanitize($value, 'float');
            }
        }
        return $param;
    }
}
