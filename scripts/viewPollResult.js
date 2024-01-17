// JavaScript Document
//script to get the poll results
var fetchInterval = 30000; //fetch every 30 seconds.
var fetchHnd = null;

function  getPollResult()
{
	pollId = $('#pollGenId').val();
	$.post(prefix + "processing/pollResult.php", {"pollId": pollId}, function (data) {
														if (data != "0")
														{
															yesVotes = $(data).find("yes").text();
															noVotes = $(data).find("no").text();
															$('#yesVotesResult div.valueHolder').text(yesVotes);
															$('#noVotesResult div.valueHolder').text(noVotes);
														}
											});
}

$().ready( function () {
					 $('#pollResultHolder').hide();
					 $('#viewPollResults').click( function (event) {
															event.preventDefault();
															if ($('#viewPollResults').text() == "View Results")
															{
																 $('#pollResultHolder').fadeIn('slow', function (){
																									$('#viewPollResults').text("Close Results");
																									//start interval
																									fetchHnd = window.setInterval(getPollResult, fetchInterval);	 
																								});
															}
															else
															{
																$('#pollResultHolder').fadeOut('fast', function (){
																									$('#viewPollResults').text("View Results");
																									//start interval
																									window.clearInterval(fetchHnd);	 
																								});
															}
												});
	});