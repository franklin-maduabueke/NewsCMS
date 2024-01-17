// JavaScript Document
//script to show the Set Related Article link when an article has been selected by selecting the radio button of that row.

$().ready( function () {
					 $('#setRelatedArticleItem').hide(); //hide link
					 $('#publishedListingTable input[type^=radio]').click( function () {
											if (this.checked)
											{
												var theLink = $('#setRelatedArticleItem a').attr("href");
												var cutFrom = theLink.lastIndexOf("=");
												var articleId = this.value;
												
												if (cutFrom != -1)
												{
													var theCut = theLink.substr(0, cutFrom + 1);
													$('#setRelatedArticleItem a').attr("href", theCut + articleId);
													$('#setRelatedArticleItem').show();
												}
											}
							});
					 });