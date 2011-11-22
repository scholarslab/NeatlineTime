<?php
/**
 * The shared neatlinetime-json browse view for Items
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package NeatlineTime
 * @subpackage Views
 * @link http://github.com/scholarslab/NeatlineTime
 * @since 1.0
 */
$neatlineTimeEvents = array();

while (loop_items()) {
    $itemDates = item('Dublin Core', 'Date', 'all');

    if (!empty($itemDates)) {
        foreach ($itemDates as $itemDate) {
            $neatlineTimeEvent = array();
            $neatlineTimeEvent['start'] = date('c', strtotime($itemDate));
            // $neatlineTimeEvent['end'] = '';
            // $neatlineTimeEvent['latestStart'] = '';
            // $neatlineTimeEvent['latestEnd'] = '';
            // $neatlineTimeEvent['isDuration'] = 'true';
            $neatlineTimeEvent['title'] = item('Dublin Core', 'Title');
            $neatlineTimeEvent['description'] = item('Dublin Core', 'Description');
            $neatlineTimeEvent['link'] = abs_item_uri();

            $neatlineTimeEvents[] = $neatlineTimeEvent;
        }
    }
}

$neatlineTimeArray = array();
$neatlineTimeArray['events'] = $neatlineTimeEvents;

$neatlinetimeJson = json_encode($neatlineTimeArray);

echo $neatlinetimeJson;