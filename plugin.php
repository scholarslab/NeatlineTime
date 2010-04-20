<?php
/**
 * @version $Id$
 * @copyright
 * @package Timeline
 **/

add_plugin_hook('install', 'timeline_install');
add_plugin_hook('uninstall', 'timeline_uninstall');
add_plugin_hook('initialize', 'timeline_initialize');
add_plugin_hook('define_routes', 'timeline_routes');

add_filter("show_item_in_page","timeline_show_item_in_page");

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
              }
}

function timeline_uninstall()
{
	delete_option('timeline_plugin_version');
}

function timeline_initialize()
{
	$writer = new Zend_Log_Writer_Stream(LOGS_DIR . DIRECTORY_SEPARATOR . "timeline.log");
	$logger = new Zend_Log($writer);
	require_once TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'helpers/CreateTimeline.php';	
}

function timeline_show_item_in_page($html, $item){
	$writer = new Zend_Log_Writer_Stream(LOGS_DIR . DIRECTORY_SEPARATOR . "timeline.log");
	$logger = new Zend_Log($writer);
	$logger->info("timeline_show_item_in_page called with item: " . print_r($item,true));
	if ($item->getItemType()->name == "Timeline") {
		$tags =  item("Item Type Metadata","Tag",array("delimiter" => ','));
		$query = array('tags' => $tags);
		$things = get_items($query);
		echo '<div id="timelinediv" style="height:200px"></div>';
		createTimeline("timelinediv",$things);
		return ""  ;
	}
	else return $html;
}

// Add the routes from routes.ini in this plugin folder.
function timeline_routes($router)
{
	$router->addConfig(new Zend_Config_Ini(TIMELINE_PLUGIN_DIR .
	DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
}
