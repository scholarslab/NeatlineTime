<?php
$this->headScript()->appendFile('http://static.simile.mit.edu/timeline/api-2.3.1/timeline-api.js?bundle=false');
queue_js('createTimeline');
?>
<?php head(); ?>
<h1><?php echo item('Dublin Core', 'Title'); ?></h1>
<?php //display_timeline_for_item(); ?>

<?php if ($timelineItems = get_items_for_timeline()) : ?>

<div id="timeline" style="height: 200px;"></div>
    
<script type="text/javascript" charset="utf-8">
            SimileAjax.History.enabled = false;
          var TLtmp = new Object();
          TLtmp.timelinediv = "timeline";
          TLtmp.events = [
          <?php
            $tmp = array();
            foreach ($timelineItems as $item) {
                array_push($tmp, get_timeline_json_for_item($item));
        	}

            $html = implode(',',$tmp);
            
            echo $html;
            ?>
          ];
          $(document).ready(function() {              
              Omeka.Timeline.createTimeline(TLtmp);
          });
          $(window).resize(Omeka.Timeline.onResize);
</script>

<?php endif; ?>
<?php foot(); ?>
