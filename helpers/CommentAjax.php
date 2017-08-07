<?php
require_once('../config/config.php');
require('../core/init.php');
	
	$rest_json = file_get_contents("php://input");
	$_POST = json_decode($rest_json, true);

	$mode = isset($_POST['Mode']) ? $_POST['Mode'] : null;

	if ($mode == "insert-comment") {
	
		$commentId = 0;
		//get userid from session
		$userId = $_SESSION['user_id'];
		// get from some function
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		
		
		if ($userId != null) {
		
			$commentData = isset($_POST['CommentData']) ? $_POST['CommentData'] : null;
			$articleGuid = isset($_POST['ArticleGuid']) ? $_POST['ArticleGuid'] : null;
			$commentGuid = isset($_POST['CommentGuid']) ? $_POST['CommentGuid'] : null;
			
			$articleId = GetArticleId($articleGuid);
			$parentId = 0;
			$level = 0;
			
			//get info from parent comment
			if (!empty($commentGuid)) {
				$parentComment = Comment::GetByCommentGuid($commentGuid);
				if ($parentComment != null) {
					$parentId = $parentComment->commentId;
					$level = $parentComment->level + 1;
				}
			}
			
			//save comment.
			$comment = new Comment();
			$comment->commentId = 0;
			$comment->articleId = $articleId;
			$comment->userId = $userId;
			$comment->parentId = $parentId;
			$comment->level = $level;
			$comment->commentData = $commentData;
			$commentId = $comment->Save($userId, $ipAddress);
		
		}
		
		//get comments in json
		echo GetComments($articleId, $commentId);
	
	}
	else if ($mode == "get-comments") {
		
		//get comments in json
		$articleGuid = isset($_POST['ArticleGuid']) ? $_POST['ArticleGuid'] : null;
		$articleId = GetArticleId($articleGuid);
		echo GetComments($articleId);
	}
	else if ($mode == "moderate-comment") {
		
		$userId = $_SESSION['user_id'];
		$commentId = 0;
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		
		$articleGuid = isset($_POST['ArticleGuid']) ? $_POST['ArticleGuid'] : null;
		$articleId = GetArticleId($articleGuid);
		
		if ($userId != null && User::IsAdmin()) {
		
			$commentGuid = isset($_POST['CommentGuid']) ? $_POST['CommentGuid'] : null;
			$approved = isset($_POST['Approved']) ? $_POST['Approved'] : null;
			
			if (!empty($commentGuid)) {
				$comment = Comment::GetByCommentGuid($commentGuid);
				
				if ($comment != null) {
					$comment->approved = ($approved == "1") ? true : false;
					$commentId = $comment->Save($userId, $ipAddress);
				}
			}
		
		}

		echo GetComments($articleId, $commentId);
	}
	
	
	function GetComments($articleId, $focusCommentId = 0)
	{
		
		$userId = $_SESSION['user_id'];

		$commentModels = Array();
		$comments = Comment::GetComments($articleId);
		foreach ($comments as $comment) {
			$commentModel = new CommentModel();
			$commentModel->commentGuid = $comment->commentGuid;
			$commentModel->parentGuid = $comment->parentGuid;
				
			if ($comment->approved || ($userId != null && User::IsAdmin())) {
				$commentModel->commentData = $comment->commentData;
			}
			else {
				$commentModel->commentData = "[removed]";
			}
			
			$commentModel->level = $comment->level;
			$commentModel->username = $comment->username;
			$commentModel->postDateText = GetPostDateText($comment->createdDate);
			$commentModel->hasFocus = ($comment->commentId == $focusCommentId) ? true : false;
			$commentModel->approved = $comment->approved;
			$commentModels[] = $commentModel;
		}
		return json_encode($commentModels);
	}
	
	function GetPostDateText($createdDate)
	{
		$s = strtotime(date("Y-m-d H:i:s")) - strtotime($createdDate);
		$all = round($s / 60);
		$d = floor ($all / 1440);
		$h = floor (($all - $d * 1440) / 60);
		$m = $all - ($d * 1440) - ($h * 60);
	   
		if ($d > 0) {
		   return $d . (($d == 1) ? " day ago" : " days ago");
		}
		else if ($h > 0) {
		   return $h . (($h == 1) ? " hour ago" : " hours ago");
		}
		else if ($m > 0) {
		   return $m . (($m == 1) ? " min ago" : " mins ago");
		}
		else {
			// if 0 sec return 1
		   return (($s <= 1) ? "1 sec ago" : $s . " secs ago");
		}

	}
	
	function GetArticleId($articleGuid) {
		return $articleGuid;
	}
	
	class CommentModel {
		public $commentGuid;
		public $parentGuid;
		public $commentData;
		public $level;
		public $username;
		public $postDateText;
		public $hasFocus;
		public $approved;
	}
	
?>			