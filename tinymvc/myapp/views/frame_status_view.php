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
if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['sheep_id'] . DS . $frame['frame_id'] . '.thumbnail.jpg')) {
    echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['sheep_id'] . '/' . $frame['frame_id'] . '.jpg" alt="" />';
} else if ($frame['state'] == 'assigned') {
    echo '<img src="/images/busy-icon.jpg" alt="assigned" />';
} else {
    echo '<img src="/images/anon-icon.jpg" alt="ready" />';
}
?>

<table class="frame">
<tr>
  <th>Sheep</th>
  <td><?= $frame['sheep_id'] ?></td>
</tr>
<tr>
  <th>Frame</th>
  <td><?= $frame['frame_id'] ?></td>
</tr>
<tr>
  <th>Status</th>
  <td><?= $frame['state'] ?></td>
</tr>

<?php if ($frame['state'] != 'ready'): ?>
<tr>
  <th>Nick</th>
  <td><?= $frame['nick'] ?></td>
</tr>
<tr>
  <th>IP Address</th>
  <td><?= $frame['ip'] ?></td>
</tr>
<tr>
  <th>UID</th>
  <td><?= $frame['uid'] ?></td>
</tr>
<tr>
  <th>Assigned</th>
  <td><?= date('F j, Y, g:i:s a', $frame['start_time']) ?></td>
</tr>
<?php endif; ?>

<?php if ($frame['state'] == 'done'): ?>
<tr>
  <th>Completed</th>
  <td><?= date('F j, Y, g:i:s a', $frame['end_time']) ?></td>
</tr>
<tr>
  <th>Duration</th>
  <td><?= ($frame['end_time'] - $frame['start_time']) ?>s</td>
</tr>
<?php endif; ?>

</table>

</body>

</html>
