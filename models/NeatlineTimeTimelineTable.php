<?php

class NeatlineTimeTimelineTable extends Omeka_Db_Table
{

    /**
     * Filter public/not public timelines.
     *
     * @see self::applySearchFilters()
     * @param Omeka_Db_Select $select
     * @param bool $isPublic Whether to retrieve only public timelines.
     * @return void
     */
    public function filterByPublic(Omeka_Db_Select $select, $isPublic)
    {
        $isPublic = (bool) $isPublic;

        if ($isPublic) {
            $select->where('neatline_time_timelines.public = 1');
        } else {
            $select->where('neatline_time_timelines.public = 0');
        }
    }

    /**
     * Apply a featured/not featured filter to the select object.
     *
     * @see self::applySearchFilters()
     * @param Omeka_Db_Select $select
     * @param bool $isFeatured
     */
    public function filterByFeatured(Omeka_Db_Select $select, $isFeatured)
    {
        $isFeatured = (bool) $isFeatured;

        if ($isFeatured) {
            $select->where('neatline_time_timelines.featured = 1');
        } else {
            $select->where('neatline_time_timelines.featured = 0');
        }
    }

    /**
     * Apply a user filter to the select object.
     *
     * @see self::applySearchFilters()
     * @param Omeka_Db_Select $select
     * @param int $userId
     */
    public function filterByUser(Omeka_Db_Select $select, $userId, $userField)
    {
        $userId = (int) $userId;

        if ($userId) {
            if (empty($userField)) {
                $userField = 'creator_id';
            }

            $select->where("neatline_time_timelines.$userField = ?", $userId);
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
