<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 9:59
 */

namespace app\lib\auth;


use WangYu\annotation\Annotation;
use WangYu\Reflex;

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
     * @throws \WangYu\exception\ReflexException
     */
    public function run()
    {
        $authList = $this->geAuthList();
        return $authList;
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \WangYu\exception\ReflexException
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
            $methodAuthList = $this->newGetMethodsDoc(new $value(), $methods);
            // 插入类权限数组
            array_push($authList, $methodAuthList);
        }
        // 类权限数组分组
        $authListGroup = $this->classAuthListGroup($authList);
        return $authListGroup;

    }

    // 新版 注解内容获取
    private function newGetMethodsDoc($class,$array){
        $data = [];
        $reflex = new Annotation($class);
        foreach ($array as $value) {
            $authAnnotation = $reflex->setMethod($value->name)->get('auth');

            $authAnnotation = $this->handleAnnotation($authAnnotation);
            if (!empty($authAnnotation)) {
                array_push($data, $authAnnotation);
            }
        }
        // 根据权限所属模型对注解内容数组进行分组
        $methodsAuthGroup = $this->authListGroup($data);
        return $methodsAuthGroup;
    }

    /**
     * @param $class
     * @param $array
     * @return array
     * @throws \WangYu\exception\ReflexException
     */
    private function getMethodsDoc($class, $array)
    {
        $data = [];
        foreach ($array as $value) {
            $reflex = new Reflex($class, $value->name);
            $authAnnotation = $reflex->get('auth');
            $authAnnotation = $this->handleAnnotation($authAnnotation);
            if (!empty($authAnnotation)) {
                array_push($data, $authAnnotation);
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
        // Todo 这里的正则纯粹是不会写暂时这样
        $pattern = "#(@[auth]+\s*[a-zA-Z0-9,]\(')(.*)(',')(.*)('\))#";

        preg_match_all($pattern, $doc, $matches, PREG_PATTERN_ORDER);

        if (empty($matches[0])) {
            return [];
        }

        return [
            $matches[4][0] => array($matches[2][0] => [''])
        ];

    }

    public function handleAnnotation( $annotation)
    {
        // 新版
        if (empty($annotation) || in_array('hidden',$annotation))return[];
        return [
            $annotation[1] => [$annotation[0]=>['']]
        ];
        // 旧版
//        if (!empty($annotation[0]) and in_array('hidden', $annotation[0])) {
//            return [];
//        }

//        return [
//            $annotation[0][1] => [$annotation[0][0] => ['']]
//        ];
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