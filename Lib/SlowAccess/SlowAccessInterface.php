<?php
namespace Lib\SlowAccess;
interface SlowAccessInterface
{
  public function saveKey($key, $value);
  public function getKey($key);
  public function saveScan($scan);
  public function getTrack($aaID, $trackID, $details = false);
  public function getAlbums();
  public function getAlbum($aaID, $details = false);
}
?>
