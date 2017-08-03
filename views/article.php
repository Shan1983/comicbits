

	
	<?php include('./partials/_header.php');
    if(!isset($_SESSION['is_logged_in'])){
    // send them packing
    redirect('index.php', "You need to be logged in to view this page!", 'warning');
    } ?>
<?php echo displayMessage(); ?>
<div class="jumbotron text-center">
    <h1 ><?php echo $article->title;?></h1>
    <p>
        <a href="articles.php?tag=<?php echo urlFormat($article->tag_id); ?>"
           class="label label-primary"><?php echo $article->name ?></a>
        | Published: <?php echo formatDate($article->created_at); ?>
        | By: <a href="articles.php?user=<?php echo urlFormat($article->user_id); ?>"><?php echo $article->username ?></a>
        | <?php echo calcReadTime($article->body); ?>
    </p>
</div>




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

			width: 100%;
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
						url: './testcomment/CommentAjax.php',
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
				url: './testcomment/CommentAjax.php',
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


<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($article->user_id == $_SESSION['user_id']) { ?>
                            <h4>Your article controls <small>(only you can see this!)</small></h4>
<!--                            <a href="#" class="btn btn-warning">Don't allow comments</a>-->
                                <?php if(userType($_SESSION['user_id']) == 'Admin') { ?>
                                    <form method="post" action="article.php?article=<?php echo $id ?>">
                                        <a href="edit.php?article=<?php echo $id ?>" class="btn btn-success">Edit article</a>
                                        <button name="do_remove_article" type="submit" class="btn btn-danger pull-right" value="<?php echo $id; ?>">Remove Article</button>
                                    </form>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <hr>
                            <?php } ?>
                            <div class="article-info">
                                <p class="article-info-body"><?php echo $article->body; ?></p>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($comments)) { ?>
                    <hr>
                    <h4 class="text-center">Comments <span class="badge"><?php echo count($comments); ?></span></h4>
                    <div class="clearfix"></div>
                    <br>
                    <?php foreach($comments as $comment) : ?>
                    <div class="col-md-2 pull-left">
                        <img src="images/avatars/<?php echo $comment->avatar; ?>" alt="user avatar" height="64" width="64">
                        <br><br>
                        <form method="post" action="article.php?article=<?php echo $comment->article_id;  ?>">
                            <button name="do_remove" type="submit" class="btn btn-danger" value="<?php echo $comment->article_id; ?>">Remove</button>
                        </form>
                    </div>
                    <div class="col-md-10">
                        <label for="username"><a href="articles.php?user=<?php echo $comment->user_id ?>"><?php echo $comment->username; ?></a></label>
                        <br>
                        <label for="date"><?php echo formatDate($comment->created_at); ?></label>
                    </div>
                    <div class="col-md-10 ">
                        <p><?php echo $comment->body; ?></p>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <?php endforeach;?>
                    <div class="clearfix"></div>
                    <h3>Add a new comment</h3>
                        <form role="form" method="post" action="article.php?article=<?php echo $_GET['article'] ?>">
                            <div class="form-group">
                                <textarea class="form-control" name="comment" rows="8" cols="80" placeholder="Your super helpful comment here.."></textarea>
                            </div>

                            <button name="lets_comment" type="submit" class="btn btn-primary">Leave a comment!</button>
                        </form>
                    <div class="clearfix"></div>
                    <?php }else { ?>
                        <div class="clearfix"></div>
                        <h3>Add a new comment</h3>
                        <form role="form" method="post" action="article.php?article=<?php echo $_GET['article'] ?>">
                            <div class="form-group">
                                <textarea class="form-control" name="comment" rows="8" cols="80" placeholder="Your super helpful comment here.."></textarea>
                            </div>

                            <button name="lets_comment" type="submit" class="btn btn-primary">Leave a comment!</button>
                        </form>
                        <div class="clearfix"></div>
                   <?php } ?>
                    <hr>
                    <p class="pull-right">Want to contribute? <a href="login.php">Login</a> or <a href="registeruser.php">Register</a></p>
                    <div class="clearfix"></div>
                    <hr>
                    
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
<?php include('./partials/_footer.php'); ?>