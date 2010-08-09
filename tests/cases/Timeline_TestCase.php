<?php
class Timeline_TestCase extends Omeka_Test_AppTestCase 
{
	const PLUGIN_NAME = 'Timeline';
	
	public function setUp()
	{
		parent::setUp();
	}
	
	public function _addPluginHooksAndFilters($pluginBroker,$pluginName)
	{
		assertTrue(1,1);
	}
	
	protected function _createNewTimeline()
	{
		
	}
}
?>