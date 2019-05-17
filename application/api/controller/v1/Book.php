<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/20
 * Time: 19:57
 */

namespace app\api\controller\v1;

use app\api\model\Book as BookModel;
use think\Request;

/**
 * Class Book
 * @route('v1/book')
 * @middleware('Auth','LinRoute')
 * @package app\api\controller\v1
 */
class Book
{
    /**
     * 查询指定bid的图书
     * @route(':bid','get')
     * @param Request $bid
     * @param('bid','bid的图书','require')
     * @return mixed
     */
    public function getBook($bid)
    {
        $result = BookModel::get($bid);
        return $result;
    }

    /**
     * 查询所有图书
     * @route('','get')
     * @return mixed
     */
    public function getBooks()
    {
        $result = BookModel::all();
        return $result;
    }

    /**
     * 搜索图书
     */
    public function search()
    {

    }

    /**
     * 新建图书
     * @route('','post')
     * @param Request $request
     * @param('title','图书名称','require')
     * @param('author','图书作者','require')
     * @param('image','图书img','require')
     * @return \think\response\Json
     */
    public function create(Request $request)
    {
        $params = $request->post();
        BookModel::create($params);
        return writeJson(201, '', '新建图书成功');
    }


    /**
     * 更新图书
     * @route(':id','put')
     * @param Request $request
     * @param('id','图书ID','require')
     * @param('title','图书名称','require')
     * @param('author','图书作者','require')
     * @param('image','图书img','require')
     * @return \think\response\Json
     */
    public function update(Request $request)
    {
        $params = $request->put();
        $bookModel = new BookModel();
        $bookModel->save($params, ['id' => $params['id']]);
        return writeJson(201, '', '更新图书成功');
    }

    /**
     * @auth('删除图书','图书')
     * @route(':bid','delete')
     * @param('id','图书ID','require')
     * @return \think\response\Json
     * @throws \app\lib\exception\token\TokenException
     * @throws \think\Exception
     */
    public function delete($bid)
    {
        BookModel::destroy($bid);

        logger('删除了id为' . $bid . '的图书');
        return writeJson(201, '', '删除图书成功');
    }
}