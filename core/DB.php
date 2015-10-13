<?php
namespace core;

/**
 * User: omybug
 * Date: 2015/02/13
 * Time: 23:52
 */
class DB
{
	# @object, The PDO object
	private $pdo;

	# @object, PDO statement object
	private $sQuery;

	# @array,  The database settings
	private $settings;

	# @bool ,  Connected to the database
	private $bConnected = false;

	# @object, Object for logging exceptions	
	private $log;

	# @array, The parameters of the SQL query
	private $parameters;
	
	private static $instance = array();

    /**
	*   Default Constructor 
	*
	*	1. Instantiate Log class.
	*	2. Connect to database.
	*	3. Creates the parameter array.
	*/
	private function __construct($settings = null){
        $this->settings = $settings;
		$this->log = Log::instance();
		$this->Connect();
		$this->parameters = array();
	}

    /**
     * @param array $settings
     * @return mixed
     */
    public static function instance($settings = null) {
        if(empty($settings)){
            $settings = Config::get('mysql');
        }
        $key = md5(http_build_query($settings));
		if (!isset(self::$instance[$key])) {
			self::$instance[$key] = new DB($settings);
		}
		return self::$instance[$key];
	}
	
    /**
	*	This method makes connection to the database.
	*	
	*	1. Reads the database settings from a ini file. 
	*	2. Puts  the ini content into the settings array.
	*	3. Tries to connect to the database.
	*	4. If connection failed, exception is displayed and a log file gets created.
	*/
	private function Connect()
	{
		$dsn = 'mysql:dbname='.$this->settings["dbname"].';host='.$this->settings["host"].'';
		try
		{
			# Read settings from INI file, set UTF8
			$this->pdo = new \PDO($dsn, $this->settings["user"], $this->settings["password"], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			
			# We can now log any exceptions on Fatal error. 
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			# Disable emulation of prepared statements, use REAL prepared statements instead.
			$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
			
			# Connection succeeded, set the boolean to true.
			$this->bConnected = true;
		}
		catch (\PDOException $e)
		{
			# Write into log
			echo $this->ExceptionLog($e->getMessage());
		}
	}

	/*
	 *   You can use this little method if you want to close the PDO connection
	 *
	 */
 	public function CloseConnection(){
 		# Set the PDO object to null to close the connection
 		# http://www.php.net/manual/en/pdo.connections.php
 		$this->pdo = null;
 	}
		
	/**
	*	Every method which needs to execute a SQL query uses this method.
	*	
	*	1. If not connected, connect to the database.
	*	2. Prepare Query.
	*	3. Parameterize Query.
	*	4. Execute Query.	
	*	5. On exception : Write Exception into the log + SQL query.
	*	6. Reset the Parameters.
	*/	
	private function Init($query,$parameters = ""){
        $this->log->sql($query);
        $this->log->sql($parameters);
		# Connect to database
		if(!$this->bConnected){
            $this->Connect();
        }
		try {
			# Prepare query
			$this->sQuery = $this->pdo->prepare($query);
			
			# Add parameters to the parameter array	
			$this->bindMore($parameters);

			# Bind parameters
			if(!empty($this->parameters)) {
				foreach($this->parameters as $param)
				{
					$parameters = explode("\x7F",$param);
					$this->sQuery->bindParam($parameters[0],$parameters[1]);
				}		
			}

			# Execute SQL
			$this->succes 	= $this->sQuery->execute();		
		}
		catch(\PDOException $e)
		{
			# Write into log and display Exception
			echo $this->ExceptionLog($e->getMessage(), $query );
            if ($e->getCode() == 'HY000') {
                $this->bConnected = false;
                $this->Connect();
                $this->Init($query,$parameters);
            }
		}
		# Reset the parameters
		$this->parameters = array();
	}

    /**
     * START TRANSACTION
     */
    public function begin(){
        $this->pdo->beginTransaction();
    }

    /**
     * ROLLBACK
     */
    public function rollback(){
        $this->pdo->rollback();
    }

    /**
     * COMMIT
     */
    public function commit(){
        $this->pdo->commit();
    }

	/**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
	public function bind($para, $value){	
		$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
	}

	/**
	*	@void
	*	
	*	Add more parameters to the parameter array
	*	@param array $parray
	*/	
	public function bindMore($parray){
		if(empty($this->parameters) && is_array($parray)) {
			$columns = array_keys($parray);
			foreach($columns as $i => &$column)	{
				$this->bind($column, $parray[$column]);
			}
		}
	}

    /**
     * @param $query
     * @param null $params
     * @param int $fetchmode
     * @return null
     */
    public function delete($query,$params = null, $fetchmode = \PDO::FETCH_ASSOC){
        if(!stristr($query, 'where') || empty($params)){
            $this->log->error($query.' has no where condition！');
            return NULL;
        }
        return $this->exec($query,$params,$fetchmode);
    }

    /**
     * @param $query
     * @param null $params
     * @param int $fetchmode
     * @return null
     */
    public function update($query,$params = null, $fetchmode = \PDO::FETCH_ASSOC){
        if(!stristr($query, 'where') || empty($params)){
            $this->log->error($query.' has no where condition！');
            return NULL;
        }
        return $this->exec($query,$params,$fetchmode);
    }

    /**
     * @param $query
     * @param null $params
     * @param int $fetchmode
     * @return mixed
     */
    public function insert($query,$params = null, $fetchmode = \PDO::FETCH_ASSOC){
        return $this->exec($query,$params,$fetchmode);
    }

	/**
	*   If the SQL query  contains a SELECT or SHOW statement it returns an array containing all of the result set row
	*	If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
	*
	*   @param  string $query
	*	@param  array  $params
	*	@param  int    $fetchmode
	*	@return mixed
	*/			
	public function query($query,$params = null, $fetchmode = \PDO::FETCH_ASSOC){
        return $this->exec($query,$params,$fetchmode);
	}


    /**
     * @param $query
     * @param null $params
     * @param int $fetchmode
     * @return null|string
     */
    private function exec($query,$params = null, $fetchmode = \PDO::FETCH_ASSOC){
        $query = trim($query);

        $this->Init($query,$params);

        $rawStatement = explode(" ", $query);

        # Which SQL statement is used
        $statement = strtolower($rawStatement[0]);

        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        }
        elseif($statement === 'insert'){
            return $this->lastInsertId();
        }
        elseif ($statement === 'update' || $statement === 'delete' ) {
            return $this->sQuery->rowCount();
        }
        else {
            return NULL;
        }
    }

