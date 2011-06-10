

The Timeline plugin was created by the [Scholars' Lab][1] at the University of Virginia Library. It is a helper function for creating SIMILE Timelines from an array of items. It allows you to specify the metadata elements from which the time data should come, as well as the element that specifies the caption (by default, the Dublin Core Title element). 

  Installation 

----------

1.  Upload the Timeline plugin directory to your server. See See: [Installing a Plugin][2].

2.  Activate the plugin from the admin → Settings → Plugins page. 

  Using the Timeline Plugin 

----------

To call the CreateTimeline function, paste something similar to this into the page where you want the timeline to appear:

<div id="timeline" style="height:100px;"></div>
<?php createTimeline('timeline', $items ); ?>

The above snip includes the minimum code needed to create a SIMILE Timeline with the Omeka.Timeline namespace. 

The full set of parameters for the CreateTimeline helper are:

*  $div: (required) The div id to which to attach the Timeline

*  $items: (required) Array of items to populate the Timeline

*  $captionElementSet: (optional) Omeka Element set to use for captions; defaults to "Dublin Core" (dc namespace)

*  $captionElement: (optional) Specific Omeka Element for the Timeline caption; default is "Title" (dc.title)

*  $dateElementSet: (optional) Omeka ElementSet used for the date; defaults to "Dublin Core" (dc namespace)

*  $dateElement: (optional) Specific element for the Timeline date

The helper function loads the SIMILE Timeline widget configuration into the Omeka.Timeline namespace to allow more sophisticated use of the Timeline. Events are drawn from JavaScript object Omeka.Timeline.eventSource, and the configuration of the Timeland bands is drawn from Omeka.Timeline.bandInfos. You may also offer an Omeka.Timeline.behavior callback accepting one parameter. This will be called with the ID of the backing Item when someone clicks on an event in the timeline. Any of these objects can overridden. If you do override them you must so do before calling createTimeline(). For more information on extending and overriding default settings in SIMILE Timeline, consult the [project's wiki][3].

In addition to the helper function, this plugin offers timelines as first-class items. Create a new Item with Item Type 'Timeline" and you will find that the Item Type Metadata includes a "Tag" field. If you offer a tag in that field, then you will find at:

/timelines/show/ID

a simple timeline with default styling featuring all of the Items in your instance with that tag. The "Tag" field is repeatable and the timelines/show view will pick up all Items with any of the Tags.

<!-- 
NewPP limit report
Preprocessor node count: 6/1000000
Post-expand include size: 0/2097152 bytes
Template argument size: 0/2097152 bytes
Expensive parser function count: 0/100
-->

Retrieved from "[http://omeka.org/codex/Plugins/Timeline](http://omeka.org/codex/Plugins/Timeline)"

[1]: http://scholarslab.org/ "http://scholarslab.org/"
[2]: http://omeka.org/codex/Installing_a_Plugin "http://omeka.org/codex/Installing_a_Plugin"
[3]: http://code.google.com/p/simile-widgets/wiki/Timeline "http://code.google.com/p/simile-widgets/wiki/Timeline"
