window.onload = function () 
{
    var datumiPrikaz = document.getElementsByClassName("datumObjave");
	var hiddenDatumi = document.getElementsByClassName("hiddenDatum");
    for (var i = 0; i < datumiPrikaz.length; i++) 
	{
        var datum = new Date(hiddenDatumi[i].innerHTML);
        datumiPrikaz[i].innerHTML = razlika(datum);
    }
}

function razlika(datum)
{
	var izmedju = new Date() - datum;
	var sekundeIzmedju = izmedju/1000;	//dobijanje sekundi razlike
	
	var brojDanaMjesec = 31;	//potrebno je provjeriri koliko dana ima prethodni mjesec u odnosu na mjesec kada je novost objavljena
	if(datum.getMonth() == 4 || datum.getMonth() == 6 || datum.getMonth() == 9 || datum.getMonth() == 11)
		brojDanaMjesec = 30;
	if(datum.getMonth() == 2)
		brojDanaMjesec == 29;		//pretpostavimo da nece biti objava prije 1.1.2016. posto tada web stranica nije ni postojala, te da ce biti aktivna
									//samo pola godine(zbog toga ce se za februar uzeti da ima 29 dana, posto je trenutno prestupna godina)
	
	if(Math.floor(sekundeIzmedju/(86400 * brojDanaMjesec)) >= 1)	//3600*24*brojDanaMjesec, ako je razlika veca od mjesec dana
		return datum.toDateString();
	
	var tekst = "Novost objavljena prije ";
	
	if(Math.floor(sekundeIzmedju/86400) >= 1)	//3600*24, ako je razlika veca od dana
	{
		var danaIzmedju = Math.floor(sekundeIzmedju/3600/24);
		
		if(danaIzmedju > 27)
			tekst = tekst + "4 sedmice";
		else if(danaIzmedju > 20)
			tekst = tekst + "3 sedmice";
		else if(danaIzmedju > 13)
			tekst = tekst + "2 sedmice";
		else if(danaIzmedju > 6)
			tekst = tekst + "1 sedmicu";
		else if(danaIzmedju > 1)
			tekst = tekst + danaIzmedju + " dana";
		else if (danaIzmedju == 1)
			tekst = tekst + danaIzmedju + " dan";
	}
	
	if(Math.floor(sekundeIzmedju/3600) >= 1  && Math.floor(sekundeIzmedju/86400) < 1)	//ako je razlika veca od sata, a manja od dana
	{
		var satiIzmedju = Math.floor(sekundeIzmedju/3600);
		
		if(satiIzmedju > 21 || (satiIzmedju < 5 && satiIzmedju > 1))
			tekst = tekst + satiIzmedju + " sata";
		else if(satiIzmedju == 21 || satiIzmedju == 1)
			tekst = tekst + satiIzmedju + " sat";
		else if(satiIzmedju < 21 && satiIzmedju > 4)
			tekst = tekst + satiIzmedju + " sati";		
	}
	
	if(Math.floor(sekundeIzmedju/60) >= 1  && Math.floor(sekundeIzmedju/3600) < 1)		//ako je veca od minute, a manja od sata
	{
		var minutaIzmedju = Math.floor(sekundeIzmedju/60);
		
		if(minutaIzmedju%10 > 4 && minutaIzmedju%10 == 0)
			tekst = tekst + minutaIzmedju + " minuta";
		else if(minutaIzmedju%10 > 1)
			tekst = tekst + minutaIzmedju + " minute";
		else tekst = tekst + minutaIzmedju + " minutu";
	}
	
	if(sekundeIzmedju < 60)
		tekst = tekst + "par sekundi";
	
	return tekst;
}

function promijeniPrikaz()
{
	var trenutno = document.getElementById("meniNovosti").value;
	var novosti = document.getElementsByClassName("novost");
	
	for (var i = 0; i < novosti.length; i++)
	{
		var datum = new Date(novosti[i].getElementsByClassName("hiddenDatum")[0].innerHTML);
		
		if(trenutno == "sve")
		{
			novosti[i].style.display = "inline";
		}
		else if(trenutno == "mjesec")
		{
			if(vrijeme(datum, trenutno) == "mjesec")
				novosti[i].style.display = "inline";
			else novosti[i].style.display = "none";
		}
		else if(trenutno == "sedmica")
		{
			if(vrijeme(datum, trenutno) == "sedmica")
				novosti[i].style.display = "inline";
			else novosti[i].style.display = "none";
		}
		else if(trenutno == "dan")
		{
			if(vrijeme(datum, trenutno) == "dan")
				novosti[i].style.display = "inline";
			else novosti[i].style.display = "none";
		}
	}
	
}

function vrijeme(datum, trenutno)
{
	var provjeraDonja = new Date();
	provjeraDonja.setHours(0);
	provjeraDonja.setMinutes(0);
	provjeraDonja.setSeconds(0);
	provjeraDonja.setMilliseconds(0);
	var pocetakDanaMS = provjeraDonja.getTime();
	
	var datumDanasMS = datum.getTime();
	
	if(trenutno == "dan")
	{
		if(datumDanasMS > pocetakDanaMS)		//provjeravam da li je broj ms vremena objavljivanja veci od broja ms vremena pocetka dana
			return "dan";
	}

	var pocetakSedmiceMS = provjeraDonja.getTime();
	
	var danProvjeraDonja = provjeraDonja.getDay() || 7;
	
	if(danProvjeraDonja != 1)				//ako nije ponedjeljak, treba oduzet odredjeni broj ms * broj dana do ponedjeljka kako bi dobili pocetak sedmice
		pocetakSedmiceMS = pocetakSedmiceMS - (danProvjeraDonja	- 1)*86400000;
	
	if(trenutno == "sedmica")
	{
	if(datumDanasMS > pocetakSedmiceMS)		//provjeravam da li je broj ms vremena objavljivanja veci od broja ms vremena pocetka sedmice
		return "sedmica";
	}

	provjeraDonja.setDate(1);

	var pocetakMjesecaMS = provjeraDonja.getTime();
	
	if(trenutno == "mjesec")
	{
		if(datumDanasMS > pocetakMjesecaMS)
			return "mjesec";
	}
	
	return "sve";
}