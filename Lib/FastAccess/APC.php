<?php
namespace Lib\FastAccess;
use \Lib\FastAccess\FastAccessInterface;
class APC implements FastAccessInterface
{
  public function __construct()
  {
  }
  public function setPosition($userid, $aaID, $track, $position)
  {
    apc_add("fs_".$userid."_".$aaID, ["track"=>$track, "position"=>$position]);
  }
  public function getPosition($userid, $aaID)
  {
    $detais = apc_fetch("fs_".$userid."_".$aaID);
    $detais["track"] = intval($detais["track"]);
    $detais["position"] = intval($detais["position"]);
    return $detais;
  }
  public function exists($userid, $aaID)
  {
    return apc_exists("fs_".$userid."_".$aaID);
  }
  public function save()
  {

  }
}
