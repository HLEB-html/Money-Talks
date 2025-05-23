<?php

namespace App\Model;

use PDO;

class PostModel
{
  private $conn;
  public $title;
  public $content;
  public $image;
  public $user_id;

  public function __construct(PDO $conn)
  {
    $this->conn = $conn;
  }

  public function addPost()
  {
    $sql = "INSERT INTO posts (title, content, image, user_id) VALUES (:title, :content, :image, :user_id)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':content', $this->content);
    $stmt->bindParam(':image', $this->image);
    $stmt->bindParam(':user_id', $this->user_id);
    return $stmt->execute();
  }
}
