<?php

/**
 * 拡張ルーティング
 * 何もしないルーター
 */
class Application_Router
	extends Zend_Controller_Router_Abstract implements Zend_Controller_Router_Interface
{

	/**
	 * do nothing
	 * @param array $userParams
	 * @param null $name
	 * @param bool $reset
	 * @param bool $encode
	 */
	public function assemble($userParams, $name=null, $reset=false, $encode=true){}

	/**
	 * do nothing
	 * @param Zend_Controller_Request_Abstract $dispatcher
	 */
	public function route(Zend_Controller_Request_Abstract $dispatcher){}

}