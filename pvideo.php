<?php
require_once(dirname(__FILE__).'/palbum_object.php');
require_once('settings.php');

class Video extends AlbumObject {
  private $db_id, $filename, $date, $title, $description, $length;

  public function __construct($id) {
    global $db, $psqlAlbum;
    
    if("array" == gettype($id)) {
      $this->db_id = $id['id'];
      $this->filename = $psqlAlbum['VideoDir'];
      $this->filename = $this->filename.$id['filename'];
      $this->date = $id['datetaken'];
      $this->title = $id['title'];
      $this->description = $id['description'];
      $this->length = $id['length'];
    } else {
      //Connect DB and fetch rows.
      $this->db_id = $id;

      $db->query("SELECT video.id AS id,filename,datetaken,video.title AS title,video.description AS description,length,path_photo FROM album,video WHERE datetaken <= date_end AND datetaken >= date_begin AND video.id = $1 ORDER BY video.id;", array($id));
      $result = $db->result_rows();
      $this->filename = $psqlAlbum['VideoDir'];
      $this->filename = $this->filename.$result['filename'];
      $this->date = $result['datetaken'];
      $this->title = $result['title'];
      $this->description = $result['description'];
      $this->length = $result['length'];
    }
  }

  public function toHTMLthumbnail() {
    global $psqlAlbum;
    $style ="";
    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['ThumbnailDir'];
    $playbutton_url = $psqlAlbum['AlbumLibDir']."play-icon.png";
    $thumbnail_filename = str_replace("MP4", "JPG", $this->filename);
  
    return 
<<<HEREDOC
<DIV class="grid250">
  <A href="index.php?vid=$this->db_id"><IMG class="thumbs" style="$style" src="$dir$thumbnail_filename" alt="$this->title"><IMG class="playbutton"  src="$playbutton_url" alt="Play Button"></A>
  <DIV class="title">$this->title</DIV>
  <DIV class="description">$this->description</DIV>
</DIV>
HEREDOC;
  }
  
  public function toHTMLlarge() {
    global $psqlAlbum;

    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['VideoDir'];
  
    return 
<<<HEREDOC
<DIV>
  <VIDEO src="$this->filename" alt="$this->title">
  <DIV>$this->title</DIV>
  <DIV>$this->description</DIV>
  <DIV>$date</DIV>
</DIV>
HEREDOC;
  }

  public static function getObjectsInDateRange($datebegin, $dateend) {
    global $db;
    
    $db->query("SELECT video.id AS id,filename,datetaken,video.title AS title,video.description AS description,length,path_photo FROM album,video WHERE datetaken <= date_end AND datetaken >= date_begin AND datetaken BETWEEN $1 AND $2 ORDER BY video.id;", array($datebegin, $dateend));
    while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $videos[] = new Video($result);
    }
    
    return $videos;
  }

  public static function getObjectsBySearchQuery($query) {
    global $db;

    // $db->query("SELECT photo_view.id AS id,filename,datetaken,photo_view.title AS title,photo_view.description AS description,orientation,path_photo FROM album,photo_view WHERE (photo_view.title LIKE $1 OR photo_view.description LIKE $1) ORDER BY photo_view.id;", array("%$query%"));
    $db->query("SELECT * FROM video WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", array("%$query%"));
//SELECT * FROM photo_view WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", array("%$query%"));
     while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $videos[] = new Video($result);
    }

    return $videos;
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
