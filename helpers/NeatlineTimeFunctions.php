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
        $text = nls2p(snippet($text, 0, (int)$options['snippet']));
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
        __('Delete'),
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
    $route = 'neatline-time/timelines/items/'.$timeline->id.'?output=neatlinetime-json';
    return uri($route);
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
                        $displayValue = $value ? __('Yes') : __('No');
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
            $displayList .= '<li class="'.text_to_id($filter).'">'.__(ucwords($filter)).': '.$value.'</li>';
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

/**
 * Converts the advanced search output into acceptable input for findBy().
 *
 * @see Omeka_Db_Table::findBy()
 * @param array $query HTTP query string array
 * @return array Array of findBy() parameters
 */
function neatlinetime_convert_search_filters($query) {
    $perms  = array();
    $filter = array();
    $order  = array();

    //Show only public items
    if (@$query['public']) {
        $perms['public'] = true;
    }

    //Here we add some filtering for the request
    // User-specific item browsing
    if ($userToView = @$query['user']) {
        if (is_numeric($userToView)) {
            $filter['user'] = $userToView;
        }
    }

    if (@$query['featured']) {
        $filter['featured'] = true;
    }

    if ($collection = @$query['collection']) {
        $filter['collection'] = $collection;
    }

    if ($type = @$query['type']) {
        $filter['type'] = $type;
    }

    if (($tag = @$query['tag']) || ($tag = @$query['tags'])) {
        $filter['tags'] = $tag;
    }

    if ($excludeTags = @$query['excludeTags']) {
        $filter['excludeTags'] = $excludeTags;
    }

    if ($search = @$query['search']) {
        $filter['search'] = $search;
    }

    //The advanced or 'itunes' search
    if ($advanced = @$query['advanced']) {

        //We need to filter out the empty entries if any were provided
        foreach ($advanced as $k => $entry) {
            if (empty($entry['element_id']) || empty($entry['type'])) {
                unset($advanced[$k]);
            }
        }
        $filter['advanced_search'] = $advanced;
    };

    if ($range = @$query['range']) {
        $filter['range'] = $range;
    }

    return array_merge($perms, $filter, $order);
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
    $item = $item ? $item : get_current_item();
    
    $classArray = array('item');

    if ($itemTypeName = $item->Type->name) {
        $classArray[] = text_to_id($itemTypeName);
    }

    if ($dcTypes = item('Dublin Core', 'Type', 'all', $item)) {
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
 * Returns the HTML for an item search form
 *
 * This was copied with modifications from 
 * application/helpers/ItemFunctions.php in the Omeka source.
 *
 * @param array $props
 * @param string $formActionUri
 * @return string
 */
function neatlinetime_items_search_form($props=array(), $formActionUri = null)
{
    return __v()->partial(
        'timelines/query-form.php',
        array(
            'isPartial'      => true,
            'formAttributes' => $props,
            'formActionUri'  => $formActionUri
        )
    );
}

/**
 * Generates a form select populated by all elements and element sets.
 * 
 * @param string The NeatlineTime option name. 
 * @return string HTML.
 */
function neatlinetime_option_select($name = null) {

  if ($name) {
    return select_element(
            array('name' => $name),
            neatlinetime_get_option($name),
            null,
            array('record_types' => array('Item', 'All'),
            'sort' => 'alphaBySet')
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

    $db = get_db();

    $item = $item ? $item : get_current_item();

    $element = $db->getTable('Element')->find(neatlinetime_get_option($optionName));
    $elementTexts = $item->getTextsByElement($element);

    return item($element->getElementSet()->name, $element->name, $options, $item);

}
