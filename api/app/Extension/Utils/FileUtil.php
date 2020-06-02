<?php
/**
 * @Desc:文件
 * @author: hbh
 * @Time: 2020/4/10   19:52
 */

namespace App\Extension\Utils;


class FileUtil
{
    /**解决pathinfo()中文乱码 (php语言的坑)
     * @param $filepath
     * @return array
     * @author: hbh
     * @Time: 2020/4/9   16:35
     */
    public static function path_info($filepath)
    {
        $path_parts = array();
        $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')), "/") . "/";
        $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')), "/");
        $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);
        $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')), "/");
        return $path_parts;
    }
}
