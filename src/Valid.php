<?php
/**
 * Valid      PHP接口参数验证小工具，简单实用
 * @author    jiqing9006@126.com
 * @version   v1.1
 */
namespace Jq;
class Valid
{
    static protected $error;
    /**
     * @param $validators array array('email' => 'required|valid_email')
     * @param $input array post数据
     * @return string
     */
    public function is_valid($validators, $input) {
        foreach ($validators as $field => $rules) {
            if (!isset($input[$field]) || empty($input[$field])) {
                self::$error[] = "缺少参数";
            }

            $rules = explode('|', $rules);
            foreach ($rules as $rule) {
                $method = null;
                $param = null;

                // Check if we have rule parameters
                if (strstr($rule, ',') !== false) {
                    $rule   = explode(',', $rule);
                    $method = 'check_'.$rule[0];
                    $param  = $rule[1];
                    $rule   = $rule[0];
                } else {
                    $method = 'check_'.$rule;
                }

                $method_array = get_class_methods(new Valid());
                if (!in_array($method,$method_array)) {
                    self::$error[] = "Method not exist.";
                }

                if (!self::$method($input[$field],$param)) {
                    self::$error[] = self::get_error_tips($rule,$param);
                }
            }
        }

        if (count(self::$error) == 0) {
            return 0;
        }
        return self::$error[0]; // 返回第一个错误
    }

    /**
     * @param $field string 验证字段
     * @param $rules string 验证规则 required|max_len,100|min_len,6
     * @return string
     */
    public function validate($field,  $rules)
    {
        $rules = explode('|', $rules);
        foreach ($rules as $rule) {
            $method = null;
            $param = null;

            // Check if we have rule parameters
            if (strstr($rule, ',') !== false) {
                $rule   = explode(',', $rule);
                $method = 'check_'.$rule[0];
                $param  = $rule[1];
                $rule   = $rule[0];
            } else {
                $method = 'check_'.$rule;
            }

            $method_array = get_class_methods(new Valid());
            if (!in_array($method,$method_array)) {
                self::$error[] = "验证规则不存在";
            }

            if (!self::$method($field,$param)) {
                self::$error[] = self::get_error_tips($rule,$param);
            }
        }

        if (count(self::$error) == 0) {
            return 0;
        }
        return self::$error[0]; // 返回第一个错误
    }

    /**
     * 灵活获取参数
     * @param $rule
     * @param $param
     */
    public static function get_error_tips($rule,$param) {
        $error_tips = [
            'tel' => '手机号格式有误',
            'email' => '邮箱格式有误',
            'max_len' => '参数长度不能超过最大长度'.$param,
            'min_len' => '参数长度不能小于最小长度'.$param,
            'required' => '缺少参数',
            'r' => '缺少参数'
        ];
        return $error_tips[$rule] ? $error_tips[$rule] : '参数格式有误';
    }

    public static function check_required($field) {
        if (isset($field) && ($field === false || $field === 0 || $field === 0.0 || $field === '0' || !empty($field))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 简写
     * @param $field
     * @return bool
     */
    public static function check_r($field) {
        if (isset($field) && ($field === false || $field === 0 || $field === 0.0 || $field === '0' || !empty($field))) {
            return true;
        } else {
            return false;
        }
    }

    public static function check_tel($field) {
        if(preg_match("/^1[345678]{1}\d{9}$/",$field)){
            return true;
        }else{
            return false;
        }
    }

    public static function check_email($field) {
        if(preg_match("/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/",$field)){
            return true;
        }else{
            return false;
        }
    }

    public static function check_max_len($field,$param = null) {
        if (function_exists('mb_strlen')) {
            if (mb_strlen($field) <= (int) $param) {
                return true;
            } else  {
                return false;
            }
        } else {
            if (strlen($field) <= (int) $param) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function check_min_len($field,$param = null) {
        if (function_exists('mb_strlen')) {
            if (mb_strlen($field) >= (int) $param) {
                return true;
            } else  {
                return false;
            }
        } else {
            if (strlen($field) >= (int) $param) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function check_regex($field, $param = null)
    {
        $regex = $param;
        if (preg_match($regex, $field)) {
            return true;
        } else {
            return false;
        }
    }




}