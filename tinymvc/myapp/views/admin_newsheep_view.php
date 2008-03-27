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
<li><a href="/admin/newsheep">New Sheep</a></li>
<li><a href="/admin/newedge">New Edge</a></li>
<li><a href="/admin/prune">Prune</a></li>
</ul>
</div>

<h2>New Sheep</h2>

<?php if (isset($spex)): ?>

<pre>
<?= htmlspecialchars($spex) ?>
</pre>

<?php else: ?>

<div class="admin">
<a href="/admin/newsheep?type=random">Random</a>
</div>

<div class="admin">
<form name="upload" action="/admin/newsheep" method="post" enctype="multipart/form-data">
<input type="hidden" name="type" value="upload" />
<input type="file" name="genome" />
<input type="submit" value="Upload" />
</form>
</div>

<?php endif; ?>

</body>

</html>
