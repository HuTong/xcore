<?php
namespace HuTong\Xcore;
use Illuminate\Container\Container;
use HuTong\Ylaravel\Cache\Manager as Cache;
use HuTong\Ylaravel\Encryption\Manager as Encrypter;
use HuTong\Ylaravel\Filesystem\Manager as Filesystem;
use HuTong\Ylaravel\Redis\Manager as Redis;
use HuTong\Ylaravel\Session\Manager as Session;
use HuTong\Ylaravel\Database\Manager as Database;
use HuTong\Xcore\Pagination as Pagination;
/**
 * 过滤一些用户输入的信息
 */
class Xcontroller extends \Yaf\Controller_Abstract
{
	protected $Container;
	protected $layout;

	public function init()
	{
		$this->getContainer();

		$this->initSession();

		//do not call render for ajax request
		if($this->isJson())
		{
			\Yaf\Dispatcher::getInstance()->autoRender(FALSE);
		}

		$this->setViewPath(\Yaf\Registry::get('config')->application->view->path);

		if($this->layout)
		{
			$this->setLayout($this->layout);
		}
	}

	public function setLayout($layout)
	{
		$this->getResPonse()->layout = $layout;
	}

	protected function getContainer()
	{
		if(is_null($this->Container))
		{
			$this->Container = new Container;
		}
		return $this->Container;
	}
	/**
	 * @desc 返回请求的类型
	 *
	 * @return GET/POST/HEAD/PUT/CLI
	 */
	protected function getMethod()
	{
		return $this->getRequest()->getMethod();
	}

	/**
	 * @desc 是否是get请求
	 *
	 * @return boolean true/false
	 */
	protected function isGet()
	{
		return $this->getRequest()->isGet();
	}

	/**
	 * @desc 是否是post请求
	 *
	 * @return boolean true/false
	 */
	protected function isPost()
	{
		return $this->getRequest()->isPost();
	}

	/**
	 * @desc 是否是cli请求
	 *
	 * @return boolean true/false
	 */
	protected function isCli()
	{
		return $this->getRequest()->isCli();
	}

	/**
	 * @desc 是否是ajax请求
	 *
	 * @return boolean true/false
	 */
	protected function isJson()
	{
		return $this->getRequest()->isXmlHttpRequest();
	}

	/**
	 * @desc 获取地址栏k-v数据对
	 *
	 */
	protected function getParam($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->getParam($name, $default_value);

		if($isFilter){
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

	/**
	 * @desc 获取地址栏 所有的k-v数据对
	 *
	 */
	protected function getParams()
	{
		$data = $this->getRequest()->getParams();

		return $this->xssClean($data);
	}

	/**
	 * @desc 可以获取地址和？后面部分的参数数据
	 * eg: 可以获取 getParam、getQuery的数据
	 */
	protected function get($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->get($name, $default_value);

		if($isFilter){
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

	/**
	 * @desc 获取地址 ？ 后面部分的参数数据
	 *
	 */
	protected function getQuery($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->getQuery($name, $default_value);

		if($isFilter){
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

	protected function getPost($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->getPost($name, $default_value);

		if($isFilter){
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

	protected function getCookie($name, $default_value = null, $isFilter = true)
	{
		$data = $this->getRequest()->getCookie($name, $default_value);

		if($isFilter){
			return $this->xssClean($data);
		}else{
			return $data;
		}
	}

    /**
     * 数据安全过滤
     * @param $data
     * @return mixed
     */
    protected function xssClean($data)
    {
        if(is_array($data)){
            return filter_var_array($data, FILTER_SANITIZE_STRING);
        }else{
            return filter_var($data, FILTER_SANITIZE_STRING);
        }
    }

	/**
	 * @desc 开启redis
	 *
	 * @return object
	 */
	public function initRedis()
	{
		if(!isset($this->redis)){
			$this->redis = new Redis(\Yaf\Application::app()->getConfig()->redis->toArray(), $this->Container);
		}
		return $this->redis;
	}

	public function initFileSystem()
	{
		if(!isset($this->fileSystem)){
			$this->fileSystem = new Filesystem(\Yaf\Application::app()->getConfig()->filesystem->toArray(), $this->Container);
		}
		return $this->fileSystem;
	}

	public function initCache()
	{
		if(!isset($this->cache)){
			$cache = new Cache($this->Container);
			$cache->addConnection(\Yaf\Application::app()->getConfig()->cache->toArray());
			//$cache->setAsGlobal();

			$this->cache = $cache;
		}
		return $this->cache;
	}

	public function initEncrypter()
	{
		if(!isset($this->encrypter)){
			$this->encrypter = new Encrypter(\Yaf\Application::app()->getConfig()->encryption->toArray(), $this->Container);
		}
		return $this->encrypter;
	}

	public function initSession()
	{
		$this->initEncrypter();
		$config = \Yaf\Application::app()->getConfig()->session->toArray();

		switch ($config['driver']) {
			case 'file':
				$this->initFileSystem();
				break;
			case 'redis':
				$this->initRedis();
				$this->initCache();
				break;
			case 'database':
				$this->initDatabase();
				break;
			default:
				echo 'For the type of monitoring'; return false;
				break;
		}
		$session = new Session($config, $this->Container);
	}

	public function initDatabase()
	{
		if(!isset($this->db)){
			$capsule = new Database($this->Container);
			// 创建默认链接
	        $capsule->addConnection(\Yaf\Application::app()->getConfig()->database->toArray());
			// 设置全局静态可访问
			$capsule->setAsGlobal();

			$this->db = $capsule;

			\Yaf\Registry::set('db', $this->db);
		}
		return $this->db;
	}

	public function getPagination($page_total = 1, $page_size = 1, $page_current = 1, $page_url, $show_pages = '')
	{
		$page = new Pagination();

		return $page->page_show($page_total, $page_size, $page_current, $page_url, $show_pages);
	}
}
