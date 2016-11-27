<?php
// Earlier than version 1.1.
if (version_compare($oldVersion, '1.1', '<')) {
    if (!get_option('neatlinetime')) {
        $this->_setDefaultOptions();
    }
}

if (version_compare($oldVersion, '2.0.2', '<') && version_compare($oldVersion, '2.0', '>') ) {
    $timelines = get_records('NeatlineTime_Timeline', array(), 0);
    if ($timelines) {
        foreach ($timelines as $timeline) {
            $query = unserialize($timeline->query);
            while (!is_array($query)) {
                $query = unserialize($query);
            }
            $timeline->query = serialize($query);
            $timeline->save();
        }
    }
}

if (version_compare($oldVersion, '2.1', '<')) {
    $rows = $db->query(
        "show columns from {$db->prefix}neatline_time_timelines where field='center_date';"
    );

    if ($rows->rowCount() === 0) {
        $sqlNeatlineTimeline = "ALTER TABLE  `{$db->prefix}neatline_time_timelines`
        ADD COLUMN `center_date` date NOT NULL default '0000-00-00'";
        $db->query($sqlNeatlineTimeline);
    }
}

if (version_compare($oldVersion, '2.1.1', '<')) {
    $sql = "
    ALTER TABLE  `{$db->prefix}neatline_time_timelines`
    MODIFY COLUMN `center_date` date NULL,
    MODIFY COLUMN `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ";
    $db->query($sql);
}

if (version_compare($oldVersion, '2.1.2', '<')) {
    $sql = "
    ALTER TABLE  `{$db->prefix}neatline_time_timelines`
    MODIFY COLUMN `added` timestamp NOT NULL default '2000-01-01 00:00:00'
    ";
    $db->query($sql);
}

if (version_compare($oldVersion, '2.1.3', '<')) {
    set_option('neatline_time_library', $this->_options['neatline_time_library']);
}

if (version_compare($oldVersion, '2.1.4', '<')) {
    $sql = "
    ALTER TABLE  `{$db->prefix}neatline_time_timelines`
    CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    CHANGE COLUMN `creator_id` `owner_id` INT(10) UNSIGNED NOT NULL
    ";
    $db->query($sql);
}

if (version_compare($oldVersion, '2.1.5', '<')) {
    set_option('neatline_time_render_year', $this->_options['neatline_time_render_year']);
}

if (version_compare($oldVersion, '2.1.6', '<')) {
    $sql = "
    ALTER TABLE `{$db->NeatlineTime_Timeline}`
    ADD `parameters` TEXT COLLATE utf8_unicode_ci NOT NULL AFTER `center_date`,
    ADD KEY `public` (`public`),
    ADD KEY `featured` (`featured`),
    ADD KEY `owner_id` (`owner_id`)
    ";
    $db->query($sql);
}

if (version_compare($oldVersion, '2.1.7', '<')) {
    // Copy all the "center_date" values inside the parameters.
    $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
    SET `parameters` = CONCAT('{\"center_date\":\"', `center_date`, '\"}')
    WHERE `center_date` IS NOT NULL AND `center_date` != '0000-00-00';";
    $db->query($sql);

    $sql = "
    ALTER TABLE `{$db->NeatlineTime_Timeline}`
    DROP `center_date`
    ";
    $db->query($sql);
}

if (version_compare($oldVersion, '2.1.8', '<')) {
    set_option('neatline_time_defaults', json_encode($this->_options['neatline_time_defaults']));
}
