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
						url: './helpers/CommentAjax.php',
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
				url: './helpers/CommentAjax.php', 
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