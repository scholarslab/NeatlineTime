if (typeof(Omeka) == 'undefined') {
	Omeka = new Object();
}

if (!Omeka.Timeline) {
	Omeka.Timeline = new Array();
}
Omeka.Timeline.onLoad = function(event, config) {
	// we must override this method to provide for our Javascript behaviors on
	// events
	Timeline.DefaultEventSource.prototype._resolveRelativeURL = function(url, base) {
	    if (url == null || url == '') {
	        return url;
	    } else if (url.indexOf('://') > 0 || url.indexOf('javascript:') == 0) {
	        return url;
	    } else if (url.substr(0,1) == '/') {
	        return base.substr(0, base.indexOf('/', base.indexOf('://') + 3)) + url;
	    } else {
	        return base + url;
	    }
	};
	if (!config.eventSource) {
		config.eventSource = new Timeline.DefaultEventSource();		
	}
	if (!config.bandInfos) {
		config.bandInfos = [ Timeline.createBandInfo( {
			width : "70%",
			eventSource : config.eventSource,
			intervalUnit : Timeline.DateTime.MONTH,
			intervalPixels : 100
		}), Timeline.createBandInfo( {
			width : "30%",
			intervalUnit : Timeline.DateTime.YEAR,
			intervalPixels : 200
		}) ];
		config.bandInfos[1].syncWith = 0;
		config.bandInfos[1].highlight = true;
	}
	Omeka.Timeline[Omeka.Timeline.length] = Timeline.create(config.timelinediv, config.bandInfos);
	config.eventSource.loadJSON( { "events": config.events }, document.location.href);
}

var resizeTimerID = null;

function onResize() {
	if (resizeTimerID == null) {
		resizeTimerID = window.setTimeout(function() {
			resizeTimerID = null;
			Omeka.Timeline.timeline.layout();
		}, 500);
	}
}