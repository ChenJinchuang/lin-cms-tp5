<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/26
 * Time: 21:42
 */

namespace app\api\model;


use think\Exception;

class Log extends BaseModel
{
    protected $table = 'lin_log';
    protected $createTime = 'time';
    protected $updateTime = false;
    protected $autoWriteTimestamp = 'datetime';

    /**
     * @param $params
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getLogs($params)
    {
//        $userOption = [];
//        if (isset($params['user_name'])) {
//            $userOption = ['user_name' => $params['user_name']];
//        }

//        if (isset($params['start']) && isset($params['end'])) {
//            $dateOption = [
//                $params['start'],
//                $params['start']
//            ];
//        }

//        $userList = self::whereBetweenTime('time', isset($params['start']) ? $params['start'] : null,
//            isset($params['end']) ? $params['end'] : null)
//            ->where($userOption)
//            ->paginate($params['count'], false, ['page' => $params['page']]);
        $filter = [];
        if (isset($params['name'])) {
            $filter ['user_name'] = $params['name'];
        }

        if (isset($params['start']) && isset($params['end'])) {
            $filter['time'] = [$params['start'], $params['end']];
        }

        $userList = self::withSearch(['user_name', 'time'], $filter)
            ->paginate($params['count'], false, ['page' => $params['page']]);

        $result = [
            'collection' => $userList->items(),
            'total_nums' => $userList->total()
        ];
        return $result;

    }

    public function searchUserNameAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->where('user_name', $value);
        }
    }

    public function searchTimeAttr($query, $value, $data)
    {
        if (!empty($value)) {
            $query->whereBetweenTime('time', $value[0], $value[1]);
        }
    }
}