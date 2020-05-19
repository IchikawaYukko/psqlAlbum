<?php
class DBConn {
	private $link, $host, $dbname, $user, $pass;
	private $rowcount = 0;

	public function __construct($param) {
		$this->host = $param['Server'];
		$this->dbname = $param['DBname'];
		$this->user = $param['Username'];
		$this->pass = $param['Password'];
	}

	public function __destruct() {
		$this->disconnect();
	}

	public function conn() {
		$this->link = pg_connect("host=$this->host dbname=$this->dbname user=$this->user password=$this->pass");
		if(!$this->link) {
			die("pgSQL Error\n");
		}
	}

	public function close() {
		$this->disconnect();
	}

	public function disconnect() {
		if("pgsql link" == get_resource_type($this->link)) {
			pg_close($this->link);
		}
	}

	public function query($sql, $sql_param) {
		if(!$this->link) {
			die("Not connected to PostgreSQL server. Connect before query.\n");
		}

		$this->data = pg_query_params($this->link, $sql, $sql_param);
		if(!$this->data) {
			die("query Error\n");
		}

		$this->rowcount = 0;
		return $this->result();
	}

	public function result() {
		return $this->data;
	}

	public function result_rows() {
		return pg_fetch_array($this->data, $this->rowcount++, PGSQL_ASSOC);
	}

	public function nextRow() {
		return $this->result_rows();
	}

	public function hasMoreRows() {
		return $this->rowcount < pg_num_rows($this->data);
	}

	public function isNoResult() {
		if( pg_num_rows($this->data) == 0 ) {
			return true;
		} else {
			return false;
		}
	}

	public static function date_toJapanese($date) {
		$temp = substr($date, 0, 4);
		$temp .= "年";
		$temp .= substr($date, 5, 2);
		$temp .= "月";
		$temp .= substr($date, 8, 2);
		$temp .= "日";

		return $temp;
	}
}
