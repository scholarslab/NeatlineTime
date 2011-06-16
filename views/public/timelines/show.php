<?php
queue_js('timeglider-0.0.9.min');
queue_css('timeglider');
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Timelines | Show'));
head($head);
?>
<h1><?php echo timeline('title'); ?></h1>

<div id="primary">
    <?php echo timeline('description'); ?>
    <?php echo timeline('permalink'); ?>
    <script>
    jQuery(document).ready(function($){
        $('#timeglider')
            .css({'height': '300px'})
            .timeline({
                "data_source":"http://omeka.dev/admin/items/browse?search=&advanced%5B0%5D%5Belement_id%5D=40&advanced%5B0%5D%5Btype%5D=is+not+empty&advanced%5B0%5D%5Bterms%5D=&range=&collection=&type=&user=&tags=&public=&featured=&contributed=&submit_search=Search&output=timeglider-json",
                "min_zoom":1,
                "max_zoom":50,
                "show_footer": false
            });
    });
    </script>
    <div id="timeglider"></div>
</div>
<?php foot(); ?>