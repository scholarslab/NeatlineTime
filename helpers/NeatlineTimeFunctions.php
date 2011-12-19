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
 *
 * Timeline helper functions
 */

/**
 * Return specific field for a timeline record.
 *
 * @since 1.0
 * @param string
 * @param array $options
 * @param Timeline|null
 *
 * @return string
 */
function timeline($fieldname, $options = array(), $timeline = null)
{

    $timeline = $timeline ? $timeline : get_current_timeline(); 

    $fieldname = strtolower($fieldname);  
    $text = $timeline->$fieldname;

    if (strlen($text) == 0){
        throw new Exception("Field doesn't exist");
    }

    if(isset($options['snippet'])) {
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

    $text = $text ? $text : $timeline->title;

    $route = 'neatline-time/timelines/'.$action.'/'.$timeline->id;
    $uri = uri($route);
    $props['href'] = $uri;

    return '<a ' . _tag_attributes($props) . '>' . $text . '</a>';

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
        uri('neatline-time/timelines/delete-confirm/' . $timeline->id),
        $name,
        $value,
        array('class' => 'delete-confirm'),
        $formName,
        $formAttribs);

}

/**
 * Queues JavaScript and CSS for NeatlineTime in the page header.
 *
 * @since 1.0
 * @return void.
 */
function queue_timeline_assets()
{
    $headScript = __v()->headScript();

    // Check useInternalJavascripts in config.ini.
    $config = Omeka_Context::getInstance()->getConfig('basic');
    $useInternalJs = isset($config->theme->useInternalJavascripts)
            ? (bool) $config->theme->useInternalJavascripts
            : false;

    if ($useInternalJs) {
        $timelineVariables = 'Timeline_ajax_url="'.src('simile-ajax-api.js', 'javascripts/simile/ajax-api').'"; '
                           . 'Timeline_urlPrefix="'.dirname(src('timeline-api.js', 'javascripts/simile/timeline-api')).'/"; '
                           . 'Timeline_parameters="bundle=true";';

        $headScript->appendScript($timelineVariables);
        $headScript->appendFile(src('timeline-api.js', 'javascripts/simile/timeline-api'));
    } else {
        $headScript->appendFile('http://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true');
    }
    $headScript->appendFile(src('neatline-time-scripts.js', 'javascripts'));

    $headScript->appendScript('SimileAjax.History.enabled = false; window.jQuery = SimileAjax.jQuery');

    queue_css('neatlinetime-timeline');
}

/**
 * Returns the URI for a timeline's query
 *
 * @since 1.0
 * @param Timeline
 * @return string URL
 */
function neatlinetime_json_uri_for_timeline($timeline = null)
{
    if (!$timeline) {
        $timeline = get_current_timeline();
    }

    $query = $timeline->query;

    if ($query) {
        $params = unserialize($query);
    }

    return items_output_uri('neatlinetime-json', $params);
}

/**
 * Displays timeline for a given NeatlineTime Timeline record.
 *
 * @since 1.0
 * @param NeatlineTimeTimeline|null
 * @return string HTML
 */
function neatlinetime_display_timeline($timeline = null)
{
    $timeline = $timeline ? $timeline : get_current_timeline();

    $timelineId = text_to_id(html_escape($timeline->title) . ' ' . $timeline->id, 'neatlinetime');

    $html = '';
    $html = '<script>'
          . 'jQuery(document).ready(function() {'
          . 'NeatlineTime.loadTimeline("'. $timelineId .'", "'. neatlinetime_json_uri_for_timeline() . '");' 
          . '});'
          . '</script>'
          . '<div id="'. $timelineId .'" class="neatlinetime-timeline"></div>';

    return $html;
}