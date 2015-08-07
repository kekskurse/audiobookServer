<?php
namespace Lib\Storage;
class Mirror implements StorageInterface
{
  public function __construct($config)
  {
    $this->config = $config;
  }
  public function scan()
  {
    $content = json_decode(file_get_contents("http://".$this->config["user"].":".$this->config["pass"]."@".$this->config["url"]), true);
    foreach($content as $artist => $v)
    {
      foreach($content[$artist] as $album => $v)
      {
        foreach($content[$artist][$album]["tracks"] as $track => $v)
        {
          unset($content[$artist][$album]["tracks"][$track]["file"]);
          $content[$artist][$album]["mirrored"] = True;
        }
      }
    }
    return $content;
  }
}
