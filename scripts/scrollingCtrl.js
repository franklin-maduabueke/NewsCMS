//jQuery script for fetching breaking news.
// JavaScript Document
var headline_count;
var headline_interval;
var old_headline = 0;
var current_headline = 0;

var fetchInterval = 30000; //wait 30 seconds then get breaking news.
var fetchHandle = null;

var newsHeadings = new Array();
var newsSCGenID = new Array();
var newsCatGenID = new Array();
var newsArticleGenID = new Array();

var lastNews = 0;

function  fetchBreakingNews()
{
	window.clearInterval(fetchHandle);
	
	$.post(prefix + "processing/breakingNews.php", {}, function (data) {
									//if data not equal 0.
									if (data != "0")
									{
										newsSet = $(data).find("News");
										newsSet.each( function (index) {
																newsHeadings[index] = $(this).find("heading").text();
																newsSCGenID[index] = $(this).find("scgenid").text();
																newsCatGenID[index] = $(this).find("catgenid").text();
																newsArticleGenID[index] = $(this).find("articlegenid").text();
																});
										//hide the scroller the change news.
										$('#text_slide').hide();
										$('#mainNewsHolder a').attr("href", prefix + "processing/redirectToRead.php?sel=" + newsCatGenID[0] + "&sc=" + newsSCGenID[0] + "&article=" + newsArticleGenID[0]).text(newsHeadings[0]);
										lastNews = 0;
										$('#text_slide').show();
									}
									else
									{
									}
									fetchHandle = setInterval(fetchBreakingNews, fetchInterval);
							});
}


function headline_rotate() {
  current_headline = (old_headline + 1) % headline_count; 
  $("div.content:eq(" + old_headline + ")").animate({left: -800},3000, function() {
    $(this).css('left','687px').hide();
	//next news
	if ($(this).attr("id") == "mainNewsHolder")
	{
		//alternate news fetched
		if (lastNews < newsHeadings.length - 1)
		{
			lastNews++;
			if (newsCatGenID[lastNews])
				$('#mainNewsHolder a').attr("href", prefix + "processing/redirectToRead.php?sel=" + newsCatGenID[lastNews] + "&sc=" + newsSCGenID[lastNews] + "&article=" + newsArticleGenID[lastNews]).text(newsHeadings[lastNews]);
		}
		else
		{
			lastNews = 0;
			if (newsCatGenID[lastNews])
				$('#mainNewsHolder a').attr("href", prefix + "processing/redirectToRead.php?sel=" + newsCatGenID[lastNews] + "&sc=" + newsSCGenID[lastNews] + "&article=" + newsArticleGenID[lastNews]).text(newsHeadings[lastNews]);
		}
	}
	
    });
  
  $("div.content:eq(" + current_headline + ")").show().animate({left: 0},3000);  
  old_headline = current_headline;
}


$(document).ready(function(){
  $("div.content").hide().css({top: "0px",
  left: "687px"});
  
  fetchHandle = setInterval(fetchBreakingNews, fetchInterval);
  
  fetchBreakingNews();
  
  headline_count = $("div.content").length;
  $("div.content:eq("+current_headline+")").css('top','0px');
  
  headline_interval = setInterval(headline_rotate,7000); //time in milliseconds
  $('#text_slide').hover(function() {
    clearInterval(headline_interval);
  }, function() {
    headline_interval = setInterval(headline_rotate,7000); //time in milliseconds
    headline_rotate();
  });
});

