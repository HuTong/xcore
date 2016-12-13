<?php
namespace HuTong\Xcore;
use Illuminate\Container\Container;
use HuTong\Ylaravel\Database\Manager as Database;
/**
 * Model 基类
 */
class Xmodel
{
	protected $Container;
	protected $db;

	public function __construct()
	{
		$this->initDatabase();
	}

	protected function getContainer()
	{
		if(is_null($this->Container))
		{
			$this->Container = new Container;
		}
		return $this->Container;
	}

	protected function initDatabase()
	{
		if(\Yaf\Registry::has('db'))
		{
			$this->db = \Yaf\Registry::get('db');
		}

		if(!isset($this->db)){

			$this->getContainer();

			$capsule = new Database($this->Container);
			// 创建默认链接
	        $capsule->addConnection(\Yaf\Application::app()->getConfig()->database->toArray());
			// 设置全局静态可访问
			$capsule->setAsGlobal();

			$this->db = $capsule;
		}
		return $this->db;
	}
}
