// JavaScript Document

//script for the seach box of the header.
$().ready( function () {
					 $('#searchIcon').mouseover( function () {
														   	$(this).css('background-image',"url(../images/searchBtnHover.jpg)");
														   }).mouseout( function () {
															  $(this).css('background-image',"url(../images/searchBtnStay.jpg)");
														   }).click( function () {
															   var qindex = $('#headerSearchBox input').val().indexOf("Search");
															   
															   if ( qindex == -1)
															   {
															   		//redirect to search
																	window.location.href = "room.php?user=" + $('#userKey').val() + "&role=" + $('#roleKey').val() + "&tsk=search&q=" + $('#headerSearchBox input').val();
															   }
														   });
					 
					 $('#headerSearchBox').fadeTo('fast', 0.5);
					 
					 var searchDefaultValue = '';
					 $('#headerSearchBox input').click( function () {
																  	$('#headerSearchBox').fadeTo('fast',1.0);
																  	if (this.value.indexOf("Search") != -1) //found default string
																	{
														 				var box = $(this);
																  		searchDefaultValue = box.val();
																		box.val('');
																	}
																  }).focus(	function () {
																	$('#headerSearchBox').fadeTo('fast',1.0);
																	if (this.value.indexOf("Search") != -1) //found default string
																	{
														 				var box = $(this);
																  		searchDefaultValue = box.val();
																		box.val('');
																	}
																  }).blur( function () {
																	  if (this.value.indexOf("Search") != -1 || this.value.length == 0)
																	  {
																		$(this).val(searchDefaultValue);
																		$('#headerSearchBox').fadeTo('fast', 0.5);
																	  }
																  }
																  ).mouseover( function () {
																	  $('#headerSearchBox').fadeTo('fast',1.0);
																  }).mouseout( function () {
																	  if (this.value.indexOf("Search") != -1 || this.value.length == 0)
																	  {
																		$('#headerSearchBox').fadeTo('fast', 0.5);
																	  }
																  });
	});