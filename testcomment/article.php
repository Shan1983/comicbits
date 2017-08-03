<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Article</title>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<style type="text/css">
		body {
			padding: 10px;
		}
		#divCommentForm {
			display: none;
		}
		.comment-list {
			padding: 0;
			margin: 0;
			list-style-type: none;
		}
		.comment-wrapper {
			margin-top: 10px;
			border: 1px solid;
			width: 400px;
			padding: 0 10px 10px 10px;
		}
		.comment-item-wrapper {
			margin-top: 10px;
		}
		.comment-loading {
			display: none;
			text-align: center;
			padding-top: 10px;
		}
		.comment-user {
			float: left;
			font-weight: bold;
			color: #a42828;
			font-size: 10pt;
		}
		.comment-time {
			padding-left: 15px;
			padding-top: 3px;
			float: left;
			font-weight: normal;
			color: #656c7a;
			font-size: 8pt;
		}
		.comment-text {
			margin-top: 2px;
			font-weight: normal;
			color: #333333;
			font-size: 9pt;
		}
		.comment-reply {
			margin-top: 2px;
			font-weight: normal;
			color: #656c7a;
			font-size: 8pt;
			cursor: pointer;
		}
		.comment-focus .comment-block {
			background-color: #eaeaea;
		}
		.no-comment {
			padding-top: 10px;
		}
	</style>
	
	<script>
		$( document ).ready(function() {
			
			loadComments();
			
			$(".comment-add").click(function() {
				$("#CommentGuid").val('');
				$(".comment-data").val('');
				$("#CommentModal").modal('show');
			});
			
			$(".comment-save").click(function() {
				if ($(".comment-data").val().trim() != '') {

					$("#CommentModal").modal('hide');	
					$(".comment-list").hide();
					$(".comment-loading").show();
					
					var params = {}
					params['Mode'] = "insert-comment";
					params['CommentData'] = $(".comment-data").val().trim();
					params['ArticleGuid'] = $("#ArticleGuid").val();
					params['CommentGuid'] = $("#CommentGuid").val();
					
					$.ajax({
						type: 'POST',
						url: 'https://ide.c9.io/shannan/comicbit/testcomment/CommentAjax.php',
						data: JSON.stringify(params),
						contentType: 'application/json; charset=utf-8',
						dataType: 'json',
						success: function (response) {
							console.log(response);
							$(".comment-loading").hide();
							processComments(response);
							$('.comment-focus').delay(3000).queue(function(){
								$(this).removeClass("comment-focus");
							});
						},
						error: function (xhr, status, error) {
							alert(error);
						}
					});
				}
				
			});
			
			$('body').on('click', '.comment-reply', function () {
				var commentGuid = $(this).attr("data-guid");
				$("#CommentGuid").val(commentGuid);
				$(".comment-data").val('');
				$("#CommentModal").modal('show');
			});

		});
		function loadComments() {
			$(".comment-loading").show();
			
			var params = {}
			params['Mode'] = "get-comments";
			params['ArticleGuid'] = $("#ArticleGuid").val();
			
			$.ajax({
				type: 'POST',
				url: 'https://ide.c9.io/shannan/comicbit/testcomment/CommentAjax.php',
				data: JSON.stringify(params),
				contentType: 'application/json; charset=utf-8',
				dataType: 'json',
				success: function (response) {
					$(".comment-loading").hide();
					processComments(response);
				},
				error: function (xhr, status, error) {
					alert(error);
				}
			});
		}
		
		function processComments(comments) {
			$(".comment-list").empty();
			processComment(comments, '');
			if ($('.comment-list li').length == 0) {
				$(".comment-list").append('<li class="no-comment">Be the first to comment.</li>');
			}
			$(".comment-list").show();
			if ($('.comment-focus').length > 0) {
				$("html,body").animate({scrollTop: $('.comment-focus').offset().top - 50});
			}
		}
		
		function processComment(comments, parentGuid) {
			$.each(comments, function(i, comment) {
				if (comment["parentGuid"] == parentGuid) {
					$(".comment-list").append(getCommentRow(comment));
					processComment(comments, comment["commentGuid"])
				}
			});
		}
		
		function getCommentRow(comment) {
			var colWidth = 12 - comment["level"];
			var colOffset = comment["level"];
			var h = '';
			if (comment["hasFocus"]) {
				h += '<li class="comment-focus">';
			}
			else {
				h += '<li>';
			}
			h += '<div class="row">';
			h += '<div class="col-xs-offset-' + colOffset + ' col-xs-' + colWidth + ' comment-item-wrapper">';
			h += '<div class="comment-block">';
			h += '<div class="comment-name-wrapper">';
			h += '<div class="comment-user"><i class="fa fa-user" aria-hidden="true"></i> ' + htmlEncode(comment["username"]) + '</div>';
			h += '<div class="comment-time"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + comment["postDateText"] + '</div>';
			h += '<div style="clear: both;"></div>';
			h += '</div>';
			h += '<div class="comment-text">' + htmlEncode(comment["commentData"]) + '</div>';
			if (comment["level"] < 8) {
				h += '<div class="comment-reply" data-guid="' + comment["commentGuid"] + '"><i class="fa fa-reply" aria-hidden="true"></i> Reply</div>';
			}
			h += '</div>';
			h += '</div>';
			h += '</div>';
			h += '</li>';
			return h;
		}
		
		function htmlEncode(value){
			return $('<div/>').text(value).html();
		}

	</script>
	
  </head>
  <body>
	<?php 
	$a = new Article(); 
	
	
	?>
	<input type="hidden" id="ArticleGuid" value="<?php echo $id; ?>" />
	<input type="hidden" id="CommentGuid" value="" />
	
	<button type="button" class="btn btn-primary comment-add">Add Comment</button>
	
	<div class="comment-wrapper">
		<div class="comment-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		<ul class="comment-list"></ul>
	</div>
	
	<div class="modal fade" id="CommentModal" role="dialog">
		<div class="modal-dialog modal-md">
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Add Comment</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-offset-1 col-xs-10"> 
						<div class="form-horizontal">
							<div class="form-group">
								  <textarea class="form-control input-sm comment-data" rows="3"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-primary comment-save">Submit</button>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	
	
	

    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>