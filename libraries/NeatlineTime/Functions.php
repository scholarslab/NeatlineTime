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
 * @param NeatlineTime_Timeline|null
 * @return string
 * @deprecated
 */
function timeline($fieldname, $options = array(), $timeline = null)
{
    $timeline = $timeline ?: get_current_record('neatline_time_timeline');
    return metadata($timeline, $fieldname, $options);
}

/**
 * Returns a link to a specific timeline.
 *
 * @since 1.0
 * @param string HTML for the text of the link.
 * @param array Attributes for the <a> tag. (optional)
 * @param string The action for the link. Default is 'show'.
 * @param NeatlineTime_Timeline|null
 * @return string HTML
 * @deprecated
 */
function link_to_timeline($text = null, $props = array(), $action = 'show', $timeline = null)
{
    $timeline = $timeline ?: get_current_record('neatline_time_timeline');
    $text = $text ?: $timeline->title;
    return link_to($timeline, $action, $text, $props);
}

/**
 * Queues JavaScript and CSS for NeatlineTime in the page header.
 *
 * @deprecated Uses hooks for header instead.
 * @see NeatlineTimePlugin::_head()
 * @since 1.0
 * @return void.
 */
function queue_timeline_assets()
{
    $library = get_option('neatline_time_library');
    if ($library == 'knightlab') {
        queue_css_url('//cdn.knightlab.com/libs/timeline3/latest/css/timeline.css');
        queue_js_url('//cdn.knightlab.com/libs/timeline3/latest/js/timeline.js');
        return;
    }

    // Default simile library.
    queue_css_file('neatlinetime-timeline');

    queue_js_file('neatline-time-scripts');

    $internalAssets = get_option('neatline_time_internal_assets');
    if ($internalAssets) {
        $useInternalJs = true;
    } else {
        // Check useInternalJavascripts in config.ini.
        $config = Zend_Registry::get('bootstrap')->getResource('Config');
        $useInternalJs = isset($config->theme->useInternalJavascripts)
            ? (bool) $config->theme->useInternalJavascripts
            : false;
        $useInternalJs = isset($config->theme->useInternalAssets)
            ? (bool) $config->theme->useInternalAssets
            : $useInternalJs;
    }

    if ($useInternalJs) {
        $timelineVariables = 'Timeline_ajax_url="' . src('simile-ajax-api.js', 'javascripts/simile/ajax-api') . '";
            Timeline_urlPrefix="' . dirname(src('timeline-api.js', 'javascripts/simile/timeline-api')) . '/";
            Timeline_parameters="bundle=true";';
        queue_js_string($timelineVariables);
        queue_js_file('timeline-api', 'javascripts/simile/timeline-api');
        queue_js_string('SimileAjax.History.enabled = false; // window.jQuery = SimileAjax.jQuery;');
    } else {
        queue_js_url('//api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true');
        queue_js_string('SimileAjax.History.enabled = false; window.jQuery = SimileAjax.jQuery;');
    }
}

/**
 * Get metadata for a record according to the parameters of a timeline.
 *
 * @uses Omeka_View_Helper_Metadata::metadata()
 * @param Omeka_Record_AbstractRecord|string $record The record to get metadata
 * for. If an Omeka_Record_AbstractRecord, that record is used. If a string,
 * that string is used to look up a record in the current view.
 * @param mixed $metadata The metadata to get. If an array is given, this is
 * Element metadata, identified by array('Element Set', 'Element'). If a string,
 * the metadata is a record-specific "property."
 * @param array $options Options for getting the metadata.
 * @param NeatlineTime_Timeline|null $timeline The timeline where the parameters
 * are set. If null, use the current timeline.
 * @return mixed
 */
function neatlinetime_metadata($record, $metadata, $options = array(), $timeline = null)
{
    $timeline = $timeline ?: get_current_record('neatline_time_timeline');
    if (is_string($metadata)) {
        $elementId = $timeline->getProperty($metadata);
        if (!empty($elementId)) {
            $element = $record->getElementById($elementId);
            if (!empty($element)) {
                $metadata = array($element->getElementSet()->name, $element->name);
            }
        }
        // Avoid some useless process.
        elseif ($metadata == 'item_date_end') {
            return array();
        }
    }
    return metadata($record, $metadata, $options);
}

/**
 * Returns the URI for a timeline's json output.
 *
 * @since 1.0
 * @param NeatlineTime_Timeline|null
 * @return string URL the items output uri for the neatlinetime-json output.
 */
function neatlinetime_json_uri_for_timeline($timeline = null)
{
    $timeline = $timeline ?: get_current_record('neatline_time_timeline');
    return record_url($timeline, 'items') . '?output=neatlinetime-json';
}

/**
 * Construct id for container div.
 *
 * @since 1.0
 * @param NeatlineTime_Timeline|null
 * @return string HTML
 */
