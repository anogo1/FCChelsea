function validiraj(input)
{
	if(input.name == "naslov")
	{
		if(input.value.length < 3){
			input.style.backgroundColor = "#ff4d4d";
			return false;
		}
		else input.style.backgroundColor = "white";
		return true;
	}
	else if(input.name == "tekst")
	{
		if(input.value.length < 10){
			input.style.backgroundColor = "#ff4d4d";
			return false;
		}
		else input.style.backgroundColor = "white";
		return true;
	}
	else if(input.name == "slika")
	{
		var regexSlika = /\.(jpg|jpeg|gif|png)$/;
		if(regexSlika.test(input.value) == false || input.value.length < 3){
			alert("Niste odabrali validnu sliku!");
			return false;
		}
		return true;
	}
}

function validirajSve()
{
	if(!validiraj(document.getElementById('naslovV')))
	{
		alert("Morate naslov ispravno unijeti!");
		return false;
	}
	else if(!validiraj(document.getElementById('tekstV')))
	{
		alert("Morate tekst ispravno unijeti!");
		return false;
		
	}
	else if(!validiraj(document.getElementById('slikaV')))
	{
		alert("Morate odabrati ispravnu sliku!");
		return false;
	}
	else if(!crossProvjera())
	{
		alert("Niste unijeli broj ispravno");
		return false;
	}
	else 
	{
		alert("Uspješno dodana novost!");
		return true;
	}
}

function crossProvjera()
{
	var odabranaDrzava = document.getElementById('kod').value.toLowerCase(); 
	var uneseniBroj = document.getElementById('telefon').value.split('+').join("");
	var textboxTelefon = document.getElementById('telefon');
	var pozivni = 0;
	var regexBroj = /^[0-9]+$/;
	var brojac = 0;
	
	if(regexBroj.test(uneseniBroj) == false && uneseniBroj != "")
	{
		alert("Broj telefona treba biti unesen u pravilnom formatu!");
		textboxTelefon.style.backgroundColor = "#ff4d4d";
		return false;
	}
	if(uneseniBroj == ""){
		textboxTelefon.style.backgroundColor = "white";
		return false;
	}
	
	var ajax = new XMLHttpRequest();
	
	ajax.open("GET", "https://restcountries.eu/rest/v1/alpha?codes=" + odabranaDrzava, true);
	
	ajax.onreadystatechange = function() {		
		if (ajax.readyState == 4 && ajax.status == 404)
			alert("Pogreska pri ucitavanju!");
				
		var json = JSON.parse(ajax.responseText);
		pozivni = json[0].callingCodes;
		if(uneseniBroj.startsWith(json[0].callingCodes))
		{
			brojac = 1;
			textboxTelefon.style.backgroundColor = "white";
			return true;
		}
		//stvarno ne znam ni sto ni kako ni zasto, ali bez ove kvazilogike sa varijablom brojac, dva puta se pojavljivao alert
		if(brojac == 0){
		alert("Uneseni broj telefona ima pogrešan pozivni broj!");
		textboxTelefon.style.backgroundColor = "#ff4d4d";
		brojac = 1;
		return false;
		}
	}

	ajax.send();
	return true;
}