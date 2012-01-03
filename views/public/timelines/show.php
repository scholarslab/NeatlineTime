<?php
/**
 * The public show view for Timelines.
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary',
              'title' => timeline('title')
              );
head($head);
?>
<h1><?php echo timeline('title'); ?></h1>

<div id="primary">
    <?php echo neatlinetime_display_timeline(); ?>
    <?php echo timeline('description'); ?>
</div>
<?php foot(); ?>
