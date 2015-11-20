<?php
require_once(dirname(__FILE__).'/palbum_object.php');
require_once('settings.php');

class Photo extends AlbumObject {
  private $db_id, $filename, $date, $title, $description, $orientation;

  public function __construct($id) {
    global $db;
    
    if("array" == gettype($id)) {
      //IF #id is not photo id (is array), just set it.
      $this->db_id = $id['id'];
      $this->filename = $id['path_photo'];
      $this->filename = $this->filename.$id['filename'];
      $this->date = $id['datetaken'];
      $this->title = $id['title'];
      $this->description = $id['description'];
      $this->orientation = $id['orientation'];
    } else {
      //IF $id is photoid
      //Connect DB and fetch one record.
      $this->db_id = $id;

      $db->query("SELECT * FROM photo_view WHERE id = $1 ORDER BY id;", array($id));
      $result = $db->result_rows();

      $this->filename = $result['path_photo'];
      $this->filename = $this->filename.$result['filename'];
      $this->date = $result['datetaken'];
      $this->title = $result['title'];
      $this->description = $result['description'];
      $this->orientation = $result['orientation'];
    }
  }
  
  public function toHTMLthumbnail() {
    global $psqlAlbum;
    $style = $this->orientationToCSS($this->orientation);
    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['ThumbnailDir'];
  
    return 
<<<HEREDOC
<DIV class="grid250">
  <A href="index.php?pid=$this->db_id"><IMG class="thumbs" style="$style" src="$dir$this->filename" alt="$this->title"></A>
  <DIV class="title">$this->title</DIV>
  <DIV class="description">$this->description</DIV>
</DIV>
HEREDOC;
  }
  
  public function toHTMLlarge() {
    global $psqlAlbum;

    $style = $this->orientationToCSS($this->orientation);
    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['PhotoDir'];
  
    return 
<<<HEREDOC
<DIV>
  <IMG style="$style" src="$dir$this->filename" alt="$this->title">
  <DIV>$this->title</DIV>
  <DIV>$this->description</DIV>
  <DIV>$date</DIV>
</DIV>
HEREDOC;
  }

  public static function getObjectsInDateRange($datebegin, $dateend) {
    global $db;
    
    $db->query("SELECT id,filename,datetaken,title,description,orientation,path_photo FROM photo_view WHERE datetaken BETWEEN $1 AND $2 ORDER BY id;", array($datebegin, $dateend));
    while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $photos[] = new Photo($result);
    }
    
    return $photos;  
  }

  public static function getObjectsBySearchQuery($query) {
    global $db;

    // $db->query("SELECT photo_view.id AS id,filename,datetaken,photo_view.title AS title,photo_view.description AS description,orientation,path_photo FROM album,photo_view WHERE (photo_view.title LIKE $1 OR photo_view.description LIKE $1) ORDER BY photo_view.id;", array("%$query%"));
    $db->query("SELECT * FROM photo_view WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", array("%$query%"));
//SELECT * FROM photo_view WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", array("%$query%"));
     while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $photos[] = new Photo($result);
    }

    return $photos;
  }
  
  private function orientationToCSS($orientation) {
    switch ($orientation) {
      case "1":
        return;
      case "2":
        return "transform: rotateX( 180deg );";
      case "3":
        return "transform: rotate( 180deg );";
      case "4":
        return "transform: rotateY( 180deg );";
      case "5":
        return; //Undefined
      case "6":
        return "transform: rotate( 90deg );";
      case "7":
        return; //Undefined
      case "8":
        return "transform: rotate( 270deg );";
    }
  }

  public function getTitle() {
    return $this->title;
  }
  public function getDescription() {
    return $this->description;
  }
  public function getFilename() {
    return $this->filename;
  }
}
?>
