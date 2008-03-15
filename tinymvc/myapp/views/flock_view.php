<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
foreach ($sheep as $s) {
     echo '<a href="/status?sheep=' . $s['sheep_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['sheep_id'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
     }
     echo '</a>';
 }
?>

</body>

</html>
