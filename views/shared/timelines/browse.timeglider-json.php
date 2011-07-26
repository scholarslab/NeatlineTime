<?php
/**
 * The shared timeglider-json browse view for Timelines
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

foreach ($timelines as $timeline) {
    $timelineJsonArray[] = timeglider_json_for_timeline($timeline);
}

echo json_encode($timelineJsonArray);