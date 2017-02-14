<?php
class Admin_ArchiveController extends Admin_Controller_Abstract
{


	public function init()
	{
		$this->table
			= new Application_Model_DbTable_Archive();

		parent::init();
	}


	public function indexAction()
	{
		$xSelect = $this->table->select();
		$xSelect
			->setIntegrityCheck(false)
			->from("archive", new Zend_Db_Expr("SQL_CALC_FOUND_ROWS archive.*"))
			->join("origin", "originCode=archiveOrigin")
			->limit(100, $this->getRequest()->getParam("offset", 0))
		;
		$this->view->rowSet		= $this->table->fetchAll($xSelect);
		$this->view->results	= $this->table->fetchCount();
	}


}