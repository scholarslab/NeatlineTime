function onLoad() {
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
	if (!Omeka.Timeline.eventSource) {
		Omeka.Timeline.eventSource = new Timeline.DefaultEventSource();		
	}
	if (!Omeka.Timeline.bandInfos) {
		Omeka.Timeline.bandInfos = [ Timeline.createBandInfo( {
			width : "70%",
			eventSource : Omeka.Timeline.eventSource,
			intervalUnit : Timeline.DateTime.MONTH,
			intervalPixels : 100
		}), Timeline.createBandInfo( {
			width : "30%",
			intervalUnit : Timeline.DateTime.YEAR,
			intervalPixels : 200
		}) ];
		Omeka.Timeline.bandInfos[1].syncWith = 0;
		Omeka.Timeline.bandInfos[1].highlight = true;
	}
	Omeka.Timeline.timeline = Timeline.create(Omeka.Timeline.timelinediv, Omeka.Timeline.bandInfos);
	Omeka.Timeline.eventSource.loadJSON( { "events": Omeka.Timeline.events }, document.location.href);
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