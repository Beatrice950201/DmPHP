<?php
/**
 +------------------------------------------------------------------------------
 * DirectoryIterator实现类 PHP5以上内置了DirectoryIterator类
 +------------------------------------------------------------------------------
 */
namespace library;
class Dir{

    /**
     * 遍历一个文件夹下所有文件和子文件夹的函数
     * User: 一根小腿毛@qq：1368213727
     * @param $dir
     * @return array
     */
    static function list_dir($dir) {
        $files = array();
        if(@$handle = opendir($dir)) {
            while(($file = readdir($handle)) !== false) {
                if($file != ".." && $file != ".") {
                    $str_length_fast = substr($file,"0",1);
                    if(is_dir($dir."/".$file) && "." != $str_length_fast) {
                        $files[$file] = self::list_dir($dir."/".$file);
                    } else {
                        ("." != $str_length_fast) &&  $files[] = $file;
                    }
                }
            }
            closedir($handle);
            return $files;
        }
    }

    /**
     * +----------------------------------------------------------
     * 判断目录是否为空
     * +----------------------------------------------------------
     * @access static
     * +----------------------------------------------------------
     * @param $directory
     * @return bool +----------------------------------------------------------
     * +----------------------------------------------------------
     */
	function isEmpty($directory)
	{
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "." && $file != "..")
			{
				closedir($handle);
				return false;
			}
		}
		closedir($handle);
		return true;
	}

    /**
     * +----------------------------------------------------------
     * 删除目录（包括下面的文件）
     * +----------------------------------------------------------
     * @access static
     * +----------------------------------------------------------
     * @param $directory
     * @return void
     * +----------------------------------------------------------
     */
	public function delDir($directory){
		if (is_dir($directory) == false){
			exit("The Directory Is Not Exist!");
		}
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false){
			if ($file != "." && $file != "..")
			{
			is_dir("$directory/$file")?
				Dir::delDir("$directory/$file"):
				unlink("$directory/$file");
			}
		}
		if (readdir($handle) == false)
		{
			closedir($handle);
			rmdir($directory);
		}
	}

    /**
     * +----------------------------------------------------------
     * 删除目录下面的所有文件，但不删除目录
     * +----------------------------------------------------------
     * @access static
     * +----------------------------------------------------------
     * @param $directory
     * @return void
     * +----------------------------------------------------------
     */
	function del($directory)
	{
		if (is_dir($directory) == false)
		{
			exit("The Directory Is Not Exist!");
		}
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "." && $file != ".." && is_file("$directory/$file"))
			{
				unlink("$directory/$file");
			}
		}
		closedir($handle);
	}

    /**
     * +----------------------------------------------------------
     * 复制目录
     * +----------------------------------------------------------
     * @access static
     * +----------------------------------------------------------
     * @param $source
     * @param $destination
     * @return void
     * +----------------------------------------------------------
     */
	function copyDir($source, $destination)
	{
		if (is_dir($source) == false)
		{
			exit("The Source Directory Is Not Exist!");
		}
		if (is_dir($destination) == false)
		{
			mkdir($destination, 0700);
		}
		$handle=opendir($source);
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..")
			{
				is_dir("$source/$file")?
				Dir::copyDir("$source/$file", "$destination/$file"):
				copy("$source/$file", "$destination/$file");
			}
		}
		closedir($handle);
	}

    /**
     * 创建文件夹
     *User:一根小腿毛；
     *QQ:1368213727
     * @param $dir
     * @return bool
     */
    public static function  make_dir($dir)
    {
        if (!is_dir(dirname($dir))) {
           self::make_dir(dirname($dir));
        }
        return mkdir($dir);
    }



    }