<?php
/**
 * Timelines Controller
 */
class NeatlineTime_TimelinesController extends Omeka_Controller_AbstractActionController
{
    /**
     * The number of records to browse per page.
     *
     * @var string
     */
    protected $_browseRecordsPerPage = 100;

    protected $_autoCsrfProtection = true;

    /**
     * Initialization.
     *
     * @todo Add our own setting for recordsPerPage instead of using setting
     * intended for Omeka Items.
     */
    public function init()
    {
        $this->_helper->db->setDefaultModelName('NeatlineTime_Timeline');
    }

    /**
     * The browse action.
     *
     */
    public function browseAction()
    {
        if (!$this->getParam('sort_field')) {
            $this->setParam('sort_field', 'added');
            $this->setParam('sort_dir', 'd');
        }

        parent::browseAction();
    }

    public function addAction()
    {
        $form = new NeatlineTime_Form_TimelineAdd;
        $defaults = json_decode(get_option('neatline_time_defaults'), true) ?: array();
        $form->setDefaults($defaults);
        $this->view->form = $form;
        parent::addAction();
    }

    public function editAction()
    {
        $timeline = $this->_helper->db->findById();

        $form = new NeatlineTime_Form_TimelineAdd;
        // Set the existings values.
        $parameters = $timeline->getParameters();
        $existing = array(
            'title' => $timeline->title,
            'description' => $timeline->description,
            'public' => $timeline->public,
            'featured' => $timeline->featured,
        );
        $form->setDefaults(array_merge($parameters, $existing));
        $this->view->form = $form;
        parent::editAction();
    }

    public function queryAction()
    {
        $timeline = $this->_helper->db->findById();

        if(isset($_GET['search'])) {
            $timeline->setQuery($_GET);
            $timeline->save();
            $this->_helper->flashMessenger($this->_getEditSuccessMessage($timeline), 'success');
            $this->_helper->redirector->gotoRoute(array('action' => 'show'));
        }
        else {
            $query = $timeline->getQuery();
            // Some parts of the advanced search check $_GET, others check
            // $_REQUEST, so we set both to be able to edit a previous query.
            $_GET = $query;
            $_REQUEST = $query;
        }

        $this->view->neatline_time_timeline = $timeline;
    }

    public function itemsAction()
    {
        $timeline = $this->_helper->db->findById();
        $items = $timeline->getItems();

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
