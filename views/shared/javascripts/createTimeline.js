if (typeof (Omeka) == 'undefined') {
    Omeka = new Object();
}

if (!Omeka.Timeline) {
    Omeka.Timeline = new Array();
}

if (!Omeka.Timeline.history) {
    Omeka.Timeline.history = new Array();
}

Omeka.Timeline.createTimeline = function(config) {
    
    if (!config.eventSource) {
        config.eventSource = new Timeline.DefaultEventSource();
    }
    
    if (!config.bandInfos) {
        
        config.bandInfos = [
                Timeline.createBandInfo( {
                    width : "70%",
                    eventSource : config.eventSource,
                    intervalUnit : Timeline.DateTime.MONTH,
                    intervalPixels : 100
                }),
                Timeline.createBandInfo( {
                    width : "30%",
                    intervalUnit : Timeline.DateTime.YEAR,
                    intervalPixels : 200,
                    eventSource : config.secondSource ? config.secondSource
                            : new Timeline.DefaultEventSource(),
                    overview : true
                })
        ];
        config.bandInfos[1].syncWith = 0;
        config.bandInfos[1].highlight = true;
    }
    config.eventSource.loadJSON( {
        "events" : config.events
        }, 
        document.location.href
    );

    Omeka.Timeline[Omeka.Timeline.length] = Timeline.create(document.getElementById(config.timelinediv),
            config.bandInfos);

    Omeka.Timeline[Omeka.Timeline.length-1].setCenter = function(s) {
        this._bands.each(function(i) {
            i.setCenterVisibleDate(SimileAjax.DateTime
                    .parseGregorianDateTime(s))
        });
    }   
}

var resizeTimerID = null;

Omeka.Timeline.onResize = function() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            Omeka.Timeline.timeline.layout();
        }, 500);
    }
}
