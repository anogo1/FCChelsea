<?php
require_once('password.php');
session_start();
?>
<!DOCTYPE html>
<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <TITLE>Modifikacija korisnika</TITLE>
  <link rel="stylesheet" type="text/css" href="korisnici.css">
</HEAD>
<BODY>
<?php
if(isset($_POST['dodaj']))
{
	$username = htmlEntities($_POST['username'], ENT_QUOTES);
	$password = htmlEntities($_POST['password'], ENT_QUOTES);
	$hash = password_hash($password, PASSWORD_DEFAULT);
	
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	$rezultat = $veza->query("select id, username, password from autor");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	
	$postoji = false;
	foreach($rezultat as $korisnik)
	{
		if($korisnik['username'] == $username)
			$postoji = true;
	}
	
	if(!$postoji){
	$rezultat2 = $veza->exec("INSERT INTO autor SET username = '$username', password = '$hash'");
	if(!$rezultat2)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	}
}
if(isset($_POST['modifikuj']) && $_POST['noviPassword'] == $_POST['noviPassword2'])
{
	$username = htmlEntities($_POST['noviUsername'], ENT_QUOTES);
	$password = htmlEntities($_POST['noviPassword'], ENT_QUOTES);
	$hash = password_hash($password, PASSWORD_DEFAULT);
	$id = $_POST['idModifikacija'];
	
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	if($_POST['noviUsername'] != "" && $_POST['noviPassword'] == "")
	{
		$rezultat = $veza->query("update autor set username = '$username' where id = '$id'");
		if(!$rezultat)
		{
			$greska = $veza->errorInfo();
			print "SQL greska: " . $greska[2];
			exit();
		}
	}
	if($_POST['noviUsername'] == "" && $_POST['noviPassword'] != "")
	{
		$rezultat = $veza->query("update autor set password = '$hash' where id = '$id'");
		if(!$rezultat)
		{
			$greska = $veza->errorInfo();
			print "SQL greska: " . $greska[2];
			exit();
		}
	}
	if($_POST['noviUsername'] != "" && $_POST['noviPassword'] != "")
	{
		$rezultat = $veza->query("update autor set username = '$username', password = '$hash' where id = '$id'");
		if(!$rezultat)
		{
			$greska = $veza->errorInfo();
			print "SQL greska: " . $greska[2];
			exit();
		}
	}
}
if(isset($_POST['obrisi']))
{
	$id = $_POST['idBrisanje'];
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	$rezultat1 = $veza->query("select id from novost where autor = '$id'");
	if(!$rezultat1)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	foreach($rezultat1 as $novost)
	{
		$idNovosti = $novost['id'];
		$rezultat2 = $veza->query("delete from komentar where novost = '$idNovosti'");
		if(!$rezultat2)
		{
			$greska = $veza->errorInfo();
			print "SQL greska: " . $greska[2];
			exit();
		}
	}
	
	$rezultat3 = $veza->query("delete from novost where autor = '$id'");
	if(!$rezultat3)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	
	$rezultat = $veza->query("delete from autor where id = '$id'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
}

?>
<div id="okvirstranice">
<div id="naslov">
<p> Chelsea FC </p>
</div>

<div id="meni">
<div class="logo">C F C</div>
<div class="logo1">C F C</div>
<ul>
<li><a class= "menilink" href="index.php?sve">Naslovnica</a></li>
<li><a class= "menilink" href="tabelarna.php#">Tabela</a></li>
<li><a class= "menilink" href="forma.php#">Forma</a></li>
<li><a class= "menilink" href="linkovi.php#">Linkovi</a></li>
</ul>
</div>
<br class="ocisti" />
<hr>

<?php
if(isset($_SESSION['korisnik']) && $_SESSION['korisnik'] == 'admin')
{
?>
<div class="forma">
<form id ="dodaj" action="korisnici.php" method="post">
<h3>Dodavanje autora</h3>
<label>Username autora:</label>
<input class="tekst" type="text" name="username" required>
<BR><BR>

<label>Password autora:</label>
<input class="tekst" type="password" name="password" required>
<BR><BR>

<input class="btn" type="submit" name="dodaj" value="Dodaj">
<BR><BR>

</form>
</div>

<div class="forma">
<form id ="modifikuj" action="korisnici.php" method="post">
<h3>Modifikacija autora</h3>
<label>Stari username autora:</label>
<select name="idModifikacija">
<?php
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
$veza->exec("set names utf8");
$rezultat = $veza->query("select id, username, password from autor");
if(!$rezultat)
{
	$greska = $veza->errorInfo();
	print "SQL greska: " . $greska[2];
	exit();
}
foreach($rezultat as $korisnik)
{
	if($korisnik['username'] != 'admin')
		print "<option value=".$korisnik['id'].">".$korisnik['username']."</option>";
}
?>
</select>
<BR><BR>

<label>Novi username autora:</label>
<input class="tekst" type="text" name="noviUsername" placeholder="Ukoliko nije unesen, ne mijenja se">
<BR><BR>

<label>Novi password autora:</label>
<input class="tekst" type="password" name="noviPassword" placeholder="Ukoliko nije unesen, ne mijenja se">
<BR><BR>

<label>Ponoviti novi password autora:</label>
<input class="tekst" type="password" name="noviPassword2" placeholder="Unijeti isti password">
<BR><BR>

<input class="btn" type="submit" name="modifikuj" value="Modifikuj">
<BR><BR>

</form>
</div>

<div class="forma">
<form id ="obrisi" action="korisnici.php" method="post">
<h3>Brisanje autora</h3>
<label>Username autora:</label>
<select name="idBrisanje">
<?php
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
$veza->exec("set names utf8");
$rezultat = $veza->query("select id, username, password from autor");
if(!$rezultat)
{
	$greska = $veza->errorInfo();
	print "SQL greska: " . $greska[2];
	exit();
}
foreach($rezultat as $korisnik)
{
	if($korisnik['username'] != 'admin')
		print "<option value=".$korisnik['id'].">".$korisnik['username']."</option>";
}
?>
</select>
<BR><BR>

<input class="btn" type="submit" name="obrisi" value="Obriši">
<BR><BR>

</form>
</div>
<?php
}
?>

</div>
</BODY>
</HTML>