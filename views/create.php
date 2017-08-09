<?php include('./partials/_header.php');
    // first check if the user is logged in
    if(!isset($_SESSION['is_logged_in'])){
    // send them packing
    redirect('index.php', "You need to be logged in to view this page!", 'warning');
    }

    ?>
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
                            <input type="file" name="article-image">
                            <p class="help-block"></p>
                        </div>
<!--        <div class="form-group">-->
<!--            <label for="avatar">Upload article header image</label>-->
<!--            <input type="file" name="password-confirm">-->
<!--        </div>-->
        <div class="form-group">
            <label>Article Body: </label>
            <textarea id="body" rows="10" cols="80" class="form-control" name="body"></textarea>
        </div>
        <button name="lets_create" id="mainBtn" type="submit" class="btn btn-success pull-right">Create Article!</button>
        <div class="clearfix"></div>
    </form>

<?php include('./partials/_footer.php'); ?>