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

	/**
	*  @desc 根据两点间的经纬度计算距离
	*        借助 composer require geohash/geohash 可以查找附近的事物
	*  @param float $latitude 纬度值
	*  @param float $longitude 经度值
	*/
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
	{
	    $earth_radius = 6371000;   //半径

	    $dLat = deg2rad($latitude2 - $latitude1);
	    $dLon = deg2rad($longitude2 - $longitude1);

	    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
	    $c = 2 * asin(sqrt($a));
	    $d = $earth_radius * $c;

	    return round($d);   //四舍五入
	}

	/**
	 * @desc 获取文件后缀名
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	function static function getExtension($path)
	{
		$ext = explode('.', $file_name);
        $ext = array_pop($ext);

        return strtolower($ext);
	}
}
