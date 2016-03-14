<?php
/**
 * Timeline display partial.
 */

?>

<!-- Container. -->
<!-- <div id="<?php //echo neatlinetime_timeline_id(); ?>" class="neatlinetime-timeline">
</div> -->

<div id='timeline-embed' style="width: 100%; height: 600px"></div>

<script src="//cdn.knightlab.com/libs/timeline3/latest/js/timeline.js"></script>
<script>
  jQuery(document).ready(function($) {

        $('head').append('<link rel="stylesheet" type="text/css" href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">');

        var centerDate = '<?php echo $this->center_date; ?>';

        var jsonTimelineUri = '<?php echo neatlinetime_json_uri_for_timeline(); ?>';

        $.getJSON(jsonTimelineUri, function(data) {
          console.log('data ', data);
          var timelineEvents = new Array();
          for (var i = 0; i < data.events.length; i++) {
            var timelineEntry = {
              "text": {
                "text": data.events[i].description,
              },
              "start_date": {
                  "year": "1963"
              },
            };
            timelineEvents.push(timelineEntry);
          }
          // console.log("timeline events: ", JSON.stringify(timelineEvents));

          var slides = {
            "events": timelineEvents
          };

          window.timeline = new TL.Timeline('timeline-embed', slides);

          console.log(JSON.stringify(slides));
        });

        NeatlineTime.loadTimeline(
            '<?php echo neatlinetime_timeline_id(); ?>',
            '<?php echo neatlinetime_json_uri_for_timeline(); ?>',
            centerDate
        );
    });
</script>
