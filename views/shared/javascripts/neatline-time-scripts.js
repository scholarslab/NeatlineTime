var NeatlineTime = {
  resizeTimerID: null,

  resizeTimeline: function() {
     if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            tl.layout();
        }, 500);
    }
  },

  loadTimeline: function(timelineId, timelineData) {
    var eventSource = new Timeline.DefaultEventSource();

    var defaultTheme = Timeline.getDefaultTheme();
    defaultTheme.mouseWheel = 'zoom';

    var bandInfos = [
        Timeline.createBandInfo({
            eventSource:    eventSource,
            width:          "80%",
            intervalUnit:   Timeline.DateTime.MONTH,
            intervalPixels: 100,
            zoomIndex:      10,
            zoomSteps:      new Array(
              {pixelsPerInterval: 280,  unit: Timeline.DateTime.HOUR},
              {pixelsPerInterval: 140,  unit: Timeline.DateTime.HOUR},
              {pixelsPerInterval:  70,  unit: Timeline.DateTime.HOUR},
              {pixelsPerInterval:  35,  unit: Timeline.DateTime.HOUR},
              {pixelsPerInterval: 400,  unit: Timeline.DateTime.DAY},
              {pixelsPerInterval: 200,  unit: Timeline.DateTime.DAY},
              {pixelsPerInterval: 100,  unit: Timeline.DateTime.DAY},
              {pixelsPerInterval:  50,  unit: Timeline.DateTime.DAY},
              {pixelsPerInterval: 400,  unit: Timeline.DateTime.MONTH},
              {pixelsPerInterval: 200,  unit: Timeline.DateTime.MONTH},
              {pixelsPerInterval: 100,  unit: Timeline.DateTime.MONTH} // DEFAULT zoomIndex
            )
        }),
        Timeline.createBandInfo({
            overview:       true,
            eventSource:    eventSource,
            width:          "20%",
            intervalUnit:   Timeline.DateTime.YEAR,
            intervalPixels: 200
        })
    ];

    bandInfos[1].syncWith = 0;
    bandInfos[1].highlight = true;

    tl = Timeline.create(document.getElementById(timelineId), bandInfos);
    tl.loadJSON(timelineData, function(json, url) {
        eventSource.loadJSON(json, url);
    });
  },

};


