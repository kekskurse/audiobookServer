<?php
namespace Lib\FastAccess;
use \Lib\FastAccess\FastAccessInterface;
class Filesystem implements FastAccessInterface
{
  public function __construct($path)
  {
    if(!is_dir($path))
    {
      mkdir($path, 0777, true);
    }
    $this->path = $path;
  }
  public function setPosition($userid, $aaID, $track, $position)
  {
    $filename = $this->path."/".$userid."_".$aaID.".json";
    file_put_contents($filename, json_encode(["track"=>$track, "position"=>$position]));
  }
  public function getPosition($userid, $aaID)
  {
    $filename = $this->path."/".$userid."_".$aaID.".json";
    $detais = json_decode(file_get_contents($filename), true);
    $detais["track"] = intval($detais["track"]);
    $detais["position"] = intval($detais["position"]);
    return $detais;
  }
  public function exists($userid, $aaID)
  {
    $filename = $this->path."/".$userid."_".$aaID.".json";
    if(file_exists($filename))
    {
      return true;
    }
    return false;
  }
}
