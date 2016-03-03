<?php
require_once(dirname(__FILE__).'/palbum_object.php');
require_once('settings.php');

class Sound extends AlbumObject {
  private $db_id, $filename, $date, $title, $description, $length;

  public function __construct($id) {
    global $db, $psqlAlbum;
    
    if("array" == gettype($id)) {
      //引数idが配列の場合
      $this->db_id	= $id['id'];
      $this->filename	= $psqlAlbum['SoundDir'];
      $this->filename	= $this->filename.$id['filename'];
      $this->date	= $id['datetaken'];
      $this->title	= $id['title'];
      $this->description = $id['description'];
      $this->length	= $id['length'];
    } else {
      //引数idが数値(DBのPRIMARY KEY)の場合
      //Connect DB and fetch rows.
      $this->db_id = $id;

      $db->query("SELECT * FROM video WHERE id = $1;", array($id));
      $result = $db->result_rows();

      $this->filename	= $psqlAlbum['SoundDir'];
      $this->filename	= $this->filename.$result['filename'];
      $this->date	= $result['datetaken'];
      $this->title	= $result['title'];
      $this->description = $result['description'];
      $this->length	= $result['length'];
    }
  }

  public function toHTMLthumbnail() {
    global $psqlAlbum;
    $style ="";
    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['ThumbnailDir'];
  
    return 
<<<HEREDOC
<DIV class="album_object">
  <A href="index.php?sid=$this->db_id"><IMG class="thumbs" style="$style" src="$dir$this->filename" alt="$this->title"></A>
  <DIV class="title">$this->title</DIV>
  <DIV class="description">$this->description</DIV>
</DIV>
HEREDOC;
  }
  
  public function toHTMLlarge() {
    global $psqlAlbum;

    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['SoundDir'];

    $audiotag = "<AUDIO src=\"$this->filename\" alt=\"$this->title\" controls>";

    return 
<<<HEREDOC
<DIV>
  $audiotag
  <DIV>$this->title</DIV>
  <DIV>$this->description</DIV>
  <DIV>$date</DIV>
</DIV>
HEREDOC;
  }

  public static function getObjectsInDateRange($datebegin, $dateend) {
    global $db;
    
    $db->query(
//"SELECT video.id AS id,filename,datetaken,video.title AS title,video.description AS description,length,path_photo FROM album,video WHERE datetaken <= date_end AND datetaken >= date_begin AND datetaken BETWEEN $1 AND $2 ORDER BY video.id;"
<<<EOM
SELECT
  sound.id AS id
  ,filename
  ,datetaken
  ,sound.title AS title
  ,sound.description AS description
  ,length
  ,path_photo
FROM album,sound
WHERE datetaken <= date_end
  AND datetaken >= date_begin
  AND datetaken BETWEEN $1 AND $2
ORDER BY sound.id;
EOM
, array($datebegin, $dateend));
    while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $sounds[] = new Sound($result);
    }

    if($db->isNoResult()) {
      throw new Exception('AlbumObjectNotFound');
    }
    
    return $sounds;
  }

  public static function getObjectsBySearchQuery($query) {
    global $db;

    $db->query("SELECT * FROM sound WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", array("%$query%"));
     while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $sounds[] = new Sound($result);
    }

    if($db->isNoResult()) {
      throw new Exception('AlbumObjectNotFound');
    }

    return $sounds;
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
