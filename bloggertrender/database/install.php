<?php
	class Install
	{
		protected $m_database = NULL;
		protected $m_error = "";
		public function __construct(dbSettings $database)
		{
			$this->m_database = $database;
		}
		public function GetError()
		{
			return $this->m_error;
		}
		public function CreateDatabase($name)
		{
			if($stmt = $this->m_database->PrepareStatement("CREATE DATABASE $name"))
			{
				
				if($stmt->execute() == false)
				{
					$this->m_error = $stmt->error;
					return false;
				}
				$stmt->Close();
			}
			return true;	
		}
		public function CreateTable($sql)
		{
			if($stmt = $this->m_database->PrepareStatement($sql))
			{
				if($stmt->execute() == false)
				{
					$this->m_error = $stmt->error;
					return false;
				}
				$stmt->Close();
			}
			return true;
		}
		public function DoesTableExist($name)
		{
			$name = $this->m_database->RealEscapeString($name);
			if($stmt = $this->m_database->PrepareStatement("Show tables like \"$name\""))
			{
				if($stmt->execute() == false)
				{
					$this->m_error = $stmt->error;
					return false;
				}
				$return = false;
				if($stmt->fetch())
				{
					$return = true;
				}
				$stmt->Close();
				return $return;
			}
			return false;
		}
		public function DropTable($name)
		{
			if($stmt = $this->m_database->PrepareStatement("Drop table $name"))
			{
				if ($stmt->execute() == false) {
				$this->m_error = $stmt->error;
		    	return false;
		    }
		    $stmt->close();
			}
		}
	}