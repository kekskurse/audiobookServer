<?php
namespace Lib\Storage;
interface StorageInterface
{
    public function scan();
    public function __construct($config);
}
?>
