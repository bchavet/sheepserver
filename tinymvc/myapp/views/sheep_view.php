<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<?php
if (isset($action)) {
    switch ($action) {
    case 'archive':
        echo '<div class="message confirm">';
        echo 'Are you sure you want to archive this sheep? ';
        echo '<a href="/admin/archive?sheep=' . $sheep['sheep_id'] . '">Yes</a> ';
        echo '<a href="/sheep?sheep=' . $sheep['sheep_id'] . '">No</a>';
        echo '</div>';
        break;

    case 'delete':
        echo '<div class="message confirm">';
        echo 'Are you sure you want to delete this sheep? ';
        echo '<a href="/admin/delete?sheep=' . $sheep['sheep_id'] . '">Yes</a> ';
        echo '<a href="/sheep?sheep=' . $sheep['sheep_id'] . '">No</a>';
        echo '</div>';
        break;

    case 'requeue':
        echo '<div class="message confirm">';
        echo 'Are you sure you want to return this sheep to the queue? ';
        echo '<a href="/admin/requeue?sheep=' . $sheep['sheep_id'] . '">Yes</a> ';
        echo '<a href="/sheep?sheep=' . $sheep['sheep_id'] . '">No</a>';
        echo '</div>';
        break;
    }
}
?>

<table>
<tr>

<td>
<?php
foreach ($before as $sheep) {
     echo '<div>';
     echo '<a href="/sheep?sheep=' . $sheep['first'] . '">';

     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['first'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
     }

     echo '</a>';
     echo '</div>';
}
?>
<?php if ($current['first'] == $current['last'] && !empty($_SESSION['logged_in']) && $current['state'] != 'archive'): ?>
<div>
<a href="/admin/newedge?type=random&amp;last=<?= $current['sheep_id'] ?>&amp;return=<?= $current['sheep_id'] ?>">New Edge Here</a>
</div>
<?php endif; ?>
</td>

<td>
<?php
if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $current['flock_id'] . DS . $current['sheep_id'] . DS . '0.image.jpg')) {
    echo '<img src="/gen/' . $current['flock_id'] . '/' . $current['sheep_id'] . '/0.image.jpg" alt="" class="frame" />';
} else if ($current['state'] == 'incomplete') {
    echo '<img src="/images/busy-icon.jpg" alt="assigned" />';
} else {
    echo '<img src="/images/anon-icon.jpg" alt="ready" />';
}
?>
<?php if ($current['num_votes'] > 0): ?>
<p>
<strong>Rating:</strong> <?php echo $current['rating']; ?> (<?php echo $current['num_votes'] ?> votes)
</p>
<?php endif; ?>
</td>

<td>
<?php
foreach ($after as $sheep) {
     echo '<div>';
     echo '<a href="/sheep?sheep=' . $sheep['last'] . '">';

     if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['last'] . DS . '0.thumbnail.jpg')) {
         echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
     } else {
         echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
     }

     echo '</a>';
     echo '</div>';
}
?>
<?php if ($current['first'] == $current['last'] && !empty($_SESSION['logged_in']) && $current['state'] != 'archive'): ?>
<div>
<a href="/admin/newedge?type=random&amp;first=<?= $current['sheep_id'] ?>&amp;return=<?= $current['sheep_id'] ?>">New Edge Here</a>
</div>
<?php endif; ?>
</td>

</tr>
</table>

</body>

</html>
