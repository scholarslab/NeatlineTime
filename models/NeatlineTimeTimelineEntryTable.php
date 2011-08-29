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
 * Timeline Entry Table class.
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Neatline Time
 * @subpackage Models
 */
class NeatlineTimeTimelineEntryTable extends Omeka_Db_Table
{

    public function findByTimeline(Omeka_Record $timeline)
    {

        $select = $this->getSelect();
        $select->where('`timeline_id` = ?', (int)$timeline->id);
        return $this->fetchObjects($select);

    }

}
