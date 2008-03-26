<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<p>Queue (<?= count($queue) ?>)</p>
<?php
foreach ($queue as $sheep) {
    echo '<a href="/sheep/frames?sheep=' . $sheep['sheep_id'] . '">';
    echo '<div>';
    if ($sheep['first'] == $sheep['last']) {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['sheep_id'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
        } else {
            echo '<img src="/images/busy-icon.jpg" alt="" class="thumbnail" />';
        }

    } else {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['first'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
        }
     
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['last'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
        }
    }
    echo '</a>';
    echo (int)($sheep['complete'] / $nframes * 100) . '%';
    echo '</div>';

}
?>

<p>Post-queue (<?= count($postqueue); ?>)</p>
<?php
foreach ($postqueue as $sheep) {
    echo '<a href="/sheep/frames?sheep=' . $sheep['sheep_id'] . '">';
    if ($sheep['first'] == $sheep['last']) {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['sheep_id'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
        } else {
            echo '<img src="/images/busy-icon.jpg" alt="" class="thumbnail" />';
        }

    } else {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['first'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
        }
     
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['last'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
        }
    }
    echo '</a>';
}
?>

<p>Assigned Frames (<?= count($assigned) ?>)</p>
<table>
<tr><th>Sheep</th><th>Frame</th><th>IP Address</th><th>UID</th><th>Nick</th><th>Time</th></tr>
<?php
foreach ($assigned as $frame) {
    echo '<tr>';

    echo '<td class="icon"><a href="/sheep?sheep=' . $frame['sheep_id'] . '">';

    if ($frame['first'] == $frame['last']) {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['sheep_id'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
        } else {
            echo '<img src="/images/busy-icon.jpg" alt="" class="thumbnail" />';
        }

    } else {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['first'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
        }
     
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $frame['flock_id'] . DS . $frame['last'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $frame['flock_id'] . '/' . $frame['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
        }
        
    }

    echo '</a></td>';

    echo '<td><a href="/frame?sheep=' . $frame['sheep_id'] . '&amp;frame=' . $frame['frame_id'] . '">' . $frame['frame_id'] . '</a></td>';
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
