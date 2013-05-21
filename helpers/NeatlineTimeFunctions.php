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

    $timeline = $timeline ? $timeline : get_current_record('neatline_time_timeline');

    $fieldname = strtolower($fieldname);
    $text = $timeline->$fieldname;

    if(isset($options['snippet'])) {
        $text = nls2p(snippet($text, 0, (int)$options['snippet']));
    }

    if ($fieldname == 'query') {
        $text = unserialize($text);
    }

    return $text;

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

    $timeline = $timeline ? $timeline : get_current_record('neatline_time_timeline');

    $text = $text ? $text : $timeline->title;

    return link_to($timeline, $action, $text, $props);

}

/**
 * Queues JavaScript and CSS for NeatlineTime in the page header.
 *
 * @since 1.0
 * @return void.
 */
function queue_timeline_assets()
{
    $headScript = get_view()->headScript();
    $headScript->appendFile(src('neatline-time-scripts.js', 'javascripts'));

    // Check useInternalJavascripts in config.ini.
    $config = Zend_Registry::get('bootstrap')->getResource('Config');
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

    queue_css_file('neatlinetime-timeline');
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
    $timeline = $timeline ? $timeline : get_current_record('neatline_time_timeline');
    return record_url($timeline, 'items') . '?output=neatlinetime-json';
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
    $timeline = $timeline ? $timeline : get_current_record('neatline_time_timeline');
    return text_to_id(html_escape($timeline->title) . ' ' . $timeline->id, 'neatlinetime');
}

/**
 * Displays random featured timelines
 *
 * @param int Maximum number of random featured timelines to display.
 * @return string HTML
 */
function neatlinetime_display_random_featured_timelines($num = 1) {
  $html = '';

  $timelines = get_db()->getTable('NeatlineTimeTimeline')->findBy(array('random' => 1, 'featured' => 1), $num);

  if ($timelines) {
    foreach ($timelines as $timeline) {
      $html .= '<h3>' . link_to_timeline(null, array(), 'show', $timeline) . '</h3>'
        . '<div class="description timeline-description">'
        . timeline('description', array('snippet' => 150), $timeline)
        . '</div>';
    }
    return $html;
  }
}

/**
 * Returns a string for neatline_json 'classname' attribute for an item.
 *
 * Default fields included are: 'item', item type name, all DC:Type values.
 *
 * Output can be filtered using the 'neatlinetime_item_class' filter.
 *
 * @return string
 */
function neatlinetime_item_class($item = null) {
    $classArray = array('item');

    if ($itemTypeName = metadata($item, 'item_type_name')) {
        $classArray[] = text_to_id($itemTypeName);
    }

    if ($dcTypes = metadata($item, array('Dublin Core', 'Type'), array('all' => true))) {
        foreach ($dcTypes as $type) {
            $classArray[] = text_to_id($type);
        }
    }

    $classAttribute = implode(' ', $classArray);
    $classAttribute = apply_filters('neatlinetime_item_class', $classAttribute);
    return $classAttribute;
}

/**
 * Generates an ISO-8601 date from a date string
 *
 * @see Zend_Date
 * @return string ISO-8601 date
 */
function neatlinetime_convert_date($date) {
  if (preg_match('/^\d{4}$/', $date) > 0) {
      return false;
  }

  $newDate = null;
  try {
    $newDate = new Zend_Date($date, Zend_Date::ISO_8601);
  } catch (Exception $e) {
      try {
          $newDate = new Zend_Date($date);
      } catch (Exception $e) {
      }
  }

  if (is_null($newDate)) {
      $date_out = false;
  } else {
      $date_out = $newDate->get('c');
      $date_out = preg_replace('/^(-?)(\d{3}-)/', '${1}0\2',   $date_out);
      $date_out = preg_replace('/^(-?)(\d{2}-)/', '${1}00\2',  $date_out);
      $date_out = preg_replace('/^(-?)(\d{1}-)/', '${1}000\2', $date_out);
  }
  return $date_out;

}

/**
 * Generates a form select populated by all elements and element sets.
 * 
 * @param string The NeatlineTime option name. 
 * @return string HTML.
 */
function neatlinetime_option_select($name = null) {

  if ($name) {
    return get_view()->formSelect(
                    $name,
                    neatlinetime_get_option($name),
                    array(),
                    get_table_options('Element', null, array(
                        'record_types' => array('Item', 'All'),
                        'sort' => 'alphaBySet')
                    )
                );

  }

    return false;

}

/**
 * Gets the value for an option set in the neatlinetime option array.
 *
 * @param string The NeatlineTime option name. 
 * @return string
 */
function neatlinetime_get_option($name = null) {

  if ($name) {
    $options = get_option('neatlinetime');
    $options = unserialize($options);
    return $options[$name];
  }

  return false;

}

/**
 * Returns the value of an element set in the NeatlineTime config options.
 *
 * @param string The NeatlineTime option name.
 * @param array An array of options.
 * @param Item
 * @return string|array|null
 */
function neatlinetime_get_item_text($optionName, $options = array(), $item = null) {

    $element = get_db()->getTable('Element')->find(neatlinetime_get_option($optionName));

    return metadata($item, array($element->getElementSet()->name, $element->name), $options);

}
