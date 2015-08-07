<?php
namespace Lib\User;
class Test implements UserInterface
{
  public function checkUser($username, $passwod)
  {
    return 1;
  }
}
