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

<table>

<tr>
  <th>Frame</th>
  <th>State</th>
  <th>Nick</th>
  <th>Start Time</th>
  <th>End Time</th>
  <th>Duration</th>
</tr>

<?php
foreach ($frames as $frame) {
    echo '<tr><td>';
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['sheep_id'] . DS . $frame['frame_id'] . '.thumbnail.jpg')) {
        echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['sheep_id'] . '/' . $frame['frame_id'] . '.thumbnail.jpg" alt="" class="thumbnail" />';
    } else if ($frame['state'] == 'assigned') {
        echo '<img src="/images/busy-icon.jpg" alt="assigned" class="thumbnail" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" alt="ready" class="thumbnail" />';
    }
    echo '<td>' . $frame['state'] . '</td>';
    if ($frame['state'] != 'ready') {
        echo '<td>' . $frame['nick'] . '</td>';
        echo '<td>' . date('F j, Y, g:i:s a', $frame['start_time']) . '</td>';
    }
    if ($frame['state'] == 'done') {
        echo '<td>' . date('F j, Y, g:i:s a', $frame['end_time']) . '</td>';
        echo '<td>' . ($frame['end_time'] - $frame['start_time']) . 's</td>';
    }
    echo '</td></tr>';
  }
?>

</table>

</body>

</html>
