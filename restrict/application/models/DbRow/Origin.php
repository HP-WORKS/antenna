<?php

class Application_Model_DbRow_Origin extends Zend_Db_Table_Row_Abstract
{


	/**
	 * 最新記事を取得
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function latestArchives()
	{
		$archive = new Application_Model_DbTable_Archive();
		$aSelect = $archive->select();
		$aSelect
			->setIntegrityCheck(false)
			->from("archive")
			->join("origin", "originCode=archiveOrigin")
			->where("archiveOrigin = ?", $this->originCode)
			->order("archiveCreate DESC")
			->limit(5, 0)
		;
		return $archive->fetchAll($aSelect);
	}



}