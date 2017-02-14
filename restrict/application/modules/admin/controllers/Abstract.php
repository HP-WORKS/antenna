<?php

abstract class Admin_Controller_Abstract extends Zend_Controller_Action
{

	/**
	 * 作業対象のテーブル
	 * @var Application_Model_DbTable_Abstract
	 */
	protected $table;


	/**
	 * セッション
	 * @var Zend_Session_Namespace
	 */
	protected $session;



	public function init()
	{
		$this->session = new Zend_Session_Namespace("admin");

		if ($this->session->login !== true){
			$this->_helper->redirector->gotoUrlAndExit("/admin/login/");
		}

		Zend_Layout::startMvc([
			'layout'		=> 'layout',
			'layoutPath'	=> APPLICATION_PATH . '/modules/admin/views/layout/',
			'content'		=> 'content'
		]);
	}


	/**
	 * 一覧画面
	 */
	public function indexAction(){}


	/**
	 * 追加画面
	 */
	public function addAction(){}


	/**
	 * 詳細画面
	 * @throws Zend_Db_Table_Exception
	 * @throws Zend_Db_Table_Rowset_Exception
	 */
	public function getAction()
	{
		$row = $this->table->find(
			$this->getRequest()->getParam("primary")
		);
		$this->view->row = $row->offsetGet(0);
	}


	/**
	 * インサート処理
	 */
	public function insertAction()
	{
		$row = $this->_insert();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/{$this->getRequest()->getControllerName()}".
			"/get/primary/{$row->{$this->table->getPrimaryKey()}}/do/insert/"
		);
	}


	/**
	 * 更新処理
	 */
	public function updateAction()
	{
		$row = $this->_update();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/{$this->getRequest()->getControllerName()}".
			"/get/primary/{$row->{$this->table->getPrimaryKey()}}/do/update/"
		);
	}


	/**
	 * 削除処理
	 * @throws Zend_Db_Table_Exception
	 * @throws Zend_Db_Table_Row_Exception
	 * @throws Zend_Db_Table_Rowset_Exception
	 */
	public function deleteAction()
	{
		$row = $this->table->find(
			$this->getRequest()->getParam("primary")
		)
			->offsetGet(0);

		$row->delete();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/{$this->getRequest()->getControllerName()}/index/do/delete/"
		);
	}


	/**
	 * インサート処理
	 * @return Zend_Db_Table_Row_Abstract
	 */
	protected function _insert()
	{
		$row = $this->table->createRow();

		foreach ($this->getRequest()->getParams() as $key => $value){
			if (isset($row->{$key})){
				$row->{$key} = $value;
			}
		}
		$row->save();

		return $row;
	}


	/**
	 * 更新処理
	 * @return Zend_Db_Table_Row_Abstract
	 * @throws Zend_Db_Table_Exception
	 * @throws Zend_Db_Table_Rowset_Exception
	 */
	protected function _update()
	{
		$row = $this->table->find(
			$this->getRequest()->getParam("primary")
		)
			->offsetGet(0);

		foreach ($this->getRequest()->getParams() as $key => $value){
			if (isset($row->{$key})){
				$row->{$key} = $value;
			}
		}
		$row->save();

		return $row;
	}


}