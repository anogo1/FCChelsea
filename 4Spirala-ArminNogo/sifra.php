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
if(isset($_POST['promijeni']) && $_POST['novi'] == $_POST['novi2'])
{
	$password = htmlEntities($_POST['novi'], ENT_QUOTES);
	$hash = password_hash($password, PASSWORD_DEFAULT);
	
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	
	$autorid = $_SESSION['id'];
	
	$rezultat = $veza->query("select password from  autor where id = '$autorid'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	
	foreach($rezultat as $korisnik)
	{
		if(password_verify($_POST['stari'], $korisnik['password']))
		{
			$rezultat2 = $veza->query("update autor set password = '$hash' where id = '$autorid'");
			if(!$rezultat2)
			{
				$greska = $veza->errorInfo();
				print "SQL greska: " . $greska[2];
				exit();
			}
			
			session_unset();
			session_destroy();
		}
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
if(isset($_SESSION['korisnik']) && $_SESSION['korisnik'] != 'admin')
{
?>
<div class="forma">
<form class="obrisi" action="sifra.php" method="post">
<h3>Promjena passworda</h3>
<label>Stari password:</label>
<input class="tekst" type="password" name="stari" required>
<BR><BR>

<label>Novi password:</label>
<input class="tekst" type="password" name="novi" required>
<BR><BR>

<label>Ponoviti novi password:</label>
<input class="tekst" type="password" name="novi2" required>
<BR><BR>

<input class="btn" type="submit" name="promijeni" value="Promijeni">
<BR><BR>

</form>
</div>
<?php
}
?>

</div>
</BODY>
</HTML>