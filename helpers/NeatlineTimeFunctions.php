<?php
/**
 * NeatlineTime helper functions
 */

/**
 * Return specific field for a timeline record.
 *
 * @since 1.0
 * @param string
 * @param array $options
 * @param NeatlineTimeTimeline|null
 * @return string
 */
function timeline($fieldname, $options = array(), $timeline = null)
{

    $timeline = $timeline ? $timeline : get_current_timeline();

    $fieldname = strtolower($fieldname);
    $text = $timeline->$fieldname;

    if(isset($options['snippet'])) {
        $text = snippet($text, 0, (int)$options['snippet']);
    }

    if ($fieldname == 'query') {
        $text = unserialize($text);
    }

    return $text;

}

/**
 * Returns the current timeline.
 *
 * @since 1.0
 * @return NeatlineTimeTimeline|null
 */
function get_current_timeline()
{

    return __v()->neatlinetimetimeline;

}

/**
 * Sets the current timeline.
 *
 * @since 1.0
 * @param NeatlineTimeTimeline|null
 * @return void
 */
function set_current_timeline($timeline = null)
{

    __v()->neatlinetimetimeline = $timeline;

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

    __v()->neatlinetimetimelines = $timelines;

}

/**
 * Get the set of timelines for the current loop.
 *
 * @since 1.0
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
 * @return mixed
 */
function loop_timelines()
{

    return loop_records('neatlinetimetimelines', get_timelines_for_loop(), 'set_current_timeline');

}

/**
 * Determines whether there are any timelines in the database.
 *
 * @since 1.0
 * @return boolean
 */
function has_timelines()
{

    return (total_timelines() > 0);

}

/**
 * Determines whether there are any timelines to loop on the view.
 *
 * @since 1.0
 * @return boolean
 */
function has_timelines_for_loop()
{

    $view = __v();
    return ($view->neatlinetimetimelines and count($view->neatlinetimetimelines));

}

/**
 * Returns the total number of timelines in the database.
 *
 * @since 1.0
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
 * @param NeatlineTimeTimeline|null
 *
 * @return string HTML
 **/
function link_to_timeline($text = null, $props = array(), $action = 'show', $timeline = null)
{

    $timeline = $timeline ? $timeline : get_current_timeline();

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
 * @param NeatlineTimeTimeline|null
 *
 * @return string The delete button.
 **/
function timeline_delete_button($timeline = null)
{

    $timeline = $timeline ? $timeline : get_current_timeline();

    return button_to(
        uri('neatline-time/timelines/delete-confirm/' . $timeline->id),
        null,
        'Delete',
        array('class' => 'delete-confirm')
    );

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
    $headScript->appendFile(src('neatline-time-scripts.js', 'javascripts'));

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

    $headScript->appendScript('SimileAjax.History.enabled = false; window.jQuery = SimileAjax.jQuery');

    queue_css('neatlinetime-timeline');
}

/**
 * Returns the URI for a timeline's json output.
 *
 * @since 1.0
 * @param NeatlineTimeTimeline|null
 * @return string URL the items output uri for the neatlinetime-json output.
 */
function neatlinetime_json_uri_for_timeline($timeline = null)
{
    $timeline = $timeline ? $timeline : get_current_timeline();

    $query = $timeline->query ? unserialize($timeline->query) : array();

    return items_output_uri('neatlinetime-json', $query);
}

/**
 * Construct id for container div.
 *
 * @since 1.0
 * @param NeatlineTimeTimeline|null
 * @return string HTML
 */
function neatlinetime_timeline_id($timeline = null)
{
    $timeline = $timeline ? $timeline : get_current_timeline();
    return text_to_id(html_escape($timeline->title) . ' ' . $timeline->id, 'neatlinetime');
}

/**
 * Returns a string detailing the parameters of a given query array.
 *
 * @param array A search array. If null, the function will check the front
 * controller for any parameters.
 * @return string HTML
 */
function neatlinetime_display_search_query($query = null)
{
    $html = '';

    if ($query === null) {
        $query = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    }

    if (!empty($query)) {
        $db = get_db();

        $displayList = '';
        $displayArray = array();

        foreach ($query as $key => $value) {
            $filter = $key;
            if($value != null) {
                $displayValue = null;
                switch ($key) {
                    case 'type':
                        $filter = 'Item Type';
                        $itemtype = $db->getTable('ItemType')->find($value);
                        $displayValue = $itemtype->name;
                    break;

                    case 'collection':
                        $collection = $db->getTable('Collection')->find($value);
                        $displayValue = $collection->name;
                    break;

                    case 'user':
                        $user = $db->getTable('User')->find($value);
                        $displayValue = $user->Entity->getName();
                    break;

                    case 'public':
                    case 'featured':
                        $displayValue = $value ? 'yes' : 'no';
                    break;

                    case 'search':
                    case 'tags':
                    case 'range':
                        $displayValue = $value;
                    break;
                }
                if ($displayValue) {
                    $displayArray[$filter] = $displayValue;
                }
            }
        }

        foreach($displayArray as $filter => $value) {
            $displayList .= '<li class="'.text_to_id($filter).'">'.ucwords($filter).': '.$value.'</li>';
        }

        if(array_key_exists('advanced', $query)) {
            $advancedArray = array();

            foreach ($query['advanced'] as $i => $row) {
                if (!$row['element_id'] || !$row['type']) {
                    continue;
                }
                $elementID = $row['element_id'];
                $elementDb = $db->getTable('Element')->find($elementID);
                $element = $elementDb->name;
                $type = $row['type'];
                $terms = $row['terms'];
                $advancedValue = $element . ' ' . $type;
                if ($terms) {
                    $advancedValue .= ' "' . $terms . '"';
                }
                $advancedArray[$i] = $advancedValue;
            }
            foreach($advancedArray as $advancedKey => $advancedValue) {
                $displayList .= '<li class="advanced">' . $advancedValue . '</li>';
            }
        } 

        if (!empty($displayList)) {
            $html = '<div class="filters">'
                  . '<ul id="filter-list">'
                  . $displayList
                  . '</ul>'
                  . '</div>';
        }
    }
    return $html;
}
