<div class="navigation">
<ul id="mainmenu">
  <li class="first"><a href="/flock/loops">Loops</a></li>
  <li><a href="/flock/edges">Edges</a></li>
  <li><a href="/flock/queue">Queue</a></li>
  <li><a href="/flock/credit">Credit</a></li>
  <li><a href="/flock/stats">Statistics</a></li>
  <li><a href="/admin">Admin</a></li>
</ul>
<div class="clr"></div>
</div>

<?php if (isset($sheep)): ?>
<div class="navigation">
<ul id="submenu">
  <li class="first"><a href="/sheep/status?sheep=<?= $sheep ?>">Status</a></li>
  <li><a href="/sheep/frames?sheep=<?= $sheep ?>">Frames</a></li>
  <li><a href="/sheep/motion?sheep=<?= $sheep ?>">Motion</a></li>
  <li><a href="/sheep/lineage?sheep=<?= $sheep ?>">Lineage</a></li>
  <li><a href="/sheep/genome?sheep=<?= $sheep ?>">Genome</a></li>
  <li><a href="/sheep/credit?sheep=<?= $sheep ?>">Credit</a></li>
  <li><a href="/sheep/stats?sheep=<?= $sheep ?>">Stats</a></li>
  <?php if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep . DS . 'sheep.mpg')): ?>
  <li><a href="/gen/<?= $flock ?>/<?= $sheep ?>/sheep.mpg">Download</a></li>
  <?php endif; ?>
</ul>
<div class="clr"></div>
</div>
<?php endif; ?>