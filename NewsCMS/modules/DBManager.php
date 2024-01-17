<?php
	//this type is a manager for backup and recovery processing on databases
	//storing database data in xml file following the backup.xsd schema
	class DBManager
	{
		public function __construct($dbserver, $dbusername, $dbpassword, $dbname)
		{
			if (isset($dbserver, $dbusername, $dbpassword, $dbname))
			{
				$this->mDBConn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
				if ($this->mDBConn->connect_errno != 0)
					throw new Exception("Error: unable to access database with passed parameters");
				
				$this->mDBServer = $dbserver;
				$this->mDBUsername = $dbusername;
				$this->mDBPassword = $dbpassword;
				$this->mDBName = $dbname;
			}
			else
				throw new Exception("Error: unable to access database with passed parameters");
		}
		
		public function __destruct()
		{
			if (isset($this->mDBConn))
			{
				$this->mDBConn->close();
			}
		}
		
		//used to create backup files for a database
		//@param: $dbname , name of the database to backup.
		//this will try to connect to the $dbname assuming its on the same
		//host with the connection string given on creation of DBManager instance.
		public function backupDatabase($dbname)
		{
		}
		
		//used to recover a database with the file given
		//@param: $dbname, name of the database to recovery
		//@param: $recoveryFile, the recovery file to use
		//Note: checks to see if this file was sent via http
		//if not then check for file in backups folder of site
		public function recoverDatabase($dbname, $recoveryFile)
		{
		}
		
		private $mDBServer;
		private $mDBName;
		private $mDBUsername;
		private $mDBPassword;
		private $mDBConn;
	}
?>