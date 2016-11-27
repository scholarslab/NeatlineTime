<?php
if (version_compare($oldVersion, '2.1.0', '<')) {
    throw new Omeka_Plugin_Installer_Exception(__('Please upgrade first to v2.1.0 (commit e0fbe35), the last official release.'));
}
if (version_compare($newVersion, '2.2', '<')) {
    throw new Omeka_Plugin_Installer_Exception(__('The process should be upgraded version by version from 2.1.1 to 2.2.'));
}
if (version_compare(OMEKA_VERSION, '2.2.2', '<')) {
    throw new Omeka_Plugin_Installer_Exception(__('Please upgrade first to Omeka to v2.2.2 and edit and save each of the queries manually.'));
}

// Manage the upgrade from 2.1.0.
if (version_compare($oldVersion, '2.1.0', '=') || version_compare($oldVersion, '2.1.1', '=')) {
    $sql = "
    ALTER TABLE `{$db->NeatlineTime_Timeline}`
    MODIFY COLUMN `center_date` date NULL DEFAULT '2000-01-01',
    MODIFY COLUMN `added` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
    CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    CHANGE COLUMN `creator_id` `owner_id` INT(10) UNSIGNED NOT NULL,
    ADD `parameters` TEXT COLLATE utf8_unicode_ci NOT NULL AFTER `center_date`,
    ADD KEY `public` (`public`),
    ADD KEY `featured` (`featured`),
    ADD KEY `owner_id` (`owner_id`)
    ";
    $db->query($sql);

    // Copy all the "center_date" values inside the parameters.
    $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
    SET `center_date` = NULL
    WHERE `center_date` IS NULL OR `center_date` = '0000-00-00';";
    $db->query($sql);

    $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
    SET `parameters` = CONCAT('{\"center_date\":\"', `center_date`, '\"}')
    WHERE `center_date` IS NOT NULL AND `center_date` != '0000-00-00';";
    $db->query($sql);

    $sql = "
    ALTER TABLE `{$db->NeatlineTime_Timeline}`
    DROP `center_date`
    ";
    $db->query($sql);

    set_option('neatline_time_library', $this->_options['neatline_time_library']);

    // Set default options inside timelines.
    $options = unserialize(get_option('neatlinetime'));
    $options = array_merge($this->_options['neatline_time_defaults'], $options);
    set_option('neatline_time_defaults', json_encode($options));

    delete_option('neatline_time_render_year');
    delete_option('neatlinetime');

    // Update all timelines.
    $timelines = get_records('NeatlineTime_Timeline', array(), 0);
    foreach ($timelines as $timeline) {
        $parameters = $timeline->parameters ?: array();
        if (!empty($parameters)) {
            $parameters = json_decode($parameters, true);
        }
        $parameters = array_merge($options, $parameters);
        unset($parameters['csrf_token']);
        $parameters = json_encode($parameters);
        // Direct update of the table.
        $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
        SET `parameters` = {$db->quote($parameters)}
        WHERE `id` = $timeline->id;";
        $db->query($sql);
    }
}

if (version_compare($oldVersion, '2.2.1', '<')) {
    // Because null is forbidden now, all null values should be replaced before.
    $empty = serialize(array());
    $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
    SET `query` = '$empty'
    WHERE `query` IS NULL;";
    $db->query($sql);

    $sql = "
    ALTER TABLE `{$db->NeatlineTime_Timeline}`
    CHANGE `query` `query` TEXT COLLATE utf8_unicode_ci NOT NULL AFTER `parameters`
    ";
    $db->query($sql);

    // Convert serialized queries into json.
    $timelines = get_records('NeatlineTime_Timeline', array(), 0);

    foreach ($timelines as $timeline) {
        // Queries may have been converted before during the upgrade process
        // or in case of a bug.
        $query = @unserialize($timeline->query);
        if ($query === false) {
            $query = json_decode($timeline->query, true) ?: array();
        }
        unset($query['csrf_token']);
        $timeline->setQuery($query);
        $timeline->save();
    }
}

if (version_compare($oldVersion, '2.2.2', '<')) {
    // Replace null from title and description by an empty string.
    $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
    SET `title` = ''
    WHERE `title` IS NULL;";
    $db->query($sql);
    $sql = "UPDATE `{$db->NeatlineTime_Timeline}`
    SET `description` = ''
    WHERE `description` IS NULL;";
    $db->query($sql);

    $sql = "
    ALTER TABLE `{$db->NeatlineTime_Timeline}`
    CHANGE `title` `title` TINYTEXT COLLATE utf8_unicode_ci NOT NULL,
    CHANGE `description` `description` TEXT COLLATE utf8_unicode_ci NOT NULL,
    CHANGE `owner_id` `owner_id` INT(10) UNSIGNED NOT NULL DEFAULT '0'
    ";
    $db->query($sql);
}
