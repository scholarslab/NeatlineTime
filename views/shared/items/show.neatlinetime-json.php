<?php
/**
 * The shared neatlinetime-json show view for Items
 */

$neatlineTimeEvents = array();

$itemDates = item('Dublin Core', 'Date', 'all');
$itemTitle = item('Dublin Core', 'Title');
$itemLink = abs_item_uri();
$itemDescription = item('Dublin Core', 'Description');

if ($file = get_db()->getTable('File')->findWithImages(item('id'), 0)) {
    $fileUrl = file_display_uri($file, 'square_thumbnail');
}
if (!empty($itemDates)) {
    foreach ($itemDates as $itemDate) {
        $neatlineTimeEvent = array();
        $neatlineTimeEvent['start'] = date('c', strtotime($itemDate));

        $neatlineTimeEvent['title'] = $itemTitle;
        $neatlineTimeEvent['link'] = $itemLink;

        if ($fileUrl) {
            $neatlineTimeEvent['image'] = $fileUrl;
        }

        if ($itemDescription) {
            $neatlineTimeEvent['description'] = $itemDescription;
        }

        $neatlineTimeEvents[] = $neatlineTimeEvent;
    }
}

$neatlineTimeArray = array();
$neatlineTimeArray['events'] = $neatlineTimeEvents;

$neatlinetimeJson = json_encode($neatlineTimeArray);

echo $neatlinetimeJson;
