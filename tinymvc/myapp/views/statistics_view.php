<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<table>
<?php
 foreach ($count as $client => $frames) {
     echo '<tr><td>' . $client . '</td><td>' . $frames . '</td></tr>';
 }
?>
</table>

<table class="statistics">
<tr><th>Sheep</th><th>IP Address</th><th>UID</th><th>Nick</th><th>Time</th></tr>
<?php
foreach ($assigned as $frame) {
    echo '<tr>';

    echo '<td><a href="/status?sheep=' . $frame['sheep_id'] . '">';
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['sheep_id'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
    } else {
        echo '<img src="/images/busy-icon.jpg" alt="" class="thumbnail" />';
    }
    echo '</a></td>';

    echo '<td>' . $frame['ip'] . '</td>';
    echo '<td>' . $frame['uid'] . '</td>';
    echo '<td>' . $frame['nick'] . '</td>';
    echo '<td>' . date('F j, Y, g:i:s a', $frame['start_time']) . '</td>';

    echo '</tr>';
}
?>
</table>

</body>

</html>
