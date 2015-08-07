<?php
namespace Lib\User;
class One implements UserInterface
{
  public function __construct($username, $password)
  {
    $this->username = $username;
    $this->password = $password;
  }
  public function checkUser($username, $password)
  {
    if($username==$this->username && $password == $this->password)
    {
      return 1;
    }
    return false;
  }
}
