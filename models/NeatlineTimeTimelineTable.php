<?php

class NeatlineTimeTimelineTable extends Omeka_Db_Table {

    // Set to ensure our table alias is 'ntt'.
    protected $_alias = 'ntt';

    /**
     * Filter public/not public timelines.
     * 
     * @param Zend_Db_Select
     * @param boolean Whether to retrieve only public timelines.
     * @return void
     */
    public function filterByPublic($select, $isPublic)
    {         
        $isPublic = (bool) $isPublic;

        if ($isPublic) {
            $select->where('ntt.public = 1');
        } else {
            $select->where('ntt.public = 0');
        }
    }

    /**
     * Filter featured/not featured timelines.
     *
     * @param Zend_Db_Select
     * @param boolean Whether to retrieve only featured timelines.
     */
    public function filterByFeatured($select, $isFeatured)
    {
        $isFeatured = (bool) $isFeatured;
        
        if ($isFeatured) {
            $select->where('ntt.featured = 1');
        } else {
            $select->where('ntt.featured = 0');
        }
    }

    /**
     * Filter for timelines created by a specific user.
     *
     * @param Zend_Db_Select
     * @param boolean Whether to retrieve only featured timelines.
     */
    public function filterByUser($select, $userId)
    {
        $userId = (int) $userId;
        
        if ($userId) {
            $select->where('ntt.creator_id = ?', $userId);
        }
    }

    /**
     * Possible options: 'public','user', and 'featured'.
     * 
     * @param Omeka_Db_Select
     * @param array
     * @return void
     */
    public function applySearchFilters($select, $params)
    {   
        if (isset($params['user'])) {
            $userId = $params['user'];
            $this->filterByUser($select, $userId);
        }

        if(isset($params['public'])) {
            $this->filterByPublic($select, $params['public']);
        }

        if(isset($params['featured'])) {
            $this->filterByFeatured($select, $params['featured']);
        }

        $select->group("ntt.id");
    }

    /**
     * This is a kind of simple factory that spits out proper beginnings 
     * of SQL statements when retrieving items
     *
     * @return Omeka_Db_Select
     */
    public function getSelect()
    {
        $select = parent::getSelect();

        $acl = Omeka_Context::getInstance()->acl;
        if ($acl && $acl->has('NeatlineTime_Timelines')) {
            $has_permission = $acl->checkUserPermission('NeatlineTime_Timelines', 'showNotPublic');
            if (!$has_permission) {
                $select->where('ntt.public = 1');
            }
        }

        return $select;
    }
}