<?php
$list = '';
foreach ($sheeplist as $sheep) {
    if ($sheep['first'] == $sheep['last']) {
        $list .= '<option value="' . $sheep['sheep_id'] . '">' . $sheep['sheep_id'] . '</option>';
    }
}
?>
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

<h2>New Edge</h2>

<div class="admin">
<a href="/admin/newedge?type=random">Random</a>
</div>

<div class="admin">
<form name="edge" action="/admin/newedge" method="post">
<input type="hidden" name="type" value="edge" />
<select name="first"><?= $list ?></select>
<select name="last"><?= $list ?></select>
<input type="submit" value="Edge" />
</form>
</div>

<div class="admin warning">
<form name="complete" action="/admin/newedge" method="post">
<input type="hidden" name="type" value="connect" />
<select name="sheep"><?= $list ?></select>
<input type="submit" value="Connect" />
</form>
</div>

<?php if (isset($spex)): ?>
<pre>
<?= htmlspecialchars($spex) ?>
</pre>
<?php endif; ?>

</body>

</html>
