<?php

class Table_NeatlineTime_Timeline extends Omeka_Db_Table
{
    /**
     * @param Omeka_Db_Select
     * @param array
     * @return void
     */
    public function applySearchFilters($select, $params)
    {
        $alias = $this->getTableAlias();
        $boolean = new Omeka_Filter_Boolean;
        $genericParams = array();
        foreach ($params as $key => $value) {
            if ($value === null || (is_string($value) && trim($value) == '')) {
                continue;
            }
            switch ($key) {
                case 'user':
                case 'owner':
                case 'user_id':
                case 'owner_id':
                    $this->filterByUser($select, $value, 'owner_id');
                    break;
                case 'public':
                    $this->filterByPublic($select, $boolean->filter($value));
                    break;
                case 'featured':
                    $this->filterByFeatured($select, $boolean->filter($value));
                    break;
                case 'added_since':
                    $this->filterBySince($select, $value, 'added');
                    break;
                case 'modified_since':
                    $this->filterBySince($select, $value, 'modified');
                    break;
                default:
                    $genericParams[$key] = $value;
            }
        }

        if (!empty($genericParams)) {
            parent::applySearchFilters($select, $genericParams);
        }

        $select->group($alias . '.id');
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
        $alias = $this->getTableAlias();
        return array($alias . '.id', $alias . '.title');
    }
}
