<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Welcome to the Electric Sheep<br />Server Configuration</h1>

<h2>Configuration Test</h2>

<div class="setup_test">
Database Configuration File: 
<?php if (!$db_configured): ?>
<span class="failed">Failed</span>
<br />
Please copy and paste the following code into <span class="filename"><?= $db_config_file ?></span>
<pre>
<?= htmlspecialchars($db_config) ?>
</pre>
<?php else: ?>
<span class="success">OK</span>
<?php endif; ?>
</div>

<div class="setup_test">
Database Connection: <?php echo ($db_ok) ? '<span class="success">OK</span>' : '<span class="failed">Failed</span>'; ?>
</div>

<div class="setup_test">
Config Table: <?php echo $config_table_ok ? '<span class="success">OK</span>' : '<span class="failed">Failed</span>'; ?>
<?php if (!$config_table_ok): ?>
<br />
Please execute the following in your database to create the frame table
<pre>
<?= htmlspecialchars($config_table_schema); ?>
</pre>
<?php endif; ?>
</div>

<div class="setup_test">
/gen Folder Writable: <?php echo $gen_folder_ok ? '<span class="success">OK</span>' : '<span class="failed">Failed</span>'; ?>
<?php if (!$gen_folder_ok): ?>
<br />
Please make sure <span class="filename"><?= $gen_folder_path ?> is writable by your webserver
<?php endif; ?>
</div>

<?php if (!($db_configured && $db_ok && $config_table_ok && $gen_folder_ok)): ?>
<div class="failed">Please correct failed item(s) above and re-check your setup</div><a href="<?= $retry ?>" class="button">re-check</a>
<?php else: ?>
<div class="success">Everything looks good!</div><a href="<?= $continue ?>" class="button">Finish</a>
<?php endif; ?>

</body>
</html>
