<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<h2>Credit</h2>
<table cellspacing="0" cellpadding="0">
<tr><th>Nick</th><th>Frames</th></tr>
<?php
foreach ($credit as $nick => $count) {
     echo '<tr><td>' . $nick . '</td><td>' . $count . '</td></tr>';
}
?>
</table>

</body>
</html>
