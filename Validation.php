<?php
namespace HuTong\Xcore;
/**
* 数据验证
*/
class Validation
{
	public static function isMobile($val)
	{
		return preg_match('/^1[34578]\d{9}$/', $val);
	}

	public static function isEmail($val)
	{
		return (bool)filter_var($val, FILTER_VALIDATE_EMAIL);
	}

	public static function isIp($val, $type = 0)
	{
		switch ($type) {
			case 1:
				return (bool)filter_var($val, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
				break;
			case 2:
				return (bool)filter_var($val, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
				break;
			default:
				//支持ipv4和ipv6
				return (bool)filter_var($val, FILTER_VALIDATE_IP);
				break;
		}

	}

	// 必须添加http
	public static function isUrl($val, $type = 0)
	{
		switch ($type) {
			case 0:
				//要求 URL 是 RFC 兼容 URL。（比如：http://example）
				return (bool)filter_var($val, FILTER_VALIDATE_URL);
				break;
			case 1:
				//要求 URL 包含主机名（http://www.example.com）
				return (bool)filter_var($val, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
				break;
			case 2:
				//要求 URL 在主机名后存在路径（比如：eg.com/example1/）
				return (bool)filter_var($val, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
				break;
			case 3:
				//要求 URL 存在查询字符串（比如："eg.php?age=37"）
				return (bool)filter_var($val, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED);
				break;
			default:
				//要求 URL 是 RFC 兼容 URL。（比如：http://example）
				return (bool)filter_var($val, FILTER_VALIDATE_URL);
				break;
		}
	}

	public static function isFloat($val)
	{
		return filter_var($val, FILTER_VALIDATE_FLOAT) === false ? false : true;
	}

	public static function isInt($val, $min = null, $max = null)
	{
		if(is_null($min) && is_null($max)){
			return filter_var($val, FILTER_VALIDATE_INT) === false ? false : true;
		}else{
			$int_options = array("options"=>array("min_range"=>$min, "max_range"=>$max));
			return filter_var($val, FILTER_VALIDATE_INT, $int_options) === false ? false : true;
		}
	}

	public static function isBoolean($val)
	{
		return filter_var($val, FILTER_VALIDATE_BOOLEAN) === false ? false : true;
	}

	public static function isLower($val)
	{
		return preg_match('/^[a-z]+$/', $val) ? true : false;
	}

	public static function isChinese($val)
	{
		return preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z_]+$/u", $val) ? true : false;
	}

	public static static function isUpper($val)
	{
		return preg_match("/^[A-Z]+$/", $val) ? true : false;
	}

	//是否是只有26个大小写英文字符的字符串
	public static function isAlpha($val)
	{
		return preg_match("/^[a-zA-Z]+$/", $val) ? true : false;
	}

	//是否只含有26个大小写英文字符和数字字符的字符串
	public static function isAlnum($val)
	{
		return preg_match("/^[a-zA-Z\d]+$/", $val);
	}

	public static function isPasswd($val, $len = 6) {
		return preg_match('/^[.a-z_0-9-!@#$%\^&*()]{' . $len . ',32}$/ui', $val) ? true : false;
	}

	public static function isNickname($val) {
		return preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z_]{2,16}$/u", $val) ? true : false;
	}
}
