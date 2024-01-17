// JavaScript Document
//script to stop the default action of anchors that have a delete action.
$().ready( function () {
					 //stop all deletes from processding till confirmation has been done by user.
					 $('a[href*=delete]').click( function (event) { 
														   if (!window.confirm("Do you want to perform the delete action ( " + $(this).text() + " ) ?"))
														   {
															  event.preventDefault();
														   }
														 });
					 });