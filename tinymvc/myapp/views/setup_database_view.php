<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="<?= $css ?>" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Welcome to the Electric Sheep<br />Server Configuration</h1>

<h2>Database Setup</h1>

<p>
<strong>Note:</strong> The database must already exist.
</p>

<form name="database" method="post" action="<?= $action ?>">

<table class="setupform" align="center">
<tr>
  <th>Type</th>
  <td>
    <select name="type">
      <option value="dblib">FreeTDS / Microsoft SQL Server / Sybase</option>
      <option value="firebird">Firebird/Interbase 6</option>
      <option value="ibm">IBM DB2</option>
      <option value="informix">IBM Informix Dynamic Server</option>
      <option value="mysql" selected="selected">MySQL 3.x/4.x/5.x</option>
      <option value="oci">Oracle Call Interface</option>
      <option value="odbc">ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</option>
      <option value="pgsql">PostgreSQL</option>
      <option value="sqlite">SQLite 3 and SQLite 2</option>
    </select>
  </td>
</tr>

<tr>
  <th>Hostname</th>
  <td><input type="text" name="host" value="<?= $host ?>" /></td>
</tr>

<tr>
  <th>Database</th>
  <td><input type="text" name="name" value="<?= $name ?>" /></td>
</tr>

<tr>
  <th>Username</th>
  <td><input type="text" name="user" value="<?= $user ?>" /></td>
</tr>

<tr>
  <th>Password</th>
  <td><input type="text" name="pass" value="<?= $pass ?>" /></td>
</tr>

<tr>
  <td></td>
  <td><input type="checkbox" name="persistent" <?php echo $persistent ? 'checked="checked"' : ''; ?>/>Persistent Connection</td>
</tr>
</table>

<input name="button" type="submit" value="Test" class="button" />
<input name="button"  type="submit" value="Continue" class="button" />

</form>

<?php if (isset($db_ok)): ?>
<div class="dbtest <?= $db_ok ?>">
     Database Connection: <?php echo ($db_ok ? '<span class="success">Successful</span>' : '<span class="failed">Failed</span>'); ?>
</div>
<?php endif; ?>

</body>
</html>


