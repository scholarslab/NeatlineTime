<?php
/**
 * The edit view for the Timelines administrative panel.
 */

$timelineTitle = metadata($neatline_time_timeline, 'title') ?: __('[Untitled]');
$title = __('Neatline Time | Edit "%s" Metadata', $timelineTitle);
$head = array('bodyclass' => 'timelines primary',
              'title' => html_escape($title));
echo head($head);
echo flash();
echo $form;

echo foot();
