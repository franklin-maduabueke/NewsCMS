// JavaScript Document
$().ready( function () {
					 $('#uploadImageAdvertButton').click( function () {
											//validate.
											if ($('#imageAdvertClientPIN').val().length == 0)
											{
												alert("Please enter the PIN of the client who has this advert.");
												return;
											}
											if ($('#imageAdvertLink').val().length == 0)
											{
												alert("Please enter the url to direct to.");
												return;
											}

											$('#imageAdsForm').get(0).submit();
												
					});
					 
					 $('#uploadFlashAdvertButton').click( function () {
											//validate.
											if ($('#flashAdvertClientPIN').val().length == 0)
											{
												alert("Please enter the name of the client who has this advert.");
												return;
											}
											
											$('#flashAdsForm').get(0).submit();
								});
});