<?php
require_once('simpletest/autorun.php');
require_once('webscraper/webscraperModel.php');
require_once('webscraper/wordModel.php');
require_once('webscraper/database/install.php');
require_once('webscraper/database/dbSettings.php'); 
class testOfWebscrapeModel extends UnitTestCase {
	const table_name = "pages";
	private $sql = "CREATE TABLE pages
						(PK int(4) primary key NOT NULL AUTO_INCREMENT, 
						Url varchar(200) NOT NULL, 
						Words varchar(1000) NOT NULL,
						Time timestamp NOT NULL)";
	
	//en html-sträng från testsidan
	private $html = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
 <title>Lina Sällberg - Mitt liv med hästarna</title>
 <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
 <meta name='generator' content='http://publishme.se' />
 <link rel='stylesheet' href='style.css' type='text/css' />
 <link rel='alternate' href='index.rss' type='application/rss+xml' title='RSS' />
<script src='http://code.jquery.com/jquery-1.6.2.min.js'></script>
    <!-- include jQuery Library From Google Code --> 
<script type='text/javascript' src='http://jqueryjs.googlecode.com/files/jquery-1.3.2.js'></script> 
 

</head>

<body>

<div id='wrapper'>

 <div id='header'>
  <h1><a href=''>Lina Sällberg</a></h1>
  <h2>Mitt liv med hästarna</h2>
 </div>
 <div id='newMenu'>
<ul class='blockeasing'>
        <li>
            <a href='http://www.sallberglina.blogg.se'>Startsida</a>
        </li>
        <li class='main' id='about'>
            <a href='#'>Om oss</a>
            <ul class='subnav'>
                <li><a href='http://sallberglina.blogg.se/2011/january/om-mig.html'>Lina</a></li>
                <li><a href='http://sallberglina.blogg.se/2011/january/bellona.html'>Bellona</a></li>
                <li><a href='http://sallberglina.blogg.se/2011/january/one-of-a-kind.html'>One Of a Kind</a></li>
                <li><a href='http://sallberglina.blogg.se/2011/january/dunders-aqua.html'>Dunders Aqua</a></li>
                <li><a href='http://sallberglina.blogg.se/2011/january/anja.html'>Anja</a></li>
          
            </ul>
        </li>
        <li class='main' id='results'>
            <a href='#'>Tävlingsresultat</a>
            <ul class='subnav'>
                <li><a href='http://sallberglina.blogg.se/category/tavling-bellona.html'>Bellona</a></li>
                <li><a href='http://sallberglina.blogg.se/category/tavling-dunders-aqua.html'>Dunders Aqua</a></li>
            </ul>
        </li>
        <li class='main' id='category'>
            <a href='#'>Kategorier</a>
            <ul class='subnav'>
                
                    <li><a href='category/vill-ha.html'>&quot;Vill ha&quot;</a></li>
                
                    <li><a href='category/allmant.html'>Allmänt</a></li>
                
                    <li><a href='category/bildbomb.html'>Bildbomb</a></li>
                
                    <li><a href='category/bloggtavlingar.html'>Bloggtävlingar</a></li>
                
                    <li><a href='category/bloppis.html'>Bloppis</a></li>
                
            </ul>
        </li>
        <li>
            <a href='http://sallberglina.blogg.se/2011/august/kontakt.html'>Kontakt</a>
        </li>
    </ul>
        
<span id='urladress'>sallberglina.blogg.se</span>
 </div>  
 <div id='content'>
  
   <a name='a143753311'></a>
   <h3>Vanemänniska</h3>
   <div class='entrybody'>Lorem ipsum dolor sit amet. Lorem ipsum dolor. Lorem ipsum. Lorem.</div>
   <div class='entrybody'>hundarna flyger nakna</div>
   <div class='entrymeta'>
    2011-11-10 @ 17:27:58
    <a href='2011/november/vanemanniska.html'>Permalink</a>
    <a href='category/allmant.html'>Allmänt</a>
    <a href='2011/november/vanemanniska.html#comment'>Kommentarer (3)</a>
	<a href='2011/november/vanemanniska.html#trackback'>Trackbacks ()</a>
<a title='Lina Sällberg on Bloglovin' href='http://www.bloglovin.com/sv/blog/2226346/lina-sallberg/follow' style='margin-left: 2px;'><img alt='bloglovin' border='0' src='http://www.bloglovin.com/widget/bilder/sv/lank.gif' /></a>
	<br />
	
