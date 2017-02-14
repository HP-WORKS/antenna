<?php

class Zend_View_Helper_Param
{

	/**
	 * リクエスト変数を取得
	 * @param $key
	 * @return mixed
	 */
	public function param($key)
	{
		return Zend_Controller_Front::getInstance()->getRequest()->getParam($key);
	}


}