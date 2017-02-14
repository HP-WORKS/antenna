<?php


class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

	public function initResourceLoader()
	{
		$loader = $this->getResourceLoader();
		$loader->addResourceType(
			'controller', 'controllers', 'Controller'
		);
		parent::initResourceLoader();
	}

}