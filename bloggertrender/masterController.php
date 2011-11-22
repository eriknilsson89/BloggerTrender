<?php
require_once("webscraperView.php");
require_once("webscraperModel.php");
require_once("wordModel.php");
require_once("database/dbSettings.php");
require_once("database/install.php"); 
class masterController {
	public function DoControll()
	{
		$returnHtml = "";
		$_SESSION['element'] = '.entrybody';
		$_SESSION['cacheTime'] = 1;
		$db = new dbSettings("scrapedpages", "localhost", "root", "");
		$db->connect();
		$wsm = new webscraperModel($db);
		$wom = new wordModel();
		$wsv = new webscraperView();
		
		if ($wsv->TriedToSearch())
		{
			//hämtar söksträngen
			$url = $wsv->getSearchString();
			$url = "http://" . $url . ".blogg.se";
			$_SESSION['curUrl'] = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			//kollar om urlen finns med i databasen
			if($wsm->DoesUrlExist($url))
			{
				$now = time();
				//om ja, kollar om det finns giltigt cachat data
				if($wom->checkTime($wsm->getTimeFromDb($url), $now))
				{
					//om ja, hämtar ut den cachade datan 
					return $wsm->getWordsFromDb($url);
				}
				else
				{
					$returnHtml = $this->newScrape($wsm, $wom, $url, true);
				}
			}
			else
			{

				$returnHtml = $this->newScrape($wsm, $wom, $url, false);
			}
			
			
		}
		return $returnHtml;
	}
	public function newScrape($wsm, $wom, $url, $urlExist)
	{
		$wsv = new webscraperView();
		//skrapar sidan som angavs i söksträngen
		$html = $wsm->scrapePage($url);
		if($html != "")
		{
			//hämtar orden som fanns på sidans om skrapades
			$words = $wom->getWordsFromPage($html);
			//om words inte är tom
			if(!empty($words))
			{
				//kollar vilka de mest använda orden är
				$topWords = $wom->mostUsedWords($words);
				//skapar en lista över de mest använda orden
				$returnHtml = $wsv->listOfMostUsedWords($topWords);
			}
			else
			{
				$returnHtml = $wsv->getErrorMessage();
			}
		}
		else {
			$returnHtml = $wsv->getErrorMessage();
		}
		if($urlExist)
		{
			$wsm->updateUrl($url, $returnHtml);
		}
		else
		{
			$wsm->saveDataToDb($url, $returnHtml);
		}
		return $returnHtml;
	}
}
?>
