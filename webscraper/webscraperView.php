<?php 
class webscraperView {
	//funktion som returnerar en ul-lista över de tio vanligaste orden
	public function listOfMostUsedWords($wordArray)
	{
		$html = 
		"<div id='wordListUl'> 
		<h2>De tio vanligaste orden:</h2>
		
			<ul>";
			if(count($wordArray) >= 11)
			{
				for ($i=1; $i < 11; $i++) { 
					$html .= "<li>". $i . ". " . $wordArray[$i] . "</li>";
				}
			}
			else {
				for ($i=1; $i < count($wordArray); $i++) { 
					$html .= "<li>". $i . ". " . $wordArray[$i] . "</li>";
				}
			}	
			$html .= "</ul></div>";
		return $html;
	}
	//funktion som returnerar ett felmeddelande
	public function getErrorMessage()
	{
		$html = "<p>Det du sökte på gav inga träffar. Du måste söka på en blogg från blogg.se.</p>";
		return $html;
	}
	//funktion som reagerar när sök-knappen blir klickad
	public function TriedToSearch()
	{
		if(isset($_GET["searchButton"]))
		{
			return true;
		}
		return false;
	}
	//funktion som returnerar söksträngen
	public function getSearchString()
	{
		return $_GET["searchField"];
	}
}
?>