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
<caption>Last 60 Minutes</caption>
<tr>
  <th>Frames Assigned</th>
  <td><?= $frames_assigned_60 ?></td>
  <td><?= round(($frames_assigned_60 / 60), 2) ?> frames per minute</td>
</tr>
<tr>
  <th>Frames Returned</th>
  <td><?= $frames_returned_60 ?></td>
  <td><?= round(($frames_returned_60 / 60), 2) ?> frames per minute</td>
</tr>
<tr>
  <th>Sheep Completed</th>
  <td><?= $sheep_completed_60 ?></td>
  <td><?= round((60 / $sheep_completed_60), 2) ?> minutes per sheep</td>
</tr>
</table>

<br />

<table>
<caption>Last 24 Hours</caption>
<tr>
  <th>Frames Assigned</th>
  <td><?= $frames_assigned_1440 ?></td>
  <td><?= round(($frames_assigned_1440 / 1440), 2) ?> frames per minute</td>
</tr>
<tr>
  <th>Frames Returned</th>
  <td><?= $frames_returned_1440 ?></td>
  <td><?= round(($frames_returned_1440 / 1440), 2) ?> frames per minute</td>
</tr>
<tr>
  <th>Sheep Completed</th>
  <td><?= $sheep_completed_1440 ?></td>
  <td><?= round((1440 / $sheep_completed_1440), 2) ?> minutes per sheep</td>
</tr>
</table>

</body>
</html>
