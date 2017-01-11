<?php
namespace HuTong\Xcore;
/**
* 工具
*/
class Tool
{
	/**
	 * @desc 用来生成token之类的唯一ID
	 *
	 * @return string
	 */
	public static function guid()
	{
		if (function_exists('com_create_guid')) {
	        return com_create_guid();
	    } else {
	        mt_srand((double)microtime() * 10000);
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);
	        $uuid = substr($charid, 0, 8) . $hyphen
	            . substr($charid, 8, 4) . $hyphen
	            . substr($charid, 12, 4) . $hyphen
	            . substr($charid, 16, 4) . $hyphen
	            . substr($charid, 20, 12);

	        return $uuid;
	    }
	}
}
