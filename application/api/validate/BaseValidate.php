<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/4/25
 * Time: 21:22
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * @return bool
     * @throws ParameterException
     */
    public function goCheck()
    {
        //获取HTTP传入的参数
        $params = Request::param();
        //对这些参数做校验
        $result = $this->batch()->check($params);
        if (!$result) {
            $e = new ParameterException([
                'msg' => $this->error,
            ]);
            throw $e;
        } else {
            return true;
        }
    }

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
//            return $field.'必须是正整数';
            return false;
        }
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }

    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    protected function isOrderNo($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value) || $value == 'undefined') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $arrays
     * @return array
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            //不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}