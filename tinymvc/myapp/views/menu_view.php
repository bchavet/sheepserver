<div class="navigation">
<ul>
  <li class="first">Flock <?= $flock ?></li>
  <li><a href="/flock/loops">Loops</a></li>
  <li><a href="/flock/edges">Edges</a></li>
  <li><a href="/flock/archive">Archive</a></li>
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

<?php if (isset($sheep) && is_array($sheep)): ?>
<div class="navigation">
<ul>
  <li class="first">Sheep <?= $sheep['sheep_id'] ?></li>
  <li><a href="/sheep?sheep=<?= $sheep['sheep_id'] ?>">View</a></li>
  <?php if ($sheep['state'] != 'archive'): ?>
  <li><a href="/sheep/frames?sheep=<?= $sheep['sheep_id'] ?>">Frames</a></li>
  <?php endif; ?>
  <?php if ($sheep['first'] == $sheep['last']): ?>
  <li><a href="/sheep/family?sheep=<?= $sheep['sheep_id'] ?>">Family</a></li>
  <?php endif; ?>
  <li><a href="/sheep/genome?sheep=<?= $sheep['sheep_id'] ?>">Genome</a></li>
  <?php if ($sheep['state'] != 'archive'): ?>
  <li><a href="/sheep/credit?sheep=<?= $sheep['sheep_id'] ?>">Credit</a></li>
  <?php endif; ?>
  <?php if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep['sheep_id'] . DS . 'sheep.mpg')): ?>
  <li><a href="/gen/<?= $flock ?>/<?= $sheep['sheep_id'] ?>/sheep.mpg">Download</a></li>
  <?php endif; ?>
</ul>
<?php if (!empty($_SESSION['logged_in']) && $sheep['state'] != 'archive'): ?>
<ul class="admin">
  <li class="first"><a href="/sheep?sheep=<?= $sheep['sheep_id'] ?>&amp;action=archive">Archive</a></li>
  <li><a href="/sheep?sheep=<?= $sheep['sheep_id'] ?>&amp;action=delete">Delete</a></li>
</ul>
<?php endif; ?>
<?php if (!empty($_SESSION['logged_in']) && $sheep['state'] == 'archive'): ?>
<ul class="admin">
  <li class="first"><a href="/sheep?sheep=<?= $sheep['sheep_id'] ?>&amp;action=unarchive">Unarchive</a></li>
</ul>
<?php endif; ?>
<div class="clr"></div>
</div>

<?php if (!empty($sheep['credit_link'])): ?>
<div>
Original Sheep: <a href="<?= $sheep['credit_link'] ?>"><?= $sheep['credit_link'] ?></a>
</div>
<?php endif; ?>

<?php endif; ?>
