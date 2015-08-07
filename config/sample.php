<?php

$fastAccess = new \Lib\FastAccess\Filesystem(__DIR__."/../cache/FastStorage/");
$slowAccess = new \Lib\SlowAccess\Filesystem(__DIR__."/../cache/SlowStorage/");



$storage = [];
$storage[] = [
  "name"=>"Local",
  "storage"=>new \Lib\Storage\Filesystem(
    [
      "path"=> ["/home/share/audiobook"],
      "link"=> ""#url::createURL(["ab", "_aaID_", "_trackID_", "mp3"])
    ]
  )
];

$user = new \Lib\User\One("username", "password");

#Enabled the Mirror Function, if is it enabled other Server can get the complete Scan result
$enabledMirror = false;
 ?>
