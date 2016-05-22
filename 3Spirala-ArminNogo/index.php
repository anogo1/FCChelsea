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
//file_put_contents("korisnickiPodaci.csv", "admin,".password_hash("admin", PASSWORD_BCRYPT));
if (isset($_POST['login'])){
$unos = file("korisnickiPodaci.csv");
$podaci = explode(",", $unos[0]);
$username = $podaci[0];
$password = $podaci[1];
}
if (isset($_SESSION['korisnik']))
$username = $_SESSION['korisnik'];
if (isset($_POST['logout'])){
	session_unset();
	session_destroy();
}	
if (isset($_POST['login']) && $_POST['username'] == $username && password_verify($_POST['password'], $password)){
	$_SESSION['korisnik'] = $username;	
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
<li><a class= "menilink" href="index.php#">Naslovnica</a></li>
<li><a class= "menilink" href="tabelarna.php#">Tabela</a></li>
<li><a class= "menilink" href="forma.php#">Forma</a></li>
<li><a class= "menilink" href="linkovi.php#">Linkovi</a></li>
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
else if (isset($_SESSION['korisnik']) == true){
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
if(isset($_POST['sortABC']))
{
	$novosti = file("novosti.csv");
	sort($novosti);	//sortira se abecedno po naslovu, prvo se uzimaju u obzir brojevi, pa velika slova, pa onda mala slova
					//mada je logicno da se naslov vijesti unosi sa velikim slovom kao pocetnim znakom 

	$brojNovosti = 0;
	foreach($novosti as $nov)
	{
		$nov = explode(",", $novosti[$brojNovosti]);
		print "<div class=\"novost\">
				<IMG SRC=\"$nov[1]\" ALT=\"Loading...\">
				<h4>$nov[0]</h4>
				<p>$nov[2]</p>
				<p class=\"datumObjave\"></p>
				<p class=\"hiddenDatum\">$nov[3]</p>
				</div>";
		if($brojNovosti%2 == 1)
		{
			print "<br class=\"ocisti\" />
				<br class=\"ocisti\" />";
		}
		$brojNovosti = $brojNovosti+1;
	}
}
else{
	$novosti = file("novosti.csv");
	
	$brojNovosti = 0;
	
	for ($i = sizeof($novosti)-1; $i >= 0; $i--)
	{
		$nov = explode(",", $novosti[$i]);
		print "<div class=\"novost\">
				<IMG SRC=\"$nov[1]\" ALT=\"Loading...\">
				<h4>$nov[0]</h4>
				<p>$nov[2]</p>
				<p class=\"datumObjave\"></p>
				<p class=\"hiddenDatum\">$nov[3]</p>
				</div>";
				
		if($brojNovosti%2 == 1)
		{
			print "<br class=\"ocisti\" />
				<br class=\"ocisti\" />";
		}
		$brojNovosti = $brojNovosti+1;
	}	
}
?>



</div>
</div>
</BODY>
</HTML>