<?php

/**
 * @package Timeline
 **/

class Timeline_TimelinesController extends Omeka_Controller_Action

{
//	private $logger;
	
	public function init()
	{
		$this->_modelClass = 'Item';
//		$writer = new Zend_Log_Writer_Stream(LOGS_DIR . DIRECTORY_SEPARATOR . "timeline.log");
//		$this->logger = new Zend_Log($writer);
	}

	public function showAction()
	{
		$this->view->item = $this->findById();
	}

}