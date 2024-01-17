// JavaScript Document
//script to change the currently viewed subcategory from the taskSwitcher.
$().ready( function () {
					 //change ths sc= parameter to that of the selected object.
					 $('#switchSCSelector').change( function () {
															
															  snip = "&sc=";
															  formActionString = $('#changeSubcategoryToViewForm').attr('action');
															  scQueryIndex = formActionString.indexOf(snip);
															  
															  if (scQueryIndex != -1)
															  {
																  //get the value
																  changeSC = $(this).val();
																  preSC = formActionString.substr(0 , scQueryIndex + snip.length);
																  postSC = formActionString.substr(scQueryIndex + snip.length + 8);
																  redirectTo = preSC + changeSC + postSC;
																 $('#changeSubcategoryToViewForm').attr('action', redirectTo);
																 $('#changeSubcategoryToViewForm').get(0).submit();
															  }
													});
					 });