<?php
/**
 * Assertion for any "*All" and "*Self" permissions for NeatlineTime_Timelines.
 *
 * Lovingly stolen from Omeka's Item_OwnershipAclAssertion class, licensed
 * under the GPL3.
 */
class NeatlineTime_OwnershipAclAssertion implements Zend_Acl_Assert_Interface
{
    /**
     * Assert whether or not the ACL should allow access.
     */
    public function assert(Zend_Acl $acl,
                           Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {
        $allPriv = $privilege . 'All';
        $selfPriv = $privilege . 'Self';
        if (!($role instanceof User)) {
            $allowed = false;
        } elseif ($resource instanceof NeatlineTime_Timeline) {
            $allowed = $acl->isAllowed($role, $resource, $allPriv)
                   || ($acl->isAllowed($role, $resource, $selfPriv)
                       && $this->_userOwnsTimeline($role, $resource));
        } else {
            // The "generic" privilege is allowed if the user can
            // edit any items whatsoever.
            $allowed = $acl->isAllowed($role, $resource, $allPriv)
                    || $acl->isAllowed($role, $resource, $selfPriv);
        }
        return $allowed;
    }

    private function _userOwnsTimeline($user, $resource)
    {
        return $resource->addedBy($user);
    }
}
