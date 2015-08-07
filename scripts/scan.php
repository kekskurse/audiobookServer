<?php
include(__DIR__."/../vendor/autoload.php");
#include(__DIR__."/../helper/url.php");
include(__DIR__."/../config/config.php");

#url::setURL("http://www.google.de");
#efine("SERVER_NAME", "localhost");
#$_SERVER["SERVER_NAME"]="localhost";

$scanresults = array();
foreach($storage as $s)
{
    $scanresults = array_merge($scanresults, $s["storage"]->scan(true));
}
$slowAccess->saveScan($scanresults);
