<?php
/**
 * Timeline display partial.
 */
?>

<!-- Container. -->
<div id="<?php echo neatlinetime_timeline_id(); ?>" class="neatlinetime-timeline"></div>
<script>
    jQuery(document).ready(function($) {
        NeatlineTime.loadTimeline(
            '<?php echo neatlinetime_timeline_id(); ?>',
            '<?php echo neatlinetime_json_uri_for_timeline(); ?>'
        );
    });
</script>

