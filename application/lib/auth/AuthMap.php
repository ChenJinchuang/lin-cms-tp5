<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 9:59
 */

namespace app\lib\auth;


class AuthMap
{
    private $authList;

    public function __construct()
    {
        $this->authList = [
            'app\api\controller\cms\User',
            'app\api\controller\cms\Admin',
            'app\api\controller\cms\Log',
            'app\api\controller\v1\Book',
        ];
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function run()
    {
        $authList = $this->geAuthList();
        return $authList;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private function geAuthList()
    {
        $authList = [];
        // 遍历需要解析@auth注解的控制器类
        foreach ($this->authList as $value) {
            // 反射控制器类
            $class = new \ReflectionClass($value);
            // 类下面的所有方法的数组
            $methods = $class->getMethods();
            // 类下面所有含有@auth注解的方法的注解内容数组
            $methodAuthList = $this->getMethodsDoc($class, $methods);
            // 插入类权限数组
            array_push($authList, $methodAuthList);
        }
        // 类权限数组分组
        $authListGroup = $this->classAuthListGroup($authList);
        return $authListGroup;

    }

    /**
     * @param $class
     * @param $array
     * @return array
     */
    private function getMethodsDoc($class, $array)
    {
        $data = [];
        foreach ($array as $value) {
            $doc = $class->getMethod($value->name)->getDocComment();
            $methodDoc = $this->getMethodDoc($doc);
            if (!empty($methodDoc)) {
                array_push($data, $methodDoc);
            }
        }
        // 根据权限所属模型对注解内容数组进行分组
        $methodsAuthGroup = $this->authListGroup($data);
        return $methodsAuthGroup;
    }

    /**
     * @param $doc
     * @return mixed
     */
    public function getMethodDoc($doc)
    {
        $pattern = "#(@[auth]+\s*[a-zA-Z0-9,]\(')(.*)(',')(.*)('\))#";

        preg_match_all($pattern, $doc, $matches, PREG_PATTERN_ORDER);

        if (empty($matches[0])) {
            return [];
        }

        return [
            $matches[4][0] => array($matches[2][0] => [''])
        ];
    }

    private function authListGroup($authList)
    {
        $result = [];
        foreach ($authList as $key => $value) {
            foreach ($value as $k => $v) {
                $result[$k] = [];
            }
        }

        foreach ($authList as $key => $value) {
            foreach ($value as $k => $v) {
                array_push($result[$k], $v);
            }
        }

        return $result;
    }

    private function classAuthListGroup($authList)
    {
        $result = [];
        foreach ($authList as $key => $value) {
            foreach ($value as $k => $v) {
                $result[$k] = [];
            }
        }

        foreach ($authList as $key => $value) {
            foreach ($value as $k => $v) {
                foreach ($v as $item) {
                    $arrayKeys = array_keys($item);
                    $arrayValues = array_values($item);
                    $result[$k][$arrayKeys[0]] = $arrayValues[0];
                }
            }
        }

        return $result;
    }
}