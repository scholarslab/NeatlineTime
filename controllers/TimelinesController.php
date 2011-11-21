<?php
/**
 * @author Scholars' Lab
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Neatline Time
 * @link http://omeka.org/codex/Plugins/NeatlineTime
 * @since 1.0
 */

/**
 * Timelines Controller
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Neatline Time
 * @subpackage  Controllers
 */

class NeatlineTime_TimelinesController extends Omeka_Controller_Action
{
    public function init()
    {
        $modelName = 'NeatlineTimeTimeline';
        if (version_compare(OMEKA_VERSION, '2.0-dev', '>=')) {
            $this->_helper->db->setDefaultModelName($modelName);
        } else {
            $this->_modelClass = $modelName;
        }

        $this->_browseRecordsPerPage = get_option('per_page_admin');
    }

    public function addAction()
    {
        $timeline = new NeatlineTimeTimeline;

        try {
            if ($timeline->saveForm($_POST)) {
                $successMessage = $this->_getAddSuccessMessage($timeline);
                if ($successMessage != '') {
                    $this->flashSuccess($successMessage);
                }
                $this->_redirect('neatline-time/timelines');
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        }

        require NEATLINE_TIME_FORMS_DIR . '/timeline.php';
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
                $this->_redirect('neatline-time/timelines');
            }
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        }

        require NEATLINE_TIME_FORMS_DIR . '/Timeline.php';
        $form = new Timeline_Form_Timeline;
        $form->setDefaults(array('title' => $timeline->title, 'description' => $timeline->description, 'public' => $timeline->public));

        $this->view->form = $form;
    }

    public function queryAction()
    {
        $timeline = $this->findById();

        if(isset($_GET['search'])) {
            $timeline->query = serialize($_GET);
            $timeline->forceSave();
            $this->redirect->goto('index');
        }
        else {
            $queryArray = unserialize($timeline->query);
            // Some parts of the advanced search check $_GET, others check
            // $_REQUEST, so we set both to be able to edit a previous query.
            $_GET = $queryArray;
            $_REQUEST = $queryArray;
            $this->view->timeline = $timeline;
        }
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
