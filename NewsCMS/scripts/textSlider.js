// JavaScript Document
var headline_count;
var headline_interval;
var old_headline = 0;
var current_headline = 0;

$(document).ready(function(){
  $("div.content").hide().css({top: "0px",
  left: "687px"});
  
  headline_count = $("div.content").length;
  $("div.content:eq("+current_headline+")").css('top','5px');
  
  headline_interval = setInterval(headline_rotate,7000); //time in milliseconds
  $('#text_slide, #viewPage').hover(function() {
    clearInterval(headline_interval);
  }, function() {
    headline_interval = setInterval(headline_rotate,7000); //time in milliseconds
    headline_rotate();
  });
});

function headline_rotate() {
  current_headline = (old_headline + 1) % headline_count; 
  $("div.content:eq(" + old_headline + ")").animate({left: -800},3000, function() {
    $(this).css('left','687px').hide();
    });
  $("div.content:eq(" + current_headline + ")").show().animate({left: 0},3000);  
  old_headline = current_headline;
}
