$(document).ready(function() {

    // .article-info-featured 
    
    // Featured article image:   
//   var URL = //TODO
   
   $.ajax({
                type : 'POST',
				url: 'api/index.php', 
				contentType: 'application/json; charset=utf-8',
				dataType: 'json',
				success: function (response) {
				    
				    // load the featured articles background via ajax..
				    
				    $(".article-info-featured").each(function(index) {
				        var json = JSON.parse(response[index]);
				         console.log(json.article_image);
				        $(this).css("background-image", "url(images/article_images/" + json.article_image  + ")").delay(index * 200);
				    }); 
		
				},
				error: function (xhr, status, error) {
					console.log(error);
				}
			});
// 		}
   
    // var theDiv = $(".article-info-featured");
    // $(theDiv).css({
    //     "border-style" : "inset",
    //     "border-with" : "10px",
    //     "border-color" : "#6f6398"
    //     "background-image" : 'url("URL")'
    // });
    // // Convert the headline to uppercase
    // $(".article-info-featured > h3").css("text-transform", "uppercase");

});


