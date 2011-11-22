<?php
require_once("simpleHtmlDom/simple_html_dom.php");
class wordModel {
	
	
	//funktion som returnerar en array med de cirka 800 vanligaste orden i svenska språket. 
	public function getArrayWithMostCommonWords()
	{
		$commonWordsArr = explode(" ", 'i och att det som en på är av för med till den har de inte om ett han men var jag sig från vi så kan man när år säger hon under också efter eller nu sin där vid mot ska skulle kommer ut får finns vara hade alla andra mycket än här då sedan över bara in blir upp även vad få två vill ha många hur mer går detta nya skall hans utan sina något allt första fick måste mellan blev bli dag någon några sitt stora varit dem bland kl bra tre ta genom del hela annat fram gör ingen göra enligt mig redan inom kom du helt ju samma kanske själv oss tidigare se dock denna både tid kunna fått stor a olika ser flera plats m kunde gå ur gäller honom aldrig varje lite sätt just väl tar åt mest per står fem tror rätt dessa gång därför fyra ny gick hos dessutom ger lika eftersom vilket trots tycker ligger vet kvar bättre gjorde ändå inför senaste samtidigt annan ännu the blivit fall talet exempel gamla deras tiden min hennes sista komma större visar senare tog nästa ge mindre gjort innan alltid sade först stället vår före tillbaka ner nog samt ofta själva inget fler säga egen johansson igen runt nästan lopp förra året mål längre svårt bästa andersson anders handlar dagens länge stort peter frågan spelar enda liv fortfarande medan bakom haft minst fast lars personer början alltså bort v varför anser våra mitt dess nytt tio inga fanns egna utanför ville kr långt framför båda behöver par nej största direkt folk borde väg innebär klart göran bör vidare menar håller lätt ytterligare persson hem gått flesta ja hand särskilt därmed cirka heller s gången åren började däremot sett henne jobb kring känner liten beslut egentligen länder börjar ni väder hjälp tredje vilka talar sidan riktigt små thomas h arbete vissa skriver såg slut ibland ned fråga sa hålla unga drygt jan emot magnus e nära gånger gav fel tagit nr namn p steg kommit helst ihop liksom nilsson sådan förslag vilken dagar stefan vann års spela johan of säkert varandra ganska veckan vem ens visst lång meter all nev hus sitter c form lilla barnen snabbt grund hemma d snart möjligt låg dn klara frågor verkligen precis stöd svensson delar hög larsson mats bo faktiskt land gott gärna högre vårt god dagen övriga t betala lag björn slutet verkar sagt krav stod omkring viktigt ord hoppas kort hittills minuter visa eget sådana tänker sju tycks naturligtvis endast annars antal enkelt försöker tal jonas mannen bengt totalt ute visade låter anställda vägen väldigt stå erik åtta årets via satt roll brukar nämligen fortsätter ungefär bäst händer bor hårt spelare främst program kallade rad förstås höga månader kväll familjen igenom höll martin o känns staten antalet skapa tänka vd heter alls hör köpa div sida sådant fredrik maria mina extra eriksson kräver l goda långa g hitta åtminstone numera timmar vecka knappast dig mikael skrev pettersson vore lär carl veta betyder grupp lever spelade betydligt gammal f äldre dels person laget alldeles stark valet ifrån leder kvinna ökar hävdar lägga ulf gunnar skäl låta ansvar sen visserligen använda rapport karlsson mera veckor fl såväl därefter john viss landets plötsligt kunnat nyligen möjlighet rollerna k morgon ena mars samband öka bygga litet börja behövs staden högsta ökat tala perioden ställer x försöka starkt exempelvis håll resultatet sätta dit arbetet
										verksamhet lämna anna christer bild vars eva intresse samarbete närmare januari september uppgifter sak fullt michael olsson resultat boken södra juni fortsätta dra ingenting samtliga beror alltför försök rum tur fri förklarar svar öppna tro maj tyckte vatten saker val enbart inne eus information arbeta vilja centrum europeiska vinna ökade lena leva närmaste ledningen tidigt rör sker robert tommy olof senast högt jämfört hel skriva viktiga övrigt december känna å ingår match delen slags ledning drar lennart konstaterar finnas målet klar slog räknar andreas dom knappt krävs kostar lägre viktig klockan emellertid ställa slår låt sälja öppet huset lagen råd tas uppgift möjligheter tomas starka henrik leif hälften allra medlemmar beslutet stad bl slå området förutom väntar behov kraft utvecklingen avgörande anledning vanliga problemet välja trodde full patrik förslaget lokala läsa brev snarare ekonomi nio struken mat varken svarar undan dags david fjol räcker sven betydelse vita månad uppdrag åka borta ton tag rent föll förr von kontakt tills augusti fallet lägger lära verk böcker ställning spelas norra kör tvingas fungerar n liknande kallas minska saknar vanligt resa svarta nivå tanke and ständigt vare privata bygger chans söker sätter förstå slag viktigaste följer kände ledare stan priset projekt april nuvarande strax visat sent läser lyckades tillräckligt lyckas närmast st börjat samman dåligt företagen programmet håkan kvinnan klarar verksamheten väljer leda nå a href img src id class hr cm m mm idag igår imorgon iår ikväll idag imorse rundan helvetica arial verdana georgia margin width padding startar');
		//går igenom alla ord så åäö fungerar.
		for ($i=0; $i < count($commonWordsArr); $i++) { 
			$commonWordsArr[$i] = $commonWordsArr[$i];
		}
		return $commonWordsArr;
	}
	//funktion som hämtar alla ord från sidan
	public function getWordsFromPage($page)
	{
		//får html som en textsträng
		
		$html = str_get_html($page);
		$array = Array();
		$wordArray = Array();
		//hämtar ut de vanligaste orden, för att sedan kunna plocka bort dessa från sidan
		$commonWordArray = $this->getArrayWithMostCommonWords();
		//går igenom alla element som matchar valda id:t eller klassnamnet på sidan
		foreach($html->find($_SESSION['element']) as $element)
		{
			//stoppar in alla ord i en array
       		$array = explode(" ", $element->innertext);
			//loopar igenom alla ord
			for ($i=0; $i < count($array); $i++) {

				$word = $array[$i];
				
				//tar bort punktar, utropstecken, frågetecken med mera från ordet
				$word = $this->removeDotsFromWords($word);
				
				//gör så ordet bara består av små bokstäver
				$word = $this->getOnlyLowerCases($word);
				//tar borta eventuella html-taggar
				$word = $this->removeHTMLtags($word);
				
				//kollar om ordet innehåller några mer rester av html-taggar, i så fall tas ordet bort
				if($this->removeRestOfHtml($word) == false)
				{
					//kollar om ordet ingår i arrayen över vanliga ord, i så fall tas ordet bort
					if($this->checkIfCommonWord($word, $commonWordArray) == false)
					{
						//annars läggs ordet in i en array 
						array_push($wordArray, $word);
					}
				}
				
			}
		}
		return $wordArray;
	}
	//funktion som tar bort punktar, utropstecken, frågetecken med mera från ett ord
	public function removeDotsFromWords($word)
	{
		$word = trim($word, ".");
		$word = trim($word, "?");
		$word = trim($word, "!");
		$word = trim($word, ",");
		$word = trim($word, ";");
		$word = trim($word, ":");
		$word = trim($word, "-");
		return $word;
	}
	//funktion som tar bort html-taggar från ord
	public function removeHTMLtags($string)
	{
		$string = strip_tags($string);
		return $string;
	}
	//funktion som kollar om ett ord innehåller fler rester av html-kod
	public function removeRestOfHtml($word)
	{
		for ($i=0; $i < strlen($word); $i++) {
			if($word[$i] == "<" ||$word[$i] == ">" || $word[$i] == "/" || $word[$i] == ":" || $word[$i] == '"' || $word[$i] == "-" || $word[$i] == "=" || $word[$i] == "1"|| $word[$i] == "2"|| $word[$i] == "3"|| $word[$i] == "4"|| $word[$i] == "5"|| $word[$i] == "6"|| $word[$i] == "7" ||$word[$i] == "8"|| $word[$i] == "9"|| $word[$i] == "0")
			{
				return true;
			}
		}
		return false;
	}
	//funktion som sätter ett ord till bara små bokstäver
	public function getOnlyLowerCases($string)
	{
		$string = htmlentities($string, ENT_COMPAT, 'UTF-8');
		$string = trim(strtolower($string));
		$string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
		return $string;

	}
	//funktion som räknar ut vilka de mest använda orden är
	public function mostUsedWords($input) 
	{
		//kollar hur många gånger varje ord används 
	  $counted = array_count_values($input); 
	  //sorterar arrayen
	  arsort($counted);
	  $returnArray = Array();
	  //går igenom arrayen och lägger in orden i en ny array, för att få key som siffra och inte som bokstäver som det blir 
	  //av array_count_values
	  foreach ($counted as $key => $value) {
			array_push($returnArray, $key);
		}
	  return $returnArray;     
	}
	//funktion som kollar om ordet ingår i arrayen över vanliga ord.
	public function checkIfCommonWord($word, $commonWordArray)
	{
		for ($i=0; $i < count($commonWordArray); $i++) {
			if($word == $commonWordArray[$i])
			{
				return true;
			}
			
		}
		return false;		
	}
	public function checkTime($time, $hour)
	{
		## example start/end dates
		$startdate = $time;
		$enddate = $hour;
		
		## difference between the two in seconds
		$time_period = ( $enddate - $startdate );
		
		$days = 0; 
		$hours = 0;
		$minutes = 0;
		$seconds = 0;
		
		$time_increments = array( 'Days' => 86400,
		'Hours' => 3600,
		'Minutes' => 60,
		'Seconds' => 1 );
		
		## will hold our values for ( day, minute, hour, seconds )
		$time_span = array();
		
		## cycle through time_increments 
		while( list( $key, $value ) = each( $time_increments )) {
		$this_value = (int) ( $time_period / $value );
		$time_period = ( $time_period % $value );
		
		# save value
		$time_span[$key] = $this_value;
		}
		$hoursSince = $time_span["Hours"];
		if($hoursSince >= $_SESSION['cacheTime'])
		{
			return true;
		}
		else
		{
			return false;
		}
	
	}
}
?>