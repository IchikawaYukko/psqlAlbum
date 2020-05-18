<?php
require_once('settings.php');
require_once(dirname(__FILE__).'/pdbconn.php');

class GPX {
	private $db_id, $date, $filename;

	public function __construct($id) {
		global $db,$psqlAlbum;

		if("array" == gettype($id)) {
			$this->db_id = $id['id'];
			$this->filename = $psqlAlbum['GPXdir'];
			$this->filename .= $id['filename'];
			$this->date = $id['date'];
		} else {
			//Connect DB and fetch rows.
			$this->db_id = $id;

			//fetch path of photo
			$db->query("SELECT * FROM gpx WHERE id = $1;", [$id]);
			$result = $db->result_rows();
			$this->filename = $result['filename'];
			$this->date = $result['date'];
		}
	}

	static function getGPXsInDateRange($datebegin, $dateend) {
		global $db,$db_param;

		$db->query("SELECT * FROM gpx WHERE date BETWEEN $1 AND $2 ORDER BY date;", [$datebegin, $dateend]);
		while($db->hasMoreRows()) {
			$result = $db->nextRow();
			$gpxs[] = new GPX($result);
		}

		if(isset($gpxs)) {
			return $gpxs;
		} else {
			return [];
		}
	}

	static function arrayToJSON($gpxarray) {
		$json = "[";
		foreach($gpxarray as $gpx) {
			$json .= $gpx->toJSON();
			$json .= ",";
		}
		$json .= "];";
		return $json;
	}

	public function toJSON() {
		global $psqlAlbum;
		$to_json['date'] = $this->date;
		$to_json['filename'] = $this->filename;
		
		return json_encode($to_json);
	}
	public function toHTML() {
		global $psqlAlbum;
		$date = DBConn::date_toJapanese($this->date);
		//$dir = $psqlAlbum['GPXdir'];

		return
<<<HEREDOC
<A href="$this->filename">GPSデータ:$date</A><BR>
HEREDOC;
	}
}
