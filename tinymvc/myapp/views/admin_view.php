<?php
$list = '';
$edgelist = '';
foreach ($sheeplist as $sheep) {
    if ($sheep['first'] == $sheep['last']) {
        $list .= '<option value="' . $sheep['sheep_id'] . '">' . $sheep['sheep_id'] . '</option>';
        if ($sheep['state'] != 'archive') {
            $edgelist .= '<option value="' . $sheep['sheep_id'] . '">' . $sheep['sheep_id'] . '</option>';
        }
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

<h2>New Sheep</h2>

<div class="admin">
<a href="/admin/newsheep?type=random">Random</a>
</div>

<div class="admin">
<a href="/admin/newsheep?type=symmetry">Symmetry/Singularity</a>
</div>

<div class="admin">
<form name="mutate" action="/admin/newsheep" method="post">
<input type="hidden" name="type" value="mutate" />
<select name="parent0"><?= $list ?></select>
<input type="submit" value="Mutate" />
</form>
</div>

<div class="admin">
<form name="mate" action="/admin/newsheep" method="post">
<input type="hidden" name="type" value="mate" />
<select name="parent0"><?= $list ?></select>
<select name="parent1"><?= $list ?></select>
<input type="submit" value="Mate" />
</form>
</div>

<div class="admin">
<form name="upload" action="/admin/newsheep" method="post" enctype="multipart/form-data">
<input type="hidden" name="type" value="upload" />
File: <input type="file" name="genome" />
<br />
Credit Link: <input type="text" name="creditlink" />
<input type="submit" value="Upload" />
</form>
</div>


<h2>New Edge</h2>

<div class="admin">
<a href="/admin/newedge?type=random">Random</a>
</div>

<div class="admin">
<form name="edge" action="/admin/newedge" method="post">
<input type="hidden" name="type" value="edge" />
<select name="first"><?= $edgelist ?></select>
<select name="last"><?= $edgelist ?></select>
<input type="submit" value="Edge" />
</form>
</div>

<div class="admin">
<form name="complete" action="/admin/newedge" method="post">
<input type="hidden" name="type" value="connect" />
<select name="sheep"><?= $edgelist ?></select>
<input type="submit" value="Connect" />
</form>
</div>

<h2>Maintenance</h2>

<div class="admin">
<a href="/admin/prune/">Prune</a>
</div>

<div class="admin">
<a href="/admin/reset/">Reset</a>
</div>

</body>

</html>
