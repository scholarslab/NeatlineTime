<?php
/**
 * Timeline helper functions
 *
 * @author    Scholars' Lab
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   $Id$
 * @package   Timeline
 * @link      http://omeka.org/codex/Plugins/Timeline
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
    return js_escape( $tmp[0]->text ) ;
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
    $mets = array($captionElementSet, $captionElement, $dateElementSet, $dateElement);
    echo <<<EOT
    <script type="text/javascript" charset="utf-8">
        var TLtmp = new Object();
        TLtmp.timelinediv = document.getElementById('$div');
        TLtmp.events = [
EOT;

    $tmp = array();
    foreach ($items as $item) {
        $id = $item->id;
        array_push($tmp,"{ 'title' : " . getMet($item, $mets[0], $mets[1]) . ",
            'start' : " . getMet($item, $mets[2], $mets[3]) . ",
            'description' : " . getMet($item, "Dublin Core", "Description") . ",
            'durationEvent':false , 'eventID' : " . $id . ", " .
            "'link' : 'javascript:Omeka.Timeline.behavior(" . $id . ")'" . "}");
	}
	
    echo implode(',',$tmp);

    echo '	];
        Omeka.Timeline.history.push(TLtmp);
        Omeka.jQuery(document).ready(function () { Omeka.Timeline.createTimeline(TLtmp) } );
        delete(TLtmp);
        Omeka.jQuery(document.body).resize(Omeka.Timeline.onResize);
    </script>
'; 
}
?>