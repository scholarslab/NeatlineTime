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
 * Timeline record.
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package NeatlineTime
 * @subpackage Models
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

    public function addedBy($user)
    {
        return ($user->id == $this->creator_id);
    }
}
