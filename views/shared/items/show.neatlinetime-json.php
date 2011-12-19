<?php
/**
 * The shared neatlinetime-json show view for Items
 *
 * PHP 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
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
