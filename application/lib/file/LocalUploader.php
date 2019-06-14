<?php
/*
* Created by DevilKing
* Date: 2019-06-08
*Time: 16:19
*/

namespace app\lib\file;

use think\facade\Config;
use LinCmsTp5\admin\model\LinFile;
use app\lib\exception\file\FileException;
use LinCmsTp\utils\Files;

/**
 * Class LocalUploader
 * @package app\lib\file
 */
class LocalUploader extends Files
{
    /**
     * @return array
     * @throws FileException
     */
    public function upload()
    {
        $ret = [];
        $host = Config::get('file.host') ?? "http://127.0.0.1:8000";
        foreach ($this->files as $key => $file) {
            $md5 = $this->generateMd5($file);
            $exists = LinFile::get(['md5' => $md5]);
            if ($exists) {
                array_push($ret, [
                    'key' => $key,
                    'id' => $exists['id'],
                    'url' => $host . '/uploads/' . $exists['path']
                ]);
            } else {
                $size = $this->getSize($file);
                $info = $file->move($this->storeDir);
                if ($info) {
                    $extension = '.' . $info->getExtension();
                    $path = str_replace('\\','/',$info->getSaveName());
                    $name = $info->getFilename();
                } else {
                    throw new FileException([
                        'msg' => $this->getError,
                        'error_code' => 60001
                    ]);
                }
                $linFile = LinFile::create([
                    'name' => $name,
                    'path' => $path,
                    'size' => $size,
                    'extension' => $extension,
                    'md5' => $md5,
                    'type' => 1
                ]);
                array_push($ret, [
                    'key' => $key,
                    'id' => $linFile->id,
                    'url' => $host . '/uploads/' . $path
                ]);

            }

        }
        return $ret;
    }
}
