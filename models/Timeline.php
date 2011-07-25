<?php
/**
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

/**
 * Timeline record.
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Timeline
 * @subpackage Models
 */
class Timeline extends Omeka_Record implements Zend_Acl_Resource_Interface
{
    public $title;
    public $description;
    public $creator_id;
    public $public = 0;
    public $featured = 0;
    public $added;
    public $modified;

    protected function _initializeMixins()
    {
        $this->_mixins[] = new PublicFeatured($this);
    }

    /**
     * Things to do in the beforeInsert() hook:
     *
     * Set the creator_id to the current user.
     *
     * @since 1.0
     * @return void
     */
    protected function beforeInsert()
    {
        $user = Omeka_Context::getInstance()->getCurrentUser();
        $this->creator_id = $user->id;
    }

    /**
     * Things to do in the beforeSave() hook:
     *
     * Explicitly set the modified timestamp.
     *
     * @since 1.0
     * @return void
     */
    protected function beforeSave()
    {
        $this->modified = Zend_Date::now()->toString(self::DATE_FORMAT);
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
        return 'Timeline_Timelines';
    }
}