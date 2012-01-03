<?php
/**
 * The show view for the Timelines administrative panel.
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary', 
              'title' => timeline('title')
              );
head($head);
?>
<h1><?php echo timeline('title'); ?> <span class="view-public-page">[ <a href="<?php echo html_escape(public_uri('neatline-time/timelines/show/'.timeline('id'))); ?>">View Public Page</a> ]</h1>
<?php if (has_permission(get_current_timeline(), 'edit')): ?>
<p id="edit-timeline" class="edit-button">
    <?php echo link_to_timeline('Edit Metadata', array('class' => 'edit'), 'edit'); ?>
    <?php echo link_to_timeline('Edit Items Query', array('class' => 'edit'), 'query'); ?>
</p>
<?php endif; ?>
<div id="primary">
    <?php echo neatlinetime_display_timeline(); ?>
    <?php echo timeline('description'); ?>
</div>
<?php foot(); ?>
