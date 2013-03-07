<?php
/**
 * The add view for the Timelines administrative panel.
 */

$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape(__('Neatline Time | Add a Timeline')));
echo head($head);

echo $form;

echo foot();

