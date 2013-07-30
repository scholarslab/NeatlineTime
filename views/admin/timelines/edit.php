<?php
/**
 * The edit view for the Timelines administrative panel.
 */

$timelineTitle = timeline('title') ? strip_formatting(timeline('title')) : '[Untitled]';
$title = __('Neatline Time | Edit "%s" Metadata', $timelineTitle);
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape($title));
echo head($head);

echo $form;

echo foot();
