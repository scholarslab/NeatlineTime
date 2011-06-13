<?php
/**
* @author    Scholars' Lab
* @copyright 2010 The Board and Visitors of the University of Virginia
* @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
* @version   $Id$
* @package   Timeline
* @link      http://omeka.org/codex/Plugins/Timeline
 */

/**
 * Timeline plugin class
 *
 * @package Timeline
 */
class TimelinePlugin
{
    private static $_hooks = array(
        'install',
        'uninstall',
        'upgrade',
        'define_acl',
        'define_routes',
        'admin_append_to_plugin_uninstall_message',
        'item_browse_sql'
    );

    private static $_filters = array(
        'item_square_thumbnail',
        'item_has_thumbnail',
        );

    private $_db;

    /**
     * Initializes instance properties and hooks the plugin into Omeka.
     */
    public function __construct()
    {
        $this->_db = get_db();
        self::addHooksAndFilters();
    }

    /**
     * Centralized location where plugin hooks and filters are added
     */
    public function addHooksAndFilters()
    {
        foreach (self::$_hooks as $hookName) {
            $functionName = Inflector::variablize($hookName);
            add_plugin_hook($hookName, array($this, $functionName));
        }

        foreach (self::$_filters as $filterName) {
            $functionName = Inflector::variablize($filterName);
            add_filter($filterName, array($this, $functionName));
        }
        
        // Add the tag dropdown filter separately.
        add_filter(array(
            'Form', 'Item', 'Item Type Metadata', 'Tag'
            ), array($this, 'timelineTagSelect'));
    }

    /**
     * Timeline install hook
     */
    public function install()
    {
	    # now we add 'Timeline' item type
	    $timelinemitemtype = array(
	        'name'       => "Timeline",
	        'description' => "Timeline composed of items in this repository"
	        );
	    $timelinemitemtypemetadata =
	        array(array('name' => "Tag",
	            'description' => "Items with this tag should be included in the timeline"
	            ));
	    try {
	        
			$itemtype = insert_item_type($timelinemitemtype,$timelinemitemtypemetadata);
	        define("TIMELINE_ITEMTYPE",$itemtype->id);

		} catch (Exception $e) {
	            debug("Unable to install Timeline Itemtype: " . $e->getMessage() );
	    }
    }

    /**
     * Timeline uninstall hook
     */
    public function uninstall()
    {
		delete_option('timeline_plugin_version');
    }

    public function upgrade($oldVersion, $newVersion)
    {
        // Catch-all for pre-2.0 versions
        if (version_compare($oldVersion, '2.0-dev', '<=')) {

        }
    }

    /**
     * Timeline define_acl hook
     * Restricts access to admin-only controllers and actions.
     */
    public function defineAcl($acl)
    {
        
    }

    /**
     * Timeline define_routes hook
     * Defines public-only routes that set the Timeline controller as the
     * only accessible one.
     */
    public function defineRoutes($router)
    {
        $router->addRoute(
            'timelineDefault',
            new Zend_Controller_Router_Route(
                'timelines/:action/:id',
                array(
                    'module'     => 'timeline',
                    'controller' => 'timelines',
                    'action'     => 'show'
                    )
                )
            );
    }

    public function adminAppendToPluginUninstallMessage()
    {

    }
    
    /**
     * Deal with Timeline-specific search terms.
     *
     * @param Omeka_Db_Select $select
     * @param array $params
     */
    public function itemBrowseSql($select, $params)
    {
        if (($request = Zend_Controller_Front::getInstance()->getRequest())) {

        }
    }
    
    /**
     *
     * @param <type> $thumb
     * @param <type> $item
     * @return <type>
     */
    function itemHasThumbnail($thumb, $item) {
        return true;
    }

    /**
     *
     * @param <type> $thumb
     * @param <type> $item
     * @return <type> 
     */
    function itemSquareThumbnail($thumb, $item) {
        $item = $item ? $item : get_item_by_id(item('ID'));

        if ($item->getItemType()->name == "Timeline") {
            return "<img src=" . img('timeline.png') . " />";
        } else {
            return $thumb;
        }
    }
    
    /**
     *
     * @param <type> $html
     * @param <type> $inputNameStem
     * @param <type> $value
     * @param <type> $options
     * @param <type> $record
     * @param <type> $element
     * @return <type> 
     */
    function timelineTagSelect($html,$inputNameStem,$value,$options,$record,$element)
    {
        $div = __v()->partial('widgets/tag.phtml', array("inputNameStem" =>$inputNameStem, "value" => $value, "options" => $options, "record" => $record, "element" => $element));
        return $div;
    }
}
