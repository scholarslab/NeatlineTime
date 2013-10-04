<?php
if (!array_key_exists('timeline-id', $options)) {
    return;
}
$timeline = get_record_by_id('NeatlineTimeTimeline',  $options['timeline-id']);
if (!$timeline) {
    return;
}
set_current_record('neatline_time_timeline', $timeline);
echo $this->partial('timelines/_timeline.php');
?>
