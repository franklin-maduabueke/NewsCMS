<?php
	//processing log notification
	
	class LogNotifier
	{
		//make connection by pre-created mysqli connection object.
		function __construct($conn)
		{
			$this->mConnection = $conn;
		}
		 
		public function logNotification($activity, $userId)
		{
			$sql = '';
			
			$sql = sprintf("INSERT INTO lognotification (UserID, Activity) VALUES(%d, '%s')", $userId, ucfirst(trim($activity)) . " on ". date("D j F, Y"). " at " . date("g:i A"));
				
			if ($this->mConnection)
			{
				$this->mConnection->query($sql);
					
				if ($this->mConnection->affected_rows > 0)
					return true;
				else
					return false;
			}
			else
				return false;	
		}
		
		private $mConnection;
		private $mActivity;
		private $mUserId;
	}
?>