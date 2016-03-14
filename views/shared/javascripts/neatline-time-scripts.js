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

  _monkeyPatchFillInfoBubble: function() {
      var oldFillInfoBubble =
          Timeline.DefaultEventSource.Event.prototype.fillInfoBubble;
      Timeline.DefaultEventSource.Event.prototype.fillInfoBubble =
          function(elmt, theme, labeller) {
          var doc = elmt.ownerDocument;

          var title = this.getText();
          var link = this.getLink();
          var image = this.getImage();

          if (image != null) {
              var img = doc.createElement("img");
              img.src = image;

              theme.event.bubble.imageStyler(img);
              elmt.appendChild(img);
          }

          var divTitle = doc.createElement("div");
          var textTitle = doc.createElement("span");
          textTitle.innerHTML = title;
          if (link != null) {
              var a = doc.createElement("a");
              a.href = link;
              a.appendChild(textTitle);
              divTitle.appendChild(a);
          } else {
              divTitle.appendChild(textTitle);
          }
          theme.event.bubble.titleStyler(divTitle);
          elmt.appendChild(divTitle);

          var divBody = doc.createElement("div");
          this.fillDescription(divBody);
          theme.event.bubble.bodyStyler(divBody);
          elmt.appendChild(divBody);

          var divTime = doc.createElement("div");
          this.fillTime(divTime, labeller);
          theme.event.bubble.timeStyler(divTime);
          elmt.appendChild(divTime);

          var divWiki = doc.createElement("div");
          this.fillWikiInfo(divWiki);
          theme.event.bubble.wikiStyler(divWiki);
          elmt.appendChild(divWiki);
      };
  },
// may need to set a default value of none/false to centerDate
  loadTimeline: function(timelineId, timelineData, centerDate) {
    NeatlineTime._monkeyPatchFillInfoBubble();
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

    var tl = Timeline.create(document.getElementById(timelineId), bandInfos);
    tl.loadJSON(timelineData, function(json, url) {
      // log the timelineData, and see what's there
      // figure out what's creating the timelineData json
      // console.log("json: ", json);
      // console.log("timelineData: ", timelineData);
        if (json.events.length > 0) {
            eventSource.loadJSON(json, url);
            // If centerDate is set, use it, otherwise use the earliest date
            // console.log(centerDate);
            var earliestDate = eventSource.getEarliestDate();
            // console.log("earliestDate: " + earliestDate);
            if (centerDate === '0000-00-00') {
              centerDate = earliestDate;
              // console.log("centerDate: " + centerDate);
            }
            var parsedDate = Timeline.DateTime.parseGregorianDateTime(centerDate);
            // console.log('parseddate: ', parsedDate);
            tl.getBand(0).setCenterVisibleDate(parsedDate);
        }
    });

  }
};
