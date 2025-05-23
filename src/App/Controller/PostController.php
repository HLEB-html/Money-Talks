<?php

namespace App\Controller;

use App\Model\PostModel;

class PostController
{

  private $postModel;

  public function __construct(PostModel $postModel)
  {
    $this->postModel = $postModel;
  }

  public function createPost($title, $content, $image, $user_id)
  {
    $this->postModel->title = $title;
    $this->postModel->content = $content;
    $this->postModel->image = $image;
    $this->postModel->user_id = $user_id;
    return $this->postModel->addPost();
  }
}
