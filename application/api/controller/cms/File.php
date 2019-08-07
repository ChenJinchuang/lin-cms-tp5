<?php
/*
* Created by DevilKing
* Date: 2019- 06-08
*Time: 16:26
*/

namespace app\api\controller\cms;

use think\facade\Request;
use think\Controller;
use app\lib\file\LocalUploader;
use app\lib\exception\file\FileException;

/**
 * Class File
 * @group('cms/file/')
 * @package app\api\controller\cms
 */
class File extends Controller
{
    /**
     * @doc('上传文件')
     * @route('','post')
     * @success('[
        {
        "id": 1,
        "key": "image",
        "path": "20190807\/54507f925243065a7f749db51bdde3ad.png",
        "url": "http:\/\/127.0.0.1:8000\/uploads\/20190807\/54507f925243065a7f749db51bdde3ad.png"
        },
        {
        "id": "2",
        "key": "thumb",
        "path": "20190807\/db2f03a477be1c28e530c4b3a39b1bdc.png",
        "url": "http:\/\/127.0.0.1:8000\/uploads\/20190807\/db2f03a477be1c28e530c4b3a39b1bdc.png"
        }
        ]')
     * @error('{
        "code": 60000,
        "message": "",
        "request_url": "cms\/file\/"
        }')
     * @return array
     * @throws FileException
     * @throws \LinCmsTp\exception\FileException
     */
    public function postFile()
    {
        try {
            $request = Request::file();
        } catch (\Exception $e) {
            throw new FileException([
                'msg' => '字段中含有非法字符',
            ]);
        }
        $file = (new LocalUploader($request))->upload();
        return $file;
    }
}
