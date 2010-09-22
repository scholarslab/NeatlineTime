<?php
/**
 * @version $Id$
 * @copyright Scholars' Lab
 * @package Timeline
 **/

add_plugin_hook('install', 'timeline_install');
add_plugin_hook('uninstall', 'timeline_uninstall');
add_plugin_hook('initialize', 'timeline_initialize');
add_plugin_hook('define_routes', 'timeline_routes');
add_plugin_hook('public_theme_header', 'timeline_header');

add_filter("show_item_in_page","timeline_show_item_in_page");
add_filter("item_square_thumbnail","timeline_item_square_thumbnail");
add_filter("item_has_thumbnail","timeline_item_has_thumbnail");

add_filter(array('Form','Item','Item Type Metadata','Tag'),"timeline_tag_widget");

define('TIMELINE_PLUGIN_VERSION', get_plugin_ini('Timeline', 'version'));
define('TIMELINE_PLUGIN_DIR', dirname(__FILE__));

function timeline_install()
{
	set_option('timeline_version', TIMELINE_PLUGIN_VERSION);

	# now we add 'Timeline' item type
	$timelinemitemtype = array(
     'name'       => "Timeline", 
      'description' => "Timeline composed of items in this repository"
      );

      $timelinemitemtypemetadata =
      array(array('name'        => "Tag",
              'description' => "Items with this tag should be included in the timeline"             
              ));
              try {
              	$itemtype = insert_item_type($timelinemitemtype,$timelinemitemtypemetadata);
              	define("TIMELINE_ITEMTYPE",$itemtype->id);
              }
              catch (Exception $e) {
              	debug("Unable to install Timeline Itemtype: " . $e->getMessage() );
              }
}

function timeline_uninstall()
{
	delete_option('timeline_plugin_version');
}

function timeline_initialize()
{
	require_once TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'helpers/CreateTimeline.php';
}

// for actions show and edit, loads the appropriate JS. We need both show and edit to 
// handle both presentation and editing modes
function timeline_header()
{
	$actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	switch (Zend_Controller_Front::getInstance()->getRequest()->getActionName()) {
		case 'show' :
			$j =  js('createTimeline');
			print <<<EOT
			<!-- begin Timeline plugin scripts -->
			<script type="text/javascript" src="http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=false"></script>
			$j
			<!-- end Timeline plugin scripts -->
EOT;
			break;
		case 'edit' :
			echo "<!-- begin Timeline plugin scripts -->\n";
			echo "<script type=\"text/javascript\" src=\"http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=false\"></script>\n";
			
			echo "\n<!-- end Timeline plugin scripts -->\n";
			break;
		default:
			break;
	}
}

function timeline_show_item_in_page($html, $item, $options = array()) {
	if ($item->getItemType()->name == "Timeline") {
		$CONFIG = array(
			'id' =>	'timelinediv',
			'class' =>	'',
			'height' =>	'200px'
			);
			$CONFIG = array_merge($CONFIG, $options);
			$tags =  item("Item Type Metadata","Tag",array("delimiter" => ','));
			$things = get_items(array('tags' => $tags));
			echo '<div id="timelinediv' . $item->id . '" style="height:200px"></div>';
			createTimeline("timelinediv" . $item->id,$things);
			return ""  ;
	}
	else return $html;
}

function timeline_item_has_thumbnail($thumb, $item) {
	return true;
}

function timeline_item_square_thumbnail($thumb, $item) {
	$item = $item ? $item : get_item_by_id(item('ID'));
	if ($item->getItemType()->name == "Timeline") {
		return "<img src=" . img('timeline.png') . " />";
	}
	else {
		return $thumb;
	}
}

function timeline_tag_widget($html,$inputNameStem,$value,$options,$record,$element) {
	$div = __v()->partial('widgets/tag.phtml', array("inputNameStem" =>$inputNameStem, "value" => $value, "options" => $options, "record" => $record, "element" => $element));
	return $div;
}

// Add the routes from routes.ini in this plugin folder.
function timeline_routes($router)
{
	$router->addConfig(new Zend_Config_Ini(TIMELINE_PLUGIN_DIR .
	DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
}
