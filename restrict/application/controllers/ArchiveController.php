<?php

/**
 * Class ArchiveController
 * 記事コントローラー
 */
class ArchiveController extends Zend_Controller_Action
{


	/**
	 * 詳細画面
	 * 単一の記事を取得
	 */
	public function showAction()
	{
		try {
			$archive = new Application_Model_DbTable_Archive();
			$aSelect = $archive->select();
			$aSelect
				->setIntegrityCheck(false)
				->from("archive")
				->join("origin", "originCode=archiveOrigin")
				->where("archiveCode = ?", $this->getRequest()->getParam("archiveCode"))
			;
			$row = $archive->fetchRow($aSelect);

			if ($row === null){
				throw new Zend_Db_Exception();
			}
			$this->view->archive = $row;
			$this->view->canonical = $row->archiveUrl;

			//同じサイトの記事を取得
			$aSelect = $archive->select();
			$aSelect
				->setIntegrityCheck(false)
				->from("archive")
				->join("origin", "originCode=archiveOrigin")
				->where("archiveOrigin = ?", $this->view->archive->archiveOrigin)
				->where("archiveCode != ?", $this->getRequest()->getParam("archiveCode"))
				->order("archiveCreate DESC")
				->limit(12, 0)
			;
			$this->view->archives = $archive->fetchAll($aSelect);
		}
		catch (Zend_Db_Exception $e){
			throw new Exception("データが見つかりません", 404);
		}
	}


	/**
	 * アウト記録とリダイレクト処理
	 * @throws Exception
	 */
	public function outAction()
	{
		$this->showAction();

		$outLog = new Application_Model_DbTable_Out();
		$outRow = $outLog->createRow();
		$outRow->outCreate	= time();
		$outRow->outArchive	= $this->view->archive->archiveCode;
		$outRow->outOrigin	= $this->view->archive->originCode;
		$outRow->save();

		//累計アウトも保存
		$archive = new Application_Model_DbTable_Archive();
		$archive->update([
			"archiveOut" => new Zend_Db_Expr("archiveOut + 1")
		], [
			$archive->getAdapter()->quoteInto("archiveCode = ?", $this->view->archive->archiveCode)
		]);

		$this->_helper->redirector->gotoUrlAndExit(
			$this->view->archive->archiveUrl
		);
	}

}
