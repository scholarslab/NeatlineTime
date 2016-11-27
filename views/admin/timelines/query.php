<?php
/**
 * The edit query view for a specific Timeline.
 */

$timelineTitle = metadata($neatline_time_timeline, 'title');
$title = __('Neatline Time | Edit "%s" Items Query', strip_formatting($timelineTitle));
$head = array('bodyclass' => 'timelines primary', 'title' => $title);
echo head($head);
?>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function(){
       Omeka.Search.activateSearchButtons;
    });
</script>
    <?php
$query = $neatline_time_timeline->getQuery();
if ($query) {
?>
    <p><strong><?php echo __('The &#8220;%s&#8221; timeline displays items that match the following query:', $timelineTitle) ?></strong></p>
<?php
    echo item_search_filters($query);
}
echo items_search_form(array(), current_url());

echo foot();
