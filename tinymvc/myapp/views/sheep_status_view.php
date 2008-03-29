<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<div>
<?php
if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheepstatus['flock_id'] . DS . $sheepstatus['sheep_id'] . DS . '0.jpg')) {
    echo '<img src="/gen/' . $sheepstatus['flock_id'] . '/' . $sheepstatus['sheep_id'] . '/0.jpg" alt="" />';
} else if ($sheepstatus['state'] == 'incomplete') {
    echo '<img src="/images/busy-icon.jpg" alt="assigned" />';
} else {
    echo '<img src="/images/anon-icon.jpg" alt="ready" />';
}
?>
</div>

<table>
<tr>
  <th>Sheep</th>
  <td><?= $sheepstatus['sheep_id'] ?></td>
</tr>
<tr>
  <th>Status</th>
  <td><?= $sheepstatus['state'] ?></td>
</tr>

<?php if ($sheepstatus['state'] == 'done'): ?>
<tr>
  <th>Completed</th>
  <td><?= date('F j, Y, g:i:s a', $sheepstatus['time_done']) ?></td>
</tr>
<?php endif; ?>

</table>

</body>

</html>
