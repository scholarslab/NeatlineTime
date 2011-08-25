<?php
/**
 * @author Scholars' Lab
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

/**
 * Timelines Controller
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Timeline
 * @subpackage  Controllers
 */
class Timeline_TimelinesController extends Omeka_Controller_Action
{
    // Add our timeglider-json output contexts
    public $contexts = array(
            'browse' => array('timeglider-json'),
            'show'   => array('timeglider-json')
    );

    public function init()
    {
        $modelName = 'Timeline';
        if (version_compare(OMEKA_VERSION, '2.0-dev', '>=')) {
            $this->_helper->db->setDefaultModelName($modelName);
        } else {
            $this->_modelClass = $modelName;
        }

        $this->_browseRecordsPerPage = get_option('per_page_admin');
    }

    public function addAction()
    {
        $timeline = new Timeline;

        try {
            if ($timeline->saveForm($_POST)) {
                $successMessage = $this->_getAddSuccessMessage($timeline);
                if ($successMessage != '') {
                    $this->flashSuccess($successMessage);
                }
                $this->redirect->gotoRoute(array('controller'=>'timelines', 'action'=>'show', 'id'=>$timeline->id), 'id');
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        }

        require NEATLINE_TIME_FORMS_DIR . '/Timeline.php';
        $form = new Timeline_Form_Timeline;

        $this->view->form = $form;
    }

    public function editAction()
    {
        $timeline = $this->findById();

        try {
            if ($timeline->saveForm($_POST)) {
                $successMessage = $this->_getEditSuccessMessage($timeline);
                if ($successMessage != '') {
                    $this->flashSuccess($successMessage);
                }
                $this->redirect->gotoRoute(array('controller'=>'timelines', 'action'=>'show', 'id'=>$timeline->id), 'id');
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        }

        require NEATLINE_TIME_FORMS_DIR . '/Timeline.php';
        $form = new Timeline_Form_Timeline;
        $form->setDefaults(array('title' => $timeline->title, 'description' => $timeline->description, 'public' => $timeline->public));

        $this->view->form = $form;
    }

    /**
     * Sets the add success message
     */
    protected function _getAddSuccessMessage($record)
    {
        $timeline = $record;
        return 'The timeline "' . $timeline->title . '" was successfully added!';        
    }

    /**
     * Sets the edit success message.
     */
    protected function _getEditSuccessMessage($record)
    {
        $timeline = $record;
        return 'The timeline "' . $timeline->title . '" was successfully changed!';        
    }

    /**
     * Sets the delete success message
     */
    protected function _getDeleteSuccessMessage($record)
    {
        $timeline = $record;
        return 'The timeline "' . $timeline->title . '" was successfully deleted!';        
    }

    /**
     * Sets the delete confirm message
     */
    protected function _getDeleteConfirmMessage($record)
    {
        $timeline = $record;
        return 'This will delete the timeline "'. $timeline->title .'" and '
             . 'its associated metadata. This will not delete any items '
             . 'associated with this timeline, but will delete references to '
             . 'this timeline in each item.';
    }

}
