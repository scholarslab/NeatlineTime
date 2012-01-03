<?php
/**
 * The edit query view for a specific Timeline.
 */

$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Neatline Time | Edit Timeline Query'));
head($head);
?>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function(){
       Omeka.Search.activateSearchButtons; 
    });
</script>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo items_search_form(array(), current_uri()); ?>

</div>
<?php foot(); ?>
