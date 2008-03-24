<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/strict.dtd">
<html>
<head>
  <title>Welcome to PHP Electric Sheep Server</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="/screen.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?= $menu ?>

<form name="login" action="/admin/login" method="post">
<table style="display: inline">
<tr>
  <td>Username:</td>
  <td><input type="text" name="username" /></td>
</tr>
<tr>
  <td>Password:</td>
  <td><input type="password" name="password" /></td>
</tr>
<tr>
  <td colspan="2"><input type="submit" value="Login" /></td>
</tr>
</table>
</form>

</body>

</html>
