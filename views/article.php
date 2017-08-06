
	<?php include('./partials/_header.php');
    // if(!isset($_SESSION['is_logged_in'])){
    // // send them packing
    // redirect('index.php', "You need to be logged in to view this page!", 'warning');
    // } 
    ?>
<?php echo displayMessage(); ?>
<div class="jumbotron text-center">
    <h1 ><?php echo $article->title;?></h1>
    <p>
        <a href="articles.php?tag=<?php echo urlFormat($article->tag_id); ?>"
           class="label label-primary"><?php echo $article->name ?></a>
        | Published: <?php echo formatDate($published); ?>
        | By: <a href="articles.php?user=<?php echo urlFormat($article->user_id); ?>"><?php echo $published_user ?></a>
        | <?php echo calcReadTime($article->body); ?>
    </p>
</div>



<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(userType($_SESSION['user_id']) == 'Admin') { ?>
                            <h4>Admin article controls </h4>
                                    <form method="post" action="article.php?article=<?php echo $id ?>">
                                        <a href="edit.php?article=<?php echo $id ?>" class="btn btn-info">Edit article</a>
                                        <?php if($article->isFeatured == 0) { ?>
                                        <button name="do_make_featured" type="submit" class="btn btn-success pull-right" value="<?php echo $id; ?>">Make Featured!</button>
                                        <?php }else { ?>
                                        <button name="do_remove_featured" type="submit" class="btn btn-warning pull-right" value="<?php echo $id; ?>">Remove Featured!</button>
                                        <?php } ?>
                                    </form>
                                    <hr>
                                    <div class="clearfix"></div>
                            <?php } else if($article->user_id == $_SESSION['user_id']) { ?>
                            <h4>Your article controls <small>(only you can see this!)</small></h4>
<!--                            <a href="#" class="btn btn-warning">Don't allow comments</a>-->
                                    <form method="post" action="article.php?article=<?php echo $id ?>">
                                        <a href="edit.php?article=<?php echo $id ?>" class="btn btn-success">Edit article</a>
                                    </form>

                            <div class="clearfix"></div>
                            <hr>
                            <?php } ?>
                            <div class="article-info">
                                <p class="article-info-body"><?php echo $article->body; ?></p>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                   <div class="clearfix"></div>
                    <hr>
                    <div class="clearfix"></div>
                    <input type="hidden" id="UserLoggedIn" value="<?php echo User::IsLoggedIn() ? "true" : "false"; ?>" />
                    <input type="hidden" id="ArticleGuid" value="<?php echo $id; ?>" />
	<input type="hidden" id="CommentGuid" value="" />
	<h2>Comments</h2>
	<div class="comment-add-button">
	    <button type="button" class="btn btn-primary comment-add">Add Comment</button>
	</div>
	<div class="comment-login">
	    <p><a href="/login.php">Login</a> to comment.</p>
	</div>
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
<?php include('./partials/_footer.php'); ?>