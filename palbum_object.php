<?php
require_once(dirname(__FILE__).'/pdbconn.php');

abstract class AlbumObject {
  abstract public function toHTMLthumbnail();
  abstract public function toHTMLlarge();

  abstract public function getTitle();
  abstract public function getDescription();
  abstract public function getFilename();
}
?>
