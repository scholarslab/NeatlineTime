<?php
/**
 * NeatlineTimeTimeline record.
 */
class NeatlineTimeTimeline extends Omeka_Record implements Zend_Acl_Resource_Interface
{

    public $title;
    public $description;
    public $query;
    public $creator_id;
    public $public = 0;
    public $featured = 0;
    public $added;
    public $modified;

    /**
     * Mixin initializer.
     *
     * Adds the PublicFeatured mixin to timeline records.
     */
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
        return 'NeatlineTime_Timelines';
    }

    /**
     * Checks whether a Timeline was created by a given user
     *
     * @param User
     * @return boolean
     */
    public function addedBy($user)
    {
        return ($user->id == $this->creator_id);
    }
}
