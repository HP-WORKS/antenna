<?php
class Admin_LoginController extends Admin_Controller_Abstract
{


	public function init()
	{
		$this->session = new Zend_Session_Namespace("admin");
		$this->_helper->layout->disableLayout();
	}


	/**
	 * ログイン画面
	 */
	public function indexAction(){}


	/**
	 * ログイン処理
	 */
	public function tryAction()
	{
		try {
			$config = new Zend_Config_Ini(
				APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV
			);
			if ($this->getRequest()->getParam("username") !== $config->admin->username){
				throw new Zend_Validate_Exception();
			}
			if ($this->getRequest()->getParam("password") !== $config->admin->password){
				throw new Zend_Validate_Exception();
			}
			$this->session->login = true;

			$this->_helper->redirector->gotoUrlAndExit("/admin/");
		}
		catch (Zend_Validate_Exception $e){
		}
		$this->_helper->redirector->gotoUrlAndExit("/admin/login/");
	}


	/**
	 * ログアウト処理
	 */
	public function logoutAction()
	{
		Zend_Session::destroy();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/login/index/do/logout/"
		);
	}


}