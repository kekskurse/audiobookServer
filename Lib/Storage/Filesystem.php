<?php
namespace Lib\Storage;
class Filesystem implements StorageInterface
{
  public function __construct($config)
  {
    $this->config = $config;
  }
  public function scan($print = false)
  {
    if($print) { echo "Start\r\n"; }
    $id3Tags = array();
    foreach($this->config["path"] as $path)
    {
      if($print) { echo "Scan ".$path."\r\n"; }
      $files = $this->scanDir($path);
      foreach($files as $f)
      {
        if($print) { echo "ID3 ".$f."\r\n"; }
        $r = $this->scanID3($f);
        if($r!==false)
        {
          $id3Tags[] = $r;
        }
      }
    }
    if($print) { echo "Merge\r\n"; }
    $id3Tags = $this->mergeId3($id3Tags);
    return $id3Tags;
  }
  public function mergeId3($id3tags)
  {
    $res = array();
    foreach($id3tags as $tag)
    {
      #var_dump($tag);exit();
      $newtag = $tag;
      #$newtag["aaID"] = md5($tag["artist"]."_".$tag["album"]);
      unset($newtag["artist"]);
      unset($newtag["album"]);
      $res[$tag["artist"]][$tag["album"]]["tracks"][] = $newtag;
      if(!isset($res[$tag["artist"]][$tag["album"]]["aaID"]))
      {
        $res[$tag["artist"]][$tag["album"]]["aaID"] = md5($tag["artist"]."_".$tag["album"]);
      }
    }
    return $res;
  }
  private function scanID3($file)
  {
    $res = false;
    $getID3 = new \getID3;
    $id3 = $getID3->analyze($file);
    if(isset($id3["fileformat"]) && $id3["fileformat"]=="mp3")
    {
      $res = array();
      $res["file"] = $file;

      $res["album"] = $id3["tags"]["id3v2"]["album"][0];
      $res["title"] = $id3["tags"]["id3v2"]["title"][0];
      if(isset($id3["tags"]["id3v2"]["artist"][0]))
      {
        $res["artist"] = $id3["tags"]["id3v2"]["artist"][0];
      }
      else {
        $res["artist"]="Unknown";
      }
      $res["trackNumber"] = $id3["tags"]["id3v2"]["track_number"][0];
      $res["link"] = str_replace(["_aaID_", "_trackID_"], [md5($res["artist"]."_".$res["album"]), $res["trackNumber"]], $this->config["link"]);
      if(strpos($res["trackNumber"], "/"))
      {
        $res["trackNumber"] = substr($res["trackNumber"], 0, strpos($res["trackNumber"], "/"));
      }
      $res["trackNumber"] = sprintf("%04d", $res["trackNumber"]);
    }
    else {
      #var_dump($file);
    }
    return $res;
  }
  private function scanDir($path, $onlyMP3 = true)
  {
    $res = scandir($path);
    $r = array();
    foreach($res as $re)
    {
      if(substr($re, 0, 1)!=".")
      {
        if(is_dir($path."/".$re))
        {
          $r = array_merge($r, $this->scanDir($path."/".$re));
        }
        else {
          if($onlyMP3 == false || substr($re, strrpos($re, ".")+1))
          {
            $r[] = $path."/".$re;
          }
        }
      }
    }
    return $r;
  }
}
