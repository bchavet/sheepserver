<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<h2>Reset</h2>

<?php if (!$confirm): ?>

<p>Are you sure you want to reset the server and start a new flock?</p>
<div>
<a href="/admin/reset?confirm=yes">Yes</a>
<a href="/admin">No</a>
</div>

<?php else: ?>

The server has been reset and a new flock has been created.

<?php endif; ?>

</body>

</html>
