<?php

class OriginController extends Zend_Controller_Action
{



	public function indexAction()
	{
		$origin = new Application_Model_DbTable_Origin();
		$select = $origin->select();
		$select
			->setIntegrityCheck(false)
			->order(["originIn DESC", "originOut ASC"])
			;
		$this->view->origins = $origin->fetchAll($select);
	}


	/**
	 * アウト記録とリダイレクト処理
	 * @throws Exception
	 */
	public function outAction()
	{
		$origin = new Application_Model_DbTable_Origin();
		$select = $origin->select();
		$select
			->setIntegrityCheck(false)
			->order(["originIn DESC", "originOut ASC"])
		;
		$row = $origin->fetchRow($select);

		$outLog = new Application_Model_DbTable_Out();
		$outRow = $outLog->createRow();
		$outRow->outCreate	= time();
		$outRow->outOrigin	= $row->originCode;
		$outRow->save();

		$this->_helper->redirector->gotoUrlAndExit($row->originUrl);
	}


}