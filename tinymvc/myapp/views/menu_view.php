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

<?php if (isset($_GET['sheep'])): ?>
<?php $sheep_id = (int)$_GET['sheep']; ?>
<div class="navigation">
<ul id="submenu">
  <li class="first"><a href="/sheep/status?sheep=<?= $sheep_id ?>">Status</a></li>
  <li><a href="/sheep/frames?sheep=<?= $sheep_id ?>">Frames</a></li>
  <li><a href="/sheep/motion?sheep=<?= $sheep_id ?>">Motion</a></li>
  <li><a href="/sheep/lineage?sheep=<?= $sheep_id ?>">Lineage</a></li>
  <li><a href="/sheep/genome?sheep=<?= $sheep_id ?>">Genome</a></li>
  <li><a href="/sheep/credit?sheep=<?= $sheep_id ?>">Credit</a></li>
  <li><a href="/sheep/stats?sheep=<?= $sheep_id ?>">Stats</a></li>
</ul>
<div class="clr"></div>
</div>
<?php endif; ?>