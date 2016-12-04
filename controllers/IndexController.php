<?php
/**
 * Dummy index controller.
 *
 * Simply redirects to the TimelinesController.
 */
class NeatlineTime_IndexController extends Omeka_Controller_AbstractActionController
{
    /**
     * Redirect to timelines/browse.
     */
    public function indexAction()
    {
        $this->_helper->redirector('browse', 'timelines');
    }
}
