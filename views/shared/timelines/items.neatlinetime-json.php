<?php
/**
 * The shared neatlinetime-json browse view for Items.
 *
 * @todo Manage the case of a range where the start is unknown.
 */

$timeline = $neatline_time_timeline;
if (empty($timeline)) {
    return;
}

$events = array();
foreach ($items as $item) {
    $itemDates = neatlinetime_metadata($item, 'item_date', array('all' => true, 'no_filter' => true), $timeline);
    if (empty($itemDates)) {
        continue;
    }
    $itemTitle = strip_formatting(neatlinetime_metadata($item, 'item_title', array(), $timeline));
    $itemDescription =  neatlinetime_metadata($item, 'item_description', array('snippet' => '200'), $timeline);
    $itemDatesEnd = neatlinetime_metadata($item, 'item_date_end', array('all' => true, 'no_filter' => true), $timeline) ?: [];
    $itemLink = record_url($item);
    $file = get_db()->getTable('File')->findWithImages($item->id, 0);
    $fileUrl = $file ? metadata($file, 'square_thumbnail_uri') : null;
    foreach ($itemDates as $key => $itemDate) {
        $event = array();
        if (empty($itemDatesEnd[$key])) {
            list($dateStart, $dateEnd) = neatlinetime_convert_any_date($itemDate, $timeline->getProperty('render_year'));
        } else {
            list($dateStart, $dateEnd) = neatlinetime_convert_two_dates($itemDate, $itemDatesEnd[$key], $timeline->getProperty('render_year'));
        }
        if (!$dateStart) {
            continue;
        }
        $event['start'] = $dateStart;
        if (!is_null($dateEnd)) {
            $event['end'] = $dateEnd;
        }
        $event['title'] = $itemTitle;
        $event['link'] = $itemLink;
        $event['classname'] = neatlinetime_item_class($item);
        if ($fileUrl) {
            $event['image'] = $fileUrl;
        }
        $event['description'] = $itemDescription;
        $events[] = $event;
    }
}

$data = array();
$data['dateTimeFormat'] = 'iso8601';
$data['events'] = $events;

$dataJson = version_compare(phpversion(), '5.4.0', '<')
    ? json_encode($data)
    : json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

echo $dataJson;
