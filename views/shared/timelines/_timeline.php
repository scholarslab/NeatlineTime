<?php
/**
 * Timeline display partial.
 */

 // Get the timeline
 $timeline = get_current_record('neatline_time_timeline');

?>

<!-- Container. -->
<!-- <div id="<?php //echo neatlinetime_timeline_id(); ?>" class="neatlinetime-timeline">
</div> -->

<div id='timeline-embed' style="width: 100%; height: 600px"></div>

<script src="//cdn.knightlab.com/libs/timeline3/latest/js/timeline.js"></script>
<script>
  jQuery(document).ready(function($) {
        // hackily put the timelinejs css into the head
        $('head').append('<link rel="stylesheet" type="text/css" href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">');

        var centerDate = '<?php echo $this->center_date; ?>';

        // get the location for the json data
        var jsonTimelineUri = '<?php echo neatlinetime_json_uri_for_timeline(); ?>';

        $.getJSON(jsonTimelineUri, function(data) {
          // console.log('data ', data);
          var timelineEvents = new Array();

          // Since end is reserved by jquery, change the end key to finish
          var stringData = JSON.stringify(data);
          var cleanedData = stringData.replace(/"end":/g, '"finishDate":');
          cleanedData = JSON.parse(cleanedData);
          console.log(cleanedData);

          for (var i = 0; i < data.events.length; i++) {
            // Parse the date string into Y, M, D
            // Assumes YYYY-MM-DD
            var startDate = parseDate(cleanedData.events[i].start);
            // console.log('finishDate: ', cleanedData.events[i][finishDate]);
            // var endDate = parseDate(cleanedData.events[i].finishDate);
            // console.log(startDate);
            // console.log(endDate);

            // Create the slide object for the record
            var timelineEntry = {
              "text": {
                "headline": data.events[i].title,
                "text": data.events[i].description,
              },
              "start_date": {
                  "year": startDate[0],
                  "month": startDate[1],
                  "day": startDate[2]
              },
            };

            if (data.events[i].image) {
              timelineEntry["media"] = { "url": data.events[i].image };
            }

            console.log(timelineEntry);
            // Add the slide to the events
            timelineEvents.push(timelineEntry);
          }

          // create the collection of slides
          var slides = {
            "title": {
              "text": {
                "headline": '<?php echo $timeline->title; ?>',
                "text": '<?php echo $timeline->description; ?>'
              }
            },
            "events": timelineEvents
          };

          // initialize the timeline instance
          window.timeline = new TL.Timeline('timeline-embed', slides);

          function parseDate(entryDateString) {
            console.log(entryDateString);
            var entryDate = entryDateString;
            // console.log('entrydate', entryDateString);

            var parsedDate = entryDate.split('-');

            var entryYear = parsedDate[0];
            var entryMonth = parsedDate[1];
            var entryDay = parsedDate[2].slice(0, 2);

            return [entryYear, entryMonth, entryDay];
          };

        });

        // NeatlineTime.loadTimeline(
        //     '<?php //echo neatlinetime_timeline_id(); ?>',
        //     '<?php //echo neatlinetime_json_uri_for_timeline(); ?>',
        //     centerDate
        // );
    });
</script>
