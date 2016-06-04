<?php
session_start();
?>
<!DOCTYPE html>
<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
  <TITLE>Forma</TITLE>
  <link rel="stylesheet" type="text/css" href="forma.css">
  <script src="forma.js" type="text/javascript"></script>
</HEAD>
<BODY>
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

<h1>Narudžba dresa</h1>

<div id="forma">
<br/>
<form id="formaa" action="http://www.chelseamegastore.com/stores/chelsea/en/products/kitselector/chelsea-home-kit-2015-16/228">
<div class="cjelina">
<label class="labela" id="radioprva"> Veličina dresa: </label>
<input class="check" type="radio" name="veličinaDr" value="S" checked> <label class="labela">S</label>
<input class="check" type="radio" name="veličinaDr" value="M" checked> <label class="labela">M</label>
<input class="check" type="radio" name="veličinaDr" value="L" checked> <label class="labela">L</label>
<input class="check" type="radio" name="veličinaDr" value="XL" checked> <label class="labela">XL</label>
<input class="check" type="radio" name="veličinaDr" value="XXL" checked> <label class="labela">XXL</label>
</div>

<div class="cjelina">
<label class="labela" id="radiodruga">Veličina rukava:</label>
<input class="check" type="radio" name="veličinaRu" value="K" checked> <label class="labela">Kratki</label>
<input class="check" type="radio" name="veličinaRu" value="D" checked> <label class="labela">Dugi</label>
</div>

<div class="cjelina">
<label class="labela"> Broj koji će se nalaziti na dresu(0-99):	</label>
<input class="textbox" id="broj" type="text" name="brojDresa" onblur="validiraj(this)" onkeyup="validiraj(this)" placeholder="Cijeli broj između 0 i 99" required>
</div>

<div class="cjelina">
<label class="labela"> Ime koje će se nalaziti na dresu:</label>
<input class="textbox" id="ime" type="text" name="imeDres" onblur="validiraj(this)" onkeyup="validiraj(this)" placeholder="Max 30 karaktera, npr. John Terry" required><br/>
</div>

<div class="cjelina">
<label class="labela"> Dodatni zahtjevi:</label>
<textarea id="textarea" name="poruka" cols="1" rows="5" onblur="validiraj(this)" onkeyup="validiraj(this)" placeholder="Max 255 karaktera, dva prethodna polja moraju biti ispravno unesena!" required>
</textarea>
</div>

<div class="cjelina">
<input class="dugme" type="submit" value="Potvrdi">
</div>

</form>
</div>

</div>  
</BODY>
</HTML>