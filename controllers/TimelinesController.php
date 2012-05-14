<?php
/**
 * Timelines Controller
 */
class NeatlineTime_TimelinesController extends Omeka_Controller_Action
{
    /**
     * Initialization.
     *
     * Checks version of Omeka and sets the controller's model accordingly.
     * (Omeka 2.0 does this differently.).
     *
     * Sets the recordsPerPage using the per_page_admin setting.
     *
     * Checks the ACL for the model, and returns a 404 accordingly.
     *
     * @todo Add our own setting for recordsPerPage instead of using setting
     * intended for Omeka Items.
     */
    public function init()
    {
        $modelName = 'NeatlineTimeTimeline';
        if (version_compare(OMEKA_VERSION, '2.0-dev', '>=')) {
            $this->_helper->db->setDefaultModelName($modelName);
        } else {
            $this->_modelClass = $modelName;
        }

        $this->_browseRecordsPerPage = get_option('per_page_admin');

        try {
            $this->_table = $this->getTable($modelName);
            $this->aclResource = $this->findById();
        } catch (Omeka_Controller_Exception_404 $e) {}
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

        require_once NEATLINE_TIME_FORMS_DIR . '/timeline.php';
        $form = new NeatlineTime_Form_Timeline;

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

        require_once NEATLINE_TIME_FORMS_DIR . '/timeline.php';
        $form = new NeatlineTime_Form_Timeline;
        $form->setDefaults(array('title' => $timeline->title, 'description' => $timeline->description, 'public' => $timeline->public, 'featured' => $timeline->featured));

        $this->view->form = $form;
        $this->view->neatlinetimetimeline = $timeline;
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
        }

        $this->view->neatlinetimetimeline = $timeline;
    }

    public function itemsAction()
    {
        $timeline = $this->findById();

        $query = $timeline->query ? unserialize($timeline->query) : array();
        $query = neatlinetime_convert_search_filters($query);
        $query['sort_dir'] = 'a';
        $query['sort_field'] = 'Dublin Core,Date';
        $items = get_db()->getTable('Item')->findBy($query, null);

        $this->view->neatlinetimetimeline = $timeline;
        $this->view->items = $items;
    }

    /**
     * Sets the add success message
     */
    protected function _getAddSuccessMessage($timeline)
    {
        return 'The timeline "' . $timeline->title . '" was successfully added!';
    }

    /**
     * Sets the edit success message.
     */
    protected function _getEditSuccessMessage($timeline)
    {
        return 'The timeline "' . $timeline->title . '" was successfully changed!';
    }

    /**
     * Sets the delete success message
     */
    protected function _getDeleteSuccessMessage($timeline)
    {
        return 'The timeline "' . $timeline->title . '" was successfully deleted!';
    }

    /**
     * Sets the delete confirm message
     */
    protected function _getDeleteConfirmMessage($timeline)
    {
        return 'This will delete the timeline "'. $timeline->title .'" and '
             . 'its associated metadata. This will not delete any items '
             . 'associated with this timeline.';
    }

}
