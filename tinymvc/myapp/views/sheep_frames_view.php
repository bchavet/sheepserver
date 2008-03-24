<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<?php
if ($first != $last) {
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $first . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $flock . '/' . $first . '/0.thumbnail.jpg" alt="" class="thumbnail edgefirst" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" alt="ready" class="thumbnail edgefirst" />';
    }

    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $last . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $flock . '/' . $last . '/0.thumbnail.jpg" alt="" class="thumbnail edgelast" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" alt="ready" class="thumbnail edgelast" />';
    }
}
?>

<p>
<?= $completed ?> frames complete (<?php echo (int)($completed / ($completed + $remaining) * 100) . '%'; ?>).
<?php if ($remaining > 0) { echo $remaining . ' frames remaining.'; } ?>
</p>

<?php
foreach ($frames as $frame) {
     echo '<a href="/frame?sheep=' . $frame['sheep_id'] . '&amp;frame=' . $frame['frame_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['sheep_id'] . DS . $frame['frame_id'] . '.thumbnail.jpg')) {
         echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['sheep_id'] . '/' . $frame['frame_id'] . '.thumbnail.jpg" alt="" class="thumbnail" />';
     } else if ($frame['state'] == 'assigned') {
         echo '<img src="/images/busy-icon.jpg" alt="assigned" class="thumbnail" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" alt="ready" class="thumbnail" />';
     }
     echo '</a>';
 }
?>

<?php if (!empty($_SESSION['logged_in'])): ?>
<div>
<a href="/admin/delete?sheep=<?= $sheep ?>">Delete</a>
</div>
<?php endif; ?>

</body>

</html>
