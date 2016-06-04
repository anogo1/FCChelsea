<?php
require_once('password.php');
session_start();
?>
<!DOCTYPE html>
<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <TITLE>Naslovnica</TITLE>
  <link rel="stylesheet" type="text/css" href="naslovnica.css">
  <script src="naslovnica.js" type="text/javascript"></script>
</HEAD>
<BODY>

<?php
date_default_timezone_set('Europe/Sarajevo');
if (isset($_POST['login'])){
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
	if($korisnik['username'] == $_POST['username'] && password_verify($_POST['password'], $korisnik['password']))
	{
		$_SESSION['korisnik'] = $korisnik['username'];
		$_SESSION['id'] = $korisnik['id'];
	}
}
}

if (isset($_SESSION['korisnik'])) $username = $_SESSION['korisnik'];

if (isset($_POST['logout'])){
	session_unset();
	session_destroy();
}	

//nakon sto se odabere samo prikaz novosti napravljenih od strane autora(klik na link u detaljnom prikazu novosti)
//bice prikazane samo njegove novosti sve dok se ne klikne na "Naslovnica" u meniju(dakle omoguceno je abecedno i sortiranje po datumu za autorove novosti)
if(isset($_GET['autor']))
{
	$_SESSION['idAutor'] = $_GET['autor'];
}
if(isset($_GET['moje']) && isset($_SESSION['korisnik']))
{
	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
	$veza->exec("set names utf8");
	$username = $_SESSION['korisnik'];
	$rezultat = $veza->query("select id from autor where username = '$username'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	foreach($rezultat as $autor)
	{
		$_SESSION['idAutor'] = $autor['id'];
	}
}
if(isset($_GET['sve']))
{
	$_SESSION['idAutor'] = -1;
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
<?php
if(isset($_SESSION['korisnik']))
{
	print "<li><a class= \"menilink\" href=\"index.php?moje\">Moje Novosti</a></li>";
}
?>
</ul>
</div>
<br class="ocisti" />

<?php
if (isset($_SESSION['korisnik']) == false){
?>
<div id="unos">
<form id="formPrijava" action="index.php" method="post">
 
<input id="username" type="text" name="username" placeholder="Username">
<input id="password" type="password" name="password" placeholder="Password">
<input class="btn" type="submit" name="login" value="Login">

</form>
</div>

<?php
}
else if ($username == 'admin'){
?>
<div id="prijavljen">
<form id="formDodajNovost" action="dodajNovost.php" method="post">
<input class="btn" type="submit" name="dodavanjeNovosti" value="Dodaj Novost">
</form>

<form id="formOdjava" action="index.php" method="post">
<input class="btn" type="submit" name="logout" value="Logout">
</form>
</div>

<br class="ocisti" />

<div id="modifikacije">
<form id="formKorisnici" action="korisnici.php" method="post">
<input class="btn" type="submit" name="korisnici" value="Modifikacija korisnika">
</form>

<form id="formBrisanje" action="brisanje.php" method="post">
<input class="btn" type="submit" name="brisanje" value="Modifikacija novosti i komentara">
</form>
</div>

<br class="ocisti" />

<?php
}
else{
?>
<div id="prijavljen">
<form id="formDodajNovost" action="dodajNovost.php" method="post">
<input class="btn" type="submit" name="dodavanjeNovosti" value="Dodaj Novost">
</form>

<form id="formOdjava" action="index.php" method="post">
<input class="btn" type="submit" name="logout" value="Logout">
</form>
</div>

<br class="ocisti" />

<div id="sifra">
<form id="formSifra" action="sifra.php" method="post">
<input class="btn" type="submit" name="sifra" value="Promjena šifre">
</form>


</div>

<br class="ocisti" />

<?php	
}
?>	
<div id="odabirNovosti">
<form id="odabir">

<select id="meniNovosti" onchange="promijeniPrikaz()">
  <option value="sve">Sve novosti</option>
  <option value="mjesec">Novosti ovog mjeseca</option>
  <option value="sedmica">Novosti ove sedmice</option>
  <option value="dan">Današnje novosti</option>
</select>

</form>
</div>

<div id="sortiranje">
<form id="sortirajAbecedno" action="index.php" method="post">
<input class="btn" type="submit" name="sortABC" value="Sortiraj abecedno">
</form>

<form id="sortirajDatum" action="index.php" method="post">
<input class="btn" type="submit" name="sortDatum" value="Sortiraj po datumu">
</form>
</div>
<br class="ocisti" />
<hr>

<div id="novosti">

<?php
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
        define('DB_PORT',getenv('OPENSHIFT_MYSQL_DB_PORT'));
        define('DB_USER',getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
        define('DB_PASS',getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
        define('DB_NAME',getenv('OPENSHIFT_GEAR_NAME'));
        $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';port='.DB_PORT;
        $veza = new PDO($dsn, DB_USER, DB_PASS);
$veza->exec("set names utf8");

if(isset($_GET['komentarNovost']))
{
	$tekst = $_GET['komentarNovost'];
	$novost = $_GET['idNovosti'];
	$rezultat = $veza->exec("INSERT INTO komentar SET tekst = '$tekst', vrijeme = NOW(), novost = '$novost'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
}

if(isset($_GET['komentarKomentar']))
{
	$tekst = $_GET['komentarKomentar'];
	$novost = $_GET['idNovosti'];
	$komentar = $_GET['idKomentara'];
	$rezultat = $veza->exec("INSERT INTO komentar SET tekst = '$tekst', vrijeme = NOW(), komentarID = '$komentar', novost = '$novost'");
	if(!$rezultat)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
}

if(isset($_POST['sortABC']))
{
	$rezultat = $veza->query("select id, naslov, slika, tekst, UNIX_TIMESTAMP(vrijeme) vrijeme2, autor, otvorena from novost order by naslov asc");
}
else $rezultat = $veza->query("select id, naslov, slika, tekst, UNIX_TIMESTAMP(vrijeme) vrijeme2, autor, otvorena from novost order by vrijeme desc");

if(!$rezultat)
{
	$greska = $veza->errorInfo();
	print "SQL greska: " . $greska[2];
	exit();
}
		
$brojNovosti = 0;

foreach($rezultat as $novost)
{	
	$id = $novost['id'];
	$idAutora = $novost['autor'];
	$usernameAutor = "";
	
	if(isset($_SESSION['idAutor']) && $_SESSION['idAutor'] != -1)
	{
		if($_SESSION['idAutor'] != $idAutora) continue;
	}
	
	
	if(isset($_GET['autor']) && $_GET['autor'] != $idAutora)
	{
		continue;
	}
	
	$rezultat4 = $veza->query("select username from autor where id = '$idAutora'");
	if(!$rezultat4)
	{
		$greska = $veza->errorInfo();
		print "SQL greska: " . $greska[2];
		exit();
	}
	foreach($rezultat4 as $usernameAutora)
	{
		$usernameAutor = $usernameAutora['username'];
	}
	
	$otvorena = $novost['otvorena'];
		
	print "<div class=\"novost\">
			<IMG SRC=".$novost['slika']." ALT=\"Loading...\">
			<a class=\"naslov\" href='index.php?vijest=$id'>".$novost['naslov']."</a>
			<p>".$novost['tekst']."</p>
			<p class=\"datumObjave\"></p>
			<p class=\"hiddenDatum\">".date("F j, Y H:i:s",$novost['vrijeme2'])."</p>";
	if(isset($_GET['vijest']) || isset($_GET['komentarNovost']) || isset($_GET['komentarKomentar']))		
	{
		if(isset($_GET['vijest'])) $ID = $_GET['vijest'];
		if(isset($_GET['komentarNovost'])) $ID = $_GET['idNovosti'];
		if(isset($_GET['komentarKomentar'])) $ID = $_GET['idNovosti'];
		
		if($ID == $id)
		{
			print "<a class=\"autor\" href='index.php?autor=$idAutora'> Autor novosti je korisnik: ".$usernameAutor."</a>";
		}
		
		if($otvorena == 1 && $ID == $id)
		{
			print "<form action=\"index.php?komentarNovost\" method=\"GET\">
							<input class=\"comment\" type=\"text\" name=\"komentarNovost\" placeholder=\"Ostavite komentar...\"><BR>
							<input type=\"hidden\" name=\"idNovosti\" value=$id>
					</form>";
		}
					
		$rezultat2 = $veza->query("select id, tekst, UNIX_TIMESTAMP(vrijeme) vrijeme2, komentarID, novost from komentar order by vrijeme asc");
		if(!$rezultat2)
		{
			$greska = $veza->errorInfo();
			print "SQL greska: " . $greska[2];
			exit();
		}
		
		foreach($rezultat2 as $komentarr)
		{
			$idKomentara = $komentarr['id'];
			if($komentarr['komentarID'] == null && $komentarr['novost'] == $id && $ID == $id)
			{
				print "<h4>".$komentarr['tekst']."</h4>";
				if($otvorena == 1)
				{
					print "<form action=\"index.php?komentarKomentar\" method=\"GET\">
									<input class=\"comment\" type=\"text\" name=\"komentarKomentar\" placeholder=\"Ostavite komentar...\"><BR>
									<input type=\"hidden\" name=\"idNovosti\" value=$id>
									<input type=\"hidden\" name=\"idKomentara\" value=$idKomentara>
							</form>";
				}
				
				$rezultat3 = $veza->query("select id, tekst, UNIX_TIMESTAMP(vrijeme) vrijeme2, komentarID, novost from komentar order by vrijeme asc");
				if(!$rezultat3)
				{
					$greska = $veza->errorInfo();
					print "SQL greska: " . $greska[2];
					exit();
				}
				
				foreach($rezultat3 as $komentar)
				{
					if($komentar['komentarID'] == $idKomentara && $komentar['novost'] == $id)
					{
						print "<h5>".$komentar['tekst']."</h5>";
					}
				}
			}
		}
	}
	print "	</div>";
	
	if($brojNovosti%2 == 1)
	{
		print "<br class=\"ocisti\" />
			<br class=\"ocisti\" />";
	}
	$brojNovosti = $brojNovosti+1;
}	
?>



</div>
</div>
</BODY>
</HTML>