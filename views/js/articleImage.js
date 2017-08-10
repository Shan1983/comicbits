$( document ).ready(function() {
			
	$(".image-add").click(function() {
		$("#modalImageUpload").on('show.bs.modal', function(){
			resetImageModal();    
		}).modal('show');
	});
	
	$(".image-upload").click(function() {
		uploadBrowseImage();
	});
	
	$(".article-remove-image").click(function() {
		removeImage();
	});

	$(".file-upload").click(function() {
		$(".image-upload-error-alert").hide();
	});
	
	$(".drag-progress-bar").hide();
	$(".image-load-form").show();
	$(".image-loaded-form").hide();
	$(".article-image-display").hide();
	$(".image-drag-upload-error-alert").hide();
	
	
});

function resetImageModal() {
	$("#image-upload-spinner").hide();
	$(".image-upload-progress-bar").hide();
	$("#pbBrowseImageUpload").css('width', 0 + '%').attr('aria-valuenow', 0);
	$(".image-upload-error-alert").hide();
}

function removeImage() {
	$(".image-load-form").show();
	$(".image-loaded-form").hide();
	$("#hidArticleImage").val('');
	$(".article-image-display").hide();
	
}

function uploadBrowseImage() {
	var fileName = $("#fuImage").val();
	var errorMessage = '';

	if (fileName == '') {
		errorMessage = 'Please select an image to upload';
	}

	if (errorMessage == '') {
		$("#image-upload-spinner").show();
		$("#pbBrowseImageUpload").css('width', 0 + '%').attr('aria-valuenow', 0);
		$(".image-upload-progress-bar").show();
		
		var fileUpload = $("#fuImage").get(0);
		var files = fileUpload.files;
		
		uploadImage('browse', files);
	}
	else {
		displayBrowseImageUplodError(errorMessage);
	}
	
}


function uploadImage(mode, files) {
	
    var data = new FormData();
    for (var i = 0; i < files.length; i++) {
        data.append('file', files[i]);
    }
	
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
					if (mode == 'browse') {
						$("#pbBrowseImageUpload").css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
					}
					else {
						$("#pbDragImageUpload").css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
					}
                }
            }, false);
            return xhr;
        },
        url: "./helpers/ArticleImageAjax.php",
        type: "POST",
        data: data,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.lastIndexOf('ERROR:', 0) === 0) {
				if (mode == 'browse') {
					displayBrowseImageUplodError(result.replace('ERROR:', ''));
				}
				else {
					displayDragImageUplodError(result.replace('ERROR:', ''));
				}
            }
            else {
				if (mode == 'browse') {
					$("#pbImageProcess").css('width', '100%').attr('aria-valuenow', 100).addClass('progress-bar-full');
					setTimeout(function(){
					  $("#modalImageUpload").modal('hide');
					  imageUploaded(result);
					}, 500);
				}
				else {
				    $("#pbDragImageUpload").css('width', '100%').attr('aria-valuenow', 100).addClass('progress-bar-full');
					setTimeout(function(){
					  $(".drag-progress-bar").hide();
					  imageUploaded(result);
					}, 500);
				}
				$("#hidArticleImage").val(result);
			
            }
        },
        error: function (err) {
			if (mode == 'browse') {
				displayBrowseImageUplodError(err.statusText);
			}
			else {
				displayDragImageUplodError(err.statusText);
			}
        }
    });

}

function imageUploaded(imagefile) {
    $(".article-image-display").attr('src', '/images/article_images/' + imagefile);
	setTimeout(function(){
	  $(".article-image-display").show();
	}, 500);
	$(".image-load-form").hide();
	$(".image-loaded-form").show();
}

function displayBrowseImageUplodError(errorMessage) {
    $("#image-upload-spinner").hide();
    $(".image-upload-progress-bar").hide();
    $("#pbBrowseImageUpload").addClass("reseting").css("width", 0).attr('aria-valuenow', 0).removeClass("reseting");
    $(".image-upload-error-alert").show();
    $(".image-upload-error-message").html(errorMessage);
}

function displayDragImageUplodError(errorMessage) {
    $(".drag-progress-bar").hide();
    $("#pbDragImageUpload").addClass("reseting").css("width", 0).attr('aria-valuenow', 0).removeClass("reseting");
    $(".image-drag-upload-error-alert").show();
    $(".image-drag-upload-error-message").html(errorMessage);
    setTimeout(function(){
	    $(".image-drag-upload-error-alert").hide();
	}, 3000);
}

