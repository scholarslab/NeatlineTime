<?php
/**
 * PHP 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 */

/**
 * Timelines Controller
 *
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
