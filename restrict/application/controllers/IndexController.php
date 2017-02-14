<?php

class IndexController extends Zend_Controller_Action
{


	/**
	 * INカウント処理
	 * Set-Cookieヘッダを返す
	 */
	public function inAction()
	{
		try {
			//初めてのアクセス
			if ($this->getRequest()->getCookie('in') === null)
			{
				//リファラ無し終了
				if ($this->getRequest()->getServer("HTTP_REFERER") === null) throw new Exception();

				$ref = parse_url(
					$this->getRequest()->getServer("HTTP_REFERER")
				);
				//リファラ無し終了
				if ($ref === false) throw new Exception();
				//リファラが同一なので終了
				if ($ref["host"] === $this->getRequest()->getServer("HTTP_HOST")) throw new Exception();

				$origin = new Application_Model_DbTable_Origin();
				$select = $origin->select();
				$select
					->where("originDomain = ?", $ref["host"])
					;
				$orRow = $origin->fetchRow($origin);

				//未登録サイトなので記録無し
				if ($orRow === null) throw new Exception();

				$inTable = new Application_Model_DbTable_In();
				$row = $inTable->createRow();
				$row->inCreate	= time();
				$row->inReferer	= $this->getRequest()->getServer("HTTP_REFERER");
				$row->inOrigin	= $orRow->originCode;
				$row->save();
			}
		}
		catch (Exception $e){
		}
		//cookie設定
		$this->getResponse()->setHeader("Set-Cookie", "in=1; path=/;");
	}


	public function indexAction()
	{
		$this->inAction();

		$archive = new Application_Model_DbTable_Archive();
		$aSelect = $archive->select();
		$aSelect
			->setIntegrityCheck(false)
			->from("archive", new Zend_Db_Expr("SQL_CALC_FOUND_ROWS archive.*"))
			->join("origin", "originCode=archiveOrigin")
			->limit(24, $this->getRequest()->getParam("offset", 0))
		;

		if (strlen($this->getRequest()->getParam("word")) > 0)
		{
			//スペースでキーワード分解
			$keyWord = preg_split(
				"/\s/", mb_convert_kana($this->getRequest()->getParam("word"), "s")
			);
			foreach ($keyWord as $word){
				$aSelect
					->where("archiveTitle LIKE ? OR archiveBody LIKE ?", "%{$word}%");
			}
		}

		if ($this->getRequest()->getParam("sort") === "favorite"){
			//人気順
			$aSelect
				->order(["archiveOut DESC", "archiveCreate DESC"]);
		}
		else {
			//新着順
			$aSelect
				->order(["archiveCreate DESC", "archiveOut DESC"]);
		}

		$this->view->archives	= $archive->fetchAll($aSelect);
		$this->view->results	= $archive->fetchCount();
	}


}

