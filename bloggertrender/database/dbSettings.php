<?php

class dbSettings {
	private $dbName = "";
	private $dbUser = "";
	private $dbPass = "";
	private $myDatabaseName = "";
	private $mySqliObject = NULL;
	
	public function __construct($database, $dbName, $dbUser, $dbPass) {
		$this->myDatabaseName = $database;
		$this->dbName = $dbName;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
	}
	
	public function Connect() {
		$this->mySqliObject = new mysqli($this->dbName, $this->dbUser, $this->dbPass, $this->myDatabaseName);
		
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
	}
	
	public function Close() {
		$this->mySqliObject->close();
	}
	
	public function PrepareStatement($sqlStatement) {
		return $this->mySqliObject->prepare($sqlStatement);
	}
	//funktion som tar bort oönskade tecken i en textsträng för att undvika sql injections
	public function RealEscapeString($dataString)
	{
		return $this->mySqliObject->real_escape_string($dataString);
	}
}