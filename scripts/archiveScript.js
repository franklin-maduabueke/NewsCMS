// JavaScript Document
$().ready( function () {
			$('#searchControlSubmitBtn, #searchControlTitleSubmitBtn').click( function () {
				var category = $('#searchCategoryOption').val();
				var subcategory = $('#searchSubcategoryOption').val();
				var year = $('#searchPublishYearOption').val();
				var title = $('#searchByTitleTxt').val();
				
				window.location.href = prefix + "archive.php?cat=" + category + "&sc=" + subcategory + "&py=" + year + "&q=" + title;
			});
			
			$('#searchCategoryOption').change( function () {
							var category = $('#searchCategoryOption').val();
							window.location.href = prefix + "archive.php?cat=" + category;
						}
				);
	});