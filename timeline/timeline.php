<?php $timeline = new Timeline(); ?>
<html>
  <head>
    <style type="text/css">
    body {
        font-family: "georgia", "times", "times new roman", serif;
        color: #222;
        font-size: 12px;
    }
    </style>
    <script src="http://simile.mit.edu/timeline/api/timeline-api.js" type="text/javascript"></script>
    <script><?php echo $timeline->javascript; ?></script>
  </head>
  <body onload="onLoad();" onresize="onResize();">
  <div id="my-timeline" style="height: 100%; border: 1px solid #aaa"></div>
  </body>
</html>