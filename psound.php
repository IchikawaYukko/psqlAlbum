<?php
require_once(dirname(__FILE__).'/palbum_object.php');
require_once('settings.php');

class Sound extends AlbumObject implements AlbumObjectInterface {
	private $db_id, $filename, $date, $title, $description, $length;

	public function __construct($id) {
		global $db, $psqlAlbum;

		if(is_array($id)) {
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

			$db->query("SELECT * FROM sound WHERE id = $1;", [$id]);
			if($db->isNoResult()) {
				throw new Exception("Sound Not Found", 1);
			}
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
		//$dir = $psqlAlbum['ThumbnailDir'];
		$playbutton_url = $psqlAlbum['AlbumLibDir']."play-icon.png";

		return 
<<<HEREDOC
<DIV class="album_object">
	<A href="index.php?sid=$this->db_id"><IMG class="thumbs" style="$style" src="$playbutton_url" alt="$this->title"></A>
	<DIV class="title">$this->title</DIV>
	<DIV class="description">$this->description</DIV>
</DIV>
HEREDOC;
	}

	public function toHTMLlarge() {
		global $psqlAlbum;

		$date = DBConn::date_toJapanese($this->date);
		$dir = $psqlAlbum['SoundDir'];

		$audiotag = "<AUDIO src=\"$this->filename\" alt=\"$this->title\" controls></AUDIO>";

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
, [$datebegin, $dateend]);
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

		$db->query("SELECT * FROM sound WHERE (title LIKE $1 OR description LIKE $1) ORDER BY id;", ["%$query%"]);
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
	public function getFileURL() {
		global $psqlAlbum;

		return $psqlAlbum['AlbumRoot'] . $psqlAlbum['PhotoDir'] . $this->filename;
	}
}
