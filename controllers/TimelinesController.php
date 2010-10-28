<?php

/**
 * Timeline Controller responds to actions from the Omeka framework
 *
 * @author    Scholars' Lab
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   $Id$
 * @package   Timeline
 * @link      http://omeka.org/codex/Plugins/Timeline
 */

class Timeline_TimelinesController extends Omeka_Controller_Action
{
    /**
     * Attach to the Item model
     */
    public function init()
    {
        $this->_modelClass = 'Item';
    }

    /**
     * Look up an item and attach it to the view request for the show action
     */
    public function showAction()
    {
        $this->view->item = $this->findById();
    }

    /**
     * Attach an item to a panel
     */
    public function panelAction() {
        $this->view->item = $this->findById();
    }
}