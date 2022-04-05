<?php 

class Database
{
	
	/* variables -- */
	public $cs = "";
	public $last_id = 0;
	private $query = "";
	/* open connection from database -- */
	public function openCon() {
		try {
			$this->cs = new PDO( 'mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass );
			$this->cs->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			echo 'Error: ' . $e->getMessage();
		}
	}
	
	/* close connection from database -- */
	public function closeCon() {
		//$this->cs->query('KILL CONNECTION CONNECTION_ID();');
		//unset($this->cs);
	}
	
	
	/* ---------------------------------------------------------------------------------------------------
	This function used for all query statements and return depends to what type of statement you assigned.
	Usage ex: execQuery( "SELECT * FROM table", "rows" );
	-- */
	public function execQuery( $statement, $param = array(), $type ) {
	
		$this->openCon();
		$output = array();
		$statement = trim($statement);
		
		/*
			$stmt = $this->cs->prepare('UPDATE someTable SET name = :name WHERE id = :id');
			$stmt->execute(array(
					':id'   => $id,
					':name' => $name
			));
		*/
		
		$this->query = $this->cs->prepare($statement);
		$succ = $this->query->execute($param);

		
		//$this->query = mysqli_query($this->cs, $statement) or die(mysqli_error($this->cs));
		
		if($type == "insert" || $type == "update" || $type == "delete") { /* for saving/updating data -- */
			/*$output = $this->query;
			$this->last_id = ($type == "insert") ? mysqli_insert_id($this->cs) : 0;*/			
			//$tmt->execute( array('user', 'user@example.com'));  
			$this->last_id = ($type == "insert") ? $this->cs->lastInsertId() : 0;
			$output = $succ;
		} else if($type == "rows") { /* return result rows -- */
			/*while($row = mysqli_fetch_row($this->query)) {
				array_push($output, $row);
			}*/
			foreach ($this->query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				array_push($output, $row);
			}
		} else if($type == "num") {
			/*while($row = mysqli_fetch_row($this->query)) {
				array_push($output, $row);
			}*/
			foreach ($this->query->fetchAll(PDO::FETCH_NUM) as $row) {
				array_push($output, $row);
			}
		} else if($type == "fields") {
			/*while($row = mysqli_fetch_assoc($this->query)) {
				array_push($output, $row);
			}*/
			foreach ($this->query->fetchAll(PDO::FETCH_ASSOC) as $row) {
				array_push($output, $row);
			}
		} else if($type == "count") { /* return rows count -- */
			//$output = mysqli_num_rows($this->query);
			$output = $this->query->rowCount();
		}

		/*
		mysql_fetch_assoc($result)
	-> $stmt->fetch(PDO::FETCH_ASSOC)
	mysql_num_rows($result)
	-> $stmt->rowCount()
	while ($row = mysql_fetch_assoc($result)) {}
	-> foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {}
		*/
		
		$this->closeCon();
		$this->query = "";
		return $output;
		
	}
	
}
