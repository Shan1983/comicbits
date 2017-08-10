<?php include('./partials/_header.php');
    // first check if the user is logged in
    if(!isset($_SESSION['is_logged_in'])){
    // send them packing
    redirect('index.php', "You need to be logged in to view this page!", 'warning');
    }

    ?>
    
    
    <style type="text/css">

		.article-image-display {
			max-width: 100%;
			max-height: 100%;
		}
		.article-remove-image {
			position: absolute;
			top: 3px;
			right: 8px;
			cursor: pointer;
		}
		.drag-progress-bar {
		    margin-bottom: 0 !important;
		    margin-top: 10px;
		}
	</style>
    
<div class="jumbotron text-center">
    <h1 >Help us make the Best. Blog. Ever!</h1>
    <p >Simply fill out the form below using the friendly text editor!</p>
</div>
<?php echo displayMessage(); ?>
<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block"> 
                    <div class="article-header">
                    <h3 class="pull-left">Create something amazing!</h3>
                    </div>

    <form id="create" enctype="multipart/form-data" role="form" method="post" action="create.php">
        <div class="form-group">
            <label>Article Title:</label>
            <input type="text" class="form-control" name="title" placeholder="Give your article a title">
        </div>
        <div class="form-group">
            <label>Tag:</label>

            <select name="tag" class="form-control">
                <option value="none">Please select a tag</option>
                <?php foreach (getTags() as $tag) { ?>
                    <option value="<?php echo $tag->id; ?>"><?php echo $tag->name; ?></option>
                <?php } ?>

            </select>
        </div>
        <div class="form-group">
            <label>Upload article image <small>(Optional)</small></label>

            <input type="hidden" id="hidArticleImage" name="hidArticleImage" value="" />
            
            <div class="image-load-form">
                <div style="border: 1px solid #cccccc; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary image-add">Add Image</button>
                        </div>
                        <div class="col-md-8">
                            <div id="dropZone" style="border:1px dashed #666666; height: 80px; text-align: center; padding-top: 30px; color: #666666;">
                                   Drag Image Here
                            </div>
                            <div class="progress drag-progress-bar">
                        		<div id="pbDragImageUpload" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="image-loaded-form" style="border: 1px solid #cccccc; padding: 15px; border-radius: 4px; margin-bottom: 15px; position: relative; height: 100px; text-align: center;">
	            <img class="article-image-display" src="" />
	            <div class="article-remove-image"><i class="fa fa-times" aria-hidden="true"></i></div>
            </div>
            <script type="text/javascript">
                var dropZone = document.getElementById('dropZone');
        		dropZone.addEventListener('dragover', function(e) {
        			e.stopPropagation();
        			e.preventDefault();
        			e.dataTransfer.dropEffect = 'copy';
        		});
        		dropZone.addEventListener('drop', function(e) {
        			e.stopPropagation();
        			e.preventDefault();
        			var files = e.dataTransfer.files;
        
        			$("#pbDragImageUpload").css('width', 0 + '%').attr('aria-valuenow', 0);
        			$(".drag-progress-bar").show();
        			uploadImage('drag', files);
        			
        		});
            </script>
             </div>           
                    
        <div class="form-group">
            <label>Article Body: </label>
            <textarea id="body" rows="10" cols="80" class="form-control" name="body"></textarea>
        </div>
        <button name="lets_create" id="mainBtn" type="submit" class="btn btn-success pull-right">Create Article!</button>
        <div class="clearfix"></div>
    </form>
    
    <div id="modalImageUpload" class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><span id="image-upload-spinner"><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>&nbsp;</span>Image Upload</h4>
				</div>
				<div class="modal-body">
				<p>Select the image you would like to upload.</p>
					<div class="alert alert-danger image-upload-error-alert">
						<div class="image-upload-error-message"></div>
					</div>
				   <div class="form-group">
						<input type="file" id="fuImage" class="file-upload filestyle" />
					</div>
                    <div class="image-upload-progress-bar">
    				    <div class="progress">
    						<div id="pbBrowseImageUpload" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    					</div>
    				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary image-upload">Submit</button>
				</div>
			</div>
		</div>
	</div>

<?php include('./partials/_footer.php'); ?>