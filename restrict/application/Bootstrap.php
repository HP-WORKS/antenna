<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


	protected function _initLayout()
	{
		Zend_Layout::startMvc([
			'layout'		=> 'layout',
			'layoutPath'	=> APPLICATION_PATH . '/views/layout/',
			'content'		=> 'content'
		]);
	}


}

