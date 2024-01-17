// JavaScript Document
//scripting the write article page.
var holderId = null;

function showDialog(jQueryObject)
{
	//found priority information.

	var docWidth = document.width;
	var docHeight = document.height;

	
	var popUp = $('#popUp');
	var popUpHeading = $('#popUpHeading');

	var photoFileObjectHolder = $('#photoFileObjectHolder');
	var videoFileObjectHolder = $('#videoFileObjectHolder');
	var flashFileObjectHolder = $('#flashFileObjectHolder');
	
	popper = $("#popper");
	popper.css({"width" : docWidth + "px", "height": docHeight + "px", "opacity" : 0.7});
	popper.show();

	popper.fadeIn('normal', function () {
	
		switch (jQueryObject.get(0).id)
		{
			case "flashButton":
				popUpHeading.text("Add Flash");
				//get number of file object currently contained.
				$('div.fileObjectHolder').hide();
				var count = flashFileObjectHolder.children('div.elementAttachment').length + 1;
				
				if (count <= 5)
				{
					flashFileObjectHolder.append("<div class='elementAttachment notCommited'><div class='entryLabel'>Flash " + count + "</div><input type='file' name='flashObject[]' class='fileObject' /></div>");
				}
				holderId = flashFileObjectHolder.show();
			break;
			case "photoButton":
				popUpHeading.text("Add Photo");
				//get number of file object currently contained.
				$('div.fileObjectHolder').hide();
				var count = photoFileObjectHolder.children('div.elementAttachment').length + 1;
				
				if (count <= 5)
				{
					photoFileObjectHolder.append("<div class='elementAttachment notCommited'><div class='entryLabel'>Photo " + count + "</div><input type='file' name='photoObject[]' class='fileObject' /></div>");
				}
				holderId = photoFileObjectHolder.show();
			break;
			case "videoButton":
				popUpHeading.text("Add Video");
				//get number of file object currently contained.
				$('div.fileObjectHolder').hide();
				var count = videoFileObjectHolder.children('div.elementAttachment').length + 1;
				
				if (count <= 5)
				{
					videoFileObjectHolder.append("<div class='elementAttachment notCommited'><div class='entryLabel'>Video " + count + "</div><input type='file' name='videoObject[]' class='fileObject' /></div>");
				}
				
				holderId = videoFileObjectHolder.show();
			break;
		}
		
		var pWidth = popUp.css('width').substr(0, popUp.css('width').length - 2);
		var pHeight = popUp.css('height').substr(0, popUp.css('height').length - 2);
		
		popUp.css({'top': (docHeight / 2) - (pHeight / 2) + 30 + 'px', 'left': (docWidth / 2) - (pWidth / 2) + 'px'});
		popUp.slideDown('normal');
									 }
	);

	$('#closePopUpButton').click( function () {
											//remove element with notCommited class
											holderId.children('.notCommited').remove();
											popUp.slideUp('fast', function () { popper.fadeOut('fast');});
											});
	
	$('#attachFileButton').click( function () {
											//check to see if file attached is valid.
											//change notCommited class to isCommited to indicate acceptance.
											holderId.children('.notCommited').removeClass('notCommited').addClass('isCommited');
											popUp.slideUp('fast', function () { popper.fadeOut('fast');});
											});
}

$().ready( function () {
					 	$('#popper').hide();
						$('#dlgShadow').hide();
						$('#popUp').hide();
						
					 	$('#publishButton').click(function () {
												//submit form
												//indicate in hidden element.
												$('#actionButton').get(0).value = "publish";
												$('form[name^=writeArticleForm]').get(0).submit();
											});
						
					 	$('#uploadButton').click( function () {
												//submit form
												//indicate in hidden element.
												$('#actionButton').get(0).value = "upload";
												$('form[name^=writeArticleForm]').get(0).submit();
											});
						
						$('div.wAButtonActiveArea').click( function () {
								if (this.id != "publishButton" && this.id != "uploadButton")
								{
									//check button name and add new file object to it
									//pass the selected button as a jQuery object for further use.
									showDialog($(this));
								}
						});
});