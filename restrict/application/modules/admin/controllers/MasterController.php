<?php


class Admin_MasterController extends Admin_Controller_Abstract
{


	public function indexAction()
	{
		$this->view->config = new Zend_Config_Ini(
			APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV
		);
		$this->view->writable = is_writable(
			APPLICATION_PATH . '/configs/application.ini'
		);
	}



	public function updateAction()
	{
		try {
			$adapter = Zend_Db::factory('Pdo_Mysql', [
				'host'		=> $this->getRequest()->getParam("db_host"),
				'username'	=> $this->getRequest()->getParam("db_username"),
				'password'	=> $this->getRequest()->getParam("db_password"),
				'dbname'	=> $this->getRequest()->getParam("db_dbname")
			]);
			$adapter->query('set names utf8');
		}
		catch (Zend_Db_Exception $e){
			$errors[] = "データベースに接続する事が出来ません";
		}
		if (strlen($this->getRequest()->getParam("admin_username")) == 0){
			$errors[] = "ユーザー名が入力されていません";
		}
		if (strlen($this->getRequest()->getParam("admin_password")) == 0){
			$errors[] = "パスワードが入力されていません";
		}

		if (count($errors) > 0)
		{
			$this->view->errors = $errors;
			$this->forward("index");

			return;
		}

		$config = new Zend_Config_Ini(
			APPLICATION_PATH . '/configs/application.ini', null, [
				'skipExtends' => true, 'allowModifications' => true
			]
		);
		$config->production->admin->username = $this->getRequest()->getParam("admin_username");
		$config->production->admin->password = $this->getRequest()->getParam("admin_password");

		$writer = new Zend_Config_Writer_Ini([
			'config'	=> $config,
			'filename'	=> APPLICATION_PATH . '/configs/application.ini'
		]);
		$writer->write();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/{$this->getRequest()->getControllerName()}/index/do/update/"
		);
	}

}