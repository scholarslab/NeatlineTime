<?php
/**
 * The add view for the Timelines administrative panel.
 */

queue_js('tiny_mce/tiny_mce');
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape(__('Neatline Time | Add a Timeline')));
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
