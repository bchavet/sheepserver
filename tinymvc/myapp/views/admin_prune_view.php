<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<div class="navigation">
<ul id="submenu">
<li class="first">Admin</li>
<li><a href="/admin/newsheep">New Sheep</a></li>
<li><a href="/admin/newedge">New Edge</a></li>
<li><a href="/admin/prune">Prune</a></li>
</ul>
</div>

<h2>Prune</h2>
<p>The following sheep and edges connecting them will be pruned</p>

<table>
<?php
foreach ($prunelist as $prune) {
     echo '<tr>';
     if (!$confirm) {
         echo '<td><a href="/sheep/status?sheep=' . $prune['sheep_id'] . '">';
         if ($prune['first'] != $prune['last']) {

             if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $prune['flock_id'] . DS . $prune['first'] . DS . '0.thumbnail.jpg')) {
                 echo '<img src="/gen/' . $prune['flock_id'] . '/' . $prune['first'] . '/0.thumbnail.jpg" alt="" class="thumbnail edgefirst" />';
             } else {
                 echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail edgefirst" />';
             }
             if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $prune['flock_id'] . DS . $prune['last'] . DS . '0.thumbnail.jpg')) {
                 echo '<img src="/gen/' . $prune['flock_id'] . '/' . $prune['last'] . '/0.thumbnail.jpg" alt="" class="thumbnail edgelast" />';
             } else {
                 echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail edgelast" />';
             }

         } else {

             if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $prune['flock_id'] . DS . $prune['sheep_id'] . DS . '0.thumbnail.jpg')) {
                 echo '<img src="/gen/' . $prune['flock_id'] . '/' . $prune['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
             } else {
                 echo '<img src="/images/anon-icon.jpg" alt="" class="thumbnail" />';
             }

         }
         echo '</a></td>';
     }
     echo '</tr>';
}
?>
</table>

<div><a href="/admin/prune?numdays=<?= $numdays ?>&amp;confirm=yes">OK</a></div>

</body>

</html>
