<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
foreach ($frames as $frame) {
     echo '<a href="/status/frame/' . $frame['sheep'] . '/' . $frame['frame'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['generation'] . DS . $frame['sheep'] . DS . $frame['frame'] . '.thumbnail.jpg')) {
         echo '<img src="/gen/' . $frame['generation'] . '/' . $frame['sheep'] . '/' . $frame['frame'] . '.thumbnail.jpg" alt="" class="thumbnail" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
     }         
     echo '</a>';
 }
?>

</body>

</html>
