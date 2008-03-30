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
<tr>

<td>
<?php
foreach ($before as $sheep) {
     echo '<div>';
     echo '<a href="/sheep/motion?sheep=' . $sheep['sheep_id'] . '">';

     if ($sheep['first'] == $sheep['last']) {

         if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['sheep_id'] . DS . '0.thumbnail.jpg')) {
             echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
         } else {
             echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
         }

     } else {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['first'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
        }
     
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['last'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
        }

     }

     echo '</a>';
     echo '</div>';
}
?>
<?php if ($current['first'] == $current['last'] && !empty($_SESSION['logged_in'])): ?>
<div>
<a href="/admin/newedge?type=random&amp;last=<?= $current['sheep_id'] ?>">New Edge Here</a>
</div>
<?php endif; ?>
</td>

<td>
<?php
if ($current['first'] == $current['last']) {

    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $current['flock_id'] . DS . $current['sheep_id'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $current['flock_id'] . '/' . $current['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail self" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
    }
    
} else {
    
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $current['flock_id'] . DS . $current['first'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $current['flock_id'] . '/' . $current['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst self" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
    }
    
    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $current['flock_id'] . DS . $current['last'] . DS . '0.thumbnail.jpg')) {
        echo '<img src="/gen/' . $current['flock_id'] . '/' . $current['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast self" />';
    } else {
        echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
    }
    
}
?>
</td>

<td>
<?php
foreach ($after as $sheep) {
     echo '<div>';
     echo '<a href="/sheep/motion?sheep=' . $sheep['sheep_id'] . '">';

     if ($sheep['first'] == $sheep['last']) {

         if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['sheep_id'] . DS . '0.thumbnail.jpg')) {
             echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['sheep_id'] . '/0.thumbnail.jpg" alt="" class="thumbnail" />';
         } else {
             echo '<img src="/images/anon-icon.jpg" class="thumbnail" />';
         }

     } else {

        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['first'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['first'] . '/0.thumbnail.jpg" alt="first" class="thumbnail edgefirst" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgefirst" />';
        }
     
        if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $sheep['flock_id'] . DS . $sheep['last'] . DS . '0.thumbnail.jpg')) {
            echo '<img src="/gen/' . $sheep['flock_id'] . '/' . $sheep['last'] . '/0.thumbnail.jpg" alt="last" class="thumbnail edgelast" />';
        } else {
            echo '<img src="/images/anon-icon.jpg" class="thumbnail edgelast" />';
        }

     }

     echo '</a>';
     echo '</div>';
}
?>
<?php if ($current['first'] == $current['last'] && !empty($_SESSION['logged_in'])): ?>
<div>
<a href="/admin/newedge?type=random&amp;first=<?= $current['sheep_id'] ?>">New Edge Here</a>
</div>
<?php endif; ?>
</td>

</tr>
</table>

</body>

</html>
