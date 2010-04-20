<?php
/**
 * Timeline helper functions
 *
 * @author Scholars' Lab 
 * @version $Id$
 *
 */

/**
 * Return the metadata field for a specific item
 * 
 * @param Omeka_Item $item the item from which to retrieve metadata
 * @param string $elementSet Element set used to find a specific elemnt
 * @param string $element Metadata element the text of which to retrieve
 * @return Text of the element
 * 
 */
function getMet($item, $elementSet, $element) {
	 $tmp = $item->getElementTextsByElementNameAndSetName($element, $elementSet);
	 return addslashes( $tmp[0]->text ) ;	
}

/**
 * Create a timeline widget
 * 
 * @param string $div div to push timeline to
 * @param array $items Items to populate in the timeline
 * @param string $captionElementSet Element set used for caption
 * @param string $captionElement Specific element for the timeline caption
 * @param string $dateElementSet Element set used for date
 * @param string $dateElement Specific element for the timeline date
 * @return void
 * 
 */
function createTimeline($div, $items = array(), $captionElementSet = "Dublin Core", $captionElement =  "Title", $dateElementSet = "Dublin Core", $dateElement =  "Date" ) {
		echo js("prototype");
		global $mets;
		$mets = array($captionElementSet, $captionElement, $dateElementSet, $dateElement);
		echo "
		<!--  we have to load the script in this funny way because we need to get the tag into the head of the doc
			because of the the funky way Simile Timeline loads its sub-scripts  -->
		<script type='text/javascript'>
			scripttag = document.createElement('script'); 
			scripttag.src = 'http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=false';
			scripttag.type = 'text/javascript';
			$$('head')[0].insert(scripttag);

			if (typeof(Omeka) == 'undefined') {
				Omeka = new Object();
			}

			if (!Omeka.Timeline) {
				Omeka.Timeline = new Object();
			}
			
		</script>
";
		echo "
		<script type='text/javascript' defer='defer'>	
			Omeka.Timeline.timelinediv = $('" . $div . "');
			
			Omeka.Timeline.events = [ ";
			
				function event_to_json($item) {
					global $mets;
					return "{ 'title' : '" . getMet($item, $mets[0], $mets[1]) . "', 
					'start' : '" . getMet($item, $mets[2], $mets[3]) . "',
					'description' : '" . getMet($item, "Dublin Core", "Description") . "',
					'durationEvent':false }";
				}
				echo implode(',',array_map('event_to_json', $items));
				
			echo "	];
			
		</script> "; 
	     echo js("createTimeline");
		echo "
		<script type='text/javascript'>
			Event.observe(window, 'load', onLoad);
			Event.observe(document.body, 'resize', onResize);
		</script>
"; 
}
?>