function neatlinetime_timeline_id($timeline = null)
{
    $timeline = $timeline ?: get_current_record('neatline_time_timeline');
    return text_to_id(html_escape($timeline->title) . ' ' . $timeline->id, 'neatlinetime');
}

/**
 * Displays random featured timelines
 *
 * @param int Maximum number of random featured timelines to display.
 * @return string HTML
 */
function neatlinetime_display_random_featured_timelines($num = 1)
{
    $html = '';

    $timelines = get_db()->getTable('NeatlineTime_Timeline')->findBy(array('sort_field' => 'random', 'featured' => 1), $num);

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
function neatlinetime_item_class($item = null)
{
    $classes = array('item');

    $type = metadata($item, 'item_type_name');
    if ($type) {
        $classes[] = text_to_id($type);
    }

    $dcTypes = metadata($item, array('Dublin Core', 'Type'), array('all' => true));
    if ($dcTypes) {
        foreach ($dcTypes as $type) {
            $classes[] = text_to_id($type);
        }
    }

    $classAttribute = implode(' ', $classes);
    $classAttribute = apply_filters('neatlinetime_item_class', $classAttribute);
    return $classAttribute;
}

/**
 * Generates an ISO-8601 date from a date string
 *
 * @see Zend_Date
 * @param string $date
 * @param string renderYear Force the format of a single number as a year.
 * @return string ISO-8601 date
 */
function neatlinetime_convert_date($date, $renderYear = null)
{
    if (empty($renderYear)) {
        $renderYear = neatlinetime_get_option('render_year');
    }

    // Check if the date is a single number.
    if (preg_match('/^-?\d{1,4}$/', $date)) {
        // Normalize the year.
        $date = $date < 0
            ? '-' . str_pad(substring($date, 1), 4, '0', STR_PAD_LEFT)
            : str_pad($date, 4, '0', STR_PAD_LEFT);
        switch ($renderYear) {
            case 'january_1':
                $date_out = $date . '-01-01' . 'T00:00:00+00:00';
                break;
            case 'july_1':
                $date_out = $date . '-07-01' . 'T00:00:00+00:00';
                break;
            case 'december_31':
                $date_out = $date . '-12-31' . 'T00:00:00+00:00';
                break;
            case 'june_30':
                $date_out = $date . '-06-30' . 'T00:00:00+00:00';
                break;
            case 'full_year':
                // Render a year as a range: use neatlinetime_convert_single_date().
            case 'skip':
            default:
                $date_out = false;
                break;
        }
        return $date_out;
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
        $date_out = preg_replace('/^(-?)(\d{3}-)/', '${1}0\2', $date_out);
        $date_out = preg_replace('/^(-?)(\d{2}-)/', '${1}00\2', $date_out);
        $date_out = preg_replace('/^(-?)(\d{1}-)/', '${1}000\2', $date_out);
    }
    return $date_out;
}

/**
 * Generates an array of one or two ISO-8601 dates from a string.
 *
 * @todo manage the case where the start is empty and the end is set.
 *
 * @param string $date
 * @param string renderYear Force the format of a single number as a year.
 * @return array Array of two dates.
 */
function neatlinetime_convert_any_date($date, $renderYear = null)
{
    return neatlinetime_convert_two_dates($date, null, $renderYear);
}

/**
 * Generates an array of one or two ISO-8601 dates from two strings.
 *
 * @todo manage the case where the start is empty and the end is set.
 *
 * @param string $date
 * @param string $dateEnd
 * @param string renderYear Force the format of a single number as a year.
 * @return array Array of two dates.
 */
function neatlinetime_convert_two_dates($date, $dateEnd, $renderYear = null)
{
    if (empty($renderYear)) {
        $renderYear = neatlinetime_get_option('render_year');
    }

    $dateArray = array_map('trim', explode('/', $date));

    // A range of dates.
    if (count($dateArray) == 2) {
        return neatlinetime_convert_range_dates($dateArray, $renderYear);
    }

    $dateEndArray = explode('/', $dateEnd);
    $dateEnd = trim(reset($dateEndArray));

    // A single date, or a range when the two dates are years and when the
    // render is "full_year".
    if (empty($dateEnd)) {
        return neatlinetime_convert_single_date($dateArray[0], $renderYear);
    }

    return neatlinetime_convert_range_dates(array($dateArray[0], $dateEnd), $renderYear);
}

/**
 * Generates an ISO-8601 date from a date string, with an exception for
 * "full_year" render, that returns two dates.
 *
 * @param string $date
 * @param string renderYear Force the format of a single number as a year.
 * @return array Array of two dates.
 */
function neatlinetime_convert_single_date($date, $renderYear = null)
{
    if (empty($renderYear)) {
        $renderYear = neatlinetime_get_option('render_year');
    }

    // Manage a special case for render "full_year" with a single number.
    if ($renderYear == 'full_year' && preg_match('/^-?\d{1,4}$/', $date)) {
        $dateStartValue = neatlinetime_convert_date($date, 'january_1');
        $dateEndValue = neatlinetime_convert_date($date, 'december_31');
        return array($dateStartValue, $dateEndValue);
    }

    // Only one date.
    $dateStartValue = neatlinetime_convert_date($date, $renderYear);
    return array($dateStartValue, null);
}

/**
 * Generates two ISO-8601 dates from an array of two strings.
 *
 * By construction, no "full_year" is returned.
 *
 * @param array $dates
 * @param string renderYear Force the format of a single number as a year.
 * @return array $dates
 */
function neatlinetime_convert_range_dates($dates, $renderYear = null)
{
    if (!is_array($dates)) {
        return array(null, null);
    }

    if (empty($renderYear)) {
        $renderYear = neatlinetime_get_option('render_year');
    }

    $dateStart = $dates[0];
    $dateEnd = $dates[1];

    // Check if the date are two numbers (years).
    if ($renderYear == 'skip') {
        $dateStartValue = neatlinetime_convert_date($dateStart, $renderYear);
        $dateEndValue = neatlinetime_convert_date($dateEnd, $renderYear);
        return array($dateStartValue, $dateEndValue);
    }

    // Check if there is one number and one date.
    if (!preg_match('/^-?\d{1,4}$/', $dateStart)) {
        if (!preg_match('/^-?\d{1,4}$/', $dateEnd)) {
            // TODO Check order to force the start or the end.
            $dateStartValue = neatlinetime_convert_date($dateStart, $renderYear);
            $dateEndValue = neatlinetime_convert_date($dateEnd, $renderYear);
            return array($dateStartValue, $dateEndValue);
        }
        // Force the format for the end.
        $dateStartValue = neatlinetime_convert_date($dateStart, $renderYear);
        if ($renderYear == 'full_year') $renderYear = 'december_31';
        $dateEndValue = neatlinetime_convert_date($dateEnd, $renderYear);
        return array($dateStartValue, $dateEndValue);
    }
    // The start is a year.
    elseif (!preg_match('/^-?\d{1,4}$/', $dateEnd)) {
        // Force the format of the start.
        $dateEndValue = neatlinetime_convert_date($dateEnd, $renderYear);
        if ($renderYear == 'full_year') $renderYear = 'january_1';
        $dateStartValue = neatlinetime_convert_date($dateStart, $renderYear);
        return array($dateStartValue, $dateEndValue);
    }

    $dateStart = (integer) $dateStart;
    $dateEnd = (integer) $dateEnd;

    // Same years.
    if ($dateStart == $dateEnd) {
        $dateStartValue = neatlinetime_convert_date($dateStart, 'january_1');
        $dateEndValue = neatlinetime_convert_date($dateEnd, 'december_31');
        return array($dateStartValue, $dateEndValue);
    }

    // The start and the end are years, so reorder them (may be useless).
    if ($dateStart > $dateEnd) {
        $kdate = $dateEnd;
        $dateEnd = $dateStart;
        $dateStart = $kdate;
    }

    switch ($renderYear) {
        case 'july_1':
            $dateStartValue = neatlinetime_convert_date($dateStart, 'july_1');
            $dateEndValue = neatlinetime_convert_date($dateEnd, 'june_30');
            return array($dateStartValue, $dateEndValue);
        case 'january_1':
            $dateStartValue = neatlinetime_convert_date($dateStart, 'january_1');
            $dateEndValue = neatlinetime_convert_date($dateEnd, 'january_1');
            return array($dateStartValue, $dateEndValue);
        case 'full_year':
        default:
            $dateStartValue = neatlinetime_convert_date($dateStart, 'january_1');
            $dateEndValue = neatlinetime_convert_date($dateEnd, 'december_31');
            return array($dateStartValue, $dateEndValue);
    }
}

/**
 * Gets the value for an option set in the neatlinetime option array.
 *
 * Useless now because each timeline has parameters available via getProperty().
 *
 * @param string The NeatlineTime option name.
 * @return string
 */
function neatlinetime_get_option($name = null)
{
    if ($name) {
        $options = get_option('neatline_time_defaults');
        $options = json_decode($options, true);
        return isset($options[$name]) ? $options[$name] : null;
    }
    return false;
}

/**
 * Returns the value of an element set in the NeatlineTime config options.
 *
 * @deprecated since 2.1.9
 * @see neatlinetime_metadata()
 * @param string The NeatlineTime option name.
 * @param array An array of options.
 * @param Item
 * @return string|array|null
 */
function neatlinetime_get_item_text($optionName, $options = array(), $item = null)
{
    $element = get_db()->getTable('Element')->find(neatlinetime_get_option($optionName));
    return metadata($item, array($element->getElementSet()->name, $element->name), $options);
}
