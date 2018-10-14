<?php
require_once(dirname(__FILE__).'/palbum_object.php');
require_once('settings.php');

class Video extends AlbumObject implements AlbumObjectInterface {
  private $db_id, $filename, $date, $title, $description, $length, $youtube_id;

  public function __construct($id) {
    global $db, $psqlAlbum;
    
    if("array" == gettype($id)) {
      //引数idが配列の場合
      $this->db_id	= $id['id'];
      $this->filename	= $psqlAlbum['VideoDir'];
      $this->filename	= $this->filename.$id['filename'];
      $this->date	= $id['datetaken'];
      $this->title	= $id['title'];
      $this->description = $id['description'];
      $this->length	= $id['length'];
      $this->youtube_id	= $id['youtube_id'];
    } else {
      //引数idが数値(DBのPRIMARY KEY)の場合
      //Connect DB and fetch rows.
      $this->db_id = $id;

      $db->query("SELECT * FROM video WHERE id = $1;", array($id));
      $result = $db->result_rows();

      $this->filename	= $psqlAlbum['VideoDir'];
      $this->filename	= $this->filename.$result['filename'];
      $this->date	= $result['datetaken'];
      $this->title	= $result['title'];
      $this->description = $result['description'];
      $this->length	= $result['length'];
      $this->youtube_id = $result['youtube_id'];
    }
  }

  public function toHTMLthumbnail() {
    global $psqlAlbum;
    $style ="";
    $date = DBConn::date_toJapanese($this->date);
    $dir = $psqlAlbum['ThumbnailDir'];
    $playbutton_url = $psqlAlbum['AlbumLibDir']."play-icon.png";
    $thumbnail_filename = str_replace(array("MP4", "AVI", "3gp"), "JPG", $this->filename);
  
    return 
<<<HEREDOC
<DIV class="album_object">
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

    if(is_null($this->youtube_id)) {
      //youtube_idがnullの場合はHTML5の<video>でサーバのmp4ファイルを再生。(重い…)
      $videotag = "<VIDEO src=\"$this->filename\" alt=\"$this->title\" controls></VIDEO>";
    } else {
      //youtube_idがnullでない(YouTubeに動画がある)場合は埋め込んで再生(推奨)
      $videotag = "<iframe width=\"960\" height=\"720\" src=\"https://www.youtube.com/embed/$this->youtube_id\" frameborder=\"0\" allowfullscreen></iframe>";
    }

    return 
<<<HEREDOC
<DIV>
  $videotag
  <DIV>$this->title</DIV>
  <DIV>$this->description</DIV>
  <DIV>$date</DIV>
</DIV>
HEREDOC;
  }

  public static function getObjectsInDateRange($datebegin, $dateend) {
    global $db;
    
    $db->query("SELECT video.id AS id,filename,datetaken,video.title AS title,video.description AS description,length,youtube_id,path_photo FROM album,video WHERE datetaken <= date_end AND datetaken >= date_begin AND datetaken BETWEEN $1 AND $2 ORDER BY video.id;", array($datebegin, $dateend));
    while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $videos[] = new Video($result);
    }

    if($db->isNoResult()) {
      throw new Exception('AlbumObjectNotFound');
    }

    return $videos;
  }

  public static function getObjectsBySearchQuery($query) {
    global $db;

    $db->query("SELECT * FROM video WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", array("%$query%"));
     while($db->hasMoreRows()) {
      $result = $db->nextRow();
      $videos[] = new Video($result);
    }

    if($db->isNoResult()) {
      throw new Exception('AlbumObjectNotFound');
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
  public function getFileURL() {
    global $psqlAlbum;

    return $psqlAlbum['AlbumRoot'] . $psqlAlbum['PhotoDir'] . $this->filename;
  }
}