<?php

abstract class Application_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{

	/**
	 * プラリマリーキー名を取得
	 * @return string
	 */
	public function getPrimaryKey()
	{
		return (is_array($this->_primary) === true) ? current($this->_primary) : $this->_primary;
	}


	/**
	 * カウント取得
	 * @return int
	 */
	public function fetchCount()
	{
		$stmt = $this
			->getAdapter()
			->query("SELECT FOUND_ROWS();")
		;
		$result = $stmt->fetchColumn(0);

		return (int)$result;
	}


}