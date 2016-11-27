<?php
/**
 * The shared neatlinetime-json browse view for Items.
 */

$neatlineTimeEvents = array();
foreach ($items as $item) {
    $itemTitle = strip_formatting(neatlinetime_get_item_text('item_title', array(), $item));
    $itemLink = record_url($item);
    $itemDescription =  neatlinetime_get_item_text('item_description', array('snippet' => '200'), $item);

    $itemDates = neatlinetime_get_item_text('item_date', array('all' => true, 'no_filter' => true), $item);

    $fileUrl = null;
    if ($file = get_db()->getTable('File')->findWithImages(metadata($item, 'id'), 0)) {
        $fileUrl = metadata($file, 'square_thumbnail_uri');
    }

    if (!empty($itemDates)) {
      foreach ($itemDates as $itemDate) {
            $itemDate = $itemDate;

            $neatlineTimeEvent = array();
            $dateArray = explode('/', $itemDate);

            if ($dateStart = neatlinetime_convert_date(trim($dateArray[0]))) {
                $neatlineTimeEvent['start'] = $dateStart;

                if (count($dateArray) == 2) {
                    $neatlineTimeEvent['end'] = neatlinetime_convert_date(trim($dateArray[1]));
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

$neatlinetimeJson = json_encode($neatlineTimeArray);

echo $neatlinetimeJson;
