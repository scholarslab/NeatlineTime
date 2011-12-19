<?php
/**
 * The show view for the Timelines administrative panel.
 *
 * PHP 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary', 
              'title' => timeline('title')
              );
head($head);
?>
<h1><?php echo timeline('title'); ?> <span class="view-public-page">[ <a href="<?php echo html_escape(public_uri('neatline-time/timelines/show/'.timeline('id'))); ?>">View Public Page</a> ]</h1>

<div id="primary">
    <?php echo neatlinetime_display_timeline(); ?>
    <?php echo timeline('description'); ?>
</div>
<?php foot(); ?>
