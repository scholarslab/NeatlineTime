<?php
/**
 * The edit query view for a specific Timeline.
 */

$timelineTitle = timeline('title') ? strip_formatting(timeline('title')) : '[Untitled]';
$title = 'Neatline Time | Edit "' . $timelineTitle . '" Items Query';
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape($title));
head($head);
?>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function(){
       Omeka.Search.activateSearchButtons; 
    });
</script>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo $timeline->title; ?>
    <?php if ($query = timeline('query')) : ?>
    <p><strong>The &#8220;<?php echo timeline('title'); ?>&#8221; timeline displays items that match the following query:</strong></p>
    <?php echo neatlinetime_display_search_query($query); ?>
    <?php endif; ?>
    <?php echo items_search_form(array(), current_uri()); ?>

</div>
<?php foot(); ?>
