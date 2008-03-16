<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="loops complete">
Complete Loops (<?= count($completeSheep) ?>)<br />
<?php
foreach ($completeSheep as $s) {
     echo '<a href="/status?sheep=' . $s['sheep_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['sheep_id'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
     }
     echo '</a>';
 }
?>
</div>

<div class="edges complete">
Complete Edges (<?= count($completeEdges) ?>)<br />
<?php
foreach ($completeEdges as $s) {
     echo '<a href="/status?sheep=' . $s['sheep_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['first'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
     }
     
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['last'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
     }
     echo '</a>';
 }
?>
</div>

<div class="loops incomplete">
Incomplete Loops (<?= count($busySheep) ?>)<br />
<?php
foreach ($busySheep as $s) {
     echo '<a href="/status?sheep=' . $s['sheep_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['sheep_id'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
     }
     echo '</a>';
 }
?>
</div>

<div class="edges incomplete">
Incomplete Edges (<?= count($busyEdges) ?>)<br />
<?php
foreach ($busyEdges as $s) {
     echo '<a href="/status?sheep=' . $s['sheep_id'] . '">';
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['first'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
     }
     
     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $s['flock_id'] . DS . $s['last'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $s['flock_id'] . '/' . $s['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
     }
     echo '</a>';
 }
?>
</div>

</body>

</html>
