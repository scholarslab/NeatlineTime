<?php

/**
 * Timeline helper functions
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

/**
 * Returns <a href="http://timeglider.com/jquery/?p=json">Timeglider-specific
 * JSON string</a> for a given item.
 *
 * @since 1.0
 * @param Item|null
 *
 * @return string JSON string.
 */
function get_timeline_json_for_item($item = null) {

    $html = '';

    if ($item) {
        $html = "{'title' : " . js_escape(item('Dublin Core', 'Title', array(), $item)) . ","
              . " 'startdate' : " . js_escape(date('r', strtotime(item('Dublin Core', 'Date', array(), $item)))) . ","
              . " 'description' : " . js_escape(item('Dublin Core', 'Description', array(), $item)) . ","
              . " 'link' : " . js_escape(abs_item_uri($item)) . ","
              . " 'id' : '" . $item->id . "'"
              . "}";
    }

    return $html;

}

/**
 * Return specific field for a timeline record.
 *
 * @since 1.0
 * @param string
 * @param array $options
 * @param Timeline|null
 *
 * @return string|array
 */
function timeline($fieldName, $options=array(), $timeline = null)
{

    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    // Retrieve the data to display.
    switch (strtolower($fieldName)) {
        case 'id':
            $text = $timeline->id;
            break;
        case 'title':
            $text = $timeline->title;
            break;
        case 'description':
            $text = $timeline->description;
            break;
        case 'public':
            $text = $timeline->public;
            break;
        case 'featured':
            $text = $timeline->featured;
            break;
        case 'added':
        case 'date added':
            $text = $timeline->added;
            break;
        case 'modified':
        case 'date modified':
            $text = $timeline->modified;
            break;
        case 'creator id':
        case 'creator':
            $text = $timeline->creator_id;
            break;
        case 'permalink':
        case 'url':
            $text = abs_timeline_uri($timeline);
        break;
        default:
            throw new Exception('"' . $fieldName . '" does not exist for timelines!');
            break;
    }

    // Apply the snippet option if passed.
    if (isset($options['snippet'])) {
        $text = snippet($text, 0, (int)$options['snippet']);
    }

    return $text;

}

/**
 * Returns the current timeline.
 *
 * @since 1.0
 *
 * @return Timeline|null
 */
function get_current_timeline()
{

    return __v()->neatlinetimetimeline;

}

/**
 * Sets the current timeline.
 *
 * @since 1.0
 * @param Timeline|null
 *
 * @return void
 */
function set_current_timeline($timeline = null)
{

    __v()->neatlinetimetimeline = $timeline;

}

/**
 * Generate an absolute URI to a timeline. Primarily useful for generating
 * permalinks.
 *
 * @since 1.0
 * @param Timeline|null
 *
 * @return void
 */
function abs_timeline_uri($timeline = null)
{

    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    return abs_uri('neatline-time/timelines/show/' . $timeline->id);
}

/**
 * Sets the timelines for loop
 *
 * @since 1.0
 * @param array $timelines
 *
 * @return void
 */
function set_timelines_for_loop($timelines)
{

    __v()->timelines = $timelines;

}

/**
 * Get the set of timelines for the current loop.
 *
 * @since 1.0
 * 
 * @return array
 */
function get_timelines_for_loop()
{

    return __v()->neatlinetimetimelines;

}

/**
 * Loops through timelines assigned to the view.
 *
 * @since 1.0
 * 
 * @return mixed
 */
function loop_timelines()
{

    return loop_records('neatlinetimetimelines', get_timelines_for_loop(), 'set_current_timeline');

}

/**
 * Determine whether or not there are any timelines in the database.
 *
 * @since 1.0
 *
 * @return boolean
 */
function has_timelines()
{

    return (total_timelines() > 0);

}

/**
 * Determines whether there are any timelines for loop.
 *
 * @since 1.0
 *
 * @return boolean
 */
function has_timelines_for_loop()
{

    $view = __v();
    return ($view->neatlinetimetimelines and count($view->neatlinetimetimelines));

}

/**
 * Returns the total number of timelines in the database
 *
 * @since 1.0
 *
 * @return integer
 */
function total_timelines()
{
    return get_db()->getTable('NeatlineTimeTimeline')->count();
}

/**
 * Returns a link to a specific timeline.
 *
 * @since 1.0
 * @param string HTML for the text of the link.
 * @param array Attributes for the <a> tag. (optional)
 * @param string The action for the link. Default is 'show'.
 * @param Timeline|null
 *
 * @return string HTML
 **/
function link_to_timeline($text = null, $props = array(), $action = 'show', $timeline = null)
{

    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    return '<a href="timelines/show/' . $timeline->id . '" class="edit">' . $timeline->title . '</a>';

}

/**
 * Build link to the edit page for the timeline.
 *
 * @since 1.0
 * @param Timeline|null
 *
 * @return string The link.
 **/
function link_to_edit_timeline($timeline = null)
{

    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    return '<a href="timelines/edit/' . $timeline->id . '" class="edit">Edit</a>';

}

/**
 * Build the delete button.
 *
 * @since 1.0
 * @param Timeline|null
 *
 * @return string The link.
 **/
function timeline_delete_button($timeline = null, $name = null, $value = 'Delete', $attribs = array(), $formName = null, $formAttribs = array())
{

    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    return button_to(
        'timelines/delete-confirm/' . $timeline->id,
        $name,
        $value,
        array('class' => 'delete-confirm'),
        $formName,
        $formAttribs);

}

/**
 * Returns the URL for the Timeglider JSON output of a specific timeline
 *
 * @since 1.0
 * @param Timeline|null
 *
 * @return string URL
 */
function timeline_json_output_url($timeline = null)
{

    if(!$timeline) {
        $timeline = get_current_timeline();
    }

    return uri(array('controller'=>'timelines', 'action'=>'show', 'id' => $timeline->id), 'id', array('output' => 'timeglider-json'));

}

/**
 * Generate the json for TimeGlider.
 *
 * @since 1.0
 * @param Timeline|null
 *
 * @return string string JSON
 */
function timeglider_json_for_timeline($timeline = null)
{

    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    $timegliderJsonArray = array(
        'id' => 'timeline-'.$timeline->id,
        'title' => $timeline->title,
        'initial_zoom' => '40',
        'focus_date' => '2011-04-01 12:00:00'
    );

    if ($timelineDescription = $timeline->description) {
        $timegliderJsonArray['description'] = $timelineDescription;
    }

    $timegliderJsonEventsArray = array();

    // Retrieve all TimelineEntry records for the current Timeline.
    $timelineEntries = $timeline->getTimelineEntries();

    foreach ($timelineEntries as $entry) {

        // If the entry has stuff in its data column.
        if ($data = $entry->data) {

            // If $data is numeric, we'll check to see if we can get an Item with
            // that ID number.
            if (is_numeric($data) && $item = get_item_by_id($data)) {

                $jsonData = array(
                    'title' => item('Dublin Core', 'Title', array(), $item),
                    'startdate'     => date('Y-m-d H:m:s', strtotime(item('Dublin Core', 'Date', array(), $item))),
                    'importance' => 40
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
    return json_encode($timegliderJsonArray);

}
