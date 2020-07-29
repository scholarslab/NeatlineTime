<?php
/**
 * NeatlineTimeTimeline record.
 */
class NeatlineTimeTimeline extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{

    public $title;
    public $description;
    public $query;
    public $creator_id = 0;
    public $public = 0;
    public $featured = 0;
    public $center_date;
    public $added;
    public $modified;

    /**
     * Mixin initializer.
     */
    protected function _initializeMixins()
    {
        $this->_mixins[] = new Mixin_Owner($this, 'creator_id');
        $this->_mixins[] = new Mixin_PublicFeatured($this);
        $this->_mixins[] = new Mixin_Timestamp($this);
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

    protected function beforeSave($args)
    {
        $query = $this->query;
        if (is_array($query)) {
          $this->query = serialize($query);
        }
    }

    /**
     * Get the routing parameters or the URL string to this record.
     */
    public function getRecordUrl($action = 'show')
    {
        $urlHelper = new Omeka_View_Helper_Url;
        $params = array('action' => $action, 'id' => $this->id);
        return $urlHelper->url($params, 'timelineActionRoute');
    }

    /**
    *
    **/
    protected function _validate()
    {
      $validator = new Zend_Validate_Date(array('format' => 'yyyy-MM-dd'));
      if ($this->center_date == '') {
        $this->center_date = '0000-00-00';
      } elseif ($this->center_date !== '0000-00-00' && !$validator->isValid($this->center_date)) {
        $this->addError(null, __('The center date must be in the format YYYY-MM-DD.'));
      }
    }
}
