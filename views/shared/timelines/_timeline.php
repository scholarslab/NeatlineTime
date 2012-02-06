<?php
/**
 * Timeline display partial.
 */
?>

<!-- Container. -->
<div id="<?php echo neatlinetime_timeline_id(); ?>"></div>

<script>
    NeatlineTime.data = '<?php echo neatlinetime_json_uri_for_timeline(); ?>';
    $('#<?php echo neatlinetime_timeline_id(); ?>').neatlinetimeline();
</script>
