<?php
/**
 * The show view for the Timelines administrative panel.
 */

$timelineTitle = metadata($neatline_time_timeline, 'title');
$head = array('bodyclass' => 'timelines primary',
              'title' => __('Neatline Time | %s', strip_formatting($timelineTitle))
              );
echo head($head);
?>

<div id="primary" class="seven columns alpha">

    <!-- Construct the timeline. -->
    <?php
    $library = get_option('neatline_time_library') ?: 'simile';
    $libraryPartial = $library == 'simile' ? '_timeline' : '_timeline_' . $library;
    echo $this->partial('timelines/' . $libraryPartial . '.php',
        array(
            'center_date' => metadata($neatline_time_timeline, 'center_date'),
    )); ?>

<?php
$query = unserialize($neatline_time_timeline->query);
if ($query && is_array($query)) {
?>
        <h2><?php echo __('Items Query'); ?></h2>
        <p><strong><?php echo __('The &#8220;%s&#8221; timeline displays items that match the following query:', $timelineTitle); ?></strong></p>
        <?php
echo item_search_filters($query);
} ?>

</div>

<div class="three columns omega">
<div id="edit" class="panel">
<?php if (is_allowed($neatline_time_timeline, 'edit')): ?>
    <?php echo link_to($neatline_time_timeline, 'edit', __('Edit Metadata'), array('class' => 'big green button')); ?>
    <?php echo link_to($neatline_time_timeline, 'query', __('Edit Items Query'), array('class' => 'big green button')); ?>
<?php endif; ?>
<a href="<?php echo html_escape(public_url('neatline-time/timelines/show/'.timeline('id'))); ?>" class="big blue button"><?php echo __('View Public Page'); ?></a>
<?php echo link_to($neatline_time_timeline, 'delete-confirm', __('Delete'), array('class' => 'delete-confirm big red button')); ?>
</div>
</div>
<script>
  var jsonTimelineUri = '<?php echo neatlinetime_json_uri_for_timeline(); ?>';

  $.getJSON(jsonTimelineUri, function(data) {
    if (data.events.length > 100) {
      alert("Adding more than 100 items to the timeline will create slow render times.");
    }
  });
</script>
<?php echo foot(); ?>
