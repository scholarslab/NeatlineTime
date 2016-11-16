<?php
/**
 * Timeline display partial.
 */
?>

<!-- Container. -->
<div id="<?php echo neatlinetime_timeline_id(); ?>" class="neatlinetime-timeline">
</div>
<script>
  jQuery(document).ready(function($) {
        var centerDate = <?php echo json_encode($this->center_date); ?>;

        NeatlineTime.loadTimeline(
            '<?php echo neatlinetime_timeline_id(); ?>',
            '<?php echo neatlinetime_json_uri_for_timeline(); ?>',
            centerDate
        );
    });
</script>
