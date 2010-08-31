<?php

/**
 * @package Timeline
 **/

class Timeline_TimelinesController extends Omeka_Controller_Action

{
	
	public function init()
	{
		$this->_modelClass = 'Item';
	}

	public function showAction()
	{
		$this->view->item = $this->findById();
	}

}