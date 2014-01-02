<?php

class NeatlineTimeTimelineTable extends Omeka_Db_Table {

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
            $select->where('neatline_time_timelines.public = 1');
        } else {
            $select->where('neatline_time_timelines.public = 0');
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
            $select->where('neatline_time_timelines.featured = 1');
        } else {
            $select->where('neatline_time_timelines.featured = 0');
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
            $select->where('neatline_time_timelines.creator_id = ?', $userId);
        }
    }

    /**
     * Order SELECT results randomly.
     *
     * @param Zend_Db_Select
     * @return void
     */
    public function orderSelectByRandom($select)
    {
        $select->order('RAND()');
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

        if(isset($params['random'])) {
            $this->orderSelectByRandom($select);
        }

        $select->group("neatline_time_timelines.id");
    }

    public function getSelect()
    {
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('NeatlineTime_Timelines');
        $permissions->apply($select, 'neatline_time_timelines', null);
        return $select;
    }

    /**
     * Return the columns to be used for creating an HTML select of timelines.
     *
     * @return array
     */
    public function _getColumnPairs()
    {
        return array(
            'neatline_time_timelines.id',
            'neatline_time_timelines.title'
        );
    }
}
