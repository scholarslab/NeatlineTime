<?php
/**
 * The edit view for the Timelines administrative panel.
 */

$timelineTitle = timeline('title') ? strip_formatting(timeline('title')) : '[Untitled]';
$title = 'Neatline Time | Edit "' . $timelineTitle . '" Metadata';
queue_timeline_assets();
queue_js('tiny_mce/tiny_mce');
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape($title));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
<?php echo $form; ?>

<script>
jQuery(document).ready(function($){
    Omeka.wysiwyg();
});
</script>

</div>
<?php foot(); ?>
