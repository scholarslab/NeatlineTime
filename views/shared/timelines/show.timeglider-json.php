<?php
/**
 * The shared timeglider-json show view for Timelines
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

$timegliderJsonArray = array(
    'id' => 'timeline-'.timeline('id'),
    'title' => timeline('title'),
    'initial_zoom' => '40'
);

$timegliderJsonEventsArray = array();

// Retrieve all TimelineEntry records for the current Timeline.
$timelineEntries = $timeline->getTimelineEntries();

foreach ($timelineEntries as $entry) {

    // If the entry has stuff in its data column.
    if ($data = $entry->data) {

        // If $data is numeric, we'll check to see if we can get an Item with
        // that ID number.
        if (is_numeric($data) && $item = get_item_by_id($data)) {

            set_current_item($item);
            $jsonData = array(
                'title' => item('Dublin Core', 'Title'),
                'startdate'     => date('Y-m-d H:m:s', strtotime(item('Dublin Core', 'Date')))
            );

        } else {

            $jsonData = unserialize($data);

        }

        // Set a unique ID for the timeglider JSON entry.
        $jsonData['id'] = 'timeline-entry-'.$entry->id;

        // Add the jsonData to the Timeglider JSON events array.
        $timegliderJsonEventsArray[] = $jsonData;
    }
}

// If the Timeglider JSON events array isn't empty, add it to the overall array.
if (!empty($timegliderJsonEventsArray)) {

    $timegliderJsonArray['events'] = $timegliderJsonEventsArray;

}

// Roll that beautiful bean footage.
echo '['.json_encode($timegliderJsonArray).']';