<?php


class Cli_CronController extends Zend_Controller_Action
{


	public function init()
	{
		if (PHP_SAPI != "cli")
		{
			//HTTPではアクセス出来ない様にする
			$this
				->getResponse()
				->setHttpResponseCode(403)
				->sendResponse()
				;
			exit;
		}
	}


	public function indexAction()
	{
		$this->feedEntry();
		$this->originRanking();

		exit;
	}


	/**
	 * サイトのランキングを生成
	 * @throws Zend_Db_Table_Exception
	 */
	protected function originRanking()
	{
		//OUTカウント
		$origin = new Application_Model_DbTable_Origin();
		$outLog = new Application_Model_DbTable_Out();
		$iSelect = $outLog->select();
		$iSelect
			->setIntegrityCheck(false)
			->from("out", ["count" => "COUNT(*)", "outOrigin"])
			->where("outCreate >= ?", time()-24*3600*7)
			->group("outOrigin")
			->order("count DESC")
		;
		foreach ($outLog->fetchAll($iSelect) as $row)
		{
			$mainRow = $origin->find($row->outOrigin)->offsetGet(0);
			$mainRow->originOut = $row->count;
			$mainRow->save();
		}

		//INカウント
		$inTable = new Application_Model_DbTable_In();
		$iSelect = $inTable->select();
		$iSelect
			->setIntegrityCheck(false)
			->from("in", ["count" => "COUNT(*)", "inOrigin"])
			->where("inCreate >= ?", time()-24*3600*7)
			->group("inOrigin")
			->order("count DESC")
		;
		foreach ($outLog->fetchAll($iSelect) as $row)
		{
			$mainRow = $origin->find($row->outOrigin)->offsetGet(0);
			$mainRow->originIn = $row->count;
			$mainRow->save();
		}
	}


	/**
	 * 記事を読む
	 */
	protected function feedEntry()
	{
		$archive = new Application_Model_DbTable_Archive();
		$origin = new Application_Model_DbTable_Origin();
		$select = $origin->select();
		$select
				->where("originFailure < 2")
				->where("originActive = 1")
		;

		foreach ($origin->fetchAll($select) as $orRow)
		{
			try {
				//現在時刻
				$orRow->originTryAt = time();

				foreach(new Zend_Feed_Rss($orRow->originRss) as $i => $item)
				{
					//10記事目以降は除外
					if ($i === 10) break;

					//記事の重複チェック
					$aSelect = $archive->select();
					$aSelect
						->where("archiveUrl = ?", $item->link())
					;
					if ($archive->fetchRow($aSelect) !== null){
						continue;
					}

					//時刻
					try{
						$current = new Zend_Date();
						$current->setTimestamp(strtotime($item->pubDate()));
					}
					catch (Zend_Date_Exception $e){
						$current->setTimestamp(strtotime($item->date()));
					}

					if ($current->getTimestamp() > time()) {
						$current->setTimestamp(time());
					}

					$body = ($item->content() !== null) ?
						$item->content() : $item->description();

					$mainRow = $archive->createRow();
					$mainRow->archiveCreate	= $current->getTimestamp();
					$mainRow->archiveTitle	= $item->title();
					$mainRow->archiveUrl	= $item->link();
					$mainRow->archiveBody	= strip_tags($body);
					$mainRow->archiveOrigin	= $orRow->originCode;

					//画像
					$zendDom = new Zend_Dom_Query($body);
					$results = $zendDom->query('img');

					foreach ($results as $img)
					{
						$mainRow->archiveImage = $img->getAttribute("src");
						break;
					}
					$mainRow->save();
				}

				//現在時刻
				$orRow->originGetAt = time();
			}
			catch (Zend_Exception $e){
				//失敗
				$orRow->originFailure++;

				//2回取得失敗で停止措置
				if ($orRow->originFailure >= 2) $orRow->originActive = 0;
			}
			$orRow->save();
		}
	}


}