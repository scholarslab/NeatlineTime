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

    // Escape it for display as HTML.
    if (!is_array($text)) {
        $text = html_escape($text);
    } else {
        $text = array_map('html_escape', $text);
    }

    return $text;
}

/**
 * Returns the current timeline.
 *
 * @since 1.0
 * @return Timeline|null
 */
function get_current_timeline()
{
    return __v()->timeline;
}

/**
 * Sets the current timeline.
 *
 * @since 1.0
 * @param Timeline|null
 * @return void
 */
function set_current_timeline($timeline = null)
{
    __v()->timeline = $timeline;
}

/**
 * Generate an absolute URI to a timeline. Primarily useful for generating
 * permalinks.
 *
 * @since 1.0
 * @param Timeline|null
 * @return void
 */
function abs_timeline_uri($timeline = null)
{
    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    return abs_uri(array('controller'=>'timelines', 'action'=>'show', 'id'=>$timeline->id), 'id');
}

/**
 * Sets the timelines for loop
 *
 * @since 1.0
 * @param array $timelines
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
 * @return array
 */
function get_timelines_for_loop()
{
    return __v()->timelines;
}

/**
 * Loops through timelines assigned to the view.
 *
 * @since 1.0
 * @return mixed
 */
function loop_timelines()
{
    return loop_records('timelines', get_timelines_for_loop(), 'set_current_timeline');
}

/**
 * Determine whether or not there are any timelines in the database.
 *
 * @since 1.0
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
 * @return boolean
 */
function has_timelines_for_loop()
{
    $view = __v();
    return ($view->timelines and count($view->timelines));
}

/**
 * Returns the total number of timelines in the database
 *
 * @since 1.0
 * @return integer
 */
function total_timelines()
{
    return get_db()->getTable('Timeline')->count();
}

/**
 * Returns a link to a specific timeline.
 *
 * @since 1.0
 * @param string HTML for the text of the link.
 * @param array Attributes for the <a> tag. (optional)
 * @param string The action for the link. Default is 'show'.
 * @param Timeline|null
 * @return string HTML
 **/
function link_to_timeline($text = null, $props = array(), $action = 'show', $timeline = null)
{
    if(!$timeline) {
        $timeline = get_current_timeline();
    }

	$text = (!empty($text) ? $text : strip_formatting(timeline('Title', array(), $timeline)));

	return link_to($timeline, $action, $text, $props);
}