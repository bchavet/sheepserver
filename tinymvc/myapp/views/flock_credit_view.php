<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<table>
<tr><th>Nick</th><th>Frames</th></tr>
<?php
foreach ($credit as $client) {
     echo '<tr><td>' . $client['nick'] . '</td><td>' . $client['frames'] . '</td></tr>';
}
?>
</table>

</body>
</html>
