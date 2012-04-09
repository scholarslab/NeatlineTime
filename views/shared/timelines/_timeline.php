<?php
/**
 * Timeline display partial.
 */
?>

<!-- Container. -->
<div id="<?php echo neatlinetime_timeline_id(); ?>" class="neatlinetime-timeline"></div>
<?php neatlinetime_get_startdate(); ?>
<script>
    jQuery(document).ready(function($) {
        NeatlineTime.loadTimeline(
            '<?php echo neatlinetime_timeline_id(); ?>',
            '<?php echo neatlinetime_json_uri_for_timeline(); ?>',
            '<?php echo neatlinetime_get_startdate(); ?>'

        );
    });
</script>

