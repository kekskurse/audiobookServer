<?php
namespace Lib\SlowAccess;
class Filesystem implements SlowAccessInterface
{
  public function __construct($path)
  {
    if(!is_dir($path))
    {
      mkdir($path, 0777, true);
    }
    if(!is_dir($path."/keyValueStorage"))
    {
      mkdir($path."/keyValueStorage", 0777, true);
    }
    $this->path = $path;
  }
  public function saveKey($key, $value)
  {
    $value = json_encode($value);
    file_put_contents($this->path."/keyValueStorage/".md5($key), $value);
  }
  public function getKey($key)
  {
    $r = file_get_contents($this->path."/keyValueStorage/".md5($key));
    $r = json_decode($r, true);
    return $r;
  }
  public function saveScan($scan)
  {
    $this->saveKey("scan", $scan);
  }
  public function getAlbums()
  {
    $detais = $this->getKey("scan");
    foreach($detais as $artist => $v)
    {
      foreach($detais[$artist] as $album =>$v)
      {
        unset($detais[$artist][$album]["tracks"]);
      }
    }
    return $detais;

  }
  public function getAlbum($aaID, $details = false)
  {
    var_dump($aaID);
    $detais = $this->getKey("scan");
    $ralbum = NULL;
    foreach($detais as $artist => $v)
    {
      foreach($detais[$artist] as $album =>$v)
      {
        if($detais[$artist][$album]["aaID"]==$aaID)
        {
          #var_dump($detais[$artist][$album]["aaID"]);
          #var_dump($detais[$artist][$album]);exit();
          $ralbum =  $detais[$artist][$album];
          break;
        }
      }
    }
    if($album==NULL)
    {
      throw new \Exception("Error Processing Request", 1);

    }
    if(!$details)
    {
      #var_dump($ralbum);
      foreach($ralbum["tracks"] as $key => $v)
      {
        unset($ralbum["tracks"][$key]["file"]);
      }
    }
    return $ralbum;
  }
  public function getTrack($aaID, $trackID, $details = false)
  {
    $detais = $this->getKey("scan");
    $album = NULL;
    foreach($detais as $artist => $v)
    {
      foreach($detais[$artist] as $album =>$v)
      {
        if($detais[$artist][$album]["aaID"] == $aaID)
        {
          $album = $detais[$artist][$album];
          break 2;
        }
      }
    }
    foreach($album["tracks"] as $track)
    {
      if($track["trackNumber"] == $trackID)
      {
        if(!$details)
        {
          unset($track["file"]);
        }
        return $track;
      }
    }
  }
}
