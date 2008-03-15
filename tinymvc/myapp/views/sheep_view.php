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
     echo '<a href="/status?sheep=' . $frame['sheep_id'] . '&amp;frame=' . $frame['frame_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['sheep_id'] . DS . $frame['frame_id'] . '.thumbnail.jpg')) {
         echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['sheep_id'] . '/' . $frame['frame_id'] . '.thumbnail.jpg" alt="" class="thumbnail" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail" />';
     }
     echo '</a>';
 }
?>

<p>
<?= $completed ?> frames complete (<?php echo (int)($completed / ($completed + $remaining) * 100) . '%'; ?>).
<?php if ($remaining > 0) { echo $remaining . ' frames remaining.'; } ?>
</p>

</body>

</html>
