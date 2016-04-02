function validiraj(input)
{
	if(input.id == "broj")
	{
		var regexBroj = /^\d{1,2}$/;
		var textArea = document.forms["formaa"]["textarea"];
		
		if(regexBroj.test(input.value) == false)
		{
			input.style.backgroundColor = "#ff4d4d";
			
		}
		else input.style.backgroundColor = "white";
		
		validiraj(textArea);
	}
	if(input.id == "ime")
	{
		var regexIme = /^[a-zA-Z]*\s*[a-zA-Z]*$/;			//moze se polje ostaviti prazno, ukoliko korisnik ne zeli da se ime nalazi na dresu
		var textArea = document.forms["formaa"]["textarea"];
		
		if(regexIme.test(input.value) == false || input.value.length > 30)
		{
			input.style.backgroundColor = "#ff4d4d";
			
		}
		else input.style.backgroundColor = "white";
		
		validiraj(textArea);
	}
	if(input.id == "textarea")
	{
		var broj = document.forms["formaa"]["broj"].value;
		var regexBroj = /^\d{1,2}$/;
		var ime = document.forms["formaa"]["ime"].value;
		var regexIme = /^[a-zA-Z]*\s*[a-zA-Z]*$/;	
		
		if(regexBroj.test(broj) == false || regexIme.test(ime) == false || ime.length > 30 || input.value.length > 255)	
			input.style.backgroundColor = "#ff4d4d";									//ne moraju postojati dodatni zahtjevi, ali da bi se mogli
		else input.style.backgroundColor = "white";										//unijeti, prethodna polja moraju biti validna			
	}
}


function validirajSve(dugme)
{
	var unos = document.forms["formaa"].getElementsByTagName("input");
	for (var i = 7; i < unos.length - 1; i++) 				//ne validiraju se radiobuttoni i button koji su ucitani u varijablu unos
	{
        validiraj(unos[i]);
    }
	//ovo ce se kasnije koristit kad bude trebalo jos jednom provjerit sva polja prije akcije koja bude postavljena na klik
}