<?php
/**
 * The edit query view for a specific Timeline.
 */

$timelineTitle = timeline('title') ? strip_formatting(timeline('title')) : '[Untitled]';
$title = __('Neatline Time | Edit "%s" Items Query', $timelineTitle);
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape($title));
echo head($head);
?>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function(){
       Omeka.Search.activateSearchButtons; 
    });
</script>
    <?php if ($query = timeline('query')) : ?>
    <p><strong><?php echo __('The &#8220;%s&#8221; timeline displays items that match the following query:', timeline('title')) ?></strong></p>
        <?php echo item_search_filters($query); ?>
    <?php endif; ?>
    <?php echo neatlinetime_items_search_form(array(), current_url()); ?>
<?php echo foot(); ?>
