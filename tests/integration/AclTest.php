<?php
/**
 * Test for the NeatlineTime ACL.
 */
class AclTest extends NeatlineTime_Test_AppTestCase
{
    const RESOURCE = 'NeatlineTime_Timelines';

    /**
     * Data for testAcl
     */
    public function acl()
    {
        return array(
            // $isAllowed, $role, $privilege
            array(false, null, 'add'),
            array(false, null, 'edit'),
            array(false, null, 'delete'),
            array(false, null, 'query'),
            array(true, null, 'browse'),
            array(true, null, 'show'),
            array(false, 'researcher', 'add'),
            array(false, 'researcher', 'edit'),
            array(false, 'researcher', 'delete'),
            array(false, 'researcher', 'query'),
            array(true, 'researcher', 'browse'),
            array(true, 'researcher', 'show'),
            array(true, 'researcher', 'showNotPublic'),
            array(true, 'contributor', 'add'),
            array(true, 'contributor', 'editSelf'),
            array(true, 'contributor', 'deleteSelf'),
            array(true, 'contributor', 'querySelf'),
            array(false, 'contributor', 'editAll'),
            array(false, 'contributor', 'deleteAll'),
            array(false, 'contributor', 'queryAll'),
            array(true, 'contributor', 'browse'),
            array(true, 'contributor', 'show'),
            array(true, 'contributor', 'showNotPublic'),
            array(true, 'admin', 'add'),
            array(true, 'admin', 'editSelf'),
            array(true, 'admin', 'deleteSelf'),
            array(true, 'admin', 'querySelf'),
            array(true, 'admin', 'editAll'),
            array(true, 'admin', 'deleteAll'),
            array(true, 'admin', 'queryAll'),
            array(true, 'admin', 'browse'),
            array(true, 'admin', 'show'),
            array(true, 'admin', 'showNotPublic'),
            array(true, 'super', 'add'),
            array(true, 'super', 'editSelf'),
            array(true, 'super', 'deleteSelf'),
            array(true, 'super', 'querySelf'),
            array(true, 'super', 'editAll'),
            array(true, 'super', 'deleteAll'),
            array(true, 'super', 'queryAll'),
            array(true, 'super', 'browse'),
            array(true, 'super', 'show'),
            array(true, 'super', 'showNotPublic'),
        );
    }

    public function assertPreConditions()
    {
        $this->assertTrue($this->acl->has(self::RESOURCE));
    }

    /**
     * @dataProvider acl
     */
    public function testAcl($isAllowed, $role, $privilege = null)
    {
        $this->assertEquals($isAllowed,
            $this->acl->isAllowed($role, self::RESOURCE, $privilege));
    }
}