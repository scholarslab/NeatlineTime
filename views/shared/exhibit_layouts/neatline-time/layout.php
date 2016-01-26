<?php
if (!array_key_exists('timeline-id', $options)) {
    return;
}
$timeline = get_record_by_id('NeatlineTimeTimeline',  $options['timeline-id']);
if (!$timeline) {
    return;
}
set_current_record('neatline_time_timeline', $timeline);
print_r($timeline->center_date);
// Need to pass through the center date here to make this work in exhibit builder
// echo $this->partial('timelines/_timeline.php', array('center_date' => metadata($neatline_time_timeline, 'center_date')));
echo $this->partial('timelines/_timeline.php');
?>
