<?php

	//namespace Business;

	
	class Comment {
		
		public $commentId;
		public $articleId;
		public $userId;
		public $parentId;
		public $level;
		public $commentData;
		public $commentGuid;
		public $approved;
		public $approvedBy;
		public $approvedDate;
		public $approvedIpAddress;
		public $createdDate;
		public $createdBy;
		public $createdIpAddress;
		public $modifiedDate;
		public $modifiedBy;
		public $modifiedIpAddress;
		public $parentGuid;
		public $username;
		    private $db;


		
		public function __construct($commentId = 0) {
			
	
        
			if ($commentId != 0) {
				
				$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
				$stmt = $pdo->prepare("call comment_get('ByComment', ?, null, null)");
				$stmt->bindParam(1, $commentId);
				$stmt->execute();
				
				if ($stmt->rowCount() > 0) {
					$row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
					Comment::LoadObject($this, $row);
				}
				
				$stmt = null;
				$pdo = null;
			}
			else {
				$this->commentId = 0;
			}
			
		}
		
		private static function LoadObject($object, $row) {
			$object->commentId = $row['comment_id'];
			$object->articleId = $row['article_id'];
			$object->userId = $row['user_id'];
			$object->parentId = $row['parent_id'];
			$object->level = $row['level'];
			$object->commentData = $row['comment_data'];
			$object->commentGuid = $row['comment_guid'];
			$object->approved = ($row['approved'] == 1) ? true : false;
			$object->approvedBy = $row['approved_by'];
			$object->approvedDate = $row['approved_date'];
			$object->approvedIpAddress = $row['approved_ip_address'];
			$object->createdDate = $row['created_date'];
			$object->createdBy = $row['created_by'];
			$object->createdIpAddress = $row['created_ip_address'];
			$object->modifiedDate = $row['modified_date'];
			$object->modifiedBy = $row['modified_by'];
			$object->modifiedIpAddress = $row['modified_ip_address'];
			$object->parentGuid = $row['parent_guid'];
			$object->username = $row['username'];
		}
		
		public function Save($entityId, $ipAddress) {
			
			$objectId = 0;
			
			if ($this->commentId == 0) {
				$this->commentGuid = Comment::GUID();
				$this->approved = 1;
			}
			
			$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
			$stmt = $pdo->prepare('call comment_save(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
			$stmt->bindParam(1, $this->commentId);
			$stmt->bindParam(2, $this->articleId);
			$stmt->bindParam(3, $this->userId);
			$stmt->bindParam(4, $this->parentId);
			$stmt->bindParam(5, $this->level);
			$stmt->bindParam(6, $this->commentData);
			$stmt->bindParam(7, $this->commentGuid);
			$stmt->bindParam(8, $this->approved);
			$stmt->bindParam(9, $entityId);
			$stmt->bindParam(10, $ipAddress);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$objectId = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['object_id'];
			}
				
			$stmt = null;
			$pdo = null;
			
			return $objectId;
			
		}
		
		public static function GetByCommentGuid($commentGuid) {
			
			$comment = null;
			
			$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
			$stmt = $pdo->prepare("call comment_get('ByCommentGuid', null, ?, null)");
			$stmt->bindParam(1, $commentGuid);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$comment = new Comment();
				$row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
				$comment::LoadObject($comment, $row);
			}
			
			$stmt = null;
			$pdo = null;
			
			return $comment;

		}
		

		
		public static function GetComments($articleId) {
			
			$comments = Array();

			$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
			$stmt = $pdo->prepare("call comment_get('ByArticle', null, null, ?)");
			$stmt->bindParam(1, $articleId);
			$stmt->execute();
			
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$comment = new Comment();
				Comment::LoadObject($comment, $row);
				$comments[] = $comment;
			}
			
			$stmt = null;
			$pdo = null;
			
			return $comments;
		}
		
		
		private function GUID()
		{
			if (function_exists('com_create_guid') === true)
			{
				return trim(com_create_guid(), '{}');
			}

			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
		

	
	}
	
?>			