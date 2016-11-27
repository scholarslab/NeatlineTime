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

$neatlineTimeEvents = array();
foreach ($items as $item) {
    $itemTitle = strip_formatting(neatlinetime_metadata($item, 'item_title', array(), $timeline));
    $itemLink = record_url($item);
    $itemDescription =  neatlinetime_metadata($item, 'item_description', array('snippet' => '200'), $timeline);
    $itemDates = neatlinetime_metadata($item, 'item_date', array('all' => true, 'no_filter' => true), $timeline);
    $itemDatesEnd = neatlinetime_metadata($item, 'item_date_end', array('all' => true, 'no_filter' => true), $timeline);

    $file = get_db()->getTable('File')->findWithImages($item->id, 0);
    $fileUrl = $file ? metadata($file, 'square_thumbnail_uri') : null;

    if (!empty($itemDates)) {
        foreach ($itemDates as $key => $itemDate) {
            $neatlineTimeEvent = array();
            if (empty($itemDatesEnd[$key])) {
                list($dateStart, $dateEnd) = neatlinetime_convert_any_date($itemDate, $timeline->getProperty('render_year'));
            } else {
                list($dateStart, $dateEnd) = neatlinetime_convert_two_dates($itemDate, $itemDatesEnd[$key], $timeline->getProperty('render_year'));
            }
            if ($dateStart) {
                $neatlineTimeEvent['start'] = $dateStart;

                if (!is_null($dateEnd)) {
                    $neatlineTimeEvent['end'] = $dateEnd;
                }

                $neatlineTimeEvent['title'] = $itemTitle;
                $neatlineTimeEvent['link'] = $itemLink;
                $neatlineTimeEvent['classname'] = neatlinetime_item_class($item);

                if ($fileUrl) {
                    $neatlineTimeEvent['image'] = $fileUrl;
                }

                $neatlineTimeEvent['description'] = $itemDescription;
                $neatlineTimeEvents[] = $neatlineTimeEvent;
            }
        }
    }
}

$neatlineTimeArray = array();
$neatlineTimeArray['dateTimeFormat'] = "iso8601";
$neatlineTimeArray['events'] = $neatlineTimeEvents;

$neatlinetimeJson = version_compare(phpversion(), '5.4.0', '<')
    ? json_encode($neatlineTimeArray)
    : json_encode($neatlineTimeArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

echo $neatlinetimeJson;
