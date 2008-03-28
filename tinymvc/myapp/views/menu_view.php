<div class="navigation">
<ul>
  <li class="first">Flock <?= $flock ?></li>
  <li><a href="/flock/loops">Loops</a></li>
  <li><a href="/flock/edges">Edges</a></li>
  <li><a href="/flock/queue">Queue</a></li>
  <li><a href="/flock/credit">Credit</a></li>
  <li><a href="/flock/stats">Statistics</a></li>
  <?php if (empty($_SESSION['logged_in'])): ?>
  <li><a href="/admin">Login</a></li>
  <?php else: ?>
  <li><a href="/admin">Admin</a></li>
  <li><a href="/admin/logout">Logout</a></li>
  <?php endif; ?>
</ul>
<div class="clr"></div>
</div>

<?php if (isset($sheep)): ?>
<div class="navigation">
<ul>
  <li class="first">Sheep <?= $sheep ?></li>
  <li><a href="/sheep/status?sheep=<?= $sheep ?>">Status</a></li>
  <li><a href="/sheep/frames?sheep=<?= $sheep ?>">Frames</a></li>
  <li><a href="/sheep/motion?sheep=<?= $sheep ?>">Motion</a></li>
  <li><a href="/sheep/genome?sheep=<?= $sheep ?>">Genome</a></li>
  <li><a href="/sheep/credit?sheep=<?= $sheep ?>">Credit</a></li>
  <?php if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep . DS . 'sheep.mpg')): ?>
  <li><a href="/gen/<?= $flock ?>/<?= $sheep ?>/sheep.mpg">Download</a></li>
  <?php endif; ?>
</ul>
<div class="clr"></div>
</div>
<?php endif; ?>

<?php if (isset($frame)): ?>
<div class="navigation">
<ul>
  <li class="first">Frame <?= $frame ?></li>
  <li><a href="/frame/status?sheep=<?= $sheep ?>&amp;frame=<?= $frame ?>">Status</a></li>
  <li><a href="/frame/genome?sheep=<?= $sheep ?>&amp;frame=<?= $frame ?>">Genome</a></li>
</ul>
<div class="clr"></div>
</div>
<?php endif; ?>