	/**
	*  Returns the last inserted id.
	*  @return string
	*/	
	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}

    /**
	*	Returns an array which represents a column from the result set 
	*
	*	@param  string $query
	*	@param  array  $params
	*	@return array
	*/	
	public function column($query,$params = null){
		$this->Init($query,$params);
		$Columns = $this->sQuery->fetchAll(\PDO::FETCH_NUM);
		
		$column = null;

		foreach($Columns as $cells) {
			$column[] = $cells[0];
		}
		return $column;
	}	
	
	/**
	*	Returns an array which represents a row from the result set 
	*
	*	@param  string $query
	*	@param  array  $params
	*   @param  int    $fetchmode
	*	@return array
	*/	
	public function row($query,$params = null,$fetchmode = \PDO::FETCH_ASSOC){
		$this->Init($query,$params);
		return $this->sQuery->fetch($fetchmode);			
	}

	/**
	*	Returns the value of one single field/column
	*
	*	@param  string $query
	*	@param  array  $params
	*	@return string
	*/	
	public function single($query,$params = null){
		$this->Init($query,$params);
		return $this->sQuery->fetchColumn();
	}

	/**	
	* Writes the log and returns the exception
	*
	* @param  string $message
	* @param  string $sql
	* @return string
	*/
	private function ExceptionLog($message , $sql = ""){
		$exception  = 'Unhandled Exception. <br />';
		$exception .= $message;
		$exception .= "<br /> You can find the error back in the log.";

		if(!empty($sql)) {
			# Add the Raw SQL to the Log
			$message .= "\r\nRaw SQL : "  . $sql;
		}
		# Write into log
		$this->log->error($message);
		return $exception;
	}		
}
?>
