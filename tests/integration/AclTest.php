<?php
/**
 * Test for the NeatlineTime ACL.
 */
class AclTest extends NeatlineTime_Test_AppTestCase
{
    const RESOURCE = 'NeatlineTime_Timelines';

    public function assertPreConditions()
    {
        $this->assertTrue($this->acl->has(self::RESOURCE));
    }

    public function testAclForUnauthenticatedUsers()
    {
        $this->assertTrue($this->acl->isAllowed(null, self::RESOURCE, 'browse'));
        $this->assertTrue($this->acl->isAllowed(null, self::RESOURCE, 'show'));

        $this->assertFalse($this->acl->isAllowed(null, self::RESOURCE, 'add'));
        $this->assertFalse($this->acl->isAllowed(null, self::RESOURCE, 'edit'));
        $this->assertFalse($this->acl->isAllowed(null, self::RESOURCE, 'delete'));
        $this->assertFalse($this->acl->isAllowed(null, self::RESOURCE, 'query'));
    }

    public function testAclForResearcherUsers()
    {
        $this->assertTrue($this->acl->isAllowed('researcher', self::RESOURCE, 'browse'));
        $this->assertTrue($this->acl->isAllowed('researcher', self::RESOURCE, 'show'));

        $this->assertFalse($this->acl->isAllowed('researcher', self::RESOURCE, 'add'));
        $this->assertFalse($this->acl->isAllowed('researcher', self::RESOURCE, 'edit'));
        $this->assertFalse($this->acl->isAllowed('researcher', self::RESOURCE, 'delete'));
        $this->assertFalse($this->acl->isAllowed('researcher', self::RESOURCE, 'query'));
    }

    public function testAclForContributorUsers()
    {
        $this->assertTrue($this->acl->isAllowed('contributor', self::RESOURCE, 'browse'));
        $this->assertTrue($this->acl->isAllowed('contributor', self::RESOURCE, 'show'));
        $this->assertTrue($this->acl->isAllowed('contributor', self::RESOURCE, 'add'));
        $this->assertTrue($this->acl->isAllowed('contributor', self::RESOURCE, 'editSelf'));
        $this->assertTrue($this->acl->isAllowed('contributor', self::RESOURCE, 'querySelf'));
        $this->assertTrue($this->acl->isAllowed('contributor', self::RESOURCE, 'deleteSelf'));

        $this->assertFalse($this->acl->isAllowed('contributor', self::RESOURCE, 'editAll'));
        $this->assertFalse($this->acl->isAllowed('contributor', self::RESOURCE, 'queryAll'));
        $this->assertFalse($this->acl->isAllowed('contributor', self::RESOURCE, 'deleteAll'));
    }
}