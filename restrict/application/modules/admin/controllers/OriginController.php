<?php
class Admin_OriginController extends Admin_Controller_Abstract
{


	public function init()
	{
		$this->table
			= new Application_Model_DbTable_Origin();

		parent::init();
	}


	public function indexAction()
	{
		$xSelect = $this->table->select();
		$xSelect
			->limit(100, $this->getRequest()->getParam("offset", 0))
			;
		$this->view->rowSet = $this->table->fetchAll($xSelect);
	}


	/**
	 * 有効化処理
	 * 失敗回数もリセットする
	 */
	public function activeAction()
	{
		$this->getRequest()->setParam("originActive", 1);
		$this->getRequest()->setParam("originFailure", 0);
		$this->_update();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/{$this->getRequest()->getControllerName()}/index/do/update/"
		);
	}


	/**
	 * 無効化処理
	 */
	public function disableAction()
	{
		$this->getRequest()->setParam("originActive", 0);
		$this->_update();

		$this->_helper->redirector->gotoUrlAndExit(
			"/admin/{$this->getRequest()->getControllerName()}/index/do/update/"
		);
	}


}