<?php
/**
 * The public browse view for Timelines.
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
 
$head = array('bodyclass' => 'timelines primary',
              'title' => html_escape('Neatline Time | Timelines'));
head($head);
?>

<div id="primary" class="timelines">
    <h1>Browse Timelines</h1>
    <?php if (has_timelines_for_loop()) : while ( loop_timelines() ) :?>
    <div class="timeline">
        <h2><?php echo link_to_timeline(); ?></h2>
        <?php echo timeline('Description'); ?>
    </div>
    <?php endwhile; else: ?>
        <p>You have no timelines.</p>
    <?php endif; ?>
</div>
<?php foot(); ?>