		<a href='' rel='tag'></a>
	
   </div>
   <hr class='separator' />
  

</div>

<img src='http://stats.blogg.se/?id=2426511' border='0' width='0' height='0' alt='' />


<script src='http://static.blogg.se/public/js/footer.js?id=1962144' type='text/javascript'></script>
</body>
</html>";
	//testar att skrapa en test-sida och ska få samma resultat som $this->html för att testet ska gå igenom
	public function TestScrapePage()
	{
		$_SESSION['element'] = '.entrybody';
		$_SESSION['cacheTime'] = 1;
		$db = new dbSettings("testscrapedpages", "localhost", "root", "");
		$db->connect();
		$wsm = new webscraperModel($db);
		$url = "http://127.0.0.1/BloggerTrender/test-page.html";	
		$this->assertEqual($this->html, $wsm->ScrapePage($url));
	}
	//testar om den plockar ut rätt ord. För att testet ska gå igenom så ska resultatet bli samma som $wordArray som innehåller
	//de ord som finns på testsidan
	public function TestGetWordsFromPage()
	{
		$_SESSION['element'] = '.entrybody';
		$_SESSION['cacheTime'] = 1;
		$db = new dbSettings("testscrapedpages", "localhost", "root", "");
		$db->connect();
		$wsm = new webscraperModel($db);
		$wom = new wordModel();
		$url = "http://127.0.0.1/BloggerTrender/test-page.html";
		$html = $wsm->scrapePage($url);
		$wordArray = array("lorem", "ipsum", "dolor", "sit", "amet", "lorem", "ipsum", "dolor", "lorem", "ipsum", "lorem", "hundarna", "flyger", "nakna");
		$this->assertEqual($wordArray, $wom->getWordsFromPage($html));
	}
	//testar om funktionen som tar bort punkter med mera fungerar
	public function TestToStripWordsFromDots()
	{
		$wom = new wordModel();
		$string1 = "Test?";
		$string2 = "Test!";
		$string3 = "Test.";
		$string4 = "Test!!!!?";
		$this->assertEqual("Test", $wom->removeDotsFromWords($string1));
		$this->assertEqual("Test", $wom->removeDotsFromWords($string2));
		$this->assertEqual("Test", $wom->removeDotsFromWords($string3));
		$this->assertEqual("Test", $wom->removeDotsFromWords($string4));
	}
	//testar om funktionen som gör ord till bara små bokstäver fungerar
	public function TestToOnlyGetLowerCases()
	{
		$wom = new wordModel();
		$string = "aWEirdSTRiNg";
		$this->assertEqual("aweirdstring", $wom->getOnlyLowerCases($string));
	}
	//testar om funktionen som räknar ut de mest använda orden fungerar. För att testet ska gå igenom ska resultatet matcha
	//$array som innehåller de tre använda orden i rätt ordning
	public function TestMostUsedWord()
	{
		$wom = new wordModel();
		$db = new dbSettings("testscrapedpages", "localhost", "root", "");
		$db->connect();
		$wsm = new webscraperModel($db);
		$url = "http://127.0.0.1/TwitterTrender/test-page.html";
		$html = $wsm->scrapePage($url);
		$wordArray = Array("Test", "AnotherTest", "TestAgain", "AnotherTest", "Test", "Test");
		$array = Array("Test", "AnotherTest", "TestAgain");
		$this->assertEqual($array, $wom->mostUsedWords($wordArray));
	}
	//testar om funktionen som tar bort vanliga ord fungerar med tio exempel
	public function TestRemoveCommonWords()
	{
		$wom = new wordModel();
		$commonWordArray = $wom->getArrayWithMostCommonWords();
		$this->assertTrue($wom->checkIfCommonWord("att", $commonWordArray));
		$this->assertTrue($wom->checkIfCommonWord("faktiskt", $commonWordArray));
		$this->assertTrue($wom->checkIfCommonWord("jag", $commonWordArray));
		$this->assertTrue($wom->checkIfCommonWord("i", $commonWordArray));
		$this->assertTrue($wom->checkIfCommonWord("bakom", $commonWordArray));
		$this->assertFalse($wom->checkIfCommonWord("Fotboll", $commonWordArray));
		$this->assertFalse($wom->checkIfCommonWord("Hund", $commonWordArray));
		$this->assertFalse($wom->checkIfCommonWord("monster", $commonWordArray));
		$this->assertFalse($wom->checkIfCommonWord("lYktstolpe", $commonWordArray));
		$this->assertFalse($wom->checkIfCommonWord("PLAYSTATION", $commonWordArray));
	}
	//testar om det fungerar att ta bort html-taggar
	public function TestToRemoveHTMLtags()
	{
		$wom = new wordModel();
		$imgstring = "<img src='#' alt='testbild' />";
		$pstring = "<p>hej</p>";
		$astring = "<a href='#'>test</a>";
		$this->assertEqual("", $wom->removeHTMLtags($imgstring));
		$this->assertEqual("hej", $wom->removeHTMLtags($pstring));
		$this->assertEqual("test", $wom->removeHTMLtags($astring));
	}
	//testar om ord som innehåller html-rester fungerar
	public function TestToRemoveRestOfHtml()
	{
		$wom = new wordModel();
		$testArr = Array('<style="text-align:"', 'test', '/>', 'testigen');
		$rightArr = Array('test', 'testigen');
		$this->assertTrue($wom->removeRestOfHtml('<style="text-align:"'));
		$this->assertTrue($wom->removeRestOfHtml('/>'));
		$this->assertFalse($wom->removeRestOfHtml('test'));
	}
	public function TestDatabase()
	{
		$db = new dbSettings("testscrapedpages", "localhost", "root", "");
		$db->connect();
		$i = new install($db);
		$wsm = new webscraperModel($db);
		$wom = new wordModel();
		$_SESSION['element'] = '.entrybody';
		$_SESSION['cacheTime'] = 1;
		if($i->DoesTableExist(self::table_name) == true)
		{
			$i->DropTable(self::table_name);
		}
		$this->assertFalse($i->DoesTableExist(self::table_name));
		if($i->CreateTable($this->sql));
		$this->assertTrue($i->DoesTableExist(self::table_name));
		//kollar om url finns, vilket den inte ska
		$testBlogg = "http://test.blogg.se";
		$this->assertFalse($wsm->DoesUrlExist($testBlogg));
		//lagrar en url och innehåll i databasen
		$renderdHtml = "<h2>De tio vanligaste orden:</h2><ul id='wordListUl'><li>1. test1</li><li>2. test2</li><li>3. test3</li><li>4. test4</li><li>5. test5</li><li>6. test6</li><li>7. test7</li><li>8. test8</li><li>9. test9</li><li>10. test10</li></ul>";
		$wsm->saveDataToDb($testBlogg, $renderdHtml);
		//kollar igen om en url finns, vilket den nu ska
		$this->assertTrue($wsm->DoesUrlExist($testBlogg));
		//testar om det går att hämta ut sparade ord från databasen
		$this->assertEqual($renderdHtml, $wsm->getWordsFromDb($testBlogg));
		//testar att uppdatera en Url
		$updatedHtml = "<h2>De tio vanligaste orden:</h2><ul id='wordListUl'><li>1. testUpdate1</li><li>2. test2</li><li>3. test3</li><li>4. test4</li><li>5. test5</li><li>6. test6</li><li>7. test7</li><li>8. test8</li><li>9. test9</li><li>10. test10</li></ul>";
		$wsm->updateUrl($testBlogg, $updatedHtml);
		$this->assertEqual($updatedHtml, $wsm->getWordsFromDb($testBlogg));
		//kollar om funktionen som kollar ifall det gått mer än en timme sedan senaste chachingen fungerar
		$time = $wsm->getTimeFromDb($testBlogg);
		
		$now = strtotime($time);
		$hour = time();
		$onehour = strtotime("+1 hour", $hour);
		$this->assertTrue($wom->checkTime($now, $onehour));
		$oneminute = strtotime("+1 minute", $hour);
		$this->assertFalse($wom->checkTime($now, $oneminute));
	}
	
}
	

?>