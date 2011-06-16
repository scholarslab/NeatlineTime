<?php
/**
 * TimelineEntry record.
 *
 * @author      Scholars' Lab
 * @author      Jeremy Boggs
 * @copyright   2011 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version     $Id$
 * @package     Timeline
 * @link        http://omeka.org/codex/Plugins/Timeline
 */
class TimelineEntry extends Omeka_Record implements Zend_Acl_Resource_Interface
{
    public $timeline_id;
    public $type;
    public $data;
    
    /**
     * Required by Zend_Acl_Resource_Interface.
     *
     * Identifies Timeline Entry records as relating to the Timeline_Entries ACL resource.
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Timeline_TimelineEntry';
    }
}