<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

Complete Edges (<?= count($edges) ?>)<br />
<?php
foreach ($edges as $edge) {
     echo '<a href="/sheep?sheep=' . $edge['sheep_id'] . '">';
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $edge['flock_id'] . DS . $edge['first'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $edge['flock_id'] . '/' . $edge['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
        }
     
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $edge['flock_id'] . DS . $edge['last'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $edge['flock_id'] . '/' . $edge['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
        }
     echo '</a>';
 }
?>

</body>

</html>
