<?php
/**
 * @author      Scholars' Lab
 * @copyright   2010-2011 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version     $Id$
 * @package     Timeline
 * @link        http://omeka.org/codex/Plugins/Timeline
 */

/**
 * @package     Timeline
 * @copyright   2010-2011 The Board and Visitors of the University of Virginia
 */
class Timeline_AclTest extends Omeka_Test_AppTestCase
{
    const RESOURCE = 'Timeline_Timelines';

    public function setUp()
    {
        parent::setUp();
        $this->helper = new Timeline_IntegrationHelper;
        $this->helper->setUpPlugin();
    }

    public function assertPreConditions()
    {
        $this->assertTrue($this->acl->has(self::RESOURCE));
    }

    public function testUnauthenticatedUsersCannotAddOrEditTimelines()
    {
        $this->assertFalse($this->acl->isAllowed(null, self::RESOURCE, 'add'));
        $this->assertFalse($this->acl->isAllowed(null, self::RESOURCE, 'edit'));
    }
}
