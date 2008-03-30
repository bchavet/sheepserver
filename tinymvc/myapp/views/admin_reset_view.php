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

<h2>Reset</h2>

<?php if (!$confirm): ?>

<p>Are you sure you want to reset the server and start a new flock?</p>
<div><a href="/admin/reset?confirm=yes">Yes</a></div>

<?php else: ?>

The server has been reset and a new flock has been created.

<?php endif; ?>

</body>

</html>
