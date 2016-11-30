<?php
/**
 * The public show view for Timelines.
 */

$head = array(
    'bodyclass' => 'timelines primary',
    'title' => metadata($neatline_time_timeline, 'title'),
);
echo head($head);
?>
<h1><?php echo metadata($neatline_time_timeline, 'title'); ?></h1>

    <!-- Construct the timeline. -->
    <?php
    $library = get_option('neatline_time_library') ?: 'simile';
    $libraryPartial = $library == 'simile' ? '_timeline' : '_timeline_' . $library;
    echo $this->partial('timelines/' . $libraryPartial . '.php',
        array(
            'center_date' => metadata($neatline_time_timeline, 'center_date'),
    )); ?>
    <?php echo metadata($neatline_time_timeline, 'description'); ?>
<?php echo foot();
