<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<?php if (!empty($credit)): ?>
<div>
Original Sheep: <a href="<?= $credit ?>"><?= $credit ?></a>
</div>
<?php endif; ?>

<pre>
<?= htmlspecialchars($genome) ?>
</pre>

</body>

</html>
