<?php
$app->group("/v1", function () use ($app){
  $app->post("/ab/:aaid/position", function ($aaid) use ($app)
  {
    $track = $app->request->params("track", 1);
    $position = $app->request->params("position", 0);
    $app->fastAccess->setPosition($_SESSION["id"], $aaid, $track, $position);
  });
  $app->get("/ab/:aaid/position", function ($aaid) use ($app){
    $res =  ["track"=>1, "position"=>0];
    if($app->fastAccess->exists($_SESSION["id"], $aaid))
    {
      $res = $app->fastAccess->getPosition($_SESSION["id"], $aaid);
    }
    echo json_encode($res);
  });
  $app->get("/ab", function () use ($app){
    $res = $app->slowAccess->getAlbums();
    echo json_encode($res);
  });
  $app->get("/ab/:aaid", function ($aaid) use ($app) {
    $res = $app->slowAccess->getAlbum($aaid);
    echo json_encode($res);
  });
  $app->get("/ab/:aaid/:track", function ($aaid, $track) use ($app) {
    $track = $app->slowAccess->getTrack($aaid, $track);
    echo json_encode($track);
  });
  $app->get("/ab/:aaid/:track/mp3", function ($aaid, $track) use ($app) {
    $track = $app->slowAccess->getTrack($aaid, $track, true);
    if(isset($track["file"]))
    {
      $app->response->headers->set('Content-Type', 'audio/mpeg');
      $app->response->headers->set('Content-length', filesize($track["file"]));
      header('Content-Disposition: inline;filename="test.mp3"');
      header("Content-Transfer-Encoding: binary");
      header("Accept-Ranges: bytes");
      echo file_get_contents($track["file"]);
    }
    else {
      $app->halt(404);
    }
  });
  $app->get("/scan", function () use ($app) {
    if($app->enabledMirror)
    {
      echo json_encode($app->slowAccess->getKey("scan"));
    }
    else {
      echo json_encode([]);
    }
  });
  $app->post("/scan", function () use ($app){
    $scanresults = array();
    foreach($app->storage as $s)
    {
        $scanresults = array_merge($scanresults, $s["storage"]->scan());
    }
    $app->slowAccess->saveScan($scanresults);
  });
});
