<?php
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Timelines | Add a Timeline'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
<?php echo $form; ?>
</div>
<?php foot(); ?>