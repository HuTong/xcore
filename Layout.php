<?php
namespace HuTong\Xcore;
/**
 * 布局插件
 * 用来控制器渲染视图的时候使用布局
 */
class Layout extends \Yaf\Plugin_Abstract {
	private $_layoutDir;
    private $_layoutFile;
    private $_layoutVars = array();

    public function __construct($layoutDir = null)
    {
        $this->_layoutDir = ($layoutDir) ? $layoutDir : \Yaf\Registry::get('config')->application->view->path;
    }

    public function  __set($name, $value)
    {
        $this->_layoutVars[$name] = $value;
    }

	public function postDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
	{
		$body = $response->getBody();
        /*clear existing response*/
        $response->clearBody();
        /* wrap it in the layout */
        $layout = new \Yaf\View\Simple($this->_layoutDir, array());
        $layout->content = $body;
        $layout->assign('layout', $this->_layoutVars);

        /* set the response to use the wrapped version of the content */
        $response->setBody($layout->render(isset($response->layout) ? $response->layout : 'layout/layout.html', array()));
	}

	public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }

    public function preResponse(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }

    public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }

    public function routerStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {

    }
}
