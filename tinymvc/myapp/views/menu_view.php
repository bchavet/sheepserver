<?php

// Main Menu

echo '<div class="navigation">';
echo '<ul>';
echo '<li class="first">Flock ' . $flock . '</li>';
echo '<li><a href="/flock/loops">Loops</a></li>';
echo '<li><a href="/flock/edges">Edges</a></li>';
echo '<li><a href="/flock/archive">Archive</a></li>';
echo '<li><a href="/flock/queue">Queue</a></li>';
echo '<li><a href="/flock/credit">Credit</a></li>';
echo '<li><a href="/flock/stats">Statistics</a></li>';

if (empty($_SESSION['logged_in'])) {
    echo '<li><a href="/admin">Login</a></li>';
} else {
    echo '<li><a href="/admin">Admin</a></li>';
    echo '<li><a href="/admin/logout">Logout</a></li>';
}

echo '</ul>';
echo '</div>';


// Sheep Menu

if (isset($sheep) && is_array($sheep)) {
    echo '<div class="navigation">';

    echo '<ul>';
    echo '<li class="first">Sheep ' . $sheep['sheep_id'] . '</li>';
    echo '<li><a href="/sheep?sheep=' . $sheep['sheep_id'] . '">View</a></li>';

    if ($sheep['state'] != 'archive') {
        echo '<li><a href="/sheep/frames?sheep=' . $sheep['sheep_id'] . '">Frames</a></li>';
    }

    if ($sheep['first'] == $sheep['last']) {
        echo '<li><a href="/sheep/family?sheep=' . $sheep['sheep_id'] . '">Family</a></li>';
    }

    echo '<li><a href="/sheep/genome?sheep=' . $sheep['sheep_id'] . '">Genome</a></li>';

    if ($sheep['state'] != 'archive') {
        echo '<li><a href="/sheep/credit?sheep=' . $sheep['sheep_id'] . '">Credit</a></li>';
    }

    if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep['sheep_id'] . DS . 'sheep.mpg')) {
        echo '<li><a href="/gen/' . $flock . '/' . $sheep['sheep_id'] . '/sheep.mpg">Download</a></li>';
    }

    echo '</ul>';

    // Vote Menu
   
    if ($canvote) {
        echo '<ul class="vote">';
        echo '<li class="first"><a href="/sheep?sheep=' . $sheep['sheep_id'] . '&amp;action=voteup">Vote Up</a></li>';
        echo '<li><a href="/sheep?sheep=' . $sheep['sheep_id'] . '&amp;action=votedown">Vote Down</a></li>';
        echo '</ul>';
    }

    // Admin Menu

    if (!empty($_SESSION['logged_in']) && $sheep['state'] != 'archive') {
        echo '<ul class="admin">';
        echo '<li class="first"><a href="/sheep?sheep=' .  $sheep['sheep_id'] . '&amp;action=archive">Archive</a></li>';
        echo '<li><a href="/sheep?sheep=' . $sheep['sheep_id'] . '&amp;action=delete">Delete</a></li>';
        echo '</ul>';
    }

    if (!empty($_SESSION['logged_in']) && $sheep['state'] == 'archive') {
        echo '<ul class="admin">';
        echo '<li class="first"><a href="/sheep?sheep=' . $sheep['sheep_id'] . '&amp;action=unarchive">Unarchive</a></li>';
        echo '</ul>';
    }

    echo '</div>';

    if (!empty($sheep['credit_link'])) {
        echo '<div>Original Sheep: <a href="' . $sheep['credit_link'] . '">' . $sheep['credit_link'] . '</a></div>';
    }
}