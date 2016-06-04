<?php
session_start();
?>
<!DOCTYPE html>
<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <TITLE>Brisanje novosti i komentara</TITLE>
  <link rel="stylesheet" type="text/css" href="brisanje.css">
</HEAD>
<BODY>
<?php
if(isset($_POST['obrisiNovost']))
{
	$id = $_POST['idBrisanjeNovosti'];
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	$rezultat1 = $veza->query("delete from komentar where novost = '$id'");
	if(!$rezultat1)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	
	$rezultat = $veza->query("delete from novost where id = '$id'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
}
if(isset($_POST['obrisiKomentar']))
{
	$id = $_POST['idBrisanjekomentara'];
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	$rezultat1 = $veza->query("delete from komentar where komentarID = '$id'");
	if(!$rezultat1)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	
	$rezultat = $veza->query("delete from komentar where id = '$id'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
}
if(isset($_POST['modifikuj']))
{
	$id = $_POST['idModifikacija'];
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	$otvoren = 0;
	if(isset($_POST['otvorena']))
		$otvoren = 1;
	
	$rezultat = $veza->query("update novost set otvorena = '$otvoren' where id = '$id'");
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
<form class="obrisi" action="brisanje.php" method="post">
<h3>Brisanje novosti</h3>
<label>Naslov novosti (autor):</label>
<select name="idBrisanjeNovosti">
<?php
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
$veza->exec("set names utf8");
$rezultat = $veza->query("select id, naslov, autor from novost");
if(!$rezultat)
{
	$greska = $veza->errorInfo();
	print "SQL greska: " . $greska[2];
	exit();
}
foreach($rezultat as $novost)
{
	$autorID = $novost['autor'];
	$username = "";
	$rezultat2 = $veza->query("select username from autor where id = '$autorID'");
	if(!$rezultat2)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	foreach($rezultat2 as $autor)
	{
		$username = $autor['username'];
	}
	
	print "<option value=".$novost['id'].">".$novost['naslov']." (".$username.")</option>";
}
?>
</select>
<BR><BR>

<input class="btn" type="submit" name="obrisiNovost" value="Obriši">
<BR><BR>

</form>
</div>

<div class="forma">
<form class="obrisi" action="brisanje.php" method="post">
<h3>Brisanje komentara</h3>
<label>Komentar (novost):</label>		
<select name="idBrisanjekomentara">
<?php
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
$veza->exec("set names utf8");
$rezultat = $veza->query("select id, tekst, novost from komentar");
if(!$rezultat)
{
	$greska = $veza->errorInfo();
	print "SQL greska: " . $greska[2];
	exit();
}
foreach($rezultat as $komentar)
{
	$novostID = $komentar['novost'];
	$naslov = "";
	
	$rezultat2 = $veza->query("select naslov from novost where id = '$novostID'");
	if(!$rezultat2)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	foreach($rezultat2 as $novost)
	{
		$naslov = $novost['naslov'];
	}
	//prikazuje se samo novost nad kojom je napravljen komentar(ako je komentar nad komentarom, opet se nalazi na toj istoj novosti)
	print "<option value=".$komentar['id'].">".$komentar['tekst']." (".$naslov.")</option>";
}
?>
</select>
<BR><BR>

<input class="btn" type="submit" name="obrisiKomentar" value="Obriši">
<BR><BR>

</form>
</div>

<div class="forma">
<form class="obrisi" action="brisanje.php" method="post">
<h3>Modifikacija novosti</h3>
<label>Naslov novosti:</label>
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
$rezultat = $veza->query("select id, naslov from novost");
if(!$rezultat)
{
	$greska = $veza->errorInfo();
	print "SQL greska: " . $greska[2];
	exit();
}
foreach($rezultat as $novost)
{
	print "<option value=".$novost['id'].">".$novost['naslov']."</option>";
}
?>
</select>
<BR><BR>

<input type="checkbox" name="otvorena" value="otvoren">Otvorena za komentare
<BR><BR>

<input class="btn" type="submit" name="modifikuj" value="Modifikuj">
<BR><BR>

</form>
</div>

<?php
}
?>
</div>
</BODY>
</HTML>