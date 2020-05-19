<?php
require_once(dirname(__FILE__).'/pdbconn.php');
require_once('settings.php');

class Album {
	private $db_id, $datebegin, $dateend, $title, $description;

	public function __construct($id) {
		global $db;

		if(is_array($id)) {
			$this->db_id = $id['id'];
			$this->datebegin = $id['date_begin'];
			$this->dateend = $id['date_end'];
			$this->title = $id['title'];
			$this->description = $id['description'];
		} else {
			//Connect DB and fetch rows.
			$this->db_id = $id;

			//fetch path of photo
			$db->query("SELECT * FROM album WHERE id = $1", [$id]);
			if($db->isNoResult()) {
				throw new Exception("Album Not Found");
			}
			$result = $db->result_rows();

			$this->datebegin = $this->datebegin.$result['date_begin'];
			$this->dateend = $result['date_end'];
			$this->title = $result['title'];
			$this->description = $result['description'];
		}
	}

	public function toHTML() {
		$datebegin = DBConn::date_toJapanese($this->datebegin);
		$dateend = DBConn::date_toJapanese($this->dateend);

		if($datebegin === $dateend) {
			return
<<<EOM
<A href="index.php?aid=$this->db_id">$datebegin</A>$this->title<BR>
EOM;
		} else {
		return 
<<<HEREDOC
<A href="index.php?aid=$this->db_id">$datebegin ～ $dateend</A>$this->title<BR>
HEREDOC;
		}
	}

	static function getAllAlbum() {
		global $db,$db_param;

		$db->query("SELECT * FROM album ORDER BY date_begin;", []);
		while($db->hasMoreRows()) {
			$result = $db->nextRow();
			$albums[] = new Album($result);
		}

		return $albums;
	}

	//Getters
	public function getTitle() {
		return $this->title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getDatebegin() {
		return $this->datebegin;
	}
	public function getDateend() {
		return $this->dateend;
	}
}
