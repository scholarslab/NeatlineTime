<?php
/**
 * Timeline display partial.
 */

 // Get the timeline
 $timeline = get_current_record('neatline_time_timeline');
?>

<div id="timeline-<?php echo $timeline->id; ?>" style="width: 100%; height: 600px"></div>

<script>
  jQuery(document).ready(function($) {
        var centerDate = <?php echo json_encode($center_date); ?>;
        // get the location for the json data
        var jsonTimelineUri = '<?php echo neatlinetime_json_uri_for_timeline(); ?>';

        $.getJSON(jsonTimelineUri, function(data) {
          // console.log('data ', data);
          var timelineEvents = new Array();

          for (var i = 0; i < data.events.length; i++) {
            // Parse the date string into Y, M, D
            // Assumes YYYY-MM-DD
            var startDate = parseDate(data.events[i].start);

            // Create the slide object for the record
            var timelineEntry = {
              "text": {
                "headline": "<a href=" + data.events[i].link + ">" + data.events[i].title + "</a>"
              },
              "start_date": {
                  "year": startDate[0],
                  "month": startDate[1],
                  "day": startDate[2]
              },
            };

            // If the item has a description, include it
            if (data.events[i].description) {
              timelineEntry.text["text"] = data.events[i].description;
            }

            // If the record has an end date, include it
            if (data.events[i].end) {
              var endDate = parseDate(data.events[i].end);

              timelineEntry["end_date"] = {
                "year": endDate[0],
                "month": endDate[1],
                "day": endDate[2]
              };
            }

            // If the record has a file attachment, include that.
            // Limits based on returned JSON:
              // If multiple images are attached to the record, it only shows the first.
              // If a pdf is attached, it does not show it or indicate it.
              // If an mp3 is attached in Files, it does not appear.
            if (data.events[i].image) {
              timelineEntry["media"] = { "url": data.events[i].image };
            }

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

          var timelineDivID = '<?php echo $timeline->id; ?>';

          // initialize the timeline instance
          window.timeline = new TL.Timeline('timeline-' + timelineDivID, slides);

          function parseDate(entryDateString) {
            var entryDate = entryDateString;

            var parsedDate = entryDate.split('-');

            var entryYear = parsedDate[0];
            var entryMonth = parsedDate[1];
            var entryDay = parsedDate[2].slice(0, 2);

            return [entryYear, entryMonth, entryDay];
          };
        });
    });
</script>
