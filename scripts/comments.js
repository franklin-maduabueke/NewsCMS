// JavaScript Document
var commentLimit = 500;

function  showDialog(dialogToShow, event, scrollTop)
{
	var docWidth = document.width;
	var docHeight = document.height;
	
	$('#popper').css({"width" : docWidth + "px", "height": docHeight + "px", "opacity" : 0.6}).fadeIn("fast", function () {
										   switch (dialogToShow)
										   {
											case "email":
												$("body").append('<div align="center" id="emailBoxHolder"><div id="emailInfoHolder"><div style="height:22px"></div><label style="margin-left:49px">To:</label> <input type="text" id="receiver" /><div style="clear:both;"></div><label style="margin-left:30px">From:</label> <input type="text" id="sender" /><div id="emailButtonHolder"><input type="button" class="emailDialogButton clickable" value="Send" id="sendButton" /><input type="button" class="emailDialogButton clickable" value="Cancel" id="cancelDialog" /></div></div></div>');
												
												$('div#emailBoxHolder').hide().css({'top': scrollTop + "px", 'left': "300px"}).fadeIn("normal", function () {
																						$('#cancelDialog').click( function () {
																									$('div#emailBoxHolder').hide(50, function () {
																											$('#popper').hide();
																											$("div#emailBoxHolder").replaceWith("");
																									 });
																															});
																						
																						$('#sendButton').click(function () {
																										$.post(prefix + "processing/emailArticle.php",
																											   {
																												   'receiver': $('#receiver')[0].value,
																											   	   'sender': $('#sender')[0].value
																											   },
																											   function (data)
																											   {
																												  if (data != "0")
																												  {
																													  
																												  }
																												  else
																												  {
																												  }
																												  $('#cancelDialog').click();
																											   }
																											   );
																														 });
																						
														 });
											break;
										   }
										   });
}

$().ready( function () {
					 $('#popper').hide();
					 
					 $('label#leftCount').text(commentLimit);
					 
					 $('textarea').keyup( function (event) {
													comment = $(this).val();
													/*
													if (comment.length >= commentLimit)
													{
														comment = comment.substr(0, commentLimit);
														$(this).val(comment);
													}
													*/
													leftCount = commentLimit - comment.length;
													$('label#leftCount').text(leftCount);
														
													});
					 
					 $('#clearTxt').click( function () {
													 $('textarea').val("");
													 $('label#leftCount').text(commentLimit);
													 });
					 
					 $('a#emailFriend').click( function (event) {
														 showDialog("email", event, window.pageYOffset + 100);
													 });
					 });