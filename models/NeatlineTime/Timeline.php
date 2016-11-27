<?php
/**
 * NeatlineTime_Timeline record.
*/
class NeatlineTime_Timeline extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
    public $title;
    public $description;
    public $owner_id = 0;
    public $public = 0;
    public $featured = 0;
    public $parameters;
    public $query;
    public $added;
    public $modified;

    // Temporary unjsoned parameters and query.
    private $_parameters;
    private $_query;

    /**
     * Initialize the mixins for a record.
     */
    protected function _initializeMixins()
    {
        $this->_mixins[] = new Mixin_Owner($this, 'owner_id');
        $this->_mixins[] = new Mixin_PublicFeatured($this);
        $this->_mixins[] = new Mixin_Timestamp($this);
    }

    /**
     * Get the user object.
     *
     * @return User|null
     */
    public function getOwner()
    {
        if ($this->owner_id) {
            return $this->getTable('User')->find($this->owner_id);
        }
    }

    /**
     * Returns the parameters of the record.
     *
     * @throws UnexpectedValueException
     * @return array The parameters of the folder.
     */
    public function getParameters()
    {
        if (is_null($this->_parameters)) {
            // Check if the parameters have been set directly.
            $parameters = empty($this->parameters) ? array() : $this->parameters;
            if (!is_array($parameters)) {
                $parameters = json_decode($parameters, true);
                if (!is_array($parameters)) {
                    throw new UnexpectedValueException(__('Parameters must be an array. '
                        . 'Instead, the following was given: %s.', var_export($parameters, true)));
                }
            }
            $this->_parameters = $parameters;
        }
        return $this->_parameters;
    }

    /**
     * Returns the specified parameter of the record.
     *
     * @param string $name
     * @return string The specified parameter of the record.
     */
    public function getParameter($name)
    {
        $parameters = $this->getParameters();
        return isset($parameters[$name]) ? $parameters[$name] : null;
    }

    /**
     * Get the query.
     */
    public function getQuery()
    {
        if (is_null($this->_query)) {
            // Check if the query has been set directly.
            $query = empty($this->query) ? array() : $this->query;
            if (!is_array($query)) {
                $query = json_decode($query, true);
                if (!is_array($query)) {
                    throw new UnexpectedValueException(__('Query must be an array. '
                        . 'Instead, the following was given: %s.', var_export($parameters, true)));
                }
            }
            $this->_query = $query;
        }
        return $this->_query;
    }

    /**
     * Get a property about the record for display purposes.
     *
     * @param string $property Property to get. Always lowercase.
     * @return mixed
     */
    public function getProperty($property)
    {
        switch($property) {
            case 'added_username':
                $user = $this->getOwner();
                return $user
                    ? $user->username
                    : __('Anonymous');
            case 'parameters':
                return $this->getParameters();
            case 'item_title':
            case 'item_description':
            case 'item_date':
            case 'item_date_end':
            case 'render_year':
            case 'center_date':
                return $this->getParameter($property);
            default:
                return parent::getProperty($property);
        }
    }

    /**
     * Get the default options.
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        // For testing, the defaults should be checked if they are an array.
        $defaults = get_option('neatline_time_defaults');
        return is_array($defaults) ? $defaults : json_decode($defaults, true);
    }

    /**
     * Sets parameters.
     *
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        // Check null.
        if (empty($parameters)) {
            $parameters = array();
        }
        elseif (!is_array($parameters)) {
            throw new InvalidArgumentException(__('Parameters must be an array.'));
        }
        // This is required to manage all the cases and tests.
        $this->parameters = json_encode($parameters);
        $this->_parameters = $parameters;
    }

    /**
     * Set the specified parameter of the record.
     *
     * @param string $name
     * @param var $value
     * @return string The specified parameter of the record.
     */
    public function setParameter($name, $value)
    {
        // Initialize parameters if needed.
        $parameters = $this->getParameters();
        $this->_parameters[$name] = $value;
    }

    /**
     * Set the query.
     *
     * @param array $query
     */
    public function setQuery($query)
    {
        // Check null.
        if (empty($query)) {
            $query = array();
        }
        elseif (!is_array($query)) {
            throw new InvalidArgumentException(__('Query must be an array.'));
        }
        // This is required to manage all the cases and tests.
        $this->query = json_encode($query);
        $this->_query = $query;
    }

    /**
     * Filter post data from form submissions.
     *
     * @param array Dirty post data
     * @return array Clean post data
     */
    protected function filterPostData($post)
    {
        $options = array('inputNamespace' => 'Omeka_Filter');
        $filters = array(
            // Booleans
            'public'   =>'Boolean',
            'featured' =>'Boolean'
        );
        $filter = new Zend_Filter_Input($filters, null, $post, $options);
        $post = $filter->getUnescaped();

        $bootstrap = Zend_Registry::get('bootstrap');
        $acl = $bootstrap->getResource('Acl');
        $currentUser = $bootstrap->getResource('CurrentUser');
        // check permissions to make public and make featured
        if (!$acl->isAllowed($currentUser, 'NeatlineTime_Timelines', 'makePublic')) {
            unset($post['public']);
        }
        if (!$acl->isAllowed($currentUser, 'NeatlineTime_Timelines', 'makeFeatured')) {
            unset($post['featured']);
        }

        // This filter move all parameters inside 'parameters' of the record.
        $defaults = $this->getDefaultOptions();
        $parameters = array_intersect_key($post, $defaults);
        $this->setParameters($parameters);

        return $post;
    }

    /**
     * Get the routing parameters or the URL string to this record.
     *
     * @param string $action
     * @return string|array A URL string or a routing array.
     */
    public function getRecordUrl($action = 'show')
    {
        $urlHelper = new Omeka_View_Helper_Url;
        $params = array('action' => $action, 'id' => $this->id);
        return $urlHelper->url($params, 'timelineActionRoute');
    }

    /**
     * Required by Zend_Acl_Resource_Interface.
     *
     * Identifies Timeline records as relating to the Timeline_Timelines ACL
     * resource.
     *
     * @since 1.0
     * @return string
     */
    public function getResourceId()
    {
        return 'NeatlineTime_Timelines';
    }

    /**
     * Get the list of items according to the query.
     */
    public function getItems()
    {
        $query = $this->getQuery();
        $items = $this->_db->getTable('Item')->findBy($query, null);
        return $items;
    }

    /**
     * Executes before the record is saved.
     */
    protected function beforeSave($args)
    {
        if (is_null($this->owner_id)) {
            $this->owner_id = 0;
        }

        // Be sure to set defaults parameters to simplify queries.
        $parameters = $this->getParameters();
        $defaults = $this->getDefaultOptions();
        $parameters = array_merge($defaults, $parameters);
        $this->parameters = version_compare(phpversion(), '5.4.0', '<')
            ? json_encode($parameters)
            : json_encode($parameters, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $query = $this->getQuery();
        $this->query = version_compare(phpversion(), '5.4.0', '<')
            ? json_encode($query)
            : json_encode($query, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Template method for defining record validation rules.
     *
     * Should be overridden by subclasses.
     *
     * @return void
     */
    protected function _validate()
    {
        $centerDate = $this->getParameter('center_date');
        if (empty($centerDate)) {
            $this->setParameter('center_date', null);
        }
        // Validate the date.
        else {
            $validator = new Zend_Validate_Date(array('format' => 'yyyy-MM-dd'));
            if (!$validator->isValid($centerDate)) {
                $this->addError('center_date', __('The center date must be in the format YYYY-MM-DD.'));
            }
            else {
                $this->setParameter('center_date', $centerDate);
            }
        }
    }
}
