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
  <li><a href="/sheep/family?sheep=<?= $sheep ?>">Family</a></li>
  <li><a href="/sheep/genome?sheep=<?= $sheep ?>">Genome</a></li>
  <li><a href="/sheep/credit?sheep=<?= $sheep ?>">Credit</a></li>
  <?php if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep . DS . 'sheep.mpg')): ?>
  <li><a href="/gen/<?= $flock ?>/<?= $sheep ?>/sheep.mpg">Download</a></li>
  <?php endif; ?>
</ul>
<?php if (!empty($_SESSION['logged_in'])): ?>
<ul class="admin">
  <li class="first"><a href="/admin/delete?sheep=<?= $sheep ?>">Delete</a></li>
  <li><a href="/admin/newedge?sheep=<?= $sheep ?>&amp;type=connect">Connect</a></li>
  <li><a href="/admin/newsheep?type=mutate&amp;parent0=<?= $sheep ?>">Mutate</a></li>
</ul>
<?php endif; ?>
<div class="clr"></div>
</div>
<?php endif; ?>

<?php if (isset($sheep) && !empty($author_credit)): ?>
<div>
Original Sheep: <a href="<?= $author_credit ?>"><?= $author_credit ?></a>
</div>
<?php endif; ?>

