<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
if (file_exists(ES_BASEDIR . DS . 'gen' . DS . $flock . DS . $sheep . DS . $frame . '.thumbnail.jpg')) {
    echo '<img src="/gen/' . $flock . '/' . $sheep . '/' . $frame . '.jpg" alt="" />';
} else {
    echo '<img src="/images/anon-icon.jpg" alt="" />';
}
?>

</body>

</html>
