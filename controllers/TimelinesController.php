<?php

/**
 * @package Timeline
 **/

class Timeline_TimelinesController extends Omeka_Controller_Action

{
	private $logger;
	
	public function init()
	{
		$writer = new Zend_Log_Writer_Stream(LOGS_DIR . DIRECTORY_SEPARATOR . "timeline.log");
		$this->logger = new Zend_Log($writer);
	}

	public function showAction()
	{
		$id = (!$id) ? $this->getRequest()->getParam('id') : $id;
		$this->view->item = $this->findById($id,"Item");
	}

}