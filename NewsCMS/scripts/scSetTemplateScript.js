// JavaScript Document
//scripting to get a sections groups in setting up a subcategory tempaltes sections.

$().ready( function () {
			$('div.noticeBoard').hide();
			$('#loading').hide();
			$('select[name*=Choice]').change( function (){
														//use ajax to fetch data.

														groupElement = $(this).parent().next().find("select");
														groupOptions = $(this).parent().next().find("select option");
				
														$.post("../processing/scGetGroups.php", {"subcatGenID": $(this).val()}, function (data) {
																	if (data != "0")
																	{
																		groupOptions.remove();
																		groupElement.append("<option value='0'>None</option>");																		
																		$(data).find("group").each( function () {
																								 	groupElement.append("<option value='" + $(this).find("groupGenId:eq(0)").text() + "'>" + $(this).find("name:eq(0)").text() + "</option>");
																								 });
																	}
																	else
																	{
																		groupOptions.remove();
																		groupElement.append("<option value='0'>None</option>");
																	}
																	$('#loading').hide();
																	$('#popper').fadeOut('fast');
																});
														});
		  });