<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

Queue (<?= count($queue) ?>)

<?php
foreach ($queue as $sheep) {
    echo '<div>';
    echo '<a href="/sheep/frames?id=' . $sheep['sheep_id'] . '">';
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
    echo '</div>';

}
?>

Post-queue (<?= count($postqueue); ?>)

<?php
foreach ($postqueue as $sheep) {
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
    echo '</div>';
}
?>

</body>

</html>
