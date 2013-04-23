<?php
/**
 * Timelines Controller
 */
class NeatlineTime_TimelinesController extends Omeka_Controller_AbstractActionController
{
    /**
     * Initialization.
     *
     * @todo Add our own setting for recordsPerPage instead of using setting
     * intended for Omeka Items.
     */
    public function init()
    {
        $this->_helper->db->setDefaultModelName('NeatlineTimeTimeline');

        $this->_browseRecordsPerPage = get_option('per_page_admin');

    }

    public function addAction()
    {
        require_once NEATLINE_TIME_FORMS_DIR . '/timeline.php';
        $form = new NeatlineTime_Form_Timeline;
        $this->view->form = $form;
        parent::addAction();
    }

    public function editAction()
    {
        $timeline = $this->_helper->db->findById();

        require_once NEATLINE_TIME_FORMS_DIR . '/timeline.php';
        $form = new NeatlineTime_Form_Timeline;
        $form->setDefaults(array('title' => $timeline->title, 'description' => $timeline->description, 'public' => $timeline->public, 'featured' => $timeline->featured));

        $this->view->form = $form;
        parent::editAction();
    }

    public function queryAction()
    {
        $timeline = $this->_helper->db->findById();

        if(isset($_GET['search'])) {
            $timeline->query = serialize($_GET);
            $timeline->save();
            $this->_helper->flashMessenger($this->_getEditSuccessMessage($timeline), 'success');
            $this->_helper->redirector->gotoRoute(array('action' => 'index'));
        }
        else {
            $queryArray = unserialize($timeline->query);
            // Some parts of the advanced search check $_GET, others check
            // $_REQUEST, so we set both to be able to edit a previous query.
            $_GET = $queryArray;
            $_REQUEST = $queryArray;
        }

        $this->view->neatline_time_timeline = $timeline;
    }

    public function itemsAction()
    {
        $timeline = $this->_helper->db->findById();

        $query = $timeline->query ? unserialize($timeline->query) : array();
        $query = neatlinetime_convert_search_filters($query);
        $items = get_db()->getTable('Item')->findBy($query, null);

        $this->view->neatline_time_timeline = $timeline;
        $this->view->items = $items;
    }

    /**
     * Sets the add success message
     */
    protected function _getAddSuccessMessage($timeline)
    {
        return __('The timeline "%s" was successfully added!', $timeline->title);
    }

    /**
     * Sets the edit success message.
     */
    protected function _getEditSuccessMessage($timeline)
    {
        return __('The timeline "%s" was successfully changed!', $timeline->title);
    }

    /**
     * Sets the delete success message
     */
    protected function _getDeleteSuccessMessage($timeline)
    {
        return __('The timeline "%s" was successfully deleted!', $timeline->title);
    }

    /**
     * Sets the delete confirm message
     */
    protected function _getDeleteConfirmMessage($timeline)
    {
        return __('This will delete the timeline "%s" and its associated metadata. This will not delete any items associated with this timeline.', $timeline->title);
    }

}
