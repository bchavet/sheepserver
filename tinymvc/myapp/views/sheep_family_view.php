<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<?php if ($parents['parent0'] !== null || $parents['parent1'] !== null): ?>
<div class="parents">
Parents<br />
<?php 
if ($parents['parent0'] !== null) {
    echo '<a href="/sheep/family?sheep=' . $parents['parent0'] . '">';
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $parents['parent0'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $flock . '/' . $parents['parent0'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail" />';
    }
    echo '</a>';
}

if ($parents['parent1'] !== null) {
    echo '<a href="/sheep/family?sheep=' . $parents['parent1'] . '">';
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $parents['parent1'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $flock . '/' . $parents['parent1'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail" />';
    }
    echo '</a>';
}
?>
</div>
<?php endif; ?>

<div>
Myself<br />
<?php
if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep['sheep_id'] . DS . '0.thumbnail.jpg')) {
    echo '<img src="/gen/' . $flock . '/' . $sheep['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail self" />';
} else {
    echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail self" />';
}
?>
</div>

<?php if (count($children) > 0): ?>
<div class="children">
Children<br />
<?php
foreach ($children as $child) {
    echo '<a href="/sheep/family?sheep=' . $child['sheep_id'] . '">';
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $child['sheep_id'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $flock . '/' . $child['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail" />';
    }
    echo '</a>';
}
?>
</div>
<?php endif; ?>

</body>

</html>
