<?php
/**
 * Timeline display partial.
 */

// Get the timeline
if (empty($timeline)) $timeline = get_current_record('neatline_time_timeline');
?>

<!-- Container. -->
<div id="<?php echo neatlinetime_timeline_id(); ?>" class="neatlinetime-timeline">
</div>
<script>
  jQuery(document).ready(function($) {
        var centerDate = <?php echo json_encode($timeline->getProperty('center_date')); ?>;

        NeatlineTime.loadTimeline(
            '<?php echo neatlinetime_timeline_id($timeline); ?>',
            '<?php echo neatlinetime_json_uri_for_timeline($timeline); ?>',
            centerDate
        );
    });
</script>
