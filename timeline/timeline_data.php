<?php
header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
$filename = PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Timeline' . DIRECTORY_SEPARATOR . 'data.xml';
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
echo $contents;
fclose($handle);
?>