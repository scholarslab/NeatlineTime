<?php
if (!array_key_exists('timeline-id', $options)) {
    return;
}
$timeline = get_db()->getTable('NeatlineTime_Timeline')->find($options['timeline-id']);
if (empty($timeline)) {
    return;
}
set_current_record('neatline_time_timeline', $timeline);
$library = get_option('neatline_time_library') ?: 'simile';
$libraryPartial = $library == 'simile' ? '_timeline' : '_timeline_' . $library;
echo $this->partial('timelines/' . $libraryPartial . '.php',
    array(
        'timeline' => $timeline,
));
