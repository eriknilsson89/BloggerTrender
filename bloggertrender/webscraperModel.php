<?php
require_once("database/install.php");
class webscraperModel extends install{
	private $db = NULL;
	const table_name = "pages";
	private $sql = "CREATE TABLE pages
						(PK int(4) primary key NOT NULL AUTO_INCREMENT, 
						Url varchar(200) NOT NULL, 
						Words varchar(1000) NOT NULL,
						Time timestamp NOT NULL)";
	public function __construct($database)
	{
		parent::__construct($database);
		$this->db = $database;	
		if($this->DoesTableExist(self::table_name) == false)
		{
			$this->CreateTable($this->sql);
		}	
	}
	public function ScrapePage($url)
	{
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, true); 
		$html = curl_exec($ch);
		curl_close($ch);
		return $html; 
	}
	public function DoesUrlExist($url)
	{
		$result = false;
			/* create a prepared statement */
			if ($stmt = $this->db->PrepareStatement("SELECT * FROM pages WHERE Url = ?")) {
			
				$stmt->bind_param("s", $url);
				
			    /* execute query */
			    $stmt->execute();
			
			    $stmt->bind_result($pk, $url, $words, $time);
			 	while ($stmt->fetch()) {
				     $result = true;
				     break; 
			    }
			    
			    /* close statement */
			    $stmt->close();
			}
	
			return $result;
	}
	public function saveDataToDb($url, $html)
	{
			if($this->DoesUrlExist($url) == false)
			{
					
				/* create a prepared statement */
				if ($stmt = $this->db->PrepareStatement("INSERT INTO pages (Url, Words, Time) VALUES(?, ?, ?)")) 
				{

				    /* bind parameters for markers */
				    $stmt->bind_param("ssi", $url, $html, $time);
				
				    /* execute query */
				    $stmt->execute();
		
				    /* close statement */
				    $stmt->close();
				    
				    return true;
				}

				
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
			return false;
	}
	public function getWordsFromDb($url)
	{
		$theWords = "";
		if($this->DoesUrlExist($url))
		{
			/* create a prepared statement */
			if ($stmt = $this->db->PrepareStatement("SELECT Words FROM pages WHERE Url = ?")) {
			
				$stmt->bind_param("s", $url);
			
			    /* execute query */
			    $stmt->execute();
			    
			    $stmt->bind_result($words);
			    while ($stmt->fetch()) {
				     $theWords = $words;
				     break; 
			    }
			    /* close statement */
			    $stmt->close();
			}
			return $theWords;
		}
		return false;
	}
	public function updateUrl($url, $html)
	{
		if($this->DoesUrlExist($url))
			{
					
				/* create a prepared statement */
				if ($stmt = $this->db->PrepareStatement("UPDATE pages SET Words = ? WHERE Url = ?")) 
				{
				    /* bind parameters for markers */
				    $stmt->bind_param("ss", $html, $url);
				
				    /* execute query */
				    $stmt->execute();
		
				    /* close statement */
				    $stmt->close();
				    
				    return true;
				}

				
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
			return false;
	}
	public function getTimeFromDb($url)
	{
		$theTime = "";
		if($this->DoesUrlExist($url))
		{
			/* create a prepared statement */
			if ($stmt = $this->db->PrepareStatement("SELECT Time FROM pages WHERE Url = ?")) {
			
				$stmt->bind_param("s", $url);
			
			    /* execute query */
			    $stmt->execute();
			    
			    $stmt->bind_result($time);
			    while ($stmt->fetch()) {
				     $theTime = $time;
				     break; 
			    }
			    /* close statement */
			    $stmt->close();
			}
			return $theTime;
		}
		return false;
	}
	
}
?>