<?php
/**
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Neatline Time
 * @link http://omeka.org/codex/Plugins/NeatlineTime
 * @since 1.0
 */

/**
 * TimelineEntry record.
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Neatline Time
 * @subpackage Models
 */
class NeatlineTimeTimelineEntry extends Omeka_Record implements Zend_Acl_Resource_Interface
{
    public $timeline_id;
    public $data;

    /**
     * Required by Zend_Acl_Resource_Interface.
     *
     * Identifies Timeline Entry records as relating to the
     * Timeline_TimelineEntry ACL resource.
     *
     * @since 1.0
     * @return string
     */
    public function getResourceId()
    {
        return 'Timeline_TimelineEntry';
    }
}
