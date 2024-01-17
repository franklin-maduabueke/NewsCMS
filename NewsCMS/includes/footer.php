<div id="footer">
	<div id="copyright">
		Copyright &copy; ISV 2016<?php $currentYear = date("Y"); if ( $currentYear != "2011" && $currentYear > "2011" ) echo " - " . date("Y")?>. All rights reserved.
	</div>
	<div id="visitorMonitor">
		<?php
			$visitDateResult = $dbConn->query("SELECT VisitDate FROM visitormonitor");
			
			//scan the data searching for the number of comma indicating the dates and likewise the hits on the site.
			$count = 0;
			for (; (($row = $visitDateResult->fetch_array()) != FALSE);)
			{
				$count += substr_count($row['VisitDate'], ",");
			}
			
			?>
			Site Hits: ( <?php echo number_format($count);?> )
	</div>
</div>