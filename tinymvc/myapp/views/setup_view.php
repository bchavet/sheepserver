<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="<?= $css ?>" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Welcome to PHP Electric Sheep Server</h1>

<p>
<strong>Note:</strong> The database must already exist.
</p>

<form name="database" type="put" action="<?= $setup ?>">

<table class="setupform">
<tr>
  <th>Type</th>
  <td>
    <select name="type">
      <option name="dblib">FreeTDS / Microsoft SQL Server / Sybase</option>
      <option name="firebird">Firebird/Interbase 6</option>
      <option name="ibm">IBM DB2</option>
      <option name="informix">IBM Informix Dynamic Server</option>
      <option name="mysql" selected="selected">MySQL 3.x/4.x/5.x</option>
      <option name="oci">Oracle Call Interface</option>
      <option name="odbc">ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</option>
      <option name="pgsql">PostgreSQL</option>
      <option name="sqlite">SQLite 3 and SQLite 2</option>
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
  <th>Persistent Connction</th>
  <td><input type="checkbox" name="persistent" /></td>
</tr>
</table>

<input type="submit" value="Submit" />

</form>

</body>
</html>


