<?php

namespace App\Controller;

use App\Model\UserModel;

class UserController
{
  private $userModel;

  public function __construct(UserModel $userModel)
  {
    $this->userModel = $userModel;
  }

  public function createUser($username, $email, $password)
  {
    $this->userModel->username = $username;
    $this->userModel->email = $email;
    $this->userModel->password = password_hash($password, PASSWORD_DEFAULT);
    return $this->userModel->create();
  }
}
