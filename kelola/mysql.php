<?php/*author: Muhammad Romliemail : roemly@gmail.com*/class Mysqli_db{	//public $link='';	public $db_name="";	public $mysqli;	public function __construct($db_host,$db_user,$db_pass,$db_name)	{		//	$this->link=mysqli_connect($db_host,$db_user,$db_pass);	//	mysqli_select_db($this->link,$db_name);				$this->db_name=$db_name;		$this->mysqli=mysqli_connect($db_host,$db_user,$db_pass);		if(mysqli_connect_errno())		{			printf("Connect failed %s\n",mysqli_connect_error());			exit();		}		if(!mysqli_select_db($this->mysqli,$db_name)){			echo mysqli_error($this->mysqli);			exit();		}			}	public function insert_id(){
		return $this->mysqli->insert_id;
	}	public function query($sql,$debug=0,$die=0)	{		if($die){			die($sql);		}		$q=mysqli_query($this->mysqli,$sql);		if(!$q and $debug) {		echo mysqli_error($this->mysqli);			die($sql);			exit();		}		return $q;			}	public function real_escape_string($str)	{		return mysqli_real_escape_string($this->mysqli,$str);	}	public function fetch_assoc($q)	{		return mysqli_fetch_assoc($q);	}	public function fetch_array($q)	{		return mysqli_fetch_array($q);	}	public function assoc($q)	{		return mysqli_fetch_assoc($q);	}	public function fetch_row($q)	{		return mysqli_fetch_row($q);	}	public function row($q)	{		return mysqli_fetch_row($q);	}	public function free_result($q)	{		mysqli_free_result($q);	}	public function num_rows($q)	{		return mysqli_num_rows($q);	}		public function query_data($sql,$field_key="")	{		$mydata=array();		$r=$this->query("$sql");		if($r and $this->num_rows($r)>0)		{			while($d=$this->assoc($r))			{				if($field_key!=""){
					$mydata[$d[$field_key]]=$d;
				}else{
					$mydata[]=$d;	
				}							}		}		return $mydata;	}	public function get1value($sql)	{		$r=$this->query("$sql");		$val="";		if($r and $this->num_rows($r)>0)		{			list($val)=$this->row($r);		}		return $val;	}	public function last_auto_increment($table="")	{		if($table!="")		{			$sql="			SELECT `AUTO_INCREMENT`			FROM  INFORMATION_SCHEMA.TABLES			WHERE TABLE_SCHEMA = '".$this->db_name."'			AND   TABLE_NAME   = '$table'";			return $this->get1value($sql);		}	}	public function r_assoc($table="",$field="*",$condition="",$debug=0)	{		$field=$field==""?"*":$field;		/*		 * Fungsi untuk melakukan query dan memberikan return value hasil dalam bentuk array		 * cocok untuk data kecil 		 * p1 untuk nama tablenya		 * p2 untuk field field yang mau di ambil		 * p3 untuk kondisi		 * */		if($table!="")		{			$temp=array();						$r=$this->query("SELECT $field FROM $table $condition");			if($debug){die("SELECT $field FROM $table $condition");}			$val="";			if($r and $this->num_rows($r)>0)			{				while($d=$this->assoc($r))				{					$temp[]=$d;				}			}			return $temp;		}	}	public function getMaxNumber($tabel, $kolom, $kondisi = "") {			$max=1;		$sql = "SELECT max($kolom) FROM $tabel ";		if (strlen($kondisi) > 0) {			$sql .= "WHERE $kondisi ";		}				$result = $this->query($sql);		if($result and $this->num_rows($result)>0)		{			list($max) = $this->row($result);		}		return $max;	}	public function autocommit($flag=false){		mysqli_autocommit ($this->mysqli,$flag);	}	public function showerror(){		return mysqli_error($this->mysqli);	}	public function commit(){		mysqli_commit($this->mysqli);	}	public function rollback(){		mysqli_rollback($this->mysqli);		}	public function close(){
		mysqli_close($this->mysqli);
	}	public function affected_rows(){		return mysqli_affected_rows($this->mysqli);	}	public function result($res,$row,$field){				if(count($this->result_temp)>0){			return $this->result_temp[$row][$field];		}else{			while($d=$this->assoc($res))			{				$this->result_temp[]=$d;			}			return $this->result_temp[$row][$field];		}	}		public function set_charset($charset)	{		return mysqli_set_charset($this->mysqli, $charset);	}	}?>