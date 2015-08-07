<?php
namespace Lib\FastAccess;
interface FastAccessInterface
{
    public function setPosition($userid, $aaID, $track, $position);
    public function getPosition($userid, $aaID);
    public function exists($userid, $aaid);
}
?